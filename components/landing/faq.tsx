"use client";

import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion";

const faqs = [
  {
    question: "パソコンが苦手でも使えますか？",
    answer:
      "はい、ご安心ください。メールアドレスの登録と、質問・回答の入力だけで使えます。プログラミングの知識は一切不要です。ホームページへの設置も、コードを1行貼り付けるだけで完了します。",
  },
  {
    question: "どんな業種で使えますか？",
    answer:
      "飲食店、美容室、クリニック、士業（税理士・弁護士など）、不動産、ECショップなど、お客様からの問い合わせが多い業種で幅広くご利用いただけます。営業時間、料金、予約方法、アクセスなどのFAQを登録すれば、AIが24時間自動で回答します。",
  },
  {
    question: "本当に無料ですか？追加料金はかかりませんか？",
    answer:
      "はい、現在すべての機能を完全無料でご利用いただけます。初期費用も月額費用もかかりません。将来的に高機能な有料プランを追加する可能性はありますが、現在の機能は引き続き無料でお使いいただけます。",
  },
  {
    question: "AIの回答は正確ですか？",
    answer:
      "登録していただいたFAQ情報をもとにAIが回答を生成するため、正確性が高いのが特長です。お店独自の情報（メニュー、料金、営業時間など）を登録すれば、お客様の質問に的確に答えられます。",
  },
  {
    question: "ホームページがなくても使えますか？",
    answer:
      "はい、使えます。チャットボットの専用URLが発行されるので、そのリンクをLINE、Instagram、Googleマップのプロフィールなどに貼るだけでお客様に利用してもらえます。",
  },
  {
    question: "FAQの内容はあとから変更できますか？",
    answer:
      "はい、管理画面からいつでもFAQの追加・編集・削除が可能です。変更はチャットボットにすぐ反映されます。季節メニューの更新やキャンペーン情報の追加も簡単です。",
  },
  {
    question: "お客様の情報は安全に管理されますか？",
    answer:
      "はい。すべての通信はSSL暗号化で保護されています。登録されたFAQデータは各アカウントごとに管理され、他のユーザーに公開されることはありません。",
  },
  {
    question: "WordPressやWixなどのサイトにも設置できますか？",
    answer:
      "はい。生成されるコードを貼り付けるだけで、WordPress、Wix、Shopify、Jimdo、ペライチなど、ほとんどのホームページ作成ツールに対応しています。",
  },
];

export function Faq() {
  return (
    <section id="faq" className="py-20">
      <div className="mx-auto max-w-3xl px-4">
        <div className="text-center">
          <h2 className="text-3xl font-bold md:text-4xl">よくある質問</h2>
          <p className="mt-3 text-muted-foreground">
            導入をご検討中の方からよくいただくご質問
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
