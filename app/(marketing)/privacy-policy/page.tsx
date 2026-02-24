import { Header } from "@/components/landing/header";
import { Footer } from "@/components/landing/footer";

export default function PrivacyPolicyPage() {
  return (
    <>
      <Header />
      <main className="mx-auto max-w-3xl px-4 py-16">
        <h1 className="text-3xl font-bold">プライバシーポリシー</h1>
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
              <li>チャット履歴</li>
              <li>お問い合わせ時にご提供いただく情報</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              個人情報の利用目的
            </h2>
            <ul className="list-disc space-y-1 pl-6">
              <li>本サービスの提供・運営</li>
              <li>ユーザーからのお問い合わせへの対応</li>
              <li>サービスの改善・新サービスの開発</li>
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
                サービスの提供に必要な範囲で業務委託先に提供する場合（OpenAI
                APIへの送信を含む）
              </li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              データの安全管理
            </h2>
            <p>
              当社は、個人情報の漏洩、紛失、破損の防止のため、適切な安全管理措置を講じます。
              データ通信は暗号化され、安全に管理されています。
            </p>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              プライバシーポリシーの変更
            </h2>
            <p>
              当社は、必要に応じて本ポリシーを変更することがあります。
              変更後のプライバシーポリシーは、本ページに掲載した時点から効力を生じるものとします。
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
