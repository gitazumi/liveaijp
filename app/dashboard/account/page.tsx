"use client";

import { useEffect, useState, useCallback } from "react";
import { createClient } from "@/lib/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { toast } from "sonner";

export default function AccountPage() {
  const [email, setEmail] = useState("");
  const [companyName, setCompanyName] = useState("");
  const [saving, setSaving] = useState(false);

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
    </div>
  );
}
