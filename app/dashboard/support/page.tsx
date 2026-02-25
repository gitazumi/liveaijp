"use client";

import { useEffect, useState, useRef, useCallback } from "react";
import { createClient } from "@/lib/supabase/client";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";
import { Send, LifeBuoy } from "lucide-react";
import { Skeleton } from "@/components/ui/skeleton";

interface Message {
  id: string;
  sender_role: "user" | "admin";
  content: string;
  created_at: string;
}

interface Ticket {
  id: string;
  status: string;
  subject: string | null;
  created_at: string;
}

export default function SupportPage() {
  const [ticket, setTicket] = useState<Ticket | null>(null);
  const [messages, setMessages] = useState<Message[]>([]);
  const [input, setInput] = useState("");
  const [sending, setSending] = useState(false);
  const [loading, setLoading] = useState(true);
  const composingRef = useRef(false);
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const chatContainerRef = useRef<HTMLDivElement>(null);

  const scrollToBottom = useCallback(() => {
    if (chatContainerRef.current) {
      chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
    }
  }, []);

  // 初回ロード
  useEffect(() => {
    async function load() {
      const supabase = createClient();
      const {
        data: { user },
      } = await supabase.auth.getUser();
      if (!user) return;

      // 既存のオープンチケットを取得
      const { data: tickets } = await supabase
        .from("support_tickets")
        .select("*")
        .eq("user_id", user.id)
        .order("created_at", { ascending: false })
        .limit(1);

      if (tickets && tickets.length > 0) {
        setTicket(tickets[0]);
        // メッセージ取得
        const { data: msgs } = await supabase
          .from("support_messages")
          .select("*")
          .eq("ticket_id", tickets[0].id)
          .order("created_at");
        setMessages(msgs ?? []);

        // 既読にする（admin からのメッセージ）
        await supabase
          .from("support_messages")
          .update({ read_at: new Date().toISOString() })
          .eq("ticket_id", tickets[0].id)
          .eq("sender_role", "admin")
          .is("read_at", null);
      }
      setLoading(false);
    }
    load();
  }, []);

  // Realtime購読
  useEffect(() => {
    if (!ticket) return;
    const supabase = createClient();

    const channel = supabase
      .channel(`support-${ticket.id}`)
      .on(
        "postgres_changes",
        {
          event: "INSERT",
          schema: "public",
          table: "support_messages",
          filter: `ticket_id=eq.${ticket.id}`,
        },
        (payload) => {
          const newMsg = payload.new as Message;
          setMessages((prev) => {
            // 重複防止
            if (prev.some((m) => m.id === newMsg.id)) return prev;
            return [...prev, newMsg];
          });
          // admin メッセージを既読にする
          if (newMsg.sender_role === "admin") {
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
  }, [ticket]);

  useEffect(() => {
    scrollToBottom();
  }, [messages, scrollToBottom]);

  async function handleSend() {
    const text = input.trim();
    if (!text || sending) return;

    setSending(true);
    setInput("");
    const supabase = createClient();
    const {
      data: { user },
    } = await supabase.auth.getUser();
    if (!user) return;

    let ticketId = ticket?.id;

    // チケットがなければ作成
    if (!ticketId) {
      const { data: newTicket } = await supabase
        .from("support_tickets")
        .insert({
          user_id: user.id,
          subject: text.slice(0, 100),
        })
        .select("*")
        .single();

      if (!newTicket) {
        setSending(false);
        return;
      }
      setTicket(newTicket);
      ticketId = newTicket.id;
    }

    // メッセージ送信
    const { data: msg } = await supabase
      .from("support_messages")
      .insert({
        ticket_id: ticketId,
        sender_role: "user",
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
        <Skeleton className="h-8 w-36" />
        <Skeleton className="mt-2 h-4 w-56" />
        <Card className="mt-6">
          <CardContent className="space-y-3 pt-6">
            {Array.from({ length: 3 }).map((_, i) => (
              <div key={i} className={`flex ${i % 2 === 0 ? "justify-end" : "justify-start"}`}>
                <Skeleton className={`h-10 ${i % 2 === 0 ? "w-48" : "w-64"} rounded-2xl`} />
              </div>
            ))}
          </CardContent>
        </Card>
      </div>
    );
  }

  return (
    <div>
      <h1 className="text-2xl font-bold">お問い合わせ</h1>
      <p className="mt-1 text-sm text-muted-foreground">
        ご質問やお困りのことがあればお気軽にメッセージをお送りください
      </p>

      <Card className="mt-6">
        <CardHeader className="pb-3">
          <div className="flex items-center gap-2 text-sm text-muted-foreground">
            <LifeBuoy className="h-4 w-4" />
            {ticket ? (
              <span>
                チケット: {ticket.subject ?? "お問い合わせ"}
                {ticket.status === "closed" && (
                  <span className="ml-2 rounded bg-muted px-1.5 py-0.5 text-xs">クローズ済み</span>
                )}
              </span>
            ) : (
              <span>新しいお問い合わせを開始してください</span>
            )}
          </div>
        </CardHeader>
        <CardContent>
          <div className="flex flex-col rounded-lg border">
            <div
              ref={chatContainerRef}
              className="h-96 space-y-3 overflow-y-auto p-4"
            >
              {messages.length === 0 && (
                <div className="flex h-full items-center justify-center text-sm text-muted-foreground">
                  メッセージを送信してお問い合わせを開始しましょう
                </div>
              )}
              {messages.map((msg) => (
                <div
                  key={msg.id}
                  className={`flex ${msg.sender_role === "user" ? "justify-end" : "justify-start"}`}
                >
                  <div className="max-w-[80%]">
                    <div
                      className={`whitespace-pre-wrap rounded-2xl px-4 py-2.5 text-sm leading-relaxed ${
                        msg.sender_role === "user"
                          ? "rounded-tr-md bg-primary text-white"
                          : "rounded-tl-md bg-muted"
                      }`}
                    >
                      {msg.content}
                    </div>
                    <p className={`mt-0.5 text-[10px] text-muted-foreground ${
                      msg.sender_role === "user" ? "text-right" : "text-left"
                    }`}>
                      {msg.sender_role === "admin" && "サポート · "}
                      {formatTime(msg.created_at)}
                    </p>
                  </div>
                </div>
              ))}
              <div ref={messagesEndRef} />
            </div>
            {ticket?.status !== "closed" && (
              <div className="flex gap-2 border-t p-3">
                <Textarea
                  value={input}
                  onChange={(e) => setInput(e.target.value)}
                  onKeyDown={handleKeyDown}
                  onCompositionStart={() => { composingRef.current = true; }}
                  onCompositionEnd={() => { composingRef.current = false; }}
                  placeholder="メッセージを入力..."
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
    </div>
  );
}
