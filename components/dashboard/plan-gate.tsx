"use client";

import Link from "next/link";
import { Lock } from "lucide-react";
import { Button } from "@/components/ui/button";
import type { FeatureKey } from "@/lib/stripe";
import { usePlan } from "@/lib/hooks/use-plan";

const FEATURE_LABELS: Record<FeatureKey, { name: string; plan: string }> = {
  analytics: { name: "チャット分析", plan: "スタンダード" },
  widgetCustom: { name: "ウィジェットカスタマイズ", plan: "スタンダード" },
  csvExport: { name: "CSVインポート/エクスポート", plan: "スタンダード" },
  multilingual: { name: "多言語対応", plan: "プロ" },
  reports: { name: "レポート生成", plan: "プロ" },
};

interface PlanGateProps {
  feature: FeatureKey;
  children: React.ReactNode;
}

export function PlanGate({ feature, children }: PlanGateProps) {
  const { canUse, loading } = usePlan();

  if (loading) {
    return <>{children}</>;
  }

  if (canUse(feature)) {
    return <>{children}</>;
  }

  const label = FEATURE_LABELS[feature];

  return (
    <div className="relative">
      <div className="pointer-events-none select-none blur-[2px] opacity-50">
        {children}
      </div>
      <div className="absolute inset-0 flex items-center justify-center">
        <div className="rounded-xl border bg-background/95 px-8 py-6 text-center shadow-lg backdrop-blur">
          <Lock className="mx-auto h-8 w-8 text-muted-foreground" />
          <h3 className="mt-3 text-lg font-semibold">
            {label.name}
          </h3>
          <p className="mt-1 text-sm text-muted-foreground">
            この機能は{label.plan}プラン以上でご利用いただけます
          </p>
          <Button asChild className="mt-4">
            <Link href="/dashboard/billing">
              アップグレードする
            </Link>
          </Button>
        </div>
      </div>
    </div>
  );
}
