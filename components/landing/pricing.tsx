"use client";

import Link from "next/link";
import { motion } from "framer-motion";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader } from "@/components/ui/card";
import { Check } from "lucide-react";

const features = [
  "AIチャットボット作成",
  "FAQ無制限登録",
  "チャット履歴閲覧・分析",
  "Webサイト埋め込み（JSスニペット）",
  "専用URLでのリンク共有",
  "カスタム挨拶メッセージ",
  "マルチデバイス対応",
  "暗号化通信による安全なデータ管理",
];

export function Pricing() {
  return (
    <section id="pricing" className="bg-muted/30 py-20">
      <div className="mx-auto max-w-6xl px-4">
        <div className="text-center">
          <h2 className="text-3xl font-bold md:text-4xl">料金プラン</h2>
          <p className="mt-3 text-muted-foreground">
            現在、すべての機能を無料でお使いいただけます
          </p>
        </div>
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="mx-auto mt-12 max-w-md"
        >
          <Card className="relative overflow-hidden border-primary/20 shadow-lg">
            <div className="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary to-primary/60" />
            <CardHeader className="text-center">
              <p className="text-sm font-medium text-primary">フリープラン</p>
              <div className="mt-2 flex items-baseline justify-center gap-1">
                <span className="text-5xl font-bold">¥0</span>
                <span className="text-muted-foreground">/ 月</span>
              </div>
              <p className="mt-2 text-sm text-muted-foreground">
                すべての機能が無料で利用可能
              </p>
            </CardHeader>
            <CardContent>
              <ul className="space-y-3">
                {features.map((f) => (
                  <li key={f} className="flex items-start gap-3 text-sm">
                    <Check className="mt-0.5 h-4 w-4 shrink-0 text-primary" />
                    {f}
                  </li>
                ))}
              </ul>
              <Button asChild className="mt-8 w-full" size="lg">
                <Link href="/register">無料で始める</Link>
              </Button>
            </CardContent>
          </Card>
        </motion.div>
      </div>
    </section>
  );
}
