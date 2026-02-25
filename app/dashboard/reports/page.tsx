"use client";

import { useEffect, useState } from "react";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Switch } from "@/components/ui/switch";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { PlanGate } from "@/components/dashboard/plan-gate";
import { ReportsSkeleton } from "@/components/dashboard/skeletons";
import { FileText, Send, CheckCircle2, Loader2 } from "lucide-react";
import { toast } from "sonner";

interface ReportSettings {
  cycle: string;
  enabled: boolean;
  last_sent_at: string | null;
}

export default function ReportsPage() {
  const [settings, setSettings] = useState<ReportSettings | null>(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [sending, setSending] = useState(false);

  useEffect(() => {
    async function load() {
      try {
        const res = await fetch("/api/reports/settings");
        if (res.ok) {
          const data = await res.json();
          setSettings(data.settings);
        }
      } catch {
        toast.error("設定の読み込みに失敗しました");
      } finally {
        setLoading(false);
      }
    }
    load();
  }, []);

  async function handleSave(updates: Partial<ReportSettings>) {
    if (!settings) return;
    const newSettings = { ...settings, ...updates };
    setSettings(newSettings);
    setSaving(true);

    try {
      const res = await fetch("/api/reports/settings", {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          cycle: newSettings.cycle,
          enabled: newSettings.enabled,
        }),
      });

      if (!res.ok) throw new Error();
      const data = await res.json();
      setSettings(data.settings);
      toast.success("設定を保存しました");
    } catch {
      toast.error("設定の保存に失敗しました");
    } finally {
      setSaving(false);
    }
  }

  async function handleTestSend() {
    setSending(true);
    try {
      const res = await fetch("/api/reports/send", { method: "POST" });
      if (!res.ok) {
        const data = await res.json();
        throw new Error(data.error || "送信失敗");
      }
      const data = await res.json();
      toast.success(`レポートを ${data.sentTo} に送信しました`);
      // last_sent_at更新
      setSettings((prev) =>
        prev ? { ...prev, last_sent_at: new Date().toISOString() } : prev
      );
    } catch (error) {
      toast.error(
        error instanceof Error ? error.message : "レポートの送信に失敗しました"
      );
    } finally {
      setSending(false);
    }
  }

  function getNextSendDate(): string {
    if (!settings?.last_sent_at || !settings.enabled) return "ー";
    const last = new Date(settings.last_sent_at);
    switch (settings.cycle) {
      case "daily":
        last.setDate(last.getDate() + 1);
        break;
      case "weekly":
        last.setDate(last.getDate() + 7);
        break;
      case "monthly":
        last.setMonth(last.getMonth() + 1);
        break;
    }
    return last.toLocaleDateString("ja-JP", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  }

  if (loading) return <ReportsSkeleton />;

  return (
    <PlanGate feature="reports">
      <div>
        <h1 className="text-2xl font-bold">レポート</h1>
        <p className="mt-1 text-sm text-muted-foreground">
          チャット分析レポートを定期的にメールで受け取ることができます
        </p>

        <div className="mt-6 grid gap-6 lg:grid-cols-2">
          {/* 配信設定 */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <FileText className="h-5 w-5" />
                レポート配信設定
              </CardTitle>
              <CardDescription>
                レポートの配信サイクルと有効/無効を設定します
              </CardDescription>
            </CardHeader>
            <CardContent className="space-y-6">
              <div className="flex items-center justify-between">
                <Label htmlFor="report-enabled" className="text-sm font-medium">
                  レポート配信を有効にする
                </Label>
                <Switch
                  id="report-enabled"
                  checked={settings?.enabled ?? false}
                  onCheckedChange={(checked) =>
                    handleSave({ enabled: checked })
                  }
                  disabled={saving}
                />
              </div>

              <div className="space-y-2">
                <Label className="text-sm font-medium">配信サイクル</Label>
                <Select
                  value={settings?.cycle ?? "weekly"}
                  onValueChange={(value) => handleSave({ cycle: value })}
                  disabled={saving}
                >
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="daily">日次（毎日）</SelectItem>
                    <SelectItem value="weekly">週次（毎週）</SelectItem>
                    <SelectItem value="monthly">月次（毎月）</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div className="rounded-lg bg-muted/50 p-4 text-sm">
                <div className="flex items-center justify-between">
                  <span className="text-muted-foreground">次回配信予定</span>
                  <span className="font-medium">{getNextSendDate()}</span>
                </div>
                {settings?.last_sent_at && (
                  <div className="mt-2 flex items-center justify-between">
                    <span className="text-muted-foreground">最終送信日時</span>
                    <span className="flex items-center gap-1 text-xs font-medium text-green-600">
                      <CheckCircle2 className="h-3 w-3" />
                      {new Date(settings.last_sent_at).toLocaleString("ja-JP")}
                    </span>
                  </div>
                )}
              </div>
            </CardContent>
          </Card>

          {/* テスト送信 */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Send className="h-5 w-5" />
                テスト送信
              </CardTitle>
              <CardDescription>
                過去30日間の分析データでレポートを即時生成・送信します
              </CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="rounded-lg border p-4 text-sm space-y-2">
                <p className="font-medium">レポート内容:</p>
                <ul className="list-disc pl-5 space-y-1 text-muted-foreground">
                  <li>総会話数・総メッセージ数</li>
                  <li>よくある質問ランキング</li>
                  <li>未回答の質問</li>
                  <li>時間帯別会話分布</li>
                  <li>地域別統計</li>
                </ul>
              </div>

              <Button
                onClick={handleTestSend}
                disabled={sending}
                className="w-full gap-2"
              >
                {sending ? (
                  <>
                    <Loader2 className="h-4 w-4 animate-spin" />
                    送信中...
                  </>
                ) : (
                  <>
                    <Send className="h-4 w-4" />
                    テストレポートを送信
                  </>
                )}
              </Button>

              <p className="text-xs text-muted-foreground text-center">
                登録されたメールアドレスにPDFレポートが送信されます
              </p>
            </CardContent>
          </Card>
        </div>
      </div>
    </PlanGate>
  );
}
