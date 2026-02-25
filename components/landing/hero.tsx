"use client";

import Link from "next/link";
import { Button } from "@/components/ui/button";
import { motion, AnimatePresence } from "framer-motion";
import { ArrowRight, Bot, Sparkles, Send, Loader2 } from "lucide-react";
import { useState, useEffect, useRef, useCallback } from "react";

const DEMO_TOKEN = "6772cc2d-f404-41bc-96cd-2518d5641146";

// アニメーション用のデモ会話
const DEMO_CONVERSATION: { role: "user" | "assistant"; content: string }[] = [
  {
    role: "user",
    content: "どんなサービスですか？",
  },
  {
    role: "assistant",
    content:
      "LiveAIは、よくある質問を登録するだけでAIチャットボットを作れるサービスです。お店や会社のホームページに簡単に設置できます。",
  },
  {
    role: "user",
    content: "料金はかかりますか？",
  },
  {
    role: "assistant",
    content:
      "無料プランからお試しいただけます。スタンダードプラン（月額3,980円）やプロプラン（月額9,800円）もご用意しています。",
  },
];

function TypingIndicator() {
  return (
    <div className="flex justify-start">
      <div className="rounded-2xl rounded-tl-md border bg-muted/50 px-4 py-3 text-sm">
        <div className="flex gap-1">
          <span className="inline-block h-2 w-2 animate-bounce rounded-full bg-muted-foreground/40 [animation-delay:0ms]" />
          <span className="inline-block h-2 w-2 animate-bounce rounded-full bg-muted-foreground/40 [animation-delay:150ms]" />
          <span className="inline-block h-2 w-2 animate-bounce rounded-full bg-muted-foreground/40 [animation-delay:300ms]" />
        </div>
      </div>
    </div>
  );
}

