@section('title', 'ようこそLiveAIへ')
<x-app-layout>
    <div class="bg-white rounded-lg p-6 shadow-md">
        <h1 class="text-2xl font-bold text-[#173F74] mb-6">🌟ご利用の流れ</h1>
        <p class="mb-4">ようこそLiveAIへ！このページでは、チャットボットの導入までのステップをご案内します。</p>

        <div class="space-y-6">
            <div>
                <h2 class="text-xl font-semibold text-[#173F74]">① お店の登録</h2>
                <p>まずは左側のメニューから「お店の登録」を選び、店舗名や業種などの情報を入力してください。</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-[#173F74]">② FAQトレーニング</h2>
                <p>登録が完了したら、「FAQトレーニング」でお客様から想定される質問とその回答を登録しましょう。<br>
                これにより、ChatGPTがあなたのお店に関する質問に自動で答えられるようになります。</p>
                <ul class="list-none pl-4 mt-2 space-y-2">
                    <li class="flex items-start">
                        <span class="text-green-500 font-bold mr-2">✅</span>
                        <span>最初は5〜10件程度の登録がおすすめです。</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-green-500 font-bold mr-2">✅</span>
                        <span>登録されていないとチャットは正しく動作しません。</span>
                    </li>
                </ul>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-[#173F74]">③ JavaScriptコードの埋め込み</h2>
                <p>「JavaScriptコード生成」メニューで表示されるコードをコピーし、ご自身のホームページに貼り付けてください。<br>
                これだけで、あなたのサイトにチャットボットが表示され、ユーザーが利用できるようになります。</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-[#173F74]">④ チャットテスト</h2>
                <p>「チャットテスト」では、実際に登録したFAQに基づいてどのようにチャットが応答するかを確認できます。</p>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-[#173F74]">⑤ チャット履歴の確認</h2>
                <p>「チャット履歴」では、実際にユーザーがチャットを通じてやり取りした内容を確認できます。<br>
                応答の見直しやFAQの追加・修正に役立ててください。</p>
            </div>

            <div class="mt-8 border-t pt-6">
                <h2 class="text-xl font-semibold text-[#173F74]">サポート</h2>
                <p>操作方法や不具合などご不明な点がありましたら、<a href="https://liveai.jp/contact" class="text-blue-500 hover:underline">お問い合わせ</a>からいつでもご連絡ください。</p>
            </div>
        </div>
    </div>
</x-app-layout>
