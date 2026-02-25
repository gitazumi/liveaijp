"use client";

import { useEffect, useState, useCallback } from "react";
import { createClient } from "@/lib/supabase/client";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Input } from "@/components/ui/input";
import { Calendar } from "@/components/ui/calendar";
import { MessageSquare, Download, Lock, MapPin, Search, X } from "lucide-react";
import { Button } from "@/components/ui/button";
import { usePlan } from "@/lib/hooks/use-plan";
import { useDebounce } from "@/lib/hooks/use-debounce";
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

  // カレンダー用
  const [selectedDate, setSelectedDate] = useState<Date | undefined>(undefined);
  const [dateCounts, setDateCounts] = useState<Record<string, number>>({});

  // 検索用
  const [searchQuery, setSearchQuery] = useState("");
  const debouncedQuery = useDebounce(searchQuery, 300);
  const [searching, setSearching] = useState(false);

  // 日付カウント取得
  useEffect(() => {
    async function loadDateCounts() {
      try {
        const res = await fetch("/api/history/dates");
        if (res.ok) {
          const data = await res.json();
          setDateCounts(data);
        }
      } catch {
        // silent fail
      }
    }
    loadDateCounts();
  }, []);

  // 全会話ロード
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

  // 日付選択 or 検索でフィルタリング
  useEffect(() => {
    const dateStr = selectedDate
      ? selectedDate.toISOString().slice(0, 10)
      : "";

    if (!debouncedQuery && !dateStr) return;

    async function search() {
      setSearching(true);
      try {
        const params = new URLSearchParams();
        if (debouncedQuery) params.set("q", debouncedQuery);
        if (dateStr) params.set("date", dateStr);

        const res = await fetch(`/api/history/search?${params.toString()}`);
        if (res.ok) {
          const data = await res.json();
          setConversations(data.conversations);
          setSelected(data.conversations[0] ?? null);
        }
      } catch {
        toast.error("検索に失敗しました");
      } finally {
        setSearching(false);
      }
    }

    search();
  }, [debouncedQuery, selectedDate]);

  function handleResetFilters() {
    setSelectedDate(undefined);
    setSearchQuery("");
    loadConversations();
  }

  function formatDate(dateStr: string) {
    return new Date(dateStr).toLocaleString("ja-JP", {
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  }

  // カレンダーに会話がある日をハイライト
  const datesWithConversations = Object.keys(dateCounts).map(
    (d) => new Date(d + "T00:00:00")
  );

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

  // 検索語のハイライト
  function highlightText(text: string, query: string) {
    if (!query) return text;
    const parts = text.split(new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, "\\$&")})`, "gi"));
    return parts.map((part, i) =>
      part.toLowerCase() === query.toLowerCase() ? (
        <mark key={i} className="bg-yellow-200 dark:bg-yellow-800 rounded px-0.5">
          {part}
        </mark>
      ) : (
        part
      )
    );
  }

  if (pageLoading) return <HistorySkeleton />;

  const isFiltered = !!selectedDate || !!searchQuery;

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

      <div className="mt-6 grid gap-6 lg:grid-cols-[320px_1fr]">
        {/* 左カラム: カレンダー + 検索 + 会話リスト */}
        <div className="space-y-4">
          {/* カレンダー */}
          <Card className="p-0">
            <CardContent className="p-2">
              <Calendar
                mode="single"
                selected={selectedDate}
                onSelect={setSelectedDate}
                modifiers={{
                  hasConversation: datesWithConversations,
                }}
                modifiersClassNames={{
                  hasConversation:
                    "relative after:absolute after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:h-1 after:w-1 after:rounded-full after:bg-primary",
                }}
              />
            </CardContent>
          </Card>

          {/* 検索窓 */}
          <div className="relative">
            <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
            <Input
              placeholder="チャット内容を検索..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="pl-9 pr-9"
            />
            {searchQuery && (
              <button
                onClick={() => setSearchQuery("")}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
              >
                <X className="h-4 w-4" />
              </button>
            )}
          </div>

          {/* フィルターリセット */}
          {isFiltered && (
            <div className="flex items-center justify-between text-sm">
              <span className="text-muted-foreground">
                {searching ? "検索中..." : `${conversations.length}件の会話`}
                {selectedDate && (
                  <span className="ml-1">
                    ({selectedDate.toLocaleDateString("ja-JP")})
                  </span>
                )}
              </span>
              <Button
                variant="ghost"
                size="sm"
                className="h-7 gap-1 text-xs"
                onClick={handleResetFilters}
              >
                <X className="h-3 w-3" />
                リセット
              </Button>
            </div>
          )}

          {/* 会話リスト */}
          <div className="space-y-2 max-h-[400px] overflow-y-auto">
            {conversations.length === 0 && !pageLoading ? (
              <div className="py-8 text-center text-sm text-muted-foreground">
                {isFiltered
                  ? "該当する会話が見つかりません"
                  : "チャット履歴がまだありません"}
              </div>
            ) : (
              conversations.map((conv) => (
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
                    <span className="font-medium truncate mr-2">
                      {searchQuery
                        ? highlightText(
                            conv.messages[0]?.content?.slice(0, 30) ?? "会話",
                            searchQuery
                          )
                        : (conv.messages[0]?.content?.slice(0, 30) ?? "会話") +
                          ((conv.messages[0]?.content?.length ?? 0) > 30
                            ? "..."
                            : "")}
                    </span>
                    <Badge variant="secondary" className="text-xs shrink-0">
                      {conv.messages.length}件
                    </Badge>
                  </div>
                  <div className="mt-1 flex items-center gap-2 text-xs text-muted-foreground">
                    <span>{formatDate(conv.created_at)}</span>
                    {conv.country && (
                      <span className="flex items-center gap-0.5">
                        <MapPin className="h-3 w-3" />
                        {conv.city ? `${conv.city}, ` : ""}
                        {getCountryName(conv.country)}
                      </span>
                    )}
                  </div>
                </button>
              ))
            )}
          </div>
        </div>

        {/* 右カラム: 会話詳細 */}
        {selected ? (
          <Card>
            <CardHeader className="pb-3">
              <div className="flex items-center gap-3 text-sm text-muted-foreground">
                <span>{formatDate(selected.created_at)} の会話</span>
                {selected.country && (
                  <span className="flex items-center gap-1 rounded-full bg-muted px-2 py-0.5 text-xs">
                    <MapPin className="h-3 w-3" />
                    {selected.city ? `${selected.city}, ` : ""}
                    {getCountryName(selected.country)}
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
                    {searchQuery
                      ? highlightText(msg.content, searchQuery)
                      : msg.content}
                  </div>
                </div>
              ))}
            </CardContent>
          </Card>
        ) : (
          <div className="flex items-center justify-center rounded-lg border border-dashed py-20">
            <div className="text-center text-muted-foreground">
              <MessageSquare className="mx-auto h-10 w-10 opacity-30" />
              <p className="mt-3 text-sm">
                {conversations.length === 0
                  ? "チャット履歴がまだありません"
                  : "左から会話を選択してください"}
              </p>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
