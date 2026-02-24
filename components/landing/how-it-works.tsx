"use client";

import { motion } from "framer-motion";
import { UserPlus, FileText, Rocket } from "lucide-react";

const steps = [
  {
    icon: UserPlus,
    step: "Step 1",
    title: "メールアドレスで無料登録",
    description: "メールアドレスを入力するだけ。届いたリンクをクリックすれば登録完了。パスワードの設定は不要です。",
  },
  {
    icon: FileText,
    step: "Step 2",
    title: "よくある質問を登録",
    description:
      "お客様からよく聞かれる質問と回答を管理画面から入力。営業時間・料金・アクセスなど、お店の情報を登録しましょう。",
  },
  {
    icon: Rocket,
    step: "Step 3",
    title: "ホームページに設置",
    description:
      "生成されたコードをホームページに貼り付ければ完了。専用URLをSNSやLINEで共有することもできます。",
  },
];

export function HowItWorks() {
  return (
    <section id="how-it-works" className="bg-muted/30 py-20">
      <div className="mx-auto max-w-6xl px-4">
        <div className="text-center">
          <h2 className="text-3xl font-bold md:text-4xl">最短5分で導入完了</h2>
          <p className="mt-3 text-muted-foreground">
            難しい設定は一切なし。3ステップで今日からAIが顧客対応を始めます
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
