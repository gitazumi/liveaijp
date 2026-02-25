"use client";

import { useEffect, useState } from "react";
import { useParams, useRouter } from "next/navigation";
import Link from "next/link";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import {
  ArrowLeft,
  HelpCircle,
  MessageSquare,
  Ban,
  CheckCircle,
} from "lucide-react";

interface UserDetail {
  profile: {
    id: string;
    company_name: string | null;
    role: string;
    created_at: string;
  };
  email: string;
  banned: boolean;
  chatbot: {
    id: string;
    name: string;
    token: string;
    greeting: string;
  } | null;
  faqs: { id: string; question: string; answer: string }[];
  conversations: { id: string; created_at: string }[];
}

export default function AdminUserDetailPage() {
  const { id } = useParams<{ id: string }>();
  const router = useRouter();
  const [data, setData] = useState<UserDetail | null>(null);
  const [loading, setLoading] = useState(true);
  const [toggling, setToggling] = useState(false);

  useEffect(() => {
    async function load() {
      const res = await fetch(`/api/admin/users/${id}`);
      if (res.ok) {
        setData(await res.json());
      }
      setLoading(false);
    }
    load();
  }, [id]);

  async function toggleBan() {
    if (!data) return;
    setToggling(true);
    const res = await fetch(`/api/admin/users/${id}`, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ banned: !data.banned }),
    });
    if (res.ok) {
      setData({ ...data, banned: !data.banned });
    }
    setToggling(false);
  }

  if (loading) {
    return (
      <div className="flex items-center justify-center py-20">
        <p className="text-sm text-muted-foreground">読み込み中...</p>
      </div>
    );
  }

  if (!data) {
    return (
      <div className="flex flex-col items-center justify-center py-20 gap-4">
        <p className="text-sm text-muted-foreground">
          ユーザーが見つかりません
        </p>
        <Button asChild variant="ghost">
          <Link href="/dashboard/admin">
            <ArrowLeft className="mr-1 h-4 w-4" />
            一覧に戻る
          </Link>
        </Button>
      </div>
    );
  }

  return (
    <div>
      <Button asChild variant="ghost" size="sm" className="mb-4">
        <Link href="/dashboard/admin">
          <ArrowLeft className="mr-1 h-4 w-4" />
          ユーザー一覧に戻る
        </Link>
      </Button>

      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold">{data.email}</h1>
          <p className="mt-1 text-sm text-muted-foreground">
            {data.profile.company_name || "会社名未設定"} ・ 登録日:{" "}
            {new Date(data.profile.created_at).toLocaleDateString("ja-JP")}
          </p>
        </div>
        <div className="flex items-center gap-3">
          {data.banned ? (
            <span className="rounded bg-red-100 px-3 py-1 text-sm text-red-700">
              無効
            </span>
          ) : (
            <span className="rounded bg-green-100 px-3 py-1 text-sm text-green-700">
              有効
            </span>
          )}
          <Button
            variant={data.banned ? "default" : "destructive"}
            size="sm"
            onClick={toggleBan}
            disabled={toggling}
          >
            {data.banned ? (
              <>
                <CheckCircle className="mr-1 h-3.5 w-3.5" />
                有効化
              </>
            ) : (
              <>
                <Ban className="mr-1 h-3.5 w-3.5" />
                無効化
              </>
            )}
          </Button>
        </div>
      </div>

      {/* チャットボット情報 */}
      <Card className="mt-6">
        <CardHeader>
          <h2 className="text-lg font-semibold">チャットボット</h2>
        </CardHeader>
        <CardContent>
          {data.chatbot ? (
            <div className="grid gap-3 text-sm sm:grid-cols-2">
              <div>
                <span className="text-muted-foreground">名前:</span>{" "}
                {data.chatbot.name}
              </div>
              <div>
                <span className="text-muted-foreground">トークン:</span>{" "}
                <code className="rounded bg-muted px-1.5 py-0.5 text-xs">
                  {data.chatbot.token}
                </code>
              </div>
              <div>
                <span className="text-muted-foreground">挨拶文:</span>{" "}
                {data.chatbot.greeting || "-"}
              </div>
            </div>
          ) : (
            <p className="text-sm text-muted-foreground">
              チャットボット未作成
            </p>
          )}
        </CardContent>
      </Card>

      {/* FAQ一覧 */}
      <Card className="mt-6">
        <CardHeader className="flex flex-row items-center gap-3">
          <HelpCircle className="h-5 w-5 text-green-600" />
          <h2 className="text-lg font-semibold">
            FAQ一覧（{data.faqs.length}件）
          </h2>
        </CardHeader>
        <CardContent>
          {data.faqs.length === 0 ? (
            <p className="text-sm text-muted-foreground">FAQなし</p>
          ) : (
            <div className="space-y-3">
              {data.faqs.map((faq) => (
                <div key={faq.id} className="rounded-lg border p-3">
                  <p className="font-medium text-sm">Q: {faq.question}</p>
                  <p className="mt-1 text-sm text-muted-foreground">
                    A: {faq.answer}
                  </p>
                </div>
              ))}
            </div>
          )}
        </CardContent>
      </Card>

      {/* 会話履歴 */}
      <Card className="mt-6">
        <CardHeader className="flex flex-row items-center gap-3">
          <MessageSquare className="h-5 w-5 text-purple-600" />
          <h2 className="text-lg font-semibold">
            最近の会話（{data.conversations.length}件）
          </h2>
        </CardHeader>
        <CardContent>
          {data.conversations.length === 0 ? (
            <p className="text-sm text-muted-foreground">会話なし</p>
          ) : (
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="border-b text-left text-muted-foreground">
                    <th className="pb-2 font-medium">会話ID</th>
                    <th className="pb-2 font-medium">日時</th>
                  </tr>
                </thead>
                <tbody>
                  {data.conversations.map((conv) => (
                    <tr key={conv.id} className="border-b">
                      <td className="py-2">
                        <code className="text-xs">{conv.id.slice(0, 8)}...</code>
                      </td>
                      <td className="py-2">
                        {new Date(conv.created_at).toLocaleString("ja-JP")}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
