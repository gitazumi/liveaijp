"use client";

import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";

const faqs = [
  {
    question: "チャットボットの設定方法を教えてください",
    answer:
      "管理画面にログイン後、FAQ管理ページから質問と回答のペアを登録するだけです。登録した内容をもとにAIが自動で最適な回答を生成します。",
  },
  {
    question: "データのセキュリティは大丈夫ですか？",
    answer:
      "はい、登録いただいたFAQデータは外部に公開・共有されることはありません。OpenAIとの通信も暗号化されており、安全に管理されています。",
  },
  {
    question: "どのデバイスで利用できますか？",
    answer:
      "PC、スマートフォン、タブレットなど、Webブラウザが利用可能なすべてのデバイスでご利用いただけます。",
  },
  {
    question: "FAQの内容を変更した場合、すぐに反映されますか？",
    answer:
      "はい、管理画面でFAQを編集すると、チャットボットの回答にも即座に反映されます。",
  },
  {
    question: "どのようなAI技術を使っていますか？",
    answer:
      "OpenAIのChatGPT APIを活用しています。登録されたFAQをコンテキストとして使用し、自然な会話形式で正確な回答を提供します。",
  },
  {
    question: "Webサイトへの埋め込み方法は？",
    answer:
      "管理画面の設定ページからJavaScriptスニペットをコピーし、サイトのHTMLに貼り付けるだけです。専用URLを使ったリンク共有も可能です。",
  },
  {
    question: "本当に無料で利用できますか？",
    answer:
      "はい、現在すべての機能を無料でご利用いただけます。将来的に有料プランを導入する可能性はありますが、基本機能は無料でお使いいただけます。",
  },
];

export function Faq() {
  return (
    <section id="faq" className="py-20">
      <div className="mx-auto max-w-3xl px-4">
        <div className="text-center">
          <h2 className="text-3xl font-bold md:text-4xl">よくある質問</h2>
          <p className="mt-3 text-muted-foreground">
            お客様からよくいただくご質問にお答えします
          </p>
        </div>
        <Accordion type="single" collapsible className="mt-12">
          {faqs.map((faq, i) => (
            <AccordionItem key={i} value={`item-${i}`}>
              <AccordionTrigger className="text-left text-base">
                {faq.question}
              </AccordionTrigger>
              <AccordionContent className="text-muted-foreground">
                {faq.answer}
              </AccordionContent>
            </AccordionItem>
          ))}
        </Accordion>
      </div>
    </section>
  );
}
