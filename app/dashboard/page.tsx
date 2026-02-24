"use client";

import { useEffect, useState } from "react";
import { createClient } from "@/lib/supabase/client";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { HelpCircle, MessageSquare, MessagesSquare, TrendingUp } from "lucide-react";

interface Stats {
  faqCount: number;
  conversationCount: number;
  messageCount: number;
  todayConversations: number;
}

export default function DashboardPage() {
  const [stats, setStats] = useState<Stats>({
    faqCount: 0,
    conversationCount: 0,
    messageCount: 0,
    todayConversations: 0,
  });
  const [chatbotName, setChatbotName] = useState("");

  useEffect(() => {
    async function loadStats() {
      const supabase = createClient();

      const {
        data: { user },
      } = await supabase.auth.getUser();
      if (!user) return;

      const { data: chatbot } = await supabase
        .from("chatbots")
        .select("id, name")
        .eq("user_id", user.id)
        .single();

      if (!chatbot) return;
      setChatbotName(chatbot.name);

      const [faqRes, convRes, msgRes, todayRes] = await Promise.all([
        supabase
          .from("faqs")
          .select("id", { count: "exact", head: true })
          .eq("chatbot_id", chatbot.id),
        supabase
          .from("conversations")
          .select("id", { count: "exact", head: true })
          .eq("chatbot_id", chatbot.id),
        supabase
          .from("messages")
          .select("id", { count: "exact", head: true })
          .in(
            "conversation_id",
            (
              await supabase
                .from("conversations")
                .select("id")
                .eq("chatbot_id", chatbot.id)
            ).data?.map((c) => c.id) ?? []
          ),
        supabase
          .from("conversations")
          .select("id", { count: "exact", head: true })
          .eq("chatbot_id", chatbot.id)
          .gte("created_at", new Date().toISOString().split("T")[0]),
      ]);

      setStats({
        faqCount: faqRes.count ?? 0,
        conversationCount: convRes.count ?? 0,
        messageCount: msgRes.count ?? 0,
        todayConversations: todayRes.count ?? 0,
      });
    }
    loadStats();
  }, []);

  const cards = [
    {
      icon: HelpCircle,
      label: "登録FAQ数",
      value: stats.faqCount,
      color: "text-blue-600",
      bg: "bg-blue-50",
    },
    {
      icon: MessagesSquare,
      label: "総会話数",
      value: stats.conversationCount,
      color: "text-green-600",
      bg: "bg-green-50",
    },
    {
      icon: MessageSquare,
      label: "総メッセージ数",
      value: stats.messageCount,
      color: "text-purple-600",
      bg: "bg-purple-50",
    },
    {
      icon: TrendingUp,
      label: "本日の会話数",
      value: stats.todayConversations,
      color: "text-orange-600",
      bg: "bg-orange-50",
    },
  ];

  return (
    <div>
      <h1 className="text-2xl font-bold">ダッシュボード</h1>
      {chatbotName && (
        <p className="mt-1 text-muted-foreground">{chatbotName} の管理画面</p>
      )}
      <div className="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {cards.map((card) => (
          <Card key={card.label}>
            <CardHeader className="flex flex-row items-center gap-3 pb-2">
              <div className={`rounded-lg p-2 ${card.bg}`}>
                <card.icon className={`h-5 w-5 ${card.color}`} />
              </div>
              <span className="text-sm text-muted-foreground">{card.label}</span>
            </CardHeader>
            <CardContent>
              <span className="text-3xl font-bold">{card.value}</span>
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  );
}
