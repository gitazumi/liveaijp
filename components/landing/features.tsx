"use client";

import { motion } from "framer-motion";
import { Bot, Code2, Clock, BarChart3, Shield, Smartphone } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";

const features = [
  {
    icon: Bot,
    title: "ChatGPT搭載",
    description:
      "OpenAIのChatGPT APIを活用し、自然な会話で顧客の質問に回答します。",
  },
  {
    icon: Code2,
    title: "ノーコード導入",
    description:
      "JavaScriptスニペットを貼り付けるだけ。またはリンクを共有するだけで即座に利用開始。",
  },
  {
    icon: Clock,
    title: "24時間自動対応",
    description:
      "休日・深夜を問わず、AIが自動で顧客の問い合わせに対応します。",
  },
  {
    icon: BarChart3,
    title: "チャット履歴分析",
    description:
      "顧客がどんな質問をしているか把握し、サービス改善に活用できます。",
  },
  {
    icon: Shield,
    title: "安全なデータ管理",
    description:
      "FAQデータは暗号化通信で保護。外部に公開・共有されることはありません。",
  },
  {
    icon: Smartphone,
    title: "マルチデバイス対応",
    description:
      "PC・スマートフォン・タブレットなど、あらゆるデバイスで快適に利用可能。",
  },
];

const container = {
  hidden: {},
  show: { transition: { staggerChildren: 0.1 } },
};
const item = {
  hidden: { opacity: 0, y: 20 },
  show: { opacity: 1, y: 0, transition: { duration: 0.4 } },
};

export function Features() {
  return (
    <section id="features" className="py-20">
      <div className="mx-auto max-w-6xl px-4">
        <div className="text-center">
          <h2 className="text-3xl font-bold md:text-4xl">サービス概要</h2>
          <p className="mt-3 text-muted-foreground">
            LiveAIが提供する主な機能をご紹介します
          </p>
        </div>
        <motion.div
          variants={container}
          initial="hidden"
          whileInView="show"
          viewport={{ once: true, margin: "-100px" }}
          className="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
        >
          {features.map((f) => (
            <motion.div key={f.title} variants={item}>
              <Card className="h-full transition-shadow hover:shadow-md">
                <CardContent className="pt-6">
                  <div className="mb-4 inline-flex rounded-lg bg-primary/10 p-3">
                    <f.icon className="h-6 w-6 text-primary" />
                  </div>
                  <h3 className="mb-2 text-lg font-semibold">{f.title}</h3>
                  <p className="text-sm leading-relaxed text-muted-foreground">
                    {f.description}
                  </p>
                </CardContent>
              </Card>
            </motion.div>
          ))}
        </motion.div>
      </div>
    </section>
  );
}
