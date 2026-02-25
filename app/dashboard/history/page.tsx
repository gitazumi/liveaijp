"use client";

import { useEffect, useState, useCallback } from "react";
import { createClient } from "@/lib/supabase/client";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { MessageSquare, Download, Lock, MapPin } from "lucide-react";
import { Button } from "@/components/ui/button";
import { usePlan } from "@/lib/hooks/use-plan";
import { toast } from "sonner";
import Link from "next/link";
import { HistorySkeleton } from "@/components/dashboard/skeletons";
import { getCountryName } from "@/lib/country-names";

interface Conversation {
  id: string;
  created_at: string;
  country?: string | null;
  city?: string | null;
  messages: { role: string; content: string; created_at: string }[];
}

export default function HistoryPage() {
  const { canUse } = usePlan();
  const [conversations, setConversations] = useState<Conversation[]>([]);
  const [selected, setSelected] = useState<Conversation | null>(null);
  const [pageLoading, setPageLoading] = useState(true);

  const loadConversations = useCallback(async () => {
    const supabase = createClient();
    const {
      data: { user },
    } = await supabase.auth.getUser();
    if (!user) return;

    const { data: chatbot } = await supabase
      .from("chatbots")
      .select("id")
      .eq("user_id", user.id)
      .single();

    if (!chatbot) return;

    const { data: convs } = await supabase
      .from("conversations")
      .select("id, created_at, country, city")
      .eq("chatbot_id", chatbot.id)
      .order("created_at", { ascending: false })
      .limit(50);

    if (!convs || convs.length === 0) {
      setPageLoading(false);
      return;
    }

    // N+1解消: 全メッセージを1回のクエリで取得してJSでグルーピング
    const convIds = convs.map((c) => c.id);
    const { data: allMessages } = await supabase
      .from("messages")
      .select("conversation_id, role, content, created_at")
      .in("conversation_id", convIds)
      .order("created_at");

    const messagesByConv = new Map<string, { role: string; content: string; created_at: string }[]>();
    for (const msg of allMessages ?? []) {
      const list = messagesByConv.get(msg.conversation_id) ?? [];
      list.push({ role: msg.role, content: msg.content, created_at: msg.created_at });
      messagesByConv.set(msg.conversation_id, list);
    }

    const conversationsWithMessages: Conversation[] = convs.map((conv) => ({
      ...conv,
      messages: messagesByConv.get(conv.id) ?? [],
    }));

    setConversations(conversationsWithMessages);
    if (conversationsWithMessages.length > 0) {
      setSelected(conversationsWithMessages[0]);
    }
    setPageLoading(false);
  }, []);

  useEffect(() => {
    loadConversations();
  }, [loadConversations]);

  function formatDate(dateStr: string) {
    return new Date(dateStr).toLocaleString("ja-JP", {
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  }

  async function handleExport() {
    const res = await fetch("/api/history/export");
    if (!res.ok) {
      toast.error("エクスポートに失敗しました");
      return;
    }
    const blob = await res.blob();
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `chat_history_${new Date().toISOString().slice(0, 10)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
    toast.success("CSVをダウンロードしました");
  }

  if (pageLoading) return <HistorySkeleton />;

  return (
    <div>
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold">チャット履歴</h1>
          <p className="mt-1 text-sm text-muted-foreground">
            ユーザーとのチャット履歴を確認できます
          </p>
        </div>
        {canUse("csvExport") ? (
          <Button
            variant="outline"
            size="sm"
            className="gap-1.5"
            onClick={handleExport}
            disabled={conversations.length === 0}
          >
            <Download className="h-3.5 w-3.5" />
            CSVエクスポート
          </Button>
        ) : (
          <Button asChild variant="outline" size="sm" className="gap-1.5">
            <Link href="/dashboard/billing">
              <Lock className="h-3.5 w-3.5" />
              CSVエクスポート（アップグレード）
            </Link>
          </Button>
        )}
      </div>

      {conversations.length === 0 ? (
        <div className="mt-12 text-center text-muted-foreground">
          <MessageSquare className="mx-auto h-12 w-12 opacity-30" />
          <p className="mt-4">チャット履歴がまだありません</p>
        </div>
      ) : (
        <div className="mt-6 grid gap-6 lg:grid-cols-[320px_1fr]">
          <div className="space-y-2">
            {conversations.map((conv) => (
              <button
                key={conv.id}
                onClick={() => setSelected(conv)}
                className={`w-full rounded-lg border p-3 text-left text-sm transition-colors ${
                  selected?.id === conv.id
                    ? "border-primary bg-primary/5"
                    : "hover:bg-muted/50"
                }`}
              >
                <div className="flex items-center justify-between">
                  <span className="font-medium">
                    {conv.messages[0]?.content?.slice(0, 30) ?? "会話"}
                    {(conv.messages[0]?.content?.length ?? 0) > 30 && "..."}
                  </span>
                  <Badge variant="secondary" className="text-xs">
                    {conv.messages.length}件
                  </Badge>
                </div>
                <div className="mt-1 flex items-center gap-2 text-xs text-muted-foreground">
                  <span>{formatDate(conv.created_at)}</span>
                  {conv.country && (
                    <span className="flex items-center gap-0.5">
                      <MapPin className="h-3 w-3" />
                      {conv.city ? `${conv.city}, ` : ""}{getCountryName(conv.country)}
                    </span>
                  )}
                </div>
              </button>
            ))}
          </div>

          {selected && (
            <Card>
              <CardHeader className="pb-3">
                <div className="flex items-center gap-3 text-sm text-muted-foreground">
                  <span>{formatDate(selected.created_at)} の会話</span>
                  {selected.country && (
                    <span className="flex items-center gap-1 rounded-full bg-muted px-2 py-0.5 text-xs">
                      <MapPin className="h-3 w-3" />
                      {selected.city ? `${selected.city}, ` : ""}{getCountryName(selected.country)}
                    </span>
                  )}
                </div>
              </CardHeader>
              <CardContent className="space-y-3">
                {selected.messages.map((msg, i) => (
                  <div
                    key={i}
                    className={`flex ${msg.role === "user" ? "justify-end" : "justify-start"}`}
                  >
                    <div
                      className={`max-w-[80%] rounded-2xl px-4 py-2 text-sm ${
                        msg.role === "user"
                          ? "rounded-tr-md bg-primary text-white"
                          : "rounded-tl-md border bg-muted/50"
                      }`}
                    >
                      {msg.content}
                    </div>
                  </div>
                ))}
              </CardContent>
            </Card>
          )}
        </div>
      )}
    </div>
  );
}
