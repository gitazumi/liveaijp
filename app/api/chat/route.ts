import { openai } from "@ai-sdk/openai";
import { streamText } from "ai";
import { createClient } from "@supabase/supabase-js";
import { getPlanLimits, PlanType } from "@/lib/stripe";
import { checkRateLimit, getClientIp } from "@/lib/rate-limit";

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

  // --- レート制限 ---
  const clientIp = getClientIp(req);
  const ipLimit = checkRateLimit(
    { name: "chat-ip", windowMs: 60_000, maxRequests: 20 },
    clientIp
  );
  if (!ipLimit.success) {
    return new Response("リクエストが多すぎます。しばらく待ってからお試しください。", {
      status: 429,
      headers: {
        "Retry-After": String(Math.ceil((ipLimit.resetAt - Date.now()) / 1000)),
      },
    });
  }

  const tokenLimit = checkRateLimit(
    { name: "chat-token", windowMs: 60_000, maxRequests: 60 },
    token
  );
  if (!tokenLimit.success) {
    return new Response("このチャットボットへのリクエストが集中しています。少し待ってからお試しください。", {
      status: 429,
      headers: {
        "Retry-After": String(Math.ceil((tokenLimit.resetAt - Date.now()) / 1000)),
      },
    });
  }

  // --- メッセージバリデーション ---
  if (messages.length > 20) {
    return new Response("メッセージ履歴が長すぎます", { status: 400 });
  }

  for (const msg of messages) {
    if (typeof msg.content !== "string" || msg.content.length > 2000) {
      return new Response("メッセージが長すぎます（最大2000文字）", { status: 400 });
    }
    if (msg.role !== "user" && msg.role !== "assistant") {
      return new Response("不正なメッセージ形式です", { status: 400 });
    }
  }

  // --- 入力サニタイズ（HTMLタグ除去） ---
  const sanitizedMessages = messages.map((msg) => ({
    ...msg,
    content: msg.role === "user" ? msg.content.replace(/<[^>]*>/g, "") : msg.content,
  }));

  // チャットボットをトークンで取得 + FAQを並列取得
  const { data: chatbot } = await supabase
    .from("chatbots")
    .select("id, name, greeting, user_id, language, allowed_origins")
    .eq("token", token)
    .single();

  if (!chatbot) {
    return new Response("Chatbot not found", { status: 404 });
  }

  // プラン確認 + FAQ取得 + 会話数チェック + admin判定を並列実行
  const startOfMonth = new Date();
  startOfMonth.setDate(1);
  startOfMonth.setHours(0, 0, 0, 0);

  const [profileResult, subscriptionResult, faqsResult, countResult] = await Promise.all([
    // admin判定
    supabase
      .from("profiles")
      .select("role")
      .eq("id", chatbot.user_id)
      .single(),
    // プラン取得
    supabase
      .from("subscriptions")
      .select("plan")
      .eq("user_id", chatbot.user_id)
      .single(),
    // FAQ取得
    supabase
      .from("faqs")
      .select("question, answer")
      .eq("chatbot_id", chatbot.id)
      .order("sort_order"),
    // 月間会話数カウント（テストモードでなければ）
    !test
      ? supabase
          .from("conversations")
          .select("*", { count: "exact", head: true })
          .eq("chatbot_id", chatbot.id)
          .gte("created_at", startOfMonth.toISOString())
      : Promise.resolve({ count: 0 }),
  ]);

  const isAdmin = profileResult.data?.role === "admin";

  // 利用制限チェック（adminは無制限）
  if (!test && !isAdmin) {
    const plan = getPlanLimits((subscriptionResult.data?.plan as PlanType) ?? "free");
    if (plan.conversationLimit !== Infinity) {
      if ((countResult.count ?? 0) >= plan.conversationLimit) {
        return new Response(
          "月間会話数の上限に達しました。プランのアップグレードをご検討ください。",
          { status: 429 }
        );
      }
    }
  }

  // FAQコンテキストを構築
  const faqs = faqsResult.data;
  const faqContext =
    faqs && faqs.length > 0
      ? faqs.map((f) => `Q: ${f.question}\nA: ${f.answer}`).join("\n\n")
      : "FAQが登録されていません。";

  const isMultilingual = chatbot.language === "auto";

  const systemMessage = isMultilingual
    ? `あなたは「${chatbot.name}」というチャットボットアシスタントです。
以下のFAQ情報を元に、ユーザーの質問に丁寧に回答してください。
重要: ユーザーが使用している言語を自動検出し、同じ言語で回答してください。例えば英語で質問されたら英語で、中国語なら中国語で回答します。
FAQに関連する情報がない場合は、ユーザーの言語で「その質問にはお答えできません」と伝えてください。
回答は簡潔にわかりやすくしてください。

【FAQ情報】
${faqContext}`
    : `あなたは「${chatbot.name}」というチャットボットアシスタントです。
以下のFAQ情報を元に、ユーザーの質問に日本語で丁寧に回答してください。
FAQに関連する情報がない場合は、「申し訳ございませんが、その質問にはお答えできません。」と回答してください。
回答は簡潔にわかりやすくしてください。

【FAQ情報】
${faqContext}`;

  // 最新20件のみOpenAIへ送信（コスト制御）
  const recentMessages = sanitizedMessages.slice(-20);

  // DB保存を非同期で実行（ストリーミング開始をブロックしない）
  let convId = conversationId;
  const savePromise = (async () => {
    if (test) return;
    if (!convId) {
      const { data: conv } = await supabase
        .from("conversations")
        .insert({ chatbot_id: chatbot.id })
        .select("id")
        .single();
      convId = conv?.id;
    }
    const lastUserMsg = sanitizedMessages[sanitizedMessages.length - 1];
    if (convId && lastUserMsg.role === "user") {
      await supabase.from("messages").insert({
        conversation_id: convId,
        role: "user",
        content: lastUserMsg.content,
      });
    }
  })();

  const result = streamText({
    model: openai("gpt-4o-mini"),
    system: systemMessage,
    messages: recentMessages,
    async onFinish({ text }) {
      await savePromise; // ユーザーメッセージ保存完了を待つ
      if (!test && convId) {
        await supabase.from("messages").insert({
          conversation_id: convId,
          role: "assistant",
          content: text,
        });
      }
    },
  });

  // CORS オリジン制御
  const requestOrigin = req.headers.get("origin");
  const allowedOrigins: string[] | null = chatbot.allowed_origins;
  let corsOrigin = "*";
  if (allowedOrigins?.length && requestOrigin) {
    corsOrigin = allowedOrigins.includes(requestOrigin) ? requestOrigin : allowedOrigins[0];
  } else if (requestOrigin) {
    corsOrigin = requestOrigin;
  }

  return result.toTextStreamResponse({
    headers: {
      "X-Conversation-Id": convId ?? "",
      "Access-Control-Allow-Origin": corsOrigin,
      "Vary": "Origin",
    },
  });
}
