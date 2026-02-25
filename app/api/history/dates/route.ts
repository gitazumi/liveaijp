import { createClient } from "@/lib/supabase/server";
import { NextResponse } from "next/server";

/**
 * 全会話の日付別件数を返す（カレンダーハイライト用）
 * レスポンス例: { "2026-02-25": 3, "2026-02-24": 1 }
 */
export async function GET() {
  const supabase = await createClient();
  const {
    data: { user },
  } = await supabase.auth.getUser();
  if (!user) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const { data: chatbot } = await supabase
    .from("chatbots")
    .select("id")
    .eq("user_id", user.id)
    .single();

  if (!chatbot) {
    return NextResponse.json({});
  }

  // 過去90日の会話日付を取得
  const ninetyDaysAgo = new Date();
  ninetyDaysAgo.setDate(ninetyDaysAgo.getDate() - 90);

  const { data: conversations } = await supabase
    .from("conversations")
    .select("created_at")
    .eq("chatbot_id", chatbot.id)
    .gte("created_at", ninetyDaysAgo.toISOString());

  const dateCounts: Record<string, number> = {};
  for (const conv of conversations ?? []) {
    const date = conv.created_at.slice(0, 10);
    dateCounts[date] = (dateCounts[date] || 0) + 1;
  }

  return NextResponse.json(dateCounts);
}
