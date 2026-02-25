"use client";

import { useEffect, useState, Suspense } from "react";
import { useSearchParams } from "next/navigation";
import { createClient } from "@/lib/supabase/client";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { CheckCircle, CreditCard, Zap } from "lucide-react";

const PLANS = {
  free: { name: "無料", price: 0, faqLimit: 10, convLimit: 100 },
  standard: { name: "スタンダード", price: 3980, faqLimit: 50, convLimit: 1000 },
  pro: { name: "プロ", price: 9800, faqLimit: -1, convLimit: -1 },
};

interface Subscription {
  plan: string;
  status: string;
  current_period_end: string | null;
  stripe_customer_id: string | null;
}

interface Usage {
  faqCount: number;
  conversationCount: number;
}

function BillingContent() {
  const searchParams = useSearchParams();
  const success = searchParams.get("success");
  const [sub, setSub] = useState<Subscription | null>(null);
  const [usage, setUsage] = useState<Usage>({ faqCount: 0, conversationCount: 0 });
  const [loading, setLoading] = useState(true);
  const [redirecting, setRedirecting] = useState<string | null>(null);

  useEffect(() => {
    async function load() {
      const supabase = createClient();
      const {
        data: { user },
      } = await supabase.auth.getUser();
      if (!user) return;

      // サブスクリプション取得
      const { data: subscription } = await supabase
        .from("subscriptions")
        .select("*")
        .eq("user_id", user.id)
        .single();
      setSub(subscription);

      // 利用状況取得
      const { data: chatbot } = await supabase
        .from("chatbots")
        .select("id")
        .eq("user_id", user.id)
        .single();

      if (chatbot) {
        const { count: faqCount } = await supabase
          .from("faqs")
          .select("*", { count: "exact", head: true })
          .eq("chatbot_id", chatbot.id);

        const startOfMonth = new Date();
        startOfMonth.setDate(1);
        startOfMonth.setHours(0, 0, 0, 0);

        const { count: convCount } = await supabase
          .from("conversations")
          .select("*", { count: "exact", head: true })
          .eq("chatbot_id", chatbot.id)
          .gte("created_at", startOfMonth.toISOString());

        setUsage({
          faqCount: faqCount ?? 0,
          conversationCount: convCount ?? 0,
        });
      }

      setLoading(false);
    }
    load();
  }, []);

  async function handleCheckout(plan: "standard" | "pro") {
    setRedirecting(plan);
    const priceId =
      plan === "standard"
        ? process.env.NEXT_PUBLIC_STRIPE_STANDARD_PRICE_ID
        : process.env.NEXT_PUBLIC_STRIPE_PRO_PRICE_ID;

    const res = await fetch("/api/stripe/checkout", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ priceId }),
    });
    const { url } = await res.json();
    if (url) window.location.href = url;
    else setRedirecting(null);
  }

  async function handlePortal() {
    setRedirecting("portal");
    const res = await fetch("/api/stripe/portal", {
      method: "POST",
    });
    const { url } = await res.json();
    if (url) window.location.href = url;
    else setRedirecting(null);
  }

  if (loading) {
    return (
      <div className="flex items-center justify-center py-20">
        <p className="text-sm text-muted-foreground">読み込み中...</p>
      </div>
    );
  }

  const currentPlan = (sub?.plan as keyof typeof PLANS) ?? "free";
  const planInfo = PLANS[currentPlan] ?? PLANS.free;

  return (
    <div>
      <h1 className="text-2xl font-bold">課金・プラン管理</h1>
      <p className="mt-1 text-sm text-muted-foreground">
        現在のプランと利用状況を確認できます
      </p>

      {success && (
        <div className="mt-4 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
          <CheckCircle className="h-4 w-4" />
          プランのアップグレードが完了しました！
        </div>
      )}

      {/* 現在のプラン */}
      <Card className="mt-6">
        <CardHeader>
          <h2 className="text-lg font-semibold">現在のプラン</h2>
        </CardHeader>
        <CardContent>
          <div className="flex items-center justify-between">
            <div>
              <p className="text-2xl font-bold">{planInfo.name}</p>
              <p className="text-muted-foreground">
                {planInfo.price === 0
                  ? "無料"
                  : `¥${planInfo.price.toLocaleString()}/月`}
              </p>
              {sub?.status === "canceled" && sub.current_period_end && (
                <p className="mt-1 text-sm text-orange-600">
                  キャンセル済み（
                  {new Date(sub.current_period_end).toLocaleDateString("ja-JP")}
                  まで利用可能）
                </p>
              )}
            </div>
            {sub?.stripe_customer_id && (
              <Button
                variant="outline"
                onClick={handlePortal}
                disabled={redirecting === "portal"}
              >
                <CreditCard className="mr-2 h-4 w-4" />
                {redirecting === "portal" ? "移動中..." : "プラン管理"}
              </Button>
            )}
          </div>
        </CardContent>
      </Card>

      {/* 利用状況 */}
      <Card className="mt-6">
        <CardHeader>
          <h2 className="text-lg font-semibold">今月の利用状況</h2>
        </CardHeader>
        <CardContent className="space-y-4">
          <div>
            <div className="flex justify-between text-sm">
              <span>FAQ数</span>
              <span>
                {usage.faqCount} /{" "}
                {planInfo.faqLimit === -1 ? "無制限" : planInfo.faqLimit}
              </span>
            </div>
            {planInfo.faqLimit !== -1 && (
              <div className="mt-1.5 h-2 rounded-full bg-muted">
                <div
                  className="h-2 rounded-full bg-primary transition-all"
                  style={{
                    width: `${Math.min(
                      (usage.faqCount / planInfo.faqLimit) * 100,
                      100
                    )}%`,
                  }}
                />
              </div>
            )}
          </div>
          <div>
            <div className="flex justify-between text-sm">
              <span>月間会話数</span>
              <span>
                {usage.conversationCount} /{" "}
                {planInfo.convLimit === -1 ? "無制限" : planInfo.convLimit}
              </span>
            </div>
            {planInfo.convLimit !== -1 && (
              <div className="mt-1.5 h-2 rounded-full bg-muted">
                <div
                  className="h-2 rounded-full bg-primary transition-all"
                  style={{
                    width: `${Math.min(
                      (usage.conversationCount / planInfo.convLimit) * 100,
                      100
                    )}%`,
                  }}
                />
              </div>
            )}
          </div>
        </CardContent>
      </Card>

      {/* アップグレード */}
      {currentPlan !== "pro" && (
        <div className="mt-6 grid gap-4 md:grid-cols-2">
          {currentPlan === "free" && (
            <Card className="border-primary">
              <CardHeader>
                <h3 className="text-lg font-semibold">スタンダード</h3>
                <p className="text-2xl font-bold">
                  ¥3,980<span className="text-sm font-normal">/月</span>
                </p>
              </CardHeader>
              <CardContent>
                <ul className="space-y-2 text-sm">
                  <li className="flex items-center gap-2">
                    <CheckCircle className="h-4 w-4 text-green-500" />
                    FAQ 50件まで
                  </li>
                  <li className="flex items-center gap-2">
                    <CheckCircle className="h-4 w-4 text-green-500" />
                    月1,000会話まで
                  </li>
                  <li className="flex items-center gap-2">
                    <CheckCircle className="h-4 w-4 text-green-500" />
                    チャット履歴閲覧
                  </li>
                </ul>
                <Button
                  className="mt-4 w-full gap-2"
                  onClick={() => handleCheckout("standard")}
                  disabled={redirecting === "standard"}
                >
                  <Zap className="h-4 w-4" />
                  {redirecting === "standard"
                    ? "移動中..."
                    : "スタンダードにアップグレード"}
                </Button>
              </CardContent>
            </Card>
          )}
          <Card className="border-primary">
            <CardHeader>
              <h3 className="text-lg font-semibold">プロ</h3>
              <p className="text-2xl font-bold">
                ¥9,800<span className="text-sm font-normal">/月</span>
              </p>
            </CardHeader>
            <CardContent>
              <ul className="space-y-2 text-sm">
                <li className="flex items-center gap-2">
                  <CheckCircle className="h-4 w-4 text-green-500" />
                  FAQ無制限
                </li>
                <li className="flex items-center gap-2">
                  <CheckCircle className="h-4 w-4 text-green-500" />
                  会話無制限
                </li>
                <li className="flex items-center gap-2">
                  <CheckCircle className="h-4 w-4 text-green-500" />
                  優先サポート
                </li>
              </ul>
              <Button
                className="mt-4 w-full gap-2"
                onClick={() => handleCheckout("pro")}
                disabled={redirecting === "pro"}
              >
                <Zap className="h-4 w-4" />
                {redirecting === "pro" ? "移動中..." : "プロにアップグレード"}
              </Button>
            </CardContent>
          </Card>
        </div>
      )}
    </div>
  );
}

export default function BillingPage() {
  return (
    <Suspense
      fallback={
        <div className="flex items-center justify-center py-20">
          <p className="text-sm text-muted-foreground">読み込み中...</p>
        </div>
      }
    >
      <BillingContent />
    </Suspense>
  );
}
