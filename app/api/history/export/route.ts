import { createClient } from "@/lib/supabase/server";
import { NextResponse } from "next/server";
import { canUsePlanFeature, type PlanType } from "@/lib/stripe";

export async function GET() {
  const supabase = await createClient();
  const {
    data: { user },
  } = await supabase.auth.getUser();
  if (!user) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  // プラン確認
  const { data: subscription } = await supabase
    .from("subscriptions")
    .select("plan")
    .eq("user_id", user.id)
    .single();

  const plan = (subscription?.plan as PlanType) ?? "free";
  if (!canUsePlanFeature(plan, "csvExport")) {
    return NextResponse.json(
      { error: "この機能はスタンダードプラン以上でご利用いただけます" },
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

  // 会話とメッセージを取得
  const { data: conversations } = await supabase
    .from("conversations")
    .select("id, created_at")
    .eq("chatbot_id", chatbot.id)
    .order("created_at", { ascending: false })
    .limit(500);

  if (!conversations || conversations.length === 0) {
    const BOM = "\uFEFF";
    return new NextResponse(BOM + "conversation_id,role,content,created_at", {
      headers: {
        "Content-Type": "text/csv; charset=utf-8",
        "Content-Disposition": `attachment; filename="chat_history_${new Date().toISOString().slice(0, 10)}.csv"`,
      },
    });
  }

  const convIds = conversations.map((c) => c.id);
  const { data: messages } = await supabase
    .from("messages")
    .select("conversation_id, role, content, created_at")
    .in("conversation_id", convIds)
    .order("created_at");

  // CSV生成
  const BOM = "\uFEFF";
  const header = "conversation_id,role,content,created_at";
  const rows = (messages ?? []).map(
    (msg) =>
      `"${msg.conversation_id}","${msg.role}","${msg.content.replace(/"/g, '""').replace(/\n/g, "\\n")}","${msg.created_at}"`
  );
  const csv = BOM + header + "\n" + rows.join("\n");

  return new NextResponse(csv, {
    headers: {
      "Content-Type": "text/csv; charset=utf-8",
      "Content-Disposition": `attachment; filename="chat_history_${new Date().toISOString().slice(0, 10)}.csv"`,
    },
  });
}
