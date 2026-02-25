import { createClient } from "@/lib/supabase/server";
import { createClient as createServiceClient } from "@supabase/supabase-js";
import { NextResponse } from "next/server";
import { canUsePlanFeature, type PlanType } from "@/lib/stripe";
import { getAnalyticsData } from "@/lib/analytics";
import { generateReportPdf } from "@/lib/report-pdf";
import { sendReportEmail } from "@/lib/email";

export async function POST() {
  const supabase = await createClient();
  const {
    data: { user },
  } = await supabase.auth.getUser();
  if (!user) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  // プラン確認
  const [{ data: subscription }, { data: profile }] = await Promise.all([
    supabase.from("subscriptions").select("plan").eq("user_id", user.id).single(),
    supabase.from("profiles").select("role").eq("id", user.id).single(),
  ]);

  const isAdmin = profile?.role === "admin";
  const plan = (subscription?.plan as PlanType) ?? "free";
  if (!isAdmin && !canUsePlanFeature(plan, "reports")) {
    return NextResponse.json(
      { error: "この機能はプロプラン以上でご利用いただけます" },
      { status: 403 }
    );
  }

  // チャットボット取得
  const { data: chatbot } = await supabase
    .from("chatbots")
    .select("id")
    .eq("user_id", user.id)
    .single();

  if (!chatbot) {
    return NextResponse.json({ error: "Chatbot not found" }, { status: 404 });
  }

  // 分析データ取得
  const analyticsData = await getAnalyticsData(supabase, chatbot.id, 30);

  // admin用の追加統計
  let adminStats: { totalUsers: number; newUsersInPeriod: number } | undefined;
  if (isAdmin) {
    const serviceSupabase = createServiceClient(
      process.env.NEXT_PUBLIC_SUPABASE_URL!,
      process.env.SUPABASE_SERVICE_ROLE_KEY!
    );

    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);

    const [{ count: totalUsers }, { count: newUsers }] = await Promise.all([
      serviceSupabase.from("profiles").select("*", { count: "exact", head: true }),
      serviceSupabase
        .from("profiles")
        .select("*", { count: "exact", head: true })
        .gte("created_at", thirtyDaysAgo.toISOString()),
    ]);

    adminStats = {
      totalUsers: totalUsers ?? 0,
      newUsersInPeriod: newUsers ?? 0,
    };
  }

  // PDF生成
  const email = user.email ?? "";
  const pdfBuffer = generateReportPdf(analyticsData, {
    days: 30,
    email,
    adminStats,
  });

  // メール送信
  const dateStr = new Date().toISOString().slice(0, 10);
  try {
    await sendReportEmail({
      to: email,
      subject: `LiveAI レポート (${dateStr})`,
      html: `
        <div style="font-family: sans-serif; max-width: 600px; margin: 0 auto;">
          <h2 style="color: #1a1a1a;">LiveAI レポート</h2>
          <p>添付のPDFレポートをご確認ください。</p>
          <div style="background: #f5f5f5; border-radius: 8px; padding: 16px; margin: 16px 0;">
            <p style="margin: 4px 0;"><strong>期間:</strong> 過去30日間</p>
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
  } catch (error) {
    console.error("Report send error:", error);
    return NextResponse.json(
      { error: "レポートの送信に失敗しました" },
      { status: 500 }
    );
  }

  // last_sent_at更新
  await supabase
    .from("report_settings")
    .upsert(
      {
        user_id: user.id,
        last_sent_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
      },
      { onConflict: "user_id" }
    );

  return NextResponse.json({ ok: true, sentTo: email });
}
