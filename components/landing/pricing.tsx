"use client";

import Link from "next/link";
import { motion } from "framer-motion";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Check } from "lucide-react";

const plans = [
  {
    name: "無料",
    price: "¥0",
    description: "まずはお試しに",
    features: [
      "AIチャットボット作成",
      "FAQ 10件まで",
      "月100会話まで",
      "ホームページへの埋め込み",
      "専用URLでのリンク共有",
    ],
    cta: "無料で始める",
    href: "/login",
    highlighted: false,
  },
  {
    name: "スタンダード",
    price: "¥3,980",
    description: "小規模店舗・事業者に",
    features: [
      "AIチャットボット作成",
      "FAQ 50件まで",
      "月1,000会話まで",
      "チャット履歴の閲覧・分析",
      "ホームページへの埋め込み",
      "専用URLでのリンク共有",
      "挨拶メッセージのカスタマイズ",
    ],
    cta: "スタンダードを始める",
    href: "/login",
    highlighted: true,
  },
  {
    name: "プロ",
    price: "¥9,800",
    description: "本格的に活用したい企業に",
    features: [
      "AIチャットボット作成",
      "FAQ 無制限",
      "会話 無制限",
      "チャット履歴の閲覧・分析",
      "ホームページへの埋め込み",
      "専用URLでのリンク共有",
      "挨拶メッセージのカスタマイズ",
      "優先サポート",
    ],
    cta: "プロを始める",
    href: "/login",
    highlighted: false,
  },
];

export function Pricing() {
  return (
    <section id="pricing" className="bg-muted/30 py-20">
      <div className="mx-auto max-w-6xl px-4">
        <div className="text-center">
          <h2 className="text-3xl font-bold md:text-4xl">料金プラン</h2>
          <p className="mt-3 text-muted-foreground">
            無料プランから始めて、ビジネスの成長に合わせてアップグレード
          </p>
        </div>
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="mt-12 grid gap-6 md:grid-cols-3"
        >
          {plans.map((plan) => (
            <Card
              key={plan.name}
              className={`relative overflow-hidden ${
                plan.highlighted
                  ? "border-primary shadow-lg scale-105"
                  : "border-border"
              }`}
            >
              {plan.highlighted && (
                <div className="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary to-primary/60" />
              )}
              <CardHeader className="text-center">
                {plan.highlighted && (
                  <p className="mb-1 text-xs font-semibold uppercase tracking-wider text-primary">
                    おすすめ
                  </p>
                )}
                <p className="text-lg font-semibold">{plan.name}</p>
                <div className="mt-2 flex items-baseline justify-center gap-1">
                  <span className="text-4xl font-bold">{plan.price}</span>
                  <span className="text-muted-foreground">/ 月</span>
                </div>
                <p className="mt-2 text-sm text-muted-foreground">
                  {plan.description}
                </p>
              </CardHeader>
              <CardContent>
                <ul className="space-y-3">
                  {plan.features.map((f) => (
                    <li key={f} className="flex items-start gap-3 text-sm">
                      <Check className="mt-0.5 h-4 w-4 shrink-0 text-primary" />
                      {f}
                    </li>
                  ))}
                </ul>
                <Button
                  asChild
                  className="mt-8 w-full"
                  size="lg"
                  variant={plan.highlighted ? "default" : "outline"}
                >
                  <Link href={plan.href}>{plan.cta}</Link>
                </Button>
              </CardContent>
            </Card>
          ))}
        </motion.div>
      </div>
    </section>
  );
}
