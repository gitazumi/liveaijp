"use client";

import { useEffect, useState, useCallback } from "react";
import { useRouter } from "next/navigation";
import { createClient } from "@/lib/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { AlertTriangle } from "lucide-react";
import { toast } from "sonner";

export default function AccountPage() {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [companyName, setCompanyName] = useState("");
  const [saving, setSaving] = useState(false);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [confirmEmail, setConfirmEmail] = useState("");
  const [deleting, setDeleting] = useState(false);

  const loadProfile = useCallback(async () => {
    const supabase = createClient();
    const {
      data: { user },
    } = await supabase.auth.getUser();
    if (!user) return;
    setEmail(user.email ?? "");
    const { data: profile } = await supabase
      .from("profiles")
      .select("company_name")
      .eq("id", user.id)
      .single();
    if (profile) {
      setCompanyName(profile.company_name ?? "");
    }
  }, []);

  useEffect(() => {
    loadProfile();
  }, [loadProfile]);

  async function handleSave() {
    setSaving(true);
    const supabase = createClient();
    const {
      data: { user },
    } = await supabase.auth.getUser();
    if (!user) return;
    const { error } = await supabase
      .from("profiles")
      .update({ company_name: companyName.trim() })
      .eq("id", user.id);
    if (error) {
      toast.error("保存に失敗しました");
    } else {
      toast.success("アカウント情報を更新しました");
    }
    setSaving(false);
  }

  async function handleDeleteAccount() {
    if (confirmEmail !== email) return;
    setDeleting(true);
    try {
      const res = await fetch("/api/account/delete", { method: "DELETE" });
      const data = await res.json();
      if (!res.ok) {
        toast.error(data.error || "退会に失敗しました");
        setDeleting(false);
        return;
      }
      const supabase = createClient();
      await supabase.auth.signOut();
      router.push("/");
      router.refresh();
    } catch {
      toast.error("退会処理中にエラーが発生しました");
      setDeleting(false);
    }
  }

  return (
    <div>
      <h1 className="text-2xl font-bold">アカウント設定</h1>
      <p className="mt-1 text-sm text-muted-foreground">
        アカウント情報を管理します
      </p>

      <Card className="mt-6">
        <CardHeader>
          <h2 className="text-lg font-semibold">プロフィール</h2>
        </CardHeader>
        <CardContent className="space-y-4">
          <div className="space-y-2">
            <Label>メールアドレス</Label>
            <Input value={email} disabled />
            <p className="text-xs text-muted-foreground">
              メールアドレスは変更できません
            </p>
          </div>
          <div className="space-y-2">
            <Label>会社名・店舗名</Label>
            <Input
              value={companyName}
              onChange={(e) => setCompanyName(e.target.value)}
              placeholder="例: 株式会社サンプル"
            />
          </div>
          <Button onClick={handleSave} disabled={saving}>
            {saving ? "保存中..." : "保存"}
          </Button>
        </CardContent>
      </Card>

      <Card className="mt-6 border-red-200">
        <CardHeader>
          <h2 className="flex items-center gap-2 text-lg font-semibold text-red-600">
            <AlertTriangle className="h-5 w-5" />
            退会（アカウント削除）
          </h2>
          <p className="text-sm text-muted-foreground">
            アカウントを削除すると、全てのデータが完全に削除されます。この操作は取り消せません。
          </p>
        </CardHeader>
        <CardContent>
          <Dialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
            <DialogTrigger asChild>
              <Button variant="destructive">退会する</Button>
            </DialogTrigger>
            <DialogContent>
              <DialogHeader>
                <DialogTitle className="flex items-center gap-2 text-red-600">
                  <AlertTriangle className="h-5 w-5" />
                  本当に退会しますか？
                </DialogTitle>
              </DialogHeader>
              <div className="space-y-4">
                <p className="text-sm text-muted-foreground">
                  この操作は取り消せません。以下のデータが完全に削除されます：
                </p>
                <ul className="list-inside list-disc text-sm text-muted-foreground">
                  <li>登録したFAQ</li>
                  <li>チャットボットの設定</li>
                  <li>全てのチャット履歴</li>
                  <li>アカウント情報</li>
                </ul>
                <div className="space-y-2">
                  <Label>
                    確認のため、メールアドレス（
                    <span className="font-semibold">{email}</span>
                    ）を入力してください
                  </Label>
                  <Input
                    value={confirmEmail}
                    onChange={(e) => setConfirmEmail(e.target.value)}
                    placeholder={email}
                  />
                </div>
                <div className="flex justify-end gap-2">
                  <Button
                    variant="outline"
                    onClick={() => {
                      setDeleteDialogOpen(false);
                      setConfirmEmail("");
                    }}
                  >
                    キャンセル
                  </Button>
                  <Button
                    variant="destructive"
                    onClick={handleDeleteAccount}
                    disabled={confirmEmail !== email || deleting}
                  >
                    {deleting ? "退会処理中..." : "退会を確定"}
                  </Button>
                </div>
              </div>
            </DialogContent>
          </Dialog>
        </CardContent>
      </Card>
    </div>
  );
}