function ChatDemo() {
  const [visibleMessages, setVisibleMessages] = useState<
    { role: "user" | "assistant"; content: string }[]
  >([]);
  const [showTyping, setShowTyping] = useState(false);
  const [demoComplete, setDemoComplete] = useState(false);
  const [input, setInput] = useState("");
  const [sending, setSending] = useState(false);
  const [conversationId, setConversationId] = useState<string | null>(null);
  const [streamingText, setStreamingText] = useState("");
  const chatContainerRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLTextAreaElement>(null);

  // 自動スクロール（チャットコンテナ内のみ）
  const scrollToBottom = useCallback(() => {
    const el = chatContainerRef.current;
    if (el) {
      el.scrollTop = el.scrollHeight;
    }
  }, []);

  useEffect(() => {
    scrollToBottom();
  }, [visibleMessages, showTyping, streamingText, scrollToBottom]);

  // デモアニメーション
  useEffect(() => {
    let cancelled = false;
    async function runDemo() {
      for (let i = 0; i < DEMO_CONVERSATION.length; i++) {
        if (cancelled) return;
        const msg = DEMO_CONVERSATION[i];

        if (msg.role === "assistant") {
          // タイピングインジケーター表示
          setShowTyping(true);
          await new Promise((r) => setTimeout(r, 1200));
          if (cancelled) return;
          setShowTyping(false);
        } else {
          // ユーザーメッセージは少し待ってから
          await new Promise((r) => setTimeout(r, 600));
          if (cancelled) return;
        }

        setVisibleMessages((prev) => [...prev, msg]);
        // メッセージ間の間隔
        await new Promise((r) => setTimeout(r, 500));
      }
      if (!cancelled) {
        // デモ完了後、少し待って入力欄を表示
        await new Promise((r) => setTimeout(r, 800));
        if (!cancelled) {
          setDemoComplete(true);
        }
      }
    }
    runDemo();
    return () => {
      cancelled = true;
    };
  }, []);

  // 入力欄表示時にフォーカス
  useEffect(() => {
    if (demoComplete) {
      inputRef.current?.focus();
    }
  }, [demoComplete]);

  async function handleSend() {
    const text = input.trim();
    if (!text || sending) return;

    const userMsg = { role: "user" as const, content: text };
    setVisibleMessages((prev) => [...prev, userMsg]);
    setInput("");
    setSending(true);
    setShowTyping(true);

    try {
      // チャットAPI呼び出し用のメッセージ履歴（デモ＋自由入力分）
      const allMessages = [...visibleMessages, userMsg].map((m) => ({
        role: m.role,
        content: m.content,
      }));

      const res = await fetch("/api/chat", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          messages: allMessages,
          token: DEMO_TOKEN,
          conversationId,
        }),
      });

      // conversationIdを取得
      const newConvId = res.headers.get("X-Conversation-Id");
      if (newConvId) setConversationId(newConvId);

      if (!res.ok || !res.body) {
        throw new Error("API error");
      }

      // ストリーミング読み取り
      setShowTyping(false);
      const reader = res.body.getReader();
      const decoder = new TextDecoder();
      let assistantText = "";

      while (true) {
        const { done, value } = await reader.read();
        if (done) break;
        assistantText += decoder.decode(value, { stream: true });
        setStreamingText(assistantText);
      }

      // ストリーミング完了 → メッセージとして確定
      setStreamingText("");
      setVisibleMessages((prev) => [
        ...prev,
        { role: "assistant", content: assistantText },
      ]);
    } catch {
      setShowTyping(false);
      setStreamingText("");
      setVisibleMessages((prev) => [
        ...prev,
        {
          role: "assistant",
          content: "申し訳ございません、エラーが発生しました。もう一度お試しください。",
        },
      ]);
    }

    setSending(false);
  }

  function handleKeyDown(e: React.KeyboardEvent) {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      handleSend();
    }
  }

  return (
    <div className="rounded-2xl border bg-white shadow-xl">
      {/* ヘッダー */}
      <div className="flex items-center gap-2 border-b px-6 py-3 text-sm font-medium text-muted-foreground">
        <Bot className="h-5 w-5 text-primary" />
        AIチャットデモ — 実際にお試しください
      </div>

      {/* メッセージエリア */}
      <div ref={chatContainerRef} className="h-[320px] overflow-y-auto px-6 py-4">
        <div className="space-y-3">
          <AnimatePresence>
            {visibleMessages.map((msg, i) => (
              <motion.div
                key={i}
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3 }}
                className={`flex ${msg.role === "user" ? "justify-end" : "justify-start"}`}
              >
                <div
                  className={`max-w-[80%] px-4 py-2 text-sm ${
                    msg.role === "user"
                      ? "rounded-2xl rounded-tr-md bg-primary text-white"
                      : "rounded-2xl rounded-tl-md border bg-muted/50"
                  }`}
                >
                  {msg.content}
                </div>
              </motion.div>
            ))}
          </AnimatePresence>

          {/* ストリーミング中のAI回答 */}
          {streamingText && (
            <motion.div
              initial={{ opacity: 0, y: 10 }}
              animate={{ opacity: 1, y: 0 }}
              className="flex justify-start"
            >
              <div className="max-w-[80%] rounded-2xl rounded-tl-md border bg-muted/50 px-4 py-2 text-sm">
                {streamingText}
              </div>
            </motion.div>
          )}

          {showTyping && <TypingIndicator />}
        </div>
      </div>

      {/* 入力エリア */}
      <AnimatePresence>
        {demoComplete && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: "auto" }}
            transition={{ duration: 0.4 }}
            className="border-t px-4 py-3"
          >
            <div className="flex items-end gap-2">
              <textarea
                ref={inputRef}
                value={input}
                onChange={(e) => setInput(e.target.value)}
                onKeyDown={handleKeyDown}
                placeholder="メッセージを入力して試してみてください..."
                rows={1}
                className="flex-1 resize-none rounded-lg border bg-muted/30 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary/30"
                disabled={sending}
              />
              <button
                onClick={handleSend}
                disabled={!input.trim() || sending}
                className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-primary text-white transition-colors hover:bg-primary/90 disabled:opacity-50"
              >
                {sending ? (
                  <Loader2 className="h-4 w-4 animate-spin" />
                ) : (
                  <Send className="h-4 w-4" />
                )}
              </button>
            </div>
            <p className="mt-1.5 text-center text-xs text-muted-foreground">
              Enter で送信 — 実際のAIが回答します
            </p>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}

export function Hero() {
  return (
    <section className="relative overflow-hidden py-20 md:py-32">
      <div className="absolute inset-0 -z-10 bg-gradient-to-b from-primary/5 to-transparent" />
      <div className="mx-auto max-w-6xl px-4 text-center">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
        >
          <div className="mx-auto mb-6 inline-flex items-center gap-2 rounded-full border bg-white px-4 py-1.5 text-sm text-muted-foreground shadow-sm">
            <Sparkles className="h-4 w-4 text-primary" />
            初期費用0円・プログラミング不要
          </div>

          <h1 className="mx-auto max-w-4xl text-4xl font-bold leading-tight tracking-tight md:text-6xl">
            お店や会社の問い合わせを
            <br />
            <span className="text-primary">AIが24時間</span>自動対応
          </h1>

          <p className="mx-auto mt-6 max-w-2xl text-lg text-muted-foreground md:text-xl">
            よくある質問を登録するだけで、あなたのビジネス専用のAIチャットボットが完成。
            電話やメールの対応件数を減らし、スタッフの負担を軽減します。
          </p>

          <div className="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
            <Button size="lg" asChild className="gap-2 text-base">
              <Link href="/login">
                無料で始める
                <ArrowRight className="h-4 w-4" />
              </Link>
            </Button>
            <Button variant="outline" size="lg" asChild className="gap-2 text-base">
              <a href="#features">
                <Bot className="h-4 w-4" />
                詳しく見る
              </a>
            </Button>
          </div>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 40 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.3 }}
          className="mx-auto mt-16 max-w-3xl"
        >
          <ChatDemo />
        </motion.div>
      </div>
    </section>
  );
}
