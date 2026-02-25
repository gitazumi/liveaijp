import { Header } from "@/components/landing/header";
import { Footer } from "@/components/landing/footer";

export default function PolicyPage() {
  return (
    <>
      <Header />
      <main className="mx-auto max-w-3xl px-4 py-16">
        <h1 className="text-3xl font-bold">利用規約</h1>
        <p className="mt-2 text-sm text-muted-foreground">最終更新日：2025年6月</p>
        <div className="mt-8 space-y-6 text-sm leading-relaxed text-muted-foreground">
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">第1条（適用）</h2>
            <p>
              本規約は、Sound Graffiti
              株式会社（以下「当社」）が提供するLiveAIサービス（以下「本サービス」）の利用条件を定めるものです。
              ユーザーは本規約に同意の上、本サービスを利用するものとします。
            </p>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">第2条（利用登録）</h2>
            <p>
              登録希望者が当社の定める方法によって利用登録を申請し、当社がこれを承認することによって、利用登録が完了するものとします。
            </p>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">第3条（料金プラン）</h2>
            <p>
              本サービスには、無料プラン（フリー）および有料プラン（スタンダード、プロ）があります。
              各プランの料金、機能制限、利用上限は、サービス内の料金ページに記載のとおりとします。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>フリープラン：月額無料（FAQ登録数10件、月間会話数100回まで）</li>
              <li>スタンダードプラン：月額3,980円（税込）（FAQ登録数50件、月間会話数1,000回まで）</li>
              <li>プロプラン：月額9,800円（税込）（FAQ登録数・会話数無制限）</li>
            </ul>
            <p className="mt-2">
              当社は、事前に通知の上、料金およびプラン内容を変更することがあります。
            </p>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">第4条（有料プランの契約・決済）</h2>
            <p>
              有料プランの決済は、外部決済サービス（Stripe）を通じて処理されます。
              ユーザーは、Stripeの利用規約に同意の上、決済を行うものとします。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>有料プランは月額課金制（サブスクリプション）です</li>
              <li>契約は1ヶ月ごとに自動更新されます</li>
              <li>課金日は初回契約日を基準とします</li>
              <li>決済に失敗した場合、プランが一時停止されることがあります</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">第5条（プラン変更・解約）</h2>
            <p>
              ユーザーは管理画面からいつでもプランの変更または解約を行うことができます。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>解約した場合、現在の課金期間の終了時までは有料プランの機能をご利用いただけます</li>
              <li>課金期間の途中で解約した場合の日割り返金は行いません</li>
              <li>プランのダウングレードにより、登録済みデータが各プランの上限を超える場合、超過分のデータは保持されますが、新規追加はできなくなります</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">第6条（禁止事項）</h2>
            <p>ユーザーは以下の行為をしてはなりません。</p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>法令または公序良俗に違反する行為</li>
              <li>犯罪行為に関連する行為</li>
              <li>虚偽の情報を登録する行為</li>
              <li>知的財産権を侵害する行為</li>
              <li>サービスの運営を妨害する行為</li>
              <li>不正アクセスを試みる行為</li>
              <li>APIやチャット機能への過度なリクエスト（スパム行為）</li>
              <li>チャットボットを通じた悪意あるコードの送信</li>
              <li>その他、当社が不適切と判断する行為</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              第7条（サービスの提供停止）
            </h2>
            <p>
              当社は、以下の事由がある場合、事前の通知なくサービスの全部または一部の提供を停止できるものとします。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>システムの保守点検を行う場合</li>
              <li>地震、落雷等の不可抗力により提供が困難な場合</li>
              <li>ユーザーが本規約に違反した場合</li>
              <li>その他、当社が停止を必要と判断した場合</li>
            </ul>
            <p className="mt-2">
              有料プランのユーザーに対してサービスを停止する場合、当社の責めに帰する事由による場合に限り、停止期間に応じた日割り返金を行います。
            </p>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">第8条（免責事項）</h2>
            <p>
              当社は、AIによる回答の正確性、完全性、最新性について保証するものではありません。
              ユーザーは自己の責任において本サービスを利用するものとし、当社は一切の責任を負いません。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>AIの回答内容に起因する損害について、当社は責任を負いません</li>
              <li>外部サービス（OpenAI、Stripe等）の障害に起因するサービスの停止や不具合について、当社は合理的な範囲で対応しますが、これによる損害の賠償は行いません</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">第9条（準拠法・管轄）</h2>
            <p>
              本規約の解釈にあたっては日本法を準拠法とします。
              本サービスに関する紛争については、東京地方裁判所を第一審の専属的合意管轄裁判所とします。
            </p>
          </section>
        </div>
      </main>
      <Footer />
    </>
  );
}
