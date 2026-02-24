"use client";

import { motion } from "framer-motion";
import { UserPlus, FileText, Rocket } from "lucide-react";

const steps = [
  {
    icon: UserPlus,
    step: "Step 1",
    title: "アカウント登録",
    description: "メールアドレスとパスワードだけで簡単に無料登録できます。",
  },
  {
    icon: FileText,
    step: "Step 2",
    title: "FAQを登録",
    description:
      "管理画面から質問と回答のペアを登録。AIが自動で学習します。",
  },
  {
    icon: Rocket,
    step: "Step 3",
    title: "チャットボットを公開",
    description:
      "JSスニペットをサイトに貼り付けるか、専用URLを共有するだけで完了。",
  },
];

export function HowItWorks() {
  return (
    <section id="how-it-works" className="bg-muted/30 py-20">
      <div className="mx-auto max-w-6xl px-4">
        <div className="text-center">
          <h2 className="text-3xl font-bold md:text-4xl">かんたん3ステップ</h2>
          <p className="mt-3 text-muted-foreground">
            たった3ステップでAIチャットボットを導入できます
          </p>
        </div>
        <div className="mt-12 grid gap-8 md:grid-cols-3">
          {steps.map((s, i) => (
            <motion.div
              key={s.step}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: i * 0.15, duration: 0.4 }}
              className="relative text-center"
            >
              <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary text-white shadow-lg">
                <s.icon className="h-7 w-7" />
              </div>
              <span className="mb-2 block text-xs font-semibold uppercase tracking-wider text-primary">
                {s.step}
              </span>
              <h3 className="mb-2 text-xl font-bold">{s.title}</h3>
              <p className="text-sm leading-relaxed text-muted-foreground">
                {s.description}
              </p>
              {i < steps.length - 1 && (
                <div className="absolute right-0 top-8 hidden h-0.5 w-1/3 translate-x-full bg-gradient-to-r from-primary/30 to-transparent md:block" />
              )}
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
