"use client";

import { useEffect, useState, useRef, use } from "react";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";
import { MessageSquare, Send } from "lucide-react";

interface Message {
  role: "user" | "assistant";
  content: string;
}

export default function ChatPage({
  params,
}: {
  params: Promise<{ token: string }>;
}) {
  const { token } = use(params);
  const [messages, setMessages] = useState<Message[]>([]);
  const [input, setInput] = useState("");
  const [sending, setSending] = useState(false);
  const [conversationId, setConversationId] = useState<string | null>(null);
  const [chatbotName, setChatbotName] = useState("LiveAI");
  const messagesEndRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    // 挨拶メッセージを表示
    setMessages([
      { role: "assistant", content: "こんにちは！なんでもお聞きください！" },
    ]);
  }, []);

  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [messages]);

  async function handleSend() {
    const text = input.trim();
    if (!text || sending) return;

    setSending(true);
    setInput("");

    const newMessages: Message[] = [...messages, { role: "user", content: text }];
    setMessages(newMessages);

    try {
      const res = await fetch("/api/chat", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          messages: newMessages.filter(
            (m) => m.role === "user" || m.role === "assistant"
          ),
          token,
          conversationId,
        }),
      });

      const convId = res.headers.get("X-Conversation-Id");
      if (convId) setConversationId(convId);

      const reader = res.body?.getReader();
      if (!reader) return;

      const decoder = new TextDecoder();
      let fullText = "";

      setMessages((prev) => [...prev, { role: "assistant", content: "" }]);

      while (true) {
        const { done, value } = await reader.read();
        if (done) break;

        const chunk = decoder.decode(value, { stream: true });
        fullText += chunk;
        setMessages((prev) => {
          const updated = [...prev];
          updated[updated.length - 1] = {
            role: "assistant",
            content: fullText,
          };
          return updated;
        });
      }
    } catch {
      setMessages((prev) => [
        ...prev,
        {
          role: "assistant",
          content: "エラーが発生しました。もう一度お試しください。",
        },
      ]);
    }

    setSending(false);
  }

  function handleKeyDown(e: React.KeyboardEvent) {
    if (e.key === "Enter" && !e.shiftKey && !e.nativeEvent.isComposing) {
      e.preventDefault();
      handleSend();
    }
  }

  return (
    <div className="flex min-h-screen flex-col bg-muted/20">
      <header className="border-b bg-primary px-4 py-3 text-white">
        <div className="mx-auto flex max-w-2xl items-center gap-2">
          <MessageSquare className="h-5 w-5" />
          <span className="font-semibold">{chatbotName}</span>
        </div>
      </header>

      <div className="mx-auto flex w-full max-w-2xl flex-1 flex-col">
        <div className="flex-1 space-y-3 overflow-y-auto p-4">
          {messages.map((msg, i) => (
            <div
              key={i}
              className={`flex ${msg.role === "user" ? "justify-end" : "justify-start"}`}
            >
              <div
                className={`max-w-[85%] whitespace-pre-wrap rounded-2xl px-4 py-2.5 text-sm leading-relaxed ${
                  msg.role === "user"
                    ? "rounded-tr-md bg-primary text-white"
                    : "rounded-tl-md border bg-white shadow-sm"
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
          <div ref={messagesEndRef} />
        </div>

        <div className="border-t bg-white p-4">
          <div className="flex gap-2">
            <Textarea
              value={input}
              onChange={(e) => setInput(e.target.value)}
              onKeyDown={handleKeyDown}
              placeholder="メッセージを入力..."
              rows={1}
              className="min-h-[44px] max-h-32 resize-none rounded-full px-4"
            />
            <Button
              onClick={handleSend}
              disabled={sending || !input.trim()}
              size="icon"
              className="h-11 w-11 shrink-0 rounded-full"
            >
              <Send className="h-4 w-4" />
            </Button>
          </div>
          <p className="mt-2 text-center text-xs text-muted-foreground">
            Powered by LiveAI
          </p>
        </div>
      </div>
    </div>
  );
}
