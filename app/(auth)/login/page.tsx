"use client";

import Link from "next/link";
import { useState } from "react";
import { createClient } from "@/lib/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { MessageSquare, Mail } from "lucide-react";

export default function LoginPage() {
  const [email, setEmail] = useState("");
  const [sent, setSent] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setError("");
    setLoading(true);

    const supabase = createClient();
    const { error } = await supabase.auth.signInWithOtp({
      email,
      options: {
        emailRedirectTo: `${window.location.origin}/auth/callback`,
      },
    });

    if (error) {
      setError("エラーが発生しました。もう一度お試しください。");
      setLoading(false);
      return;
    }

    setSent(true);
    setLoading(false);
  }

  return (
    <div className="flex min-h-screen items-center justify-center bg-muted/30 px-4">
      <Card className="w-full max-w-md">
        <CardHeader className="text-center">
          <Link href="/" className="mx-auto flex items-center gap-2 font-bold text-xl">
            <MessageSquare className="h-6 w-6 text-primary" />
            LiveAI
          </Link>
          <h1 className="mt-4 text-2xl font-bold">ログイン / 新規登録</h1>
          <p className="text-sm text-muted-foreground">
            メールアドレスを入力するとログインリンクが届きます
          </p>
        </CardHeader>
        <CardContent>
          {sent ? (
            <div className="text-center">
              <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                <Mail className="h-8 w-8 text-primary" />
              </div>
              <h2 className="text-lg font-semibold">メールを送信しました</h2>
              <p className="mt-2 text-sm text-muted-foreground">
                <span className="font-medium text-foreground">{email}</span>
                {" "}にログインリンクを送信しました。
                メールを確認してリンクをクリックしてください。
              </p>
              <Button
                variant="ghost"
                className="mt-6"
                onClick={() => {
                  setSent(false);
                  setEmail("");
                }}
              >
                別のメールアドレスを使う
              </Button>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className="space-y-4">
              {error && (
                <div className="rounded-md bg-destructive/10 p-3 text-sm text-destructive">
                  {error}
                </div>
              )}
              <div className="space-y-2">
                <Label htmlFor="email">メールアドレス</Label>
                <Input
                  id="email"
                  type="email"
                  placeholder="mail@example.com"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                />
              </div>
              <p className="text-xs text-muted-foreground">
                アカウントがない場合は自動的に作成されます。
                登録することで、
                <Link href="/policy" className="text-primary hover:underline">
                  利用規約
                </Link>
                および
                <Link href="/privacy-policy" className="text-primary hover:underline">
                  プライバシーポリシー
                </Link>
                に同意したものとみなされます。
              </p>
              <Button type="submit" className="w-full" disabled={loading}>
                {loading ? "送信中..." : "ログインリンクを送信"}
              </Button>
            </form>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
