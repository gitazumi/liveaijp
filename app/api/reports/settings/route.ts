import { createClient } from "@/lib/supabase/server";
import { NextRequest, NextResponse } from "next/server";
import { canUsePlanFeature, type PlanType } from "@/lib/stripe";

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

  const { data: settings } = await supabase
    .from("report_settings")
    .select("*")
    .eq("user_id", user.id)
    .single();

  return NextResponse.json({
    settings: settings ?? { cycle: "weekly", enabled: false, last_sent_at: null },
  });
}

export async function PUT(req: NextRequest) {
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

  const body = await req.json();
  const { cycle, enabled } = body;

  if (cycle && !["daily", "weekly", "monthly"].includes(cycle)) {
    return NextResponse.json({ error: "Invalid cycle" }, { status: 400 });
  }

  const { data, error } = await supabase
    .from("report_settings")
    .upsert(
      {
        user_id: user.id,
        cycle: cycle ?? "weekly",
        enabled: enabled ?? false,
        updated_at: new Date().toISOString(),
      },
      { onConflict: "user_id" }
    )
    .select()
    .single();

  if (error) {
    console.error("Report settings upsert error:", error);
    return NextResponse.json({ error: "設定の保存に失敗しました" }, { status: 500 });
  }

  return NextResponse.json({ settings: data });
}
