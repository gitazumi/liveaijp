"use client";

import { useEffect, useState, useCallback, useRef } from "react";
import { createClient } from "@/lib/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Copy, Check, Code2, Link2, MessageSquareText, Send, RotateCcw, Palette, Globe, ShieldCheck } from "lucide-react";
import { toast } from "sonner";
import { PlanGate } from "@/components/dashboard/plan-gate";
import { SettingsSkeleton } from "@/components/dashboard/skeletons";

interface TestMessage {
  role: "user" | "assistant";
  content: string;
}

export default function SettingsPage() {
  const [pageLoading, setPageLoading] = useState(true);
  const [chatbotId, setChatbotId] = useState("");
  const [token, setToken] = useState("");
  const [name, setName] = useState("");
  const [greeting, setGreeting] = useState("");
  const [widgetColor, setWidgetColor] = useState("#4f46e5");
  const [widgetDisplayName, setWidgetDisplayName] = useState("");
  const [widgetPlaceholder, setWidgetPlaceholder] = useState("メッセージを入力...");
  const [language, setLanguage] = useState("ja");
  const [allowedOrigins, setAllowedOrigins] = useState("");
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
    setWidgetColor(chatbot.widget_color ?? "#4f46e5");
    setWidgetDisplayName(chatbot.widget_display_name ?? "");
    setWidgetPlaceholder(chatbot.widget_placeholder ?? "メッセージを入力...");
    setLanguage(chatbot.language ?? "ja");
    setAllowedOrigins((chatbot.allowed_origins ?? []).join("\n"));
    setTestMessages([
      { role: "assistant", content: chatbot.greeting || "こんにちは！なんでもお聞きください！" },
    ]);
    setPageLoading(false);
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
        widget_color: widgetColor,
        widget_display_name: widgetDisplayName.trim() || null,
        widget_placeholder: widgetPlaceholder.trim() || "メッセージを入力...",
        language,
        allowed_origins: allowedOrigins.trim()
          ? allowedOrigins.split("\n").map((o) => o.trim()).filter(Boolean)
          : null,
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

  if (pageLoading) return <SettingsSkeleton />;

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

        {/* ウィジェットカスタマイズ（有料機能） */}
        <PlanGate feature="widgetCustom">
          <Card>
            <CardHeader>
              <h2 className="flex items-center gap-2 text-lg font-semibold">
                <Palette className="h-5 w-5" />
                ウィジェットデザイン
              </h2>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label>テーマカラー</Label>
                <div className="flex items-center gap-3">
                  <input
                    type="color"
                    value={widgetColor}
                    onChange={(e) => setWidgetColor(e.target.value)}
                    className="h-10 w-14 cursor-pointer rounded border"
                  />
                  <Input
                    value={widgetColor}
                    onChange={(e) => setWidgetColor(e.target.value)}
                    placeholder="#4f46e5"
                    className="w-32"
                  />
                  <div
                    className="flex h-10 w-10 items-center justify-center rounded-full text-white"
                    style={{ backgroundColor: widgetColor }}
                  >
                    <MessageSquareText className="h-5 w-5" />
                  </div>
                </div>
                <p className="text-xs text-muted-foreground">
                  チャットボタン・ヘッダー・ユーザーメッセージの背景色に適用されます
                </p>
              </div>
              <div className="space-y-2">
                <Label>表示名</Label>
                <Input
                  value={widgetDisplayName}
                  onChange={(e) => setWidgetDisplayName(e.target.value)}
                  placeholder="LiveAI（デフォルト）"
                />
                <p className="text-xs text-muted-foreground">
                  ウィジェットのヘッダーに表示される名前です
                </p>
              </div>
              <div className="space-y-2">
                <Label>プレースホルダー</Label>
                <Input
                  value={widgetPlaceholder}
                  onChange={(e) => setWidgetPlaceholder(e.target.value)}
                  placeholder="メッセージを入力..."
                />
                <p className="text-xs text-muted-foreground">
                  入力欄の案内テキストです
                </p>
              </div>
              <Button onClick={handleSave} disabled={saving}>
                {saving ? "保存中..." : "デザインを保存"}
              </Button>
            </CardContent>
          </Card>
        </PlanGate>

        {/* 許可オリジン設定（有料機能） */}
        <PlanGate feature="widgetCustom">
          <Card>
            <CardHeader>
              <h2 className="flex items-center gap-2 text-lg font-semibold">
                <ShieldCheck className="h-5 w-5" />
                埋め込み許可ドメイン
              </h2>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label>許可するドメイン（1行に1つ）</Label>
                <Textarea
                  value={allowedOrigins}
                  onChange={(e) => setAllowedOrigins(e.target.value)}
                  placeholder={"https://example.com\nhttps://www.example.com"}
                  rows={4}
                />
                <p className="text-xs text-muted-foreground">
                  空欄の場合、全てのサイトでウィジェットが利用可能です。
                  ドメインを指定すると、指定したサイトのみウィジェットが動作します。
                </p>
              </div>
              <Button onClick={handleSave} disabled={saving}>
                {saving ? "保存中..." : "ドメイン設定を保存"}
              </Button>
            </CardContent>
          </Card>
        </PlanGate>

        {/* 多言語対応（Proプラン機能） */}
        <PlanGate feature="multilingual">
          <Card>
            <CardHeader>
              <h2 className="flex items-center gap-2 text-lg font-semibold">
                <Globe className="h-5 w-5" />
                多言語対応
              </h2>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label>応答言語</Label>
                <select
                  value={language}
                  onChange={(e) => setLanguage(e.target.value)}
                  className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                >
                  <option value="ja">日本語のみ</option>
                  <option value="auto">自動検出（多言語対応）</option>
                </select>
                <p className="text-xs text-muted-foreground">
                  「自動検出」にすると、ユーザーの言語を自動判別して同じ言語で応答します（英語、中国語、韓国語など）
                </p>
              </div>
              <Button onClick={handleSave} disabled={saving}>
                {saving ? "保存中..." : "言語設定を保存"}
              </Button>
            </CardContent>
          </Card>
        </PlanGate>

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
