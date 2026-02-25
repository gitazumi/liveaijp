"use client";

import { useEffect, useState, useCallback, useRef } from "react";
import { createClient } from "@/lib/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Copy, Check, Code2, Link2, MessageSquareText, Send, RotateCcw } from "lucide-react";
import { toast } from "sonner";

interface TestMessage {
  role: "user" | "assistant";
  content: string;
}

export default function SettingsPage() {
  const [chatbotId, setChatbotId] = useState("");
  const [token, setToken] = useState("");
  const [name, setName] = useState("");
  const [greeting, setGreeting] = useState("");
  const [saving, setSaving] = useState(false);
  const [copied, setCopied] = useState<string | null>(null);

  // テストチャット用state
  const [testMessages, setTestMessages] = useState<TestMessage[]>([]);
  const [testInput, setTestInput] = useState("");
  const [testSending, setTestSending] = useState(false);
  const testMessagesEndRef = useRef<HTMLDivElement>(null);

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
    setTestMessages([
      { role: "assistant", content: chatbot.greeting || "こんにちは！なんでもお聞きください！" },
    ]);
  }, []);

  useEffect(() => {
    loadSettings();
  }, [loadSettings]);

  useEffect(() => {
    testMessagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [testMessages]);

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

  async function handleTestSend() {
    const text = testInput.trim();
    if (!text || testSending || !token) return;

    setTestSending(true);
    setTestInput("");

    const newMessages: TestMessage[] = [...testMessages, { role: "user", content: text }];
    setTestMessages(newMessages);

    try {
      const res = await fetch("/api/chat", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          messages: newMessages.filter((m) => m.role === "user" || m.role === "assistant"),
          token,
          test: true,
        }),
      });

      const reader = res.body?.getReader();
      if (!reader) return;

      const decoder = new TextDecoder();
      let fullText = "";

      setTestMessages((prev) => [...prev, { role: "assistant", content: "" }]);

      while (true) {
        const { done, value } = await reader.read();
        if (done) break;
        const chunk = decoder.decode(value, { stream: true });
        fullText += chunk;
        setTestMessages((prev) => {
          const updated = [...prev];
          updated[updated.length - 1] = { role: "assistant", content: fullText };
          return updated;
        });
      }
    } catch {
      setTestMessages((prev) => [
        ...prev,
        { role: "assistant", content: "エラーが発生しました。もう一度お試しください。" },
      ]);
    }

    setTestSending(false);
  }

  function handleTestKeyDown(e: React.KeyboardEvent) {
    if (e.key === "Enter" && !e.shiftKey && !e.nativeEvent.isComposing) {
      e.preventDefault();
      handleTestSend();
    }
  }

  function handleTestReset() {
    setTestMessages([
      { role: "assistant", content: greeting || "こんにちは！なんでもお聞きください！" },
    ]);
    setTestInput("");
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

        {/* テストチャット */}
        <Card>
          <CardHeader className="flex flex-row items-center justify-between">
            <div>
              <h2 className="flex items-center gap-2 text-lg font-semibold">
                <MessageSquareText className="h-5 w-5" />
                チャットテスト
              </h2>
              <p className="text-sm text-muted-foreground">
                設置前にチャットボットの応答をテストできます（履歴には保存されません）
              </p>
            </div>
            <Button variant="outline" size="sm" onClick={handleTestReset} className="gap-1">
              <RotateCcw className="h-3.5 w-3.5" />
              リセット
            </Button>
          </CardHeader>
          <CardContent>
            <div className="flex flex-col rounded-lg border">
              <div className="h-80 space-y-3 overflow-y-auto p-4">
                {testMessages.map((msg, i) => (
                  <div
                    key={i}
                    className={`flex ${msg.role === "user" ? "justify-end" : "justify-start"}`}
                  >
                    <div
                      className={`max-w-[85%] whitespace-pre-wrap rounded-2xl px-4 py-2.5 text-sm leading-relaxed ${
                        msg.role === "user"
                          ? "rounded-tr-md bg-primary text-white"
                          : "rounded-tl-md bg-muted"
                      }`}
                    >
                      {msg.content || (
                        <span className="flex gap-1">
                          <span className="inline-block h-2 w-2 animate-bounce rounded-full bg-muted-foreground/40" />
                          <span className="inline-block h-2 w-2 animate-bounce rounded-full bg-muted-foreground/40 [animation-delay:0.2s]" />
                          <span className="inline-block h-2 w-2 animate-bounce rounded-full bg-muted-foreground/40 [animation-delay:0.4s]" />
                        </span>
                      )}
                    </div>
                  </div>
                ))}
                <div ref={testMessagesEndRef} />
              </div>
              <div className="flex gap-2 border-t p-3">
                <Textarea
                  value={testInput}
                  onChange={(e) => setTestInput(e.target.value)}
                  onKeyDown={handleTestKeyDown}
                  placeholder="テストメッセージを入力..."
                  rows={1}
                  className="min-h-[40px] max-h-24 resize-none rounded-full px-4"
                />
                <Button
                  onClick={handleTestSend}
                  disabled={testSending || !testInput.trim()}
                  size="icon"
                  className="h-10 w-10 shrink-0 rounded-full"
                >
                  <Send className="h-4 w-4" />
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
