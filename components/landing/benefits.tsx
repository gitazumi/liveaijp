"use client";

import { motion } from "framer-motion";
import { TrendingUp, Clock4, HeartHandshake, Lightbulb } from "lucide-react";

const benefits = [
  {
    icon: Clock4,
    title: "問い合わせ対応の自動化",
    description:
      "定型的な質問はAIが自動回答。スタッフの負担を大幅に軽減し、本来の業務に集中できます。",
  },
  {
    icon: HeartHandshake,
    title: "顧客満足度の向上",
    description:
      "待ち時間ゼロの即時回答で、顧客のストレスを解消。いつでもどこでもサポートを提供します。",
  },
  {
    icon: TrendingUp,
    title: "コスト削減",
    description:
      "カスタマーサポートの人件費を削減しながら、対応品質を維持。小規模ビジネスにも最適です。",
  },
  {
    icon: Lightbulb,
    title: "ニーズの可視化",
    description:
      "チャット履歴から顧客のリアルなニーズを把握。データに基づいたサービス改善が可能になります。",
  },
];

export function Benefits() {
  return (
    <section id="benefits" className="py-20">
      <div className="mx-auto max-w-6xl px-4">
        <div className="text-center">
          <h2 className="text-3xl font-bold md:text-4xl">導入メリット</h2>
          <p className="mt-3 text-muted-foreground">
            LiveAIを導入することで得られるメリット
          </p>
        </div>
        <div className="mt-12 grid gap-8 md:grid-cols-2">
          {benefits.map((b, i) => (
            <motion.div
              key={b.title}
              initial={{ opacity: 0, x: i % 2 === 0 ? -20 : 20 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4, delay: i * 0.1 }}
              className="flex gap-4 rounded-xl border bg-card p-6 shadow-sm transition-shadow hover:shadow-md"
            >
              <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                <b.icon className="h-6 w-6 text-primary" />
              </div>
              <div>
                <h3 className="mb-1 text-lg font-semibold">{b.title}</h3>
                <p className="text-sm leading-relaxed text-muted-foreground">
                  {b.description}
                </p>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
