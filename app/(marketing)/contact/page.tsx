"use client";

import { useState } from "react";
import { Header } from "@/components/landing/header";
import { Footer } from "@/components/landing/footer";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { createClient } from "@/lib/supabase/client";
import { toast } from "sonner";

export default function ContactPage() {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [message, setMessage] = useState("");
  const [loading, setLoading] = useState(false);
  const [sent, setSent] = useState(false);

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setLoading(true);

    const supabase = createClient();
    const { error } = await supabase.from("contacts").insert({
      name: name.trim(),
      email: email.trim(),
      message: message.trim(),
    });

    if (error) {
      toast.error("送信に失敗しました。もう一度お試しください。");
    } else {
      setSent(true);
    }
    setLoading(false);
  }

  return (
    <>
      <Header />
      <main className="mx-auto max-w-2xl px-4 py-16">
        <h1 className="text-3xl font-bold">お問い合わせ</h1>
        <p className="mt-2 text-muted-foreground">
          ご質問・ご要望がございましたらお気軽にお問い合わせください。
          1〜2営業日以内にご返信いたします。
        </p>

        <Card className="mt-8">
          <CardHeader>
            <h2 className="text-lg font-semibold">お問い合わせフォーム</h2>
          </CardHeader>
          <CardContent>
            {sent ? (
              <div className="rounded-md bg-primary/10 p-6 text-center">
                <p className="font-medium text-primary">
                  お問い合わせを受け付けました
                </p>
                <p className="mt-2 text-sm text-muted-foreground">
                  1〜2営業日以内にメールにてご返信いたします。
                </p>
              </div>
            ) : (
              <form onSubmit={handleSubmit} className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="name">お名前 *</Label>
                  <Input
                    id="name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="email">メールアドレス *</Label>
                  <Input
                    id="email"
                    type="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="message">お問い合わせ内容 *</Label>
                  <Textarea
                    id="message"
                    value={message}
                    onChange={(e) => setMessage(e.target.value)}
                    rows={5}
                    required
                  />
                </div>
                <Button type="submit" className="w-full" disabled={loading}>
                  {loading ? "送信中..." : "送信する"}
                </Button>
              </form>
            )}
          </CardContent>
        </Card>
      </main>
      <Footer />
    </>
  );
}
