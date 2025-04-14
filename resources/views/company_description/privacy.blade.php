@extends('company_description.description_layout')
@section('content')
    <h1 class="wf txt-42 txt-s f-weight-800">プライバシーポリシー</h1>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-16 m-0 txt-s">
            LiveAI.jp（以下「当サイト」）は、ユーザーの個人情報を保護することの重要性を認識し、以下のとおりプライバシーポリシー（以下「本ポリシー」）を定め、適切な取り扱いと保護に努めます。
        </p>
    </div>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s">第1条（取得する情報）</p>
        <div class="p-description wf m-0 ">
            <p class="wf txt-16 m-0 txt-s">
                当サイトでは、以下の情報を取得します：
            </p>
            <p class="wf txt-16 m-0 txt-s">
                登録時に取得する情報<br>
                　- メールアドレス
            </p>
            <p class="wf txt-16 m-0 txt-s">
                ログイン後に登録・管理される情報<br>
                　- お店の情報（店舗名、紹介文など）<br>
                　- FAQ（質問および回答のテキスト情報）<br>
                　- チャットボットの設定内容（スニペット生成履歴など）
            </p>
        </div>
    </div>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s">第2条（利用目的）</p>
        <div class="p-description wf m-0 ">
            <p class="wf txt-16 m-0 txt-s">
                取得した情報は、以下の目的に限り利用します：
            </p>
            <p class="wf txt-16 m-0 txt-s">
                本サービスの提供・管理・運営のため<br><br>
                ユーザーが登録したFAQに基づくチャットボット生成および動作のため<br><br>
                サポート対応やユーザーからの問い合わせへの対応のため<br><br>
                利用状況の分析および機能改善のため<br><br>
                法令や規約に違反した場合の調査・対応のため<br><br>
                必要に応じて通知や連絡を行うため
            </p>
        </div>
    </div>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s">第3条（第三者提供）</p>
        <div class="p-description wf m-0 ">
            <p class="wf txt-16 m-0 txt-s">
                当サイトは、以下の場合を除き、取得した情報を第三者に提供することはありません：
            </p>
            <p class="wf txt-16 m-0 txt-s">
                ユーザーの同意がある場合<br><br>
                法令に基づく開示が必要な場合<br><br>
                不正行為・違法行為への対応のために必要な場合
            </p>
        </div>
    </div>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s">第4条（外部サービスとの連携）</p>
        <div class="p-description wf m-0 ">
            <p class="wf txt-16 m-0 txt-s">
                当サイトでは、OpenAI API を利用してチャットボット機能を提供しています。<br>
                FAQの内容はOpenAI社のAPI経由で処理されますが、API通信は暗号化され、必要最小限のデータのみを送信します。
            </p>
        </div>
    </div>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s">第5条（情報の管理）</p>
        <div class="p-description wf m-0 ">
            <p class="wf txt-16 m-0 txt-s">
                取得した情報は、不正アクセス・漏洩・改ざん・紛失などを防ぐために、適切なセキュリティ対策を講じて管理します。
            </p>
        </div>
    </div>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s">第6条（ユーザーの権利）</p>
        <div class="p-description wf m-0 ">
            <p class="wf txt-16 m-0 txt-s">
                ユーザーは、ご自身の登録情報について、閲覧・修正・削除を求めることができます。<br>
                アカウント削除をご希望の場合は、サポートまでご連絡ください。
            </p>
        </div>
    </div>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s">第7条（Cookie等の利用）</p>
        <div class="p-description wf m-0 ">
            <p class="wf txt-16 m-0 txt-s">
                当サイトでは、サービス改善・ユーザー体験の向上のために、Cookie等の技術を利用することがあります。
            </p>
        </div>
    </div>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s">第8条（本ポリシーの変更）</p>
        <div class="p-description wf m-0 ">
            <p class="wf txt-16 m-0 txt-s">
                当サイトは、本ポリシーの内容を必要に応じて変更することがあります。<br>
                変更後の内容は、当サイト上に掲示した時点から適用されます。
            </p>
        </div>
    </div>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s">第9条（お問い合わせ窓口）</p>
        <div class="p-description wf m-0 ">
            <p class="wf txt-16 m-0 txt-s">
                プライバシーポリシーに関するご質問は、以下の窓口までご連絡ください。<br>
                <a href="https://liveai.jp/contact" class="wf txt-16 m-0 txt-s">お問い合わせフォーム</a>
            </p>
        </div>
    </div>
@endsection
