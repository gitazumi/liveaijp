"use client";

import { useEffect, useState, useCallback } from "react";
import { createClient } from "@/lib/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Copy, Check, Code2, Link2 } from "lucide-react";
import { toast } from "sonner";

export default function SettingsPage() {
  const [chatbotId, setChatbotId] = useState("");
  const [token, setToken] = useState("");
  const [name, setName] = useState("");
  const [greeting, setGreeting] = useState("");
  const [saving, setSaving] = useState(false);
  const [copied, setCopied] = useState<string | null>(null);

  const loadSettings = useCallback(async () => {
    const supabase = createClient();
    const {
      data: { user },
    } = await supabase.auth.getUser();
    if (!user) return;

    const { data: chatbot } = await supabase
      .from("chatbots")
      .select("*")
      .eq("user_id", user.id)
      .single();

    if (!chatbot) return;
    setChatbotId(chatbot.id);
    setToken(chatbot.token);
    setName(chatbot.name);
    setGreeting(chatbot.greeting ?? "");
  }, []);

  useEffect(() => {
    loadSettings();
  }, [loadSettings]);

  async function handleSave() {
    setSaving(true);
    const supabase = createClient();
    const { error } = await supabase
      .from("chatbots")
      .update({
        name: name.trim(),
        greeting: greeting.trim(),
      })
      .eq("id", chatbotId);

    if (error) {
      toast.error("保存に失敗しました");
    } else {
      toast.success("設定を保存しました");
    }
    setSaving(false);
  }

  function copyToClipboard(text: string, key: string) {
    navigator.clipboard.writeText(text);
    setCopied(key);
    toast.success("コピーしました");
    setTimeout(() => setCopied(null), 2000);
  }

  const origin = typeof window !== "undefined" ? window.location.origin : "";
  const snippetCode = `<script src="${origin}/api/widget/${token}"></script>`;
  const chatUrl = `${origin}/chat/${token}`;

  return (
    <div>
      <h1 className="text-2xl font-bold">設定</h1>
      <p className="mt-1 text-sm text-muted-foreground">
        チャットボットの設定と埋め込みコードを管理します
      </p>

      <div className="mt-6 space-y-6">
        <Card>
          <CardHeader>
            <h2 className="text-lg font-semibold">基本設定</h2>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label>チャットボット名</Label>
              <Input
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="マイチャットボット"
              />
            </div>
            <div className="space-y-2">
              <Label>挨拶メッセージ</Label>
              <Textarea
                value={greeting}
                onChange={(e) => setGreeting(e.target.value)}
                placeholder="こんにちは！なんでもお聞きください！"
                rows={3}
              />
              <p className="text-xs text-muted-foreground">
                チャット開始時にユーザーに表示されるメッセージです
              </p>
            </div>
            <Button onClick={handleSave} disabled={saving}>
              {saving ? "保存中..." : "設定を保存"}
            </Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <h2 className="text-lg font-semibold">チャットボットの公開方法</h2>
          </CardHeader>
          <CardContent>
            <Tabs defaultValue="snippet">
              <TabsList>
                <TabsTrigger value="snippet" className="gap-2">
                  <Code2 className="h-4 w-4" />
                  JSスニペット
                </TabsTrigger>
                <TabsTrigger value="url" className="gap-2">
                  <Link2 className="h-4 w-4" />
                  専用URL
                </TabsTrigger>
              </TabsList>
              <TabsContent value="snippet" className="mt-4 space-y-3">
                <p className="text-sm text-muted-foreground">
                  以下のコードをWebサイトの{" "}
                  <code className="rounded bg-muted px-1 py-0.5 text-xs">
                    &lt;/body&gt;
                  </code>{" "}
                  タグの直前に貼り付けてください。
                </p>
                <div className="relative">
                  <pre className="overflow-x-auto rounded-lg bg-muted p-4 text-sm">
                    {snippetCode}
                  </pre>
                  <Button
                    variant="ghost"
                    size="icon"
                    className="absolute right-2 top-2"
                    onClick={() => copyToClipboard(snippetCode, "snippet")}
                  >
                    {copied === "snippet" ? (
                      <Check className="h-4 w-4 text-green-600" />
                    ) : (
                      <Copy className="h-4 w-4" />
                    )}
                  </Button>
                </div>
              </TabsContent>
              <TabsContent value="url" className="mt-4 space-y-3">
                <p className="text-sm text-muted-foreground">
                  以下のURLを共有するだけで、チャットボットを利用できます。
                </p>
                <div className="relative">
                  <div className="flex items-center gap-2 rounded-lg bg-muted p-4">
                    <span className="flex-1 truncate text-sm">{chatUrl}</span>
                    <Button
                      variant="ghost"
                      size="icon"
                      onClick={() => copyToClipboard(chatUrl, "url")}
                    >
                      {copied === "url" ? (
                        <Check className="h-4 w-4 text-green-600" />
                      ) : (
                        <Copy className="h-4 w-4" />
                      )}
                    </Button>
                  </div>
                </div>
              </TabsContent>
            </Tabs>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
