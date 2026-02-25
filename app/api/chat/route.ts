import { openai } from "@ai-sdk/openai";
import { streamText } from "ai";
import { createClient } from "@supabase/supabase-js";
import { getPlanLimits, PlanType } from "@/lib/stripe";

function getSupabase() {
  const serviceRoleKey = process.env.SUPABASE_SERVICE_ROLE_KEY;
  if (!serviceRoleKey) {
    throw new Error("SUPABASE_SERVICE_ROLE_KEY is not set");
  }
  return createClient(
    process.env.NEXT_PUBLIC_SUPABASE_URL!,
    serviceRoleKey
  );
}

export async function POST(req: Request) {
  const supabase = getSupabase();
  const {
    messages,
    token,
    conversationId,
    test,
  }: {
    messages: { role: "user" | "assistant"; content: string }[];
    token: string;
    conversationId?: string;
    test?: boolean;
  } = await req.json();

  if (!token || !messages?.length) {
    return new Response("Bad request", { status: 400 });
  }

  // チャットボットをトークンで取得
  const { data: chatbot } = await supabase
    .from("chatbots")
    .select("id, name, greeting, user_id")
    .eq("token", token)
    .single();

  if (!chatbot) {
    return new Response("Chatbot not found", { status: 404 });
  }

  // 利用制限チェック（テストモード以外）
  if (!test) {
    const { data: subscription } = await supabase
      .from("subscriptions")
      .select("plan")
      .eq("user_id", chatbot.user_id)
      .single();

    const plan = getPlanLimits((subscription?.plan as PlanType) ?? "free");

    if (plan.conversationLimit !== Infinity) {
      const startOfMonth = new Date();
      startOfMonth.setDate(1);
      startOfMonth.setHours(0, 0, 0, 0);

      const { count } = await supabase
        .from("conversations")
        .select("*", { count: "exact", head: true })
        .eq("chatbot_id", chatbot.id)
        .gte("created_at", startOfMonth.toISOString());

      if ((count ?? 0) >= plan.conversationLimit) {
        return new Response(
          "月間会話数の上限に達しました。プランのアップグレードをご検討ください。",
          { status: 429 }
        );
      }
    }
  }

  // FAQを取得
  const { data: faqs } = await supabase
    .from("faqs")
    .select("question, answer")
    .eq("chatbot_id", chatbot.id)
    .order("sort_order");

  // FAQコンテキストを構築
  const faqContext =
    faqs && faqs.length > 0
      ? faqs.map((f) => `Q: ${f.question}\nA: ${f.answer}`).join("\n\n")
      : "FAQが登録されていません。";

  const systemMessage = `あなたは「${chatbot.name}」というチャットボットアシスタントです。
以下のFAQ情報を元に、ユーザーの質問に日本語で丁寧に回答してください。
FAQに関連する情報がない場合は、「申し訳ございませんが、その質問にはお答えできません。」と回答してください。
回答は簡潔にわかりやすくしてください。

【FAQ情報】
${faqContext}`;

  // テストモードでない場合のみ会話をDBに保存
  let convId = conversationId;
  if (!test) {
    if (!convId) {
      const { data: conv } = await supabase
        .from("conversations")
        .insert({ chatbot_id: chatbot.id })
        .select("id")
        .single();
      convId = conv?.id;
    }

    // ユーザーメッセージを保存
    const lastUserMsg = messages[messages.length - 1];
    if (convId && lastUserMsg.role === "user") {
      await supabase.from("messages").insert({
        conversation_id: convId,
        role: "user",
        content: lastUserMsg.content,
      });
    }
  }

  const result = streamText({
    model: openai("gpt-4o-mini"),
    system: systemMessage,
    messages,
    async onFinish({ text }) {
      if (!test && convId) {
        await supabase.from("messages").insert({
          conversation_id: convId,
          role: "assistant",
          content: text,
        });
      }
    },
  });

  return result.toTextStreamResponse({
    headers: {
      "X-Conversation-Id": convId ?? "",
    },
  });
}
