"use client";

import { useEffect, useState, useCallback } from "react";
import { createClient } from "@/lib/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Plus, Pencil, Trash2, ArrowUpCircle } from "lucide-react";
import { toast } from "sonner";
import Link from "next/link";

const PLAN_FAQ_LIMITS: Record<string, number> = {
  free: 10,
  standard: 50,
  pro: Infinity,
};

interface Faq {
  id: string;
  question: string;
  answer: string;
  sort_order: number;
}

export default function FaqsPage() {
  const [faqs, setFaqs] = useState<Faq[]>([]);
  const [chatbotId, setChatbotId] = useState<string>("");
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Faq | null>(null);
  const [question, setQuestion] = useState("");
  const [answer, setAnswer] = useState("");
  const [loading, setLoading] = useState(false);
  const [plan, setPlan] = useState<string>("free");
  const faqLimit = PLAN_FAQ_LIMITS[plan] ?? 10;
  const isAtLimit = faqLimit !== Infinity && faqs.length >= faqLimit;

  const loadFaqs = useCallback(async () => {
    const supabase = createClient();
    const {
      data: { user },
    } = await supabase.auth.getUser();
    if (!user) return;

    // プラン取得
    const { data: subscription } = await supabase
      .from("subscriptions")
      .select("plan")
      .eq("user_id", user.id)
      .single();
    if (subscription?.plan) setPlan(subscription.plan);

    const { data: chatbot } = await supabase
      .from("chatbots")
      .select("id")
      .eq("user_id", user.id)
      .single();

    if (!chatbot) return;
    setChatbotId(chatbot.id);

    const { data } = await supabase
      .from("faqs")
      .select("*")
      .eq("chatbot_id", chatbot.id)
      .order("sort_order");

    setFaqs(data ?? []);
  }, []);

  useEffect(() => {
    loadFaqs();
  }, [loadFaqs]);

  function openCreate() {
    setEditing(null);
    setQuestion("");
    setAnswer("");
    setOpen(true);
  }

  function openEdit(faq: Faq) {
    setEditing(faq);
    setQuestion(faq.question);
    setAnswer(faq.answer);
    setOpen(true);
  }

  async function handleSave() {
    if (!question.trim() || !answer.trim()) {
      toast.error("質問と回答を入力してください");
      return;
    }

    setLoading(true);
    const supabase = createClient();

    if (editing) {
      const { error } = await supabase
        .from("faqs")
        .update({
          question: question.trim(),
          answer: answer.trim(),
          updated_at: new Date().toISOString(),
        })
        .eq("id", editing.id);

      if (error) {
        toast.error("更新に失敗しました");
      } else {
        toast.success("FAQを更新しました");
      }
    } else {
      const { error } = await supabase.from("faqs").insert({
        chatbot_id: chatbotId,
        question: question.trim(),
        answer: answer.trim(),
        sort_order: faqs.length,
      });

      if (error) {
        toast.error("追加に失敗しました");
      } else {
        toast.success("FAQを追加しました");
      }
    }

    setLoading(false);
    setOpen(false);
    loadFaqs();
  }

  async function handleDelete(id: string) {
    if (!confirm("このFAQを削除しますか？")) return;

    const supabase = createClient();
    const { error } = await supabase.from("faqs").delete().eq("id", id);

    if (error) {
      toast.error("削除に失敗しました");
    } else {
      toast.success("FAQを削除しました");
      loadFaqs();
    }
  }

  return (
    <div>
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold">FAQ管理</h1>
          <p className="mt-1 text-sm text-muted-foreground">
            チャットボットが回答に使用するFAQを管理します
          </p>
        </div>
        <div className="flex items-center gap-3">
          {faqLimit !== Infinity && (
            <span className="text-sm text-muted-foreground">
              {faqs.length} / {faqLimit} 件
            </span>
          )}
          {isAtLimit ? (
            <Button asChild variant="outline" className="gap-2">
              <Link href="/dashboard/billing">
                <ArrowUpCircle className="h-4 w-4" />
                アップグレード
              </Link>
            </Button>
          ) : (
            <Dialog open={open} onOpenChange={setOpen}>
              <DialogTrigger asChild>
                <Button onClick={openCreate} className="gap-2">
                  <Plus className="h-4 w-4" />
                  FAQ追加
                </Button>
              </DialogTrigger>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>{editing ? "FAQ編集" : "FAQ追加"}</DialogTitle>
            </DialogHeader>
            <div className="space-y-4">
              <div className="space-y-2">
                <Label>質問</Label>
                <Input
                  placeholder="例: 営業時間を教えてください"
                  value={question}
                  onChange={(e) => setQuestion(e.target.value)}
                />
              </div>
              <div className="space-y-2">
                <Label>回答</Label>
                <Textarea
                  placeholder="例: 営業時間は平日 10:00〜18:00 です。"
                  value={answer}
                  onChange={(e) => setAnswer(e.target.value)}
                  rows={4}
                />
              </div>
              <div className="flex justify-end gap-2">
                <Button variant="outline" onClick={() => setOpen(false)}>
                  キャンセル
                </Button>
                <Button onClick={handleSave} disabled={loading}>
                  {loading ? "保存中..." : "保存"}
                </Button>
              </div>
            </div>
          </DialogContent>
        </Dialog>
          )}
        </div>
      </div>

      <div className="mt-6 rounded-lg border bg-card">
        {faqs.length === 0 ? (
          <div className="p-12 text-center text-muted-foreground">
            <p>FAQがまだ登録されていません</p>
            <p className="mt-1 text-sm">
              「FAQ追加」ボタンから最初のFAQを登録しましょう
            </p>
          </div>
        ) : (
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead className="w-12">#</TableHead>
                <TableHead>質問</TableHead>
                <TableHead className="hidden md:table-cell">回答</TableHead>
                <TableHead className="w-24 text-right">操作</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {faqs.map((faq, i) => (
                <TableRow key={faq.id}>
                  <TableCell className="text-muted-foreground">{i + 1}</TableCell>
                  <TableCell className="font-medium">{faq.question}</TableCell>
                  <TableCell className="hidden max-w-xs truncate text-muted-foreground md:table-cell">
                    {faq.answer}
                  </TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-1">
                      <Button
                        variant="ghost"
                        size="icon"
                        onClick={() => openEdit(faq)}
                      >
                        <Pencil className="h-4 w-4" />
                      </Button>
                      <Button
                        variant="ghost"
                        size="icon"
                        onClick={() => handleDelete(faq.id)}
                      >
                        <Trash2 className="h-4 w-4 text-destructive" />
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        )}
      </div>
    </div>
  );
}
