import { Header } from "@/components/landing/header";
import { Footer } from "@/components/landing/footer";

export default function PrivacyPolicyPage() {
  return (
    <>
      <Header />
      <main className="mx-auto max-w-3xl px-4 py-16">
        <h1 className="text-3xl font-bold">プライバシーポリシー</h1>
        <p className="mt-2 text-sm text-muted-foreground">最終更新日：2025年6月</p>
        <div className="mt-8 space-y-6 text-sm leading-relaxed text-muted-foreground">
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              個人情報の収集について
            </h2>
            <p>
              当社は、本サービスの提供にあたり、以下の個人情報を取得することがあります。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>メールアドレス</li>
              <li>チャットボットに登録されたFAQ情報</li>
              <li>チャット履歴（ユーザーのお客様との会話内容を含む）</li>
              <li>お問い合わせ時にご提供いただく情報</li>
              <li>決済に関する情報（有料プラン利用時、Stripeを通じて処理）</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              個人情報の利用目的
            </h2>
            <ul className="list-disc space-y-1 pl-6">
              <li>本サービスの提供・運営</li>
              <li>有料プランの決済処理および請求管理</li>
              <li>ユーザーからのお問い合わせへの対応</li>
              <li>サービスの改善・新サービスの開発</li>
              <li>チャット分析機能によるご利用状況の集計・表示</li>
              <li>利用規約に違反した利用者への対応</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              第三者への提供
            </h2>
            <p>
              当社は、以下の場合を除き、個人情報を第三者に提供することはありません。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>ユーザーの同意がある場合</li>
              <li>法令に基づく場合</li>
              <li>
                サービスの提供に必要な範囲で業務委託先に提供する場合
              </li>
            </ul>
            <p className="mt-2">
              本サービスでは、以下の外部サービスを利用しています。各サービスのプライバシーポリシーもご確認ください。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>
                <span className="font-medium text-foreground">OpenAI API</span>
                ：チャットボットのAI回答生成のため、FAQ情報およびチャットメッセージをOpenAI社のAPIに送信します
              </li>
              <li>
                <span className="font-medium text-foreground">Stripe</span>
                ：有料プランの決済処理のため、お支払い情報はStripe社によって安全に処理されます。当社はクレジットカード番号等を直接保持しません
              </li>
              <li>
                <span className="font-medium text-foreground">Supabase</span>
                ：データベースおよびユーザー認証基盤として利用しています
              </li>
              <li>
                <span className="font-medium text-foreground">Vercel</span>
                ：サービスのホスティングに利用しています
              </li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              チャットデータの取り扱い
            </h2>
            <p>
              チャットボットとエンドユーザー（お客様の顧客）との会話内容は以下のように取り扱われます。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>会話内容はチャットボット所有者（ユーザー）の管理画面から閲覧可能です</li>
              <li>有料プランの分析機能では、会話の統計データ（質問頻度、時間帯分布等）を集計・表示します</li>
              <li>CSVエクスポート機能により、チャット履歴をダウンロードすることができます</li>
              <li>AI回答生成のため、会話内容はOpenAI社のAPIに送信されます</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              決済情報の取り扱い
            </h2>
            <p>
              有料プランの決済はStripe社を通じて処理されます。
              クレジットカード番号等の決済情報は当社のサーバーに保存されることはなく、Stripe社のセキュリティ基準（PCI DSS準拠）に基づいて安全に管理されます。
            </p>
            <p className="mt-2">
              当社が保持する決済関連情報は、StripeのカスタマーID、サブスクリプションID、契約プラン種別、契約期間のみです。
            </p>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              データの安全管理
            </h2>
            <p>
              当社は、個人情報の漏洩、紛失、破損の防止のため、適切な安全管理措置を講じます。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>すべての通信はSSL/TLS暗号化で保護されています</li>
              <li>データベースはRow Level Security（行レベルセキュリティ）により、各ユーザーのデータは厳格に分離されています</li>
              <li>APIへの不正アクセスを防止するため、レート制限やオリジン制限等のセキュリティ対策を実施しています</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              データの削除
            </h2>
            <p>
              ユーザーがアカウントを削除した場合、関連する個人情報（FAQ、チャット履歴、サブスクリプション情報）はすべて削除されます。
              ただし、法令に基づき保持が必要なデータについてはこの限りではありません。
            </p>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              プライバシーポリシーの変更
            </h2>
            <p>
              当社は、必要に応じて本ポリシーを変更することがあります。
              変更後のプライバシーポリシーは、本ページに掲載した時点から効力を生じるものとします。
              重要な変更がある場合は、登録メールアドレスへの通知またはサービス内での告知を行います。
            </p>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">お問い合わせ</h2>
            <p>
              個人情報の取り扱いに関するお問い合わせは、以下までご連絡ください。
            </p>
            <p className="mt-2">
              Sound Graffiti 株式会社
              <br />
              〒160-0004 東京都新宿区四谷3-4-3 SCビル B1
              <br />
              TEL: 03-5315-4781
            </p>
          </section>
        </div>
      </main>
      <Footer />
    </>
  );
}
