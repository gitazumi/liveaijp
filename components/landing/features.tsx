"use client";

import { motion } from "framer-motion";
import { Bot, Code2, Clock, BarChart3, Shield, Smartphone } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";

const features = [
  {
    icon: Bot,
    title: "ChatGPT搭載の高精度AI",
    description:
      "最新のChatGPT技術で、まるで人が対応しているかのような自然な会話を実現。お客様の質問意図を正確に理解します。",
  },
  {
    icon: Code2,
    title: "IT知識不要で導入できる",
    description:
      "コードを1行コピーして貼り付けるだけ。専用URLの共有でも利用可能。ホームページの知識がなくても大丈夫です。",
  },
  {
    icon: Clock,
    title: "営業時間外も自動で対応",
    description:
      "深夜・休日・年末年始もAIが対応。「電話がつながらない」というお客様のストレスを解消します。",
  },
  {
    icon: BarChart3,
    title: "お客様の声を見える化",
    description:
      "チャット履歴からお客様がどんなことに困っているかを把握。サービス改善やFAQ追加のヒントが見つかります。",
  },
  {
    icon: Shield,
    title: "安心のセキュリティ",
    description:
      "登録データは暗号化通信で保護。お客様の情報が外部に漏れることはありません。",
  },
  {
    icon: Smartphone,
    title: "スマホでもPCでも快適",
    description:
      "お客様はスマートフォン・PC・タブレットなど、どのデバイスからでもチャットボットを利用できます。",
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
          <h2 className="text-3xl font-bold md:text-4xl">中小企業に選ばれる理由</h2>
          <p className="mt-3 text-muted-foreground">
            専門知識も高額な費用も不要。中小企業・店舗が今日から使えるAIチャットボットです
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
