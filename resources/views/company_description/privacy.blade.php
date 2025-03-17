@extends('company_description.description_layout')
@section('content')
    <h1 class="wf txt-42 txt-s f-weight-800">プライバシーポリシー</h1>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s">個人情報の定義</p>
        <div class="p-description wf m-0 ">
            <p class="wf txt-16 m-0 txt-s">
                「個人情報」とは、生存する個人に関する情報であって、氏名、生年月日等により特定の個人を識別できるものをいいます。
            </p>
        </div>
    </div>

    @php
        $sections = [
            [
                'title' => '個人情報の収集',
                'content' => [
                    '当サイトではお問合せをされた際に個人情報を収集することがございます。',
                    '収集する個人情報は以下の通りです。',
                    '1.　 お名前、フリガナ',
                    '2.　 ご住所',
                    '3.　 お電話番号',
                    '4.　 メールアドレス',
                    '5.　 パスワード',
                    '6.　 お取引履歴及びその内容',
                    '7.　 上記を組み合わせることで特定の個人が識別できる情報',
                ],
            ],
            [
                'title' => '個人情報の利用',
                'content' => [
                    'お客様からお預かりした個人情報の利用目的は以下の通りです。',
                    '1.　 お問合せの確認、照会',
                    '2.　 お問合せの返信時',
                    '当サイトでは、下記の場合を除き第三者に個人情報を開示・提供することはありません。',
                    '1.　 法令に基づく場合',
                    '2.　 人の生命、財産の保護が必要な場合',
                    '3.　 関連会社間での情報共有',
                ],
            ],
            [
                'title' => '個人情報の安全管理',
                'content' => [
                    'お預かりした個人情報の安全管理は、適切な技術的施策を講じ、漏えいや不正アクセスを防ぎます。',
                ],
            ],
            [
                'title' => 'お問合せ先',
                'content' => [
                    '眼育総研事務局',
                    '〒227-0064 神奈川県横浜市青葉区田奈町43-3-2F',
                    'TEL：045-123-4567',
                    'FAX：045-123-4567',
                    'MAIL：info@aaaa.com',
                ],
            ],
            [
                'title' => 'プライバシーポリシーの変更',
                'content' => [
                    '当サイトでは、収集する個人情報の変更、利用目的の変更、またはその他プライバシーポリシーの変更を行う際は、当ページへの変更をもって公表とさせていただきます。',
                ],
            ],
        ];
    @endphp

    @foreach ($sections as $section)
        <div class="wf mt-50 flex-col">
            <p class="wf txt-24 m-0 txt-s">{{ $section['title'] }}</p>
            <div class="p-description wf m-0 flex-col">
                @foreach ($section['content'] as $paragraph)
                    <p class="wf txt-16 m-0 txt-s">{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    @endforeach
    <div class="p-description wf m-0  flex-col">
        <p class="wf txt-16 m-0 txt-s">( 2017年 8月 30日 策定 )</p>

    </div>
@endsection
