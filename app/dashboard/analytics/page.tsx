"use client";

import { useEffect, useState } from "react";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { PlanGate } from "@/components/dashboard/plan-gate";
import {
  BarChart3,
  MessageSquare,
  HelpCircle,
  Clock,
  TrendingUp,
  Plus,
} from "lucide-react";
import { toast } from "sonner";
import Link from "next/link";

interface AnalyticsData {
  totalConversations: number;
  totalMessages: number;
  topQuestions: { question: string; count: number }[];
  unanswered: { question: string; count: number }[];
  hourly: number[];
  daily: { date: string; count: number }[];
}

export default function AnalyticsPage() {
  const [data, setData] = useState<AnalyticsData | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function load() {
      try {
        const res = await fetch("/api/analytics");
        if (res.ok) {
          setData(await res.json());
        }
      } catch {
        toast.error("分析データの取得に失敗しました");
      }
      setLoading(false);
    }
    load();
  }, []);

  if (loading) {
    return (
      <div className="flex items-center justify-center py-20">
        <p className="text-sm text-muted-foreground">読み込み中...</p>
      </div>
    );
  }

  return (
    <PlanGate feature="analytics">
      <div>
        <h1 className="text-2xl font-bold">チャット分析</h1>
        <p className="mt-1 text-sm text-muted-foreground">
          過去30日間のチャットデータを分析します
        </p>

        {/* 概要カード */}
        <div className="mt-6 grid gap-4 sm:grid-cols-2">
          <Card>
            <CardContent className="flex items-center gap-4 pt-6">
              <div className="rounded-lg bg-blue-100 p-3">
                <MessageSquare className="h-6 w-6 text-blue-600" />
              </div>
              <div>
                <p className="text-sm text-muted-foreground">総会話数</p>
                <p className="text-2xl font-bold">
                  {data?.totalConversations ?? 0}
                </p>
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent className="flex items-center gap-4 pt-6">
              <div className="rounded-lg bg-purple-100 p-3">
                <BarChart3 className="h-6 w-6 text-purple-600" />
              </div>
              <div>
                <p className="text-sm text-muted-foreground">
                  ユーザーメッセージ数
                </p>
                <p className="text-2xl font-bold">
                  {data?.totalMessages ?? 0}
                </p>
              </div>
            </CardContent>
          </Card>
        </div>

        {/* よくある質問ランキング */}
        <Card className="mt-6">
          <CardHeader className="flex flex-row items-center gap-3">
            <HelpCircle className="h-5 w-5 text-green-600" />
            <h2 className="text-lg font-semibold">よくある質問 TOP10</h2>
          </CardHeader>
          <CardContent>
            {data?.topQuestions && data.topQuestions.length > 0 ? (
              <div className="space-y-2">
                {data.topQuestions.map((item, i) => (
                  <div
                    key={i}
                    className="flex items-center justify-between rounded-lg border px-4 py-2.5"
                  >
                    <div className="flex items-center gap-3">
                      <span className="flex h-6 w-6 items-center justify-center rounded-full bg-muted text-xs font-medium">
                        {i + 1}
                      </span>
                      <span className="text-sm">{item.question}</span>
                    </div>
                    <span className="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">
                      {item.count}回
                    </span>
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-sm text-muted-foreground">
                まだデータがありません
              </p>
            )}
          </CardContent>
        </Card>

        {/* 未回答の質問 */}
        <Card className="mt-6">
          <CardHeader className="flex flex-row items-center gap-3">
            <HelpCircle className="h-5 w-5 text-orange-500" />
            <h2 className="text-lg font-semibold">
              FAQに未登録の質問
            </h2>
          </CardHeader>
          <CardContent>
            {data?.unanswered && data.unanswered.length > 0 ? (
              <div className="space-y-2">
                {data.unanswered.map((item, i) => (
                  <div
                    key={i}
                    className="flex items-center justify-between rounded-lg border px-4 py-2.5"
                  >
                    <span className="text-sm">{item.question}</span>
                    <div className="flex items-center gap-2">
                      <span className="text-xs text-muted-foreground">
                        {item.count}回
                      </span>
                      <Button asChild variant="ghost" size="sm" className="h-7 gap-1">
                        <Link href="/dashboard/faqs">
                          <Plus className="h-3 w-3" />
                          FAQ追加
                        </Link>
                      </Button>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-sm text-muted-foreground">
                全ての質問がFAQでカバーされています
              </p>
            )}
          </CardContent>
        </Card>

        {/* 時間帯別チャート */}
        <Card className="mt-6">
          <CardHeader className="flex flex-row items-center gap-3">
            <Clock className="h-5 w-5 text-blue-500" />
            <h2 className="text-lg font-semibold">時間帯別会話数</h2>
          </CardHeader>
          <CardContent>
            {data?.hourly ? (
              <div className="flex items-end gap-1" style={{ height: 160 }}>
                {data.hourly.map((count, hour) => {
                  const max = Math.max(...data.hourly, 1);
                  const height = (count / max) * 100;
                  return (
                    <div
                      key={hour}
                      className="group relative flex flex-1 flex-col items-center"
                    >
                      <div
                        className="w-full rounded-t bg-blue-500 transition-colors hover:bg-blue-600"
                        style={{
                          height: `${height}%`,
                          minHeight: count > 0 ? 4 : 0,
                        }}
                      />
                      <span className="mt-1 text-[10px] text-muted-foreground">
                        {hour}
                      </span>
                      {count > 0 && (
                        <div className="absolute -top-6 hidden rounded bg-foreground px-1.5 py-0.5 text-[10px] text-background group-hover:block">
                          {count}
                        </div>
                      )}
                    </div>
                  );
                })}
              </div>
            ) : (
              <p className="text-sm text-muted-foreground">
                データがありません
              </p>
            )}
          </CardContent>
        </Card>

        {/* 日別推移チャート */}
        <Card className="mt-6">
          <CardHeader className="flex flex-row items-center gap-3">
            <TrendingUp className="h-5 w-5 text-green-500" />
            <h2 className="text-lg font-semibold">日別会話数（過去30日）</h2>
          </CardHeader>
          <CardContent>
            {data?.daily ? (
              <div
                className="flex items-end gap-[2px]"
                style={{ height: 160 }}
              >
                {data.daily.map((item) => {
                  const max = Math.max(...data.daily.map((d) => d.count), 1);
                  const height = (item.count / max) * 100;
                  return (
                    <div
                      key={item.date}
                      className="group relative flex flex-1 flex-col items-center"
                    >
                      <div
                        className="w-full rounded-t bg-green-500 transition-colors hover:bg-green-600"
                        style={{
                          height: `${height}%`,
                          minHeight: item.count > 0 ? 4 : 0,
                        }}
                      />
                      {item.date.endsWith("-01") ||
                      item.date.endsWith("-15") ? (
                        <span className="mt-1 text-[9px] text-muted-foreground">
                          {item.date.slice(5)}
                        </span>
                      ) : null}
                      {item.count > 0 && (
                        <div className="absolute -top-6 hidden rounded bg-foreground px-1.5 py-0.5 text-[10px] text-background group-hover:block">
                          {item.count}
                        </div>
                      )}
                    </div>
                  );
                })}
              </div>
            ) : (
              <p className="text-sm text-muted-foreground">
                データがありません
              </p>
            )}
          </CardContent>
        </Card>
      </div>
    </PlanGate>
  );
}
