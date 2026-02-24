"use client";

import { useEffect, useState, useCallback } from "react";
import { createClient } from "@/lib/supabase/client";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { MessageSquare } from "lucide-react";

interface Conversation {
  id: string;
  created_at: string;
  messages: { role: string; content: string; created_at: string }[];
}

export default function HistoryPage() {
  const [conversations, setConversations] = useState<Conversation[]>([]);
  const [selected, setSelected] = useState<Conversation | null>(null);

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
      .select("id, created_at")
      .eq("chatbot_id", chatbot.id)
      .order("created_at", { ascending: false })
      .limit(50);

    if (!convs) return;

    const conversationsWithMessages: Conversation[] = [];
    for (const conv of convs) {
      const { data: messages } = await supabase
        .from("messages")
        .select("role, content, created_at")
        .eq("conversation_id", conv.id)
        .order("created_at");

      conversationsWithMessages.push({
        ...conv,
        messages: messages ?? [],
      });
    }

    setConversations(conversationsWithMessages);
    if (conversationsWithMessages.length > 0) {
      setSelected(conversationsWithMessages[0]);
    }
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

  return (
    <div>
      <h1 className="text-2xl font-bold">チャット履歴</h1>
      <p className="mt-1 text-sm text-muted-foreground">
        ユーザーとのチャット履歴を確認できます
      </p>

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
                <p className="mt-1 text-xs text-muted-foreground">
                  {formatDate(conv.created_at)}
                </p>
              </button>
            ))}
          </div>

          {selected && (
            <Card>
              <CardHeader className="pb-3">
                <p className="text-sm text-muted-foreground">
                  {formatDate(selected.created_at)} の会話
                </p>
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
