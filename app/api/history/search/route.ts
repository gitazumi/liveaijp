import { createClient } from "@/lib/supabase/server";
import { NextRequest, NextResponse } from "next/server";

/**
 * チャット履歴をメッセージ内容で検索
 * GET ?q=検索語&date=YYYY-MM-DD（オプション）
 */
export async function GET(req: NextRequest) {
  const supabase = await createClient();
  const {
    data: { user },
  } = await supabase.auth.getUser();
  if (!user) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const url = new URL(req.url);
  const query = url.searchParams.get("q")?.trim() ?? "";
  const date = url.searchParams.get("date")?.trim() ?? "";

  if (!query && !date) {
    return NextResponse.json({ conversations: [] });
  }

  const { data: chatbot } = await supabase
    .from("chatbots")
    .select("id")
    .eq("user_id", user.id)
    .single();

  if (!chatbot) {
    return NextResponse.json({ conversations: [] });
  }

  if (query) {
    // メッセージ内容で検索 → マッチする会話IDを取得
    // まずチャットボットの全会話IDを取得
    let convQuery = supabase
      .from("conversations")
      .select("id")
      .eq("chatbot_id", chatbot.id);

    if (date) {
      const dayStart = `${date}T00:00:00.000Z`;
      const dayEnd = `${date}T23:59:59.999Z`;
      convQuery = convQuery.gte("created_at", dayStart).lte("created_at", dayEnd);
    }

    const { data: chatbotConvs } = await convQuery;
    const chatbotConvIds = (chatbotConvs ?? []).map((c) => c.id);

    if (chatbotConvIds.length === 0) {
      return NextResponse.json({ conversations: [] });
    }

    // ILIKEでメッセージ内容を検索
    const { data: matchedMessages } = await supabase
      .from("messages")
      .select("conversation_id")
      .in("conversation_id", chatbotConvIds)
      .ilike("content", `%${query}%`);

    const matchedConvIds = [
      ...new Set((matchedMessages ?? []).map((m) => m.conversation_id)),
    ];

    if (matchedConvIds.length === 0) {
      return NextResponse.json({ conversations: [] });
    }

    // マッチした会話をメッセージ付きで取得
    const { data: conversations } = await supabase
      .from("conversations")
      .select("id, created_at, country, city")
      .in("id", matchedConvIds.slice(0, 20))
      .order("created_at", { ascending: false });

    const { data: allMessages } = await supabase
      .from("messages")
      .select("conversation_id, role, content, created_at")
      .in("conversation_id", matchedConvIds.slice(0, 20))
      .order("created_at");

    const messagesByConv = new Map<
      string,
      { role: string; content: string; created_at: string }[]
    >();
    for (const msg of allMessages ?? []) {
      const list = messagesByConv.get(msg.conversation_id) ?? [];
      list.push({
        role: msg.role,
        content: msg.content,
        created_at: msg.created_at,
      });
      messagesByConv.set(msg.conversation_id, list);
    }

    const result = (conversations ?? []).map((conv) => ({
      ...conv,
      messages: messagesByConv.get(conv.id) ?? [],
    }));

    return NextResponse.json({ conversations: result });
  }

  // 日付のみ指定
  if (date) {
    const dayStart = `${date}T00:00:00.000Z`;
    const dayEnd = `${date}T23:59:59.999Z`;

    const { data: conversations } = await supabase
      .from("conversations")
      .select("id, created_at, country, city")
      .eq("chatbot_id", chatbot.id)
      .gte("created_at", dayStart)
      .lte("created_at", dayEnd)
      .order("created_at", { ascending: false })
      .limit(50);

    const convIds = (conversations ?? []).map((c) => c.id);
    if (convIds.length === 0) {
      return NextResponse.json({ conversations: [] });
    }

    const { data: allMessages } = await supabase
      .from("messages")
      .select("conversation_id, role, content, created_at")
      .in("conversation_id", convIds)
      .order("created_at");

    const messagesByConv = new Map<
      string,
      { role: string; content: string; created_at: string }[]
    >();
    for (const msg of allMessages ?? []) {
      const list = messagesByConv.get(msg.conversation_id) ?? [];
      list.push({
        role: msg.role,
        content: msg.content,
        created_at: msg.created_at,
      });
      messagesByConv.set(msg.conversation_id, list);
    }

    const result = (conversations ?? []).map((conv) => ({
      ...conv,
      messages: messagesByConv.get(conv.id) ?? [],
    }));

    return NextResponse.json({ conversations: result });
  }

  return NextResponse.json({ conversations: [] });
}
