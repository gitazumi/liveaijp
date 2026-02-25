import { createClient } from "@/lib/supabase/server";
import { NextResponse } from "next/server";

export async function GET() {
  const supabase = await createClient();
  const {
    data: { user },
  } = await supabase.auth.getUser();

  if (!user) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  // admin確認
  const { data: profile } = await supabase
    .from("profiles")
    .select("role")
    .eq("id", user.id)
    .single();

  if (profile?.role !== "admin") {
    return NextResponse.json({ error: "Forbidden" }, { status: 403 });
  }

  // チケット一覧取得
  const { data: tickets } = await supabase
    .from("support_tickets")
    .select("*")
    .order("updated_at", { ascending: false });

  if (!tickets || tickets.length === 0) {
    return NextResponse.json({ tickets: [] });
  }

  // ユーザーメール取得
  const userIds = [...new Set(tickets.map((t) => t.user_id))];
  const { data: authUsers } = await supabase.auth.admin.listUsers();
  const emailMap = new Map<string, string>();
  for (const u of authUsers?.users ?? []) {
    if (userIds.includes(u.id)) {
      emailMap.set(u.id, u.email ?? "不明");
    }
  }

  // 各チケットの未読数・最新メッセージ取得
  const ticketIds = tickets.map((t) => t.id);
  const { data: allMessages } = await supabase
    .from("support_messages")
    .select("ticket_id, sender_role, content, read_at, created_at")
    .in("ticket_id", ticketIds)
    .order("created_at", { ascending: false });

  const unreadByTicket = new Map<string, number>();
  const lastMessageByTicket = new Map<string, string>();

  for (const msg of allMessages ?? []) {
    // 未読カウント（ユーザーからの未読メッセージ）
    if (msg.sender_role === "user" && !msg.read_at) {
      unreadByTicket.set(msg.ticket_id, (unreadByTicket.get(msg.ticket_id) ?? 0) + 1);
    }
    // 最新メッセージ
    if (!lastMessageByTicket.has(msg.ticket_id)) {
      lastMessageByTicket.set(msg.ticket_id, msg.content.slice(0, 50));
    }
  }

  const enrichedTickets = tickets.map((t) => ({
    ...t,
    email: emailMap.get(t.user_id) ?? "不明",
    unread_count: unreadByTicket.get(t.id) ?? 0,
    last_message: lastMessageByTicket.get(t.id) ?? "",
  }));

  // オープンが先、未読があるものが先
  enrichedTickets.sort((a, b) => {
    if (a.status !== b.status) return a.status === "open" ? -1 : 1;
    if (a.unread_count !== b.unread_count) return b.unread_count - a.unread_count;
    return 0;
  });

  return NextResponse.json({ tickets: enrichedTickets });
}
