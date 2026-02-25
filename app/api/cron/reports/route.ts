import { createClient } from "@supabase/supabase-js";
import { NextRequest, NextResponse } from "next/server";
import { canUsePlanFeature, type PlanType } from "@/lib/stripe";
import { getAnalyticsData } from "@/lib/analytics";
import { generateReportPdf } from "@/lib/report-pdf";
import { sendReportEmail } from "@/lib/email";

export async function GET(req: NextRequest) {
  // CRON_SECRET認証
  const cronSecret = process.env.CRON_SECRET;
  if (cronSecret) {
    const authHeader = req.headers.get("authorization");
    if (authHeader !== `Bearer ${cronSecret}`) {
      return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
    }
  }

  const supabase = createClient(
    process.env.NEXT_PUBLIC_SUPABASE_URL!,
    process.env.SUPABASE_SERVICE_ROLE_KEY!
  );

  // 有効なレポート設定を取得
  const { data: settings, error: settingsError } = await supabase
    .from("report_settings")
    .select("*")
    .eq("enabled", true);

  if (settingsError) {
    console.error("Report cron: settings fetch error:", settingsError);
    return NextResponse.json({ error: settingsError.message }, { status: 500 });
  }

  if (!settings || settings.length === 0) {
    return NextResponse.json({ ok: true, sent: 0, message: "No active report settings" });
  }

  const now = new Date();
  let sentCount = 0;
  const errors: string[] = [];

  for (const setting of settings) {
    try {
      // サイクルに基づいて送信すべきか判定
      if (!shouldSendReport(setting.cycle, setting.last_sent_at, now)) {
        continue;
      }

      // ユーザーのプラン確認
      const { data: subscription } = await supabase
        .from("subscriptions")
        .select("plan")
        .eq("user_id", setting.user_id)
        .single();

      const { data: profile } = await supabase
        .from("profiles")
        .select("role, email")
        .eq("id", setting.user_id)
        .single();

      const isAdmin = profile?.role === "admin";
      const plan = (subscription?.plan as PlanType) ?? "free";

      if (!isAdmin && !canUsePlanFeature(plan, "reports")) {
        continue; // プランが不足している場合はスキップ
      }

      // チャットボット取得
      const { data: chatbot } = await supabase
        .from("chatbots")
        .select("id")
        .eq("user_id", setting.user_id)
        .single();

      if (!chatbot) continue;

      // 期間の決定
      const days = setting.cycle === "daily" ? 1 : setting.cycle === "weekly" ? 7 : 30;

      // 分析データ取得
      const analyticsData = await getAnalyticsData(supabase, chatbot.id, days);

      // admin用の追加統計
      let adminStats: { totalUsers: number; newUsersInPeriod: number } | undefined;
      if (isAdmin) {
        const periodStart = new Date();
        periodStart.setDate(periodStart.getDate() - days);

        const [{ count: totalUsers }, { count: newUsers }] = await Promise.all([
          supabase.from("profiles").select("*", { count: "exact", head: true }),
          supabase
            .from("profiles")
            .select("*", { count: "exact", head: true })
            .gte("created_at", periodStart.toISOString()),
        ]);

        adminStats = {
          totalUsers: totalUsers ?? 0,
          newUsersInPeriod: newUsers ?? 0,
        };
      }

      // PDF生成
      const email = profile?.email ?? "";
      if (!email) continue;

      const pdfBuffer = generateReportPdf(analyticsData, {
        days,
        email,
        adminStats,
      });

      // メール送信
      const cycleLabel = setting.cycle === "daily" ? "日次" : setting.cycle === "weekly" ? "週次" : "月次";
      const dateStr = now.toISOString().slice(0, 10);

      await sendReportEmail({
        to: email,
        subject: `LiveAI ${cycleLabel}レポート (${dateStr})`,
        html: `
          <div style="font-family: sans-serif; max-width: 600px; margin: 0 auto;">
            <h2 style="color: #1a1a1a;">LiveAI ${cycleLabel}レポート</h2>
            <p>添付のPDFレポートをご確認ください。</p>
            <div style="background: #f5f5f5; border-radius: 8px; padding: 16px; margin: 16px 0;">
              <p style="margin: 4px 0;"><strong>期間:</strong> 過去${days}日間</p>
              <p style="margin: 4px 0;"><strong>総会話数:</strong> ${analyticsData.totalConversations}</p>
              <p style="margin: 4px 0;"><strong>総メッセージ数:</strong> ${analyticsData.totalMessages}</p>
            </div>
            <p style="color: #666; font-size: 12px;">
              このメールはLiveAIのレポート機能から自動送信されています。<br/>
              <a href="https://liveai.jp/dashboard/reports">レポート設定を変更する</a>
            </p>
          </div>
        `,
        pdfBuffer,
        filename: `liveai-report-${dateStr}.pdf`,
      });

      // last_sent_at更新
      await supabase
        .from("report_settings")
        .update({ last_sent_at: now.toISOString(), updated_at: now.toISOString() })
        .eq("id", setting.id);

      sentCount++;
    } catch (error) {
      const msg = error instanceof Error ? error.message : String(error);
      console.error(`Report cron error for user ${setting.user_id}:`, msg);
      errors.push(`${setting.user_id}: ${msg}`);
    }
  }

  console.log(`Report cron: ${sentCount}/${settings.length} reports sent at ${now.toISOString()}`);

  return NextResponse.json({
    ok: true,
    sent: sentCount,
    total: settings.length,
    errors: errors.length > 0 ? errors : undefined,
    timestamp: now.toISOString(),
  });
}

/**
 * サイクルとlast_sent_atから今回送信すべきか判定
 */
function shouldSendReport(
  cycle: string,
  lastSentAt: string | null,
  now: Date
): boolean {
  if (!lastSentAt) return true; // 一度も送信していない場合は送信

  const last = new Date(lastSentAt);
  const diffMs = now.getTime() - last.getTime();
  const diffHours = diffMs / (1000 * 60 * 60);

  switch (cycle) {
    case "daily":
      return diffHours >= 23; // 23時間以上経過
    case "weekly":
      return diffHours >= 167; // 約7日
    case "monthly":
      return diffHours >= 719; // 約30日
    default:
      return false;
  }
}
