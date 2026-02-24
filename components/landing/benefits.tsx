"use client";

import { motion } from "framer-motion";
import { TrendingUp, Clock4, HeartHandshake, Lightbulb } from "lucide-react";

const benefits = [
  {
    icon: Clock4,
    title: "電話・メール対応を大幅削減",
    description:
      "「営業時間は？」「予約方法は？」など、毎回同じ質問にスタッフが対応する必要がなくなります。空いた時間で接客や本業に集中できます。",
  },
  {
    icon: HeartHandshake,
    title: "お客様を待たせない",
    description:
      "深夜でも休日でも、AIが即座に回答。「電話がつながらない」「返信が遅い」というお客様の不満を解消し、顧客満足度がアップします。",
  },
  {
    icon: TrendingUp,
    title: "初期費用0円でスタート",
    description:
      "高額なシステム開発費は不要。無料プランですべての機能が使えるので、まずは気軽にお試しいただけます。",
  },
  {
    icon: Lightbulb,
    title: "お客様の本音がわかる",
    description:
      "チャット履歴から「お客様が本当に知りたいこと」が見えてきます。メニュー改善や新サービスのヒントとして活用できます。",
  },
];

export function Benefits() {
  return (
    <section id="benefits" className="py-20">
      <div className="mx-auto max-w-6xl px-4">
        <div className="text-center">
          <h2 className="text-3xl font-bold md:text-4xl">導入するとこう変わります</h2>
          <p className="mt-3 text-muted-foreground">
            飲食店・美容室・クリニック・士業など、問い合わせの多い業種で効果を実感いただいています
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
