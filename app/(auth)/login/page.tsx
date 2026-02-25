"use client";

import Link from "next/link";
import { useState, Suspense } from "react";
import { useSearchParams } from "next/navigation";
import { createClient } from "@/lib/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { MessageSquare, Mail } from "lucide-react";

function LoginForm() {
  const searchParams = useSearchParams();
  const callbackError = searchParams.get("error");
  const [email, setEmail] = useState("");
  const [sent, setSent] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setError("");
    setLoading(true);

    const supabase = createClient();
    const { error: otpError } = await supabase.auth.signInWithOtp({
      email,
      options: {
        emailRedirectTo: `${window.location.origin}/auth/callback`,
      },
    });

    if (otpError) {
      console.error("Supabase OTP error:", otpError.message, otpError.status);
      if (otpError.message.includes("Signups not allowed")) {
        setError("現在、新規登録を受け付けていません。管理者にお問い合わせください。");
      } else if (otpError.message.includes("rate limit")) {
        setError("送信回数の制限に達しました。しばらく待ってからもう一度お試しください。");
      } else {
        setError(`エラーが発生しました: ${otpError.message}`);
      }
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
              {callbackError === "callback_failed" && !error && (
                <div className="rounded-md bg-destructive/10 p-3 text-sm text-destructive">
                  ログインリンクの認証に失敗しました。もう一度お試しください。
                </div>
              )}
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

export default function LoginPage() {
  return (
    <Suspense>
      <LoginForm />
    </Suspense>
  );
}
