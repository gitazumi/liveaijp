import { Header } from "@/components/landing/header";
import { Footer } from "@/components/landing/footer";

export default function PolicyPage() {
  return (
    <>
      <Header />
      <main className="mx-auto max-w-3xl px-4 py-16">
        <h1 className="text-3xl font-bold">利用規約</h1>
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
            <h2 className="mb-2 text-lg font-semibold text-foreground">第3条（禁止事項）</h2>
            <p>ユーザーは以下の行為をしてはなりません。</p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>法令または公序良俗に違反する行為</li>
              <li>犯罪行為に関連する行為</li>
              <li>虚偽の情報を登録する行為</li>
              <li>知的財産権を侵害する行為</li>
              <li>サービスの運営を妨害する行為</li>
              <li>不正アクセスを試みる行為</li>
              <li>その他、当社が不適切と判断する行為</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">
              第4条（サービスの提供停止）
            </h2>
            <p>
              当社は、以下の事由がある場合、事前の通知なくサービスの全部または一部の提供を停止できるものとします。
            </p>
            <ul className="mt-2 list-disc space-y-1 pl-6">
              <li>システムの保守点検を行う場合</li>
              <li>地震、落雷等の不可抗力により提供が困難な場合</li>
              <li>その他、当社が停止を必要と判断した場合</li>
            </ul>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">第5条（免責事項）</h2>
            <p>
              当社は、AIによる回答の正確性、完全性、最新性について保証するものではありません。
              ユーザーは自己の責任において本サービスを利用するものとし、当社は一切の責任を負いません。
            </p>
          </section>
          <section>
            <h2 className="mb-2 text-lg font-semibold text-foreground">第6条（準拠法・管轄）</h2>
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
