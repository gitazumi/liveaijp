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

  // FAQ取得
  const { data: faqs } = await supabase
    .from("faqs")
    .select("question, answer")
    .eq("chatbot_id", chatbot.id)
    .order("sort_order");

  // CSV生成（BOM付きUTF-8）
  const BOM = "\uFEFF";
  const header = "question,answer";
  const rows = (faqs ?? []).map(
    (faq) =>
      `"${faq.question.replace(/"/g, '""')}","${faq.answer.replace(/"/g, '""')}"`
  );
  const csv = BOM + header + "\n" + rows.join("\n");

  return new NextResponse(csv, {
    headers: {
      "Content-Type": "text/csv; charset=utf-8",
      "Content-Disposition": `attachment; filename="faqs_${new Date().toISOString().slice(0, 10)}.csv"`,
    },
  });
}
