"use client";

import Link from "next/link";
import { Button } from "@/components/ui/button";
import { motion } from "framer-motion";
import { ArrowRight, Bot, Sparkles } from "lucide-react";

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
          <div className="rounded-2xl border bg-white p-6 shadow-xl">
            <div className="flex items-center gap-2 border-b pb-3 text-sm font-medium text-muted-foreground">
              <Bot className="h-5 w-5 text-primary" />
              AIチャットのイメージ（右下のボタンで実際にお試しいただけます）
            </div>
            <div className="mt-4 space-y-3">
              <div className="flex justify-end">
                <div className="rounded-2xl rounded-tr-md bg-primary px-4 py-2 text-sm text-white">
                  どんなサービスですか？
                </div>
              </div>
              <div className="flex justify-start">
                <div className="rounded-2xl rounded-tl-md border bg-muted/50 px-4 py-2 text-sm">
                  LiveAIは、よくある質問を登録するだけでAIチャットボットを作れるサービスです。
                  お店や会社のホームページに簡単に設置できます。
                </div>
              </div>
              <div className="flex justify-end">
                <div className="rounded-2xl rounded-tr-md bg-primary px-4 py-2 text-sm text-white">
                  料金はかかりますか？
                </div>
              </div>
              <div className="flex justify-start">
                <div className="rounded-2xl rounded-tl-md border bg-muted/50 px-4 py-2 text-sm">
                  現在は無料でお試しいただけます。
                  メールアドレスだけで登録でき、すぐにご利用を開始できます。
                </div>
              </div>
            </div>
          </div>
        </motion.div>
      </div>
    </section>
  );
}
