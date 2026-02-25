import { createClient } from "@/lib/supabase/server";
import { NextResponse } from "next/server";
import { canUsePlanFeature, type PlanType } from "@/lib/stripe";
import { getAnalyticsData } from "@/lib/analytics";

export async function GET() {
  const supabase = await createClient();
  const {
    data: { user },
  } = await supabase.auth.getUser();
  if (!user) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  // プラン確認（adminはpro相当）
  const [{ data: subscription }, { data: profile }] = await Promise.all([
    supabase
      .from("subscriptions")
      .select("plan")
      .eq("user_id", user.id)
      .single(),
    supabase
      .from("profiles")
      .select("role")
      .eq("id", user.id)
      .single(),
  ]);

  const isAdmin = profile?.role === "admin";
  const plan = (subscription?.plan as PlanType) ?? "free";
  if (!isAdmin && !canUsePlanFeature(plan, "analytics")) {
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

  const data = await getAnalyticsData(supabase, chatbot.id, 30);
  return NextResponse.json(data);
}
