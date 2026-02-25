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
  if (!canUsePlanFeature(plan, "analytics")) {
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

  // 過去30日の会話取得
  const thirtyDaysAgo = new Date();
  thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);

  const { data: conversations } = await supabase
    .from("conversations")
    .select("id, created_at, country, city")
    .eq("chatbot_id", chatbot.id)
    .gte("created_at", thirtyDaysAgo.toISOString())
    .order("created_at");

  const convIds = (conversations ?? []).map((c) => c.id);

  // メッセージ取得（ユーザーメッセージのみ分析対象）
  let userMessages: { content: string; created_at: string; conversation_id: string }[] = [];
  if (convIds.length > 0) {
    const { data: msgs } = await supabase
      .from("messages")
      .select("content, created_at, conversation_id")
      .in("conversation_id", convIds)
      .eq("role", "user")
      .order("created_at");
    userMessages = msgs ?? [];
  }

  // FAQ取得（マッチング用）
  const { data: faqs } = await supabase
    .from("faqs")
    .select("question")
    .eq("chatbot_id", chatbot.id);

  const faqQuestions = (faqs ?? []).map((f) => f.question.toLowerCase());

  // 1. よくある質問ランキング（質問の出現頻度）
  const questionCounts: Record<string, number> = {};
  for (const msg of userMessages) {
    const normalized = msg.content.trim().toLowerCase();
    if (normalized.length < 2) continue;
    questionCounts[msg.content.trim()] = (questionCounts[msg.content.trim()] || 0) + 1;
  }
  const topQuestions = Object.entries(questionCounts)
    .sort(([, a], [, b]) => b - a)
    .slice(0, 10)
    .map(([question, count]) => ({ question, count }));

  // 2. 未回答の質問（FAQとマッチしない質問）
  const unanswered: { question: string; count: number }[] = [];
  for (const [q, count] of Object.entries(questionCounts)) {
    const qLower = q.toLowerCase();
    const matched = faqQuestions.some(
      (faq) => qLower.includes(faq) || faq.includes(qLower)
    );
    if (!matched) {
      unanswered.push({ question: q, count });
    }
  }
  const topUnanswered = unanswered
    .sort((a, b) => b.count - a.count)
    .slice(0, 10);

  // 3. 時間帯別会話数
  const hourly = new Array(24).fill(0);
  for (const conv of conversations ?? []) {
    const hour = new Date(conv.created_at).getHours();
    hourly[hour]++;
  }

  // 4. 日別会話数（過去30日）
  const daily: { date: string; count: number }[] = [];
  const dateMap: Record<string, number> = {};
  for (const conv of conversations ?? []) {
    const date = conv.created_at.slice(0, 10);
    dateMap[date] = (dateMap[date] || 0) + 1;
  }
  for (let i = 29; i >= 0; i--) {
    const d = new Date();
    d.setDate(d.getDate() - i);
    const dateStr = d.toISOString().slice(0, 10);
    daily.push({ date: dateStr, count: dateMap[dateStr] || 0 });
  }

  // 5. 地域別会話数
  const countryCounts: Record<string, number> = {};
  const cityCounts: Record<string, number> = {};
  for (const conv of conversations ?? []) {
    const c = (conv as { country?: string | null }).country;
    const city = (conv as { city?: string | null }).city;
    if (c) {
      countryCounts[c] = (countryCounts[c] || 0) + 1;
    }
    if (city && c) {
      const key = `${city}|${c}`;
      cityCounts[key] = (cityCounts[key] || 0) + 1;
    }
  }
  const topCountries = Object.entries(countryCounts)
    .sort(([, a], [, b]) => b - a)
    .slice(0, 10)
    .map(([country, count]) => ({ country, count }));
  const topCities = Object.entries(cityCounts)
    .sort(([, a], [, b]) => b - a)
    .slice(0, 10)
    .map(([key, count]) => {
      const [city, country] = key.split("|");
      return { city, country, count };
    });

  return NextResponse.json({
    totalConversations: conversations?.length ?? 0,
    totalMessages: userMessages.length,
    topQuestions,
    unanswered: topUnanswered,
    hourly,
    daily,
    topCountries,
    topCities,
  });
}
