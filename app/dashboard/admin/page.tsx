"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Users, MessageSquare, HelpCircle, Eye } from "lucide-react";

const PLAN_LABELS: Record<string, string> = {
  free: "無料",
  standard: "スタンダード",
  pro: "プロ",
};

interface AdminUser {
  id: string;
  company_name: string | null;
  role: string;
  created_at: string;
  email: string;
  banned: boolean;
  plan: string;
  subscriptionStatus: string;
  chatbot: {
    id: string;
    name: string;
    token: string;
    faqCount: number;
    conversationCount: number;
  } | null;
}

export default function AdminPage() {
  const [users, setUsers] = useState<AdminUser[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function loadUsers() {
      const res = await fetch("/api/admin/users");
      if (res.ok) {
        const data = await res.json();
        setUsers(data);
      }
      setLoading(false);
    }
    loadUsers();
  }, []);

  const totalFaqs = users.reduce((sum, u) => sum + (u.chatbot?.faqCount ?? 0), 0);
  const totalConvs = users.reduce((sum, u) => sum + (u.chatbot?.conversationCount ?? 0), 0);

  return (
    <div>
      <h1 className="text-2xl font-bold">管理者ダッシュボード</h1>
      <p className="mt-1 text-sm text-muted-foreground">
        全ユーザーとチャットボットの管理
      </p>

      <div className="mt-6 grid gap-4 sm:grid-cols-3">
        <Card>
          <CardHeader className="flex flex-row items-center gap-3 pb-2">
            <div className="rounded-lg bg-blue-50 p-2">
              <Users className="h-5 w-5 text-blue-600" />
            </div>
            <span className="text-sm text-muted-foreground">登録ユーザー数</span>
          </CardHeader>
          <CardContent>
            <span className="text-3xl font-bold">{users.length}</span>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="flex flex-row items-center gap-3 pb-2">
            <div className="rounded-lg bg-green-50 p-2">
              <HelpCircle className="h-5 w-5 text-green-600" />
            </div>
            <span className="text-sm text-muted-foreground">総FAQ数</span>
          </CardHeader>
          <CardContent>
            <span className="text-3xl font-bold">{totalFaqs}</span>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="flex flex-row items-center gap-3 pb-2">
            <div className="rounded-lg bg-purple-50 p-2">
              <MessageSquare className="h-5 w-5 text-purple-600" />
            </div>
            <span className="text-sm text-muted-foreground">総会話数</span>
          </CardHeader>
          <CardContent>
            <span className="text-3xl font-bold">{totalConvs}</span>
          </CardContent>
        </Card>
      </div>

      <Card className="mt-6">
        <CardHeader>
          <h2 className="text-lg font-semibold">ユーザー一覧</h2>
        </CardHeader>
        <CardContent>
          {loading ? (
            <p className="text-sm text-muted-foreground">読み込み中...</p>
          ) : (
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="border-b text-left text-muted-foreground">
                    <th className="pb-3 font-medium">メール</th>
                    <th className="pb-3 font-medium">会社名</th>
                    <th className="pb-3 font-medium">プラン</th>
                    <th className="pb-3 font-medium">FAQ数</th>
                    <th className="pb-3 font-medium">会話数</th>
                    <th className="pb-3 font-medium">ステータス</th>
                    <th className="pb-3 font-medium">登録日</th>
                    <th className="pb-3 font-medium"></th>
                  </tr>
                </thead>
                <tbody>
                  {users.map((user) => (
                    <tr key={user.id} className="border-b">
                      <td className="py-3">{user.email}</td>
                      <td className="py-3">{user.company_name || "-"}</td>
                      <td className="py-3">
                        <span
                          className={`rounded px-2 py-0.5 text-xs ${
                            user.plan === "pro"
                              ? "bg-purple-100 text-purple-700"
                              : user.plan === "standard"
                              ? "bg-blue-100 text-blue-700"
                              : "bg-gray-100 text-gray-700"
                          }`}
                        >
                          {PLAN_LABELS[user.plan] ?? user.plan}
                        </span>
                      </td>
                      <td className="py-3">{user.chatbot?.faqCount ?? 0}</td>
                      <td className="py-3">{user.chatbot?.conversationCount ?? 0}</td>
                      <td className="py-3">
                        {user.banned ? (
                          <span className="rounded bg-red-100 px-2 py-0.5 text-xs text-red-700">
                            無効
                          </span>
                        ) : (
                          <span className="rounded bg-green-100 px-2 py-0.5 text-xs text-green-700">
                            有効
                          </span>
                        )}
                      </td>
                      <td className="py-3">
                        {new Date(user.created_at).toLocaleDateString("ja-JP")}
                      </td>
                      <td className="py-3">
                        <Button asChild variant="ghost" size="sm">
                          <Link href={`/dashboard/admin/users/${user.id}`}>
                            <Eye className="mr-1 h-3.5 w-3.5" />
                            詳細
                          </Link>
                        </Button>
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
