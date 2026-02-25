"use client";

import { useEffect, useState } from "react";
import { createClient } from "@/lib/supabase/client";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { HelpCircle, MessageSquare, MessagesSquare, TrendingUp, BookOpen, ArrowRight } from "lucide-react";
import Link from "next/link";

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

      <Card className="mt-6">
        <CardHeader>
          <h2 className="flex items-center gap-2 text-lg font-semibold">
            <BookOpen className="h-5 w-5" />
            使い方ガイド
          </h2>
          <p className="text-sm text-muted-foreground">
            LiveAIを活用するための簡単4ステップ
          </p>
        </CardHeader>
        <CardContent>
          <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {[
              {
                step: 1,
                title: "FAQ登録",
                desc: "FAQ管理ページでよくある質問と回答を登録しましょう。営業時間、料金、アクセスなどがおすすめです。",
                href: "/dashboard/faqs",
                link: "FAQ管理へ",
              },
              {
                step: 2,
                title: "テスト",
                desc: "設定ページのチャットテストで、登録したFAQに対してAIがどう応答するか確認できます。",
                href: "/dashboard/settings",
                link: "設定ページへ",
              },
              {
                step: 3,
                title: "設置",
                desc: "設定ページの埋め込みコードをコピーして、お使いのWebサイトに貼り付けるだけで設置完了です。",
                href: "/dashboard/settings",
                link: "埋め込みコードへ",
              },
              {
                step: 4,
                title: "確認",
                desc: "チャット履歴でお客様との会話内容を確認し、FAQの改善に活用しましょう。",
                href: "/dashboard/history",
                link: "チャット履歴へ",
              },
            ].map((item) => (
              <div key={item.step} className="rounded-lg border p-4">
                <div className="flex items-center gap-2 text-sm font-semibold">
                  <span className="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-xs text-white">
                    {item.step}
                  </span>
                  {item.title}
                </div>
                <p className="mt-2 text-sm text-muted-foreground">{item.desc}</p>
                <Link
                  href={item.href}
                  className="mt-2 inline-flex items-center gap-1 text-sm text-primary hover:underline"
                >
                  {item.link} <ArrowRight className="h-3 w-3" />
                </Link>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
