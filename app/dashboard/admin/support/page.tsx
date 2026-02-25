"use client";

import { useEffect, useState, useRef, useCallback } from "react";
import { createClient } from "@/lib/supabase/client";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";
import { Badge } from "@/components/ui/badge";
import { Send, LifeBuoy, XCircle } from "lucide-react";
import { toast } from "sonner";
import { Skeleton } from "@/components/ui/skeleton";

interface Ticket {
  id: string;
  user_id: string;
  status: string;
  subject: string | null;
  created_at: string;
  email?: string;
  unread_count?: number;
  last_message?: string;
}

interface Message {
  id: string;
  sender_role: "user" | "admin";
  content: string;
  created_at: string;
}

export default function AdminSupportPage() {
  const [tickets, setTickets] = useState<Ticket[]>([]);
  const [selected, setSelected] = useState<Ticket | null>(null);
  const [messages, setMessages] = useState<Message[]>([]);
  const [input, setInput] = useState("");
  const [sending, setSending] = useState(false);
  const [loading, setLoading] = useState(true);
  const composingRef = useRef(false);
  const chatContainerRef = useRef<HTMLDivElement>(null);

  const scrollToBottom = useCallback(() => {
    if (chatContainerRef.current) {
      chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
    }
  }, []);

  // チケット一覧ロード
  const loadTickets = useCallback(async () => {
    try {
      const res = await fetch("/api/admin/support");
      if (res.ok) {
        const data = await res.json();
        setTickets(data.tickets ?? []);
      }
    } catch {
      // silent
    }
    setLoading(false);
  }, []);

  useEffect(() => {
    loadTickets();
    const interval = setInterval(loadTickets, 30_000);
    return () => clearInterval(interval);
  }, [loadTickets]);

  // チケット選択時にメッセージロード
  async function selectTicket(ticket: Ticket) {
    setSelected(ticket);
    const supabase = createClient();

    const { data: msgs } = await supabase
      .from("support_messages")
      .select("*")
      .eq("ticket_id", ticket.id)
      .order("created_at");
    setMessages(msgs ?? []);

    // ユーザーメッセージを既読にする
    await supabase
      .from("support_messages")
      .update({ read_at: new Date().toISOString() })
      .eq("ticket_id", ticket.id)
      .eq("sender_role", "user")
      .is("read_at", null);

    // 未読数を0に更新（ローカル）
    setTickets((prev) =>
      prev.map((t) => (t.id === ticket.id ? { ...t, unread_count: 0 } : t))
    );
  }

  // Realtime購読
  useEffect(() => {
    if (!selected) return;
    const supabase = createClient();

    const channel = supabase
      .channel(`admin-support-${selected.id}`)
      .on(
        "postgres_changes",
        {
          event: "INSERT",
          schema: "public",
          table: "support_messages",
          filter: `ticket_id=eq.${selected.id}`,
        },
        (payload) => {
          const newMsg = payload.new as Message;
          setMessages((prev) => {
            if (prev.some((m) => m.id === newMsg.id)) return prev;
            return [...prev, newMsg];
          });
          // ユーザーメッセージを既読にする
          if (newMsg.sender_role === "user") {
            supabase
              .from("support_messages")
              .update({ read_at: new Date().toISOString() })
              .eq("id", newMsg.id)
              .then();
          }
        }
      )
      .subscribe();

    return () => {
      supabase.removeChannel(channel);
    };
  }, [selected]);

  useEffect(() => {
    scrollToBottom();
  }, [messages, scrollToBottom]);

  async function handleSend() {
    const text = input.trim();
    if (!text || sending || !selected) return;

    setSending(true);
    setInput("");
    const supabase = createClient();

    const { data: msg } = await supabase
      .from("support_messages")
      .insert({
        ticket_id: selected.id,
        sender_role: "admin",
        content: text,
      })
      .select("*")
      .single();

    if (msg) {
      setMessages((prev) => {
        if (prev.some((m) => m.id === msg.id)) return prev;
        return [...prev, msg];
      });
    }

    setSending(false);
  }

  async function handleCloseTicket() {
    if (!selected) return;
    if (!confirm("このチケットをクローズしますか？")) return;

    const supabase = createClient();
    const { error } = await supabase
      .from("support_tickets")
      .update({ status: "closed", updated_at: new Date().toISOString() })
      .eq("id", selected.id);

    if (error) {
      toast.error("クローズに失敗しました");
    } else {
      toast.success("チケットをクローズしました");
      setSelected({ ...selected, status: "closed" });
      loadTickets();
    }
  }

  function handleKeyDown(e: React.KeyboardEvent) {
    if (e.key === "Enter" && !e.shiftKey && !composingRef.current) {
      e.preventDefault();
      handleSend();
    }
  }

  function formatTime(dateStr: string) {
    return new Date(dateStr).toLocaleString("ja-JP", {
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  }

  if (loading) {
    return (
      <div>
        <Skeleton className="h-8 w-48" />
        <Skeleton className="mt-2 h-4 w-64" />
        <div className="mt-6 grid gap-6 lg:grid-cols-[320px_1fr]">
          <div className="space-y-2">
            {Array.from({ length: 4 }).map((_, i) => (
              <Skeleton key={i} className="h-20 rounded-lg" />
            ))}
          </div>
          <Skeleton className="h-96 rounded-lg" />
        </div>
      </div>
    );
  }

  return (
    <div>
      <h1 className="text-2xl font-bold">お問い合わせ管理</h1>
      <p className="mt-1 text-sm text-muted-foreground">
        ユーザーからのお問い合わせを管理・対応します
      </p>

      {tickets.length === 0 ? (
        <div className="mt-12 text-center text-muted-foreground">
          <LifeBuoy className="mx-auto h-12 w-12 opacity-30" />
          <p className="mt-4">お問い合わせはまだありません</p>
        </div>
      ) : (
        <div className="mt-6 grid gap-6 lg:grid-cols-[320px_1fr]">
          {/* チケット一覧 */}
          <div className="space-y-2">
            {tickets.map((ticket) => (
              <button
                key={ticket.id}
                onClick={() => selectTicket(ticket)}
                className={`w-full rounded-lg border p-3 text-left text-sm transition-colors ${
                  selected?.id === ticket.id
                    ? "border-primary bg-primary/5"
                    : "hover:bg-muted/50"
                }`}
              >
                <div className="flex items-center justify-between">
                  <span className="font-medium truncate">
                    {ticket.subject ?? "お問い合わせ"}
                  </span>
                  <div className="flex items-center gap-1.5">
                    {(ticket.unread_count ?? 0) > 0 && (
                      <span className="flex h-5 min-w-5 items-center justify-center rounded-full bg-destructive px-1.5 text-[10px] font-bold text-white">
                        {ticket.unread_count}
                      </span>
                    )}
                    <Badge
                      variant={ticket.status === "open" ? "default" : "secondary"}
                      className="text-[10px]"
                    >
                      {ticket.status === "open" ? "対応中" : "クローズ"}
                    </Badge>
                  </div>
                </div>
                <p className="mt-1 truncate text-xs text-muted-foreground">
                  {ticket.email ?? "不明なユーザー"}
                </p>
                <div className="mt-1 flex items-center justify-between text-xs text-muted-foreground">
                  <span className="truncate flex-1">{ticket.last_message ?? ""}</span>
                  <span className="ml-2 shrink-0">{formatTime(ticket.created_at)}</span>
                </div>
              </button>
            ))}
          </div>

          {/* チャット */}
          {selected ? (
            <Card>
              <CardHeader className="flex flex-row items-center justify-between pb-3">
                <div className="text-sm text-muted-foreground">
                  <span className="font-medium text-foreground">
                    {selected.subject ?? "お問い合わせ"}
                  </span>
                  <span className="ml-2">{selected.email}</span>
                </div>
                {selected.status === "open" && (
                  <Button
                    variant="outline"
                    size="sm"
                    className="gap-1.5 text-destructive"
                    onClick={handleCloseTicket}
                  >
                    <XCircle className="h-3.5 w-3.5" />
                    クローズ
                  </Button>
                )}
              </CardHeader>
              <CardContent>
                <div className="flex flex-col rounded-lg border">
                  <div
                    ref={chatContainerRef}
                    className="h-96 space-y-3 overflow-y-auto p-4"
                  >
                    {messages.map((msg) => (
                      <div
                        key={msg.id}
                        className={`flex ${msg.sender_role === "admin" ? "justify-end" : "justify-start"}`}
                      >
                        <div className="max-w-[80%]">
                          <div
                            className={`whitespace-pre-wrap rounded-2xl px-4 py-2.5 text-sm leading-relaxed ${
                              msg.sender_role === "admin"
                                ? "rounded-tr-md bg-primary text-white"
                                : "rounded-tl-md bg-muted"
                            }`}
                          >
                            {msg.content}
                          </div>
                          <p className={`mt-0.5 text-[10px] text-muted-foreground ${
                            msg.sender_role === "admin" ? "text-right" : "text-left"
                          }`}>
                            {msg.sender_role === "user" && "ユーザー · "}
                            {formatTime(msg.created_at)}
                          </p>
                        </div>
                      </div>
                    ))}
                  </div>
                  {selected.status !== "closed" && (
                    <div className="flex gap-2 border-t p-3">
                      <Textarea
                        value={input}
                        onChange={(e) => setInput(e.target.value)}
                        onKeyDown={handleKeyDown}
                        onCompositionStart={() => { composingRef.current = true; }}
                        onCompositionEnd={() => { composingRef.current = false; }}
                        placeholder="返信を入力..."
                        rows={1}
                        className="min-h-[40px] max-h-24 resize-none rounded-full px-4"
                      />
                      <Button
                        onClick={handleSend}
                        disabled={sending || !input.trim()}
                        size="icon"
                        className="h-10 w-10 shrink-0 rounded-full"
                      >
                        <Send className="h-4 w-4" />
                      </Button>
                    </div>
                  )}
                </div>
              </CardContent>
            </Card>
          ) : (
            <div className="flex items-center justify-center rounded-lg border border-dashed p-12 text-sm text-muted-foreground">
              チケットを選択してください
            </div>
          )}
        </div>
      )}
    </div>
  );
}
