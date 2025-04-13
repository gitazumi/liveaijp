<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LiveAI｜ChatGPT搭載のAIチャットボットを簡単導入 – FAQ登録だけで自社対応を自動化</title>
    <meta name="description" content="ChatGPTを活用したAIチャットボットを、わずか数分であなたのWebサイトに。LiveAIではFAQを登録するだけで、訪問者の質問に自動対応する専用チャットボットを生成。コードの知識不要で、誰でも簡単に導入できます。">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" href="./css/base_style.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/custom.css">
    <link rel="stylesheet" href="./css/responsive.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
</head>

<body>

    <header class="bg-white" id="#top">
        <figure>
            <form action="">
                <a href="">
                    <img src="./images/logo .png" alt="logo.png">
                </a>
            </form>
        </figure>
        <div class="h-access m-0">
            <div class="h-menu m-0">
                @php
                    $topItems = [
                        ['name' => 'サービスの概要', 'link' => '#service'],
                        ['name' => '使い方', 'link' => '#direct'],
                        ['name' => '導入メリット', 'link' => '#benifits'],
                        ['name' => '料金プラン', 'link' => '#plan'],
                        ['name' => 'よくある質問', 'link' => '#faq'],
                    ];
                @endphp
                <li class="">
                    @foreach ($topItems as $topItem)
                        <form action=""><a href="{{ $topItem['link'] }}"
                                class="txt-16 dec-n ">{{ $topItem['name'] }}</a></form>
                    @endforeach
                </li>
            </div>
            <div class="h-btn">
                <form action="" class="hamburger">
                    <div class="button_container" id="toggle"><img src="./images/menu.png" alt=""></div>
                    <div class="overlay" id="overlay">
                        <div class="overlay-menu">
                            <ul>
                                @php
                                    $menuItems = [
                                        ['url' => 'login', 'method' => 'get', 'label' => 'ログイン'],
                                        ['url' => 'register', 'method' => 'get', 'label' => '会員登録'],
                                        ['url' => '#service', 'method' => 'get', 'label' => 'サービスの概要'],
                                        ['url' => '#direct', 'method' => 'get', 'label' => '使い方'],
                                        ['url' => '#benifit', 'method' => 'get', 'label' => '導入メリット'],
                                        ['url' => '#plan', 'method' => 'get', 'label' => '料金プラン'],
                                        ['url' => '#faq', 'method' => 'get', 'label' => 'よくある質問'],
                                    ];
                                @endphp
                                @foreach ($menuItems as $item)
                                    <li>
                                        <a href="{{ $item['url'] }}" method="{{ $item['method'] }}" class="side-list">
                                            <p class="txt-20 color-white txt-hidden">{{ $item['label'] }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </form>
                <a href="{{ asset('/login') }}" class="login">
                    <button>
                        ログイン
                    </button>
                </a>
                <form action="{{ asset('/register') }}" class="register">
                    <button>
                        会員登録
                    </button>
                </form>
            </div>
        </div>
    </header>
    <div class="scroll-up" style="display: none;" id="fadeUp">
        <a href="#top">
            <img src="./images/up.png " alt="">
        </a>
    </div>

    <section class="hero">
        <div class="hero-content">
            <div class="content">
                <div class="highlight txt-24 bold">ユーザーの疑問をリアルタイムで解決。</div>
                <h1 class="hero-ai-description">AIチャットで<br class="br">ユーザー体験を向上！</h1>
                <p class=" hero-faq">FAQを登録するだけで、<br class="hero-faq-br">あなた専用のAIが対応。<br>今すぐ無料で試せる！</p>
                <form action="register" method="get " class="btn-1">
                    無料体験する <div><img src="./images/gp 1854.png" alt=""></div>
                </form>
            </div>
            <div class="image-container">
                @php
                    $chats = [
                        ['text' => '営業時間は何時ですか？', 'img' => 'chatting_user.png', 'type' => 'user'],
                        ['text' => '当店の営業時間は10:00〜22:00です。', 'img' => 'chatting_bot.png', 'type' => 'bot'],
                        [
                            'text' => 'ホールレンタルの料金を教えてください。',
                            'img' => 'chatting_user_2.png',
                            'type' => 'user',
                        ],
                        [
                            'text' => '1時間あたり5,000円（税込）です。詳しいプランはこちらをご覧ください。',
                            'img' => 'chatting_bot.png',
                            'type' => 'bot',
                        ],
                    ];
                @endphp

                @foreach ($chats as $chat)
                    <div class="chatting">
                        @if ($chat['type'] === 'user')
                            <div class="tooltip_">{{ $chat['text'] }}</div>
                            <div class="chatting-img_">
                                <img src="./images/{{ $chat['img'] }}" alt="">
                            </div>
                        @else
                            <div class="chatting-img">
                                <img src="./images/{{ $chat['img'] }}" alt="">
                            </div>
                            <div class="tooltip">{{ $chat['text'] }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="color-white txt-24 scroll">Scroll</div>
        </svg>
    </section>

    <section class="container flex-col mt-75" id="service">
        <h1 class="heading">SERVICE</h1>
        <div class="wf flex-col">
            <h2 class=" txt-c txt-42 ">サービス概要</h2>
            <div class="wf d-flex service-f">
                <div class=" flex-col gap-50 service_title ">
                    <p class=" txt-24 service_summary font-w-500">あなたのサイトに、簡単操作で独自のAIチャットボットを導入！
                        LiveAIは、ChatGPTのAPIを活用し、FAQを登録するだけで、
                        あなたのビジネスに最適化されたAIチャットボットを作成できます！</p>
                    <div class="service_description txt-24">
                        <ul class="service_detail">
                            <li>
                                <p class="txt-16 line-30">・ノーコードで簡単導入！ JavaScriptのスニペットをコピペするだけで、即時設置！</p>
                            </li>
                            <li>
                                <p class="txt-16 line-30">・FAQを学習するAI！ あなたのデータを元に、適切な回答を自動生成！</p>
                            </li>
                            <li>
                                <p class="txt-16 line-30">・まるで人間のように臨機応変に対応！ シンプルなFAQだけでなく、会話の流れを理解し、
                                    スムーズな応答が可能！</p>
                            </li>
                            <li>
                                <p class="txt-16 line-30">・過去のチャット履歴を分析！ ユーザーが何を知りたがっているのかを簡単に把握し、
                                    FAQの改善に活かせる！</p>
                            </li>
                        </ul>
                        <div class=" m-0 service_img">
                            <img class="chatbot_1" src="./images/chatbot (2) 1.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wf service_detail_s mt-50">
            <p class="wf txt-24 service_summary font-w-500">LiveAIなら、こんな悩みを解決！</p>
        </div>
        <div class="wf flex-col mt-50">
            <div class="wf d-flex space-between service">
                <div class="w-40 service_img2">
                    <img src="./images/illuster5.png" alt="">
                </div>
                <div class="w-55 flex-col gap-50 service_title">
                    <p class="wf txt-24 service_title service_detail">LiveAIなら、こんな悩みを解決！</p>
                    <ul class="service_detail">
                        @php
                            $serviceDetails = [
                                ['detail' => '・カスタマーサポートの負担を軽減したい '],
                                ['detail' => '・問い合わせ対応を自動化し、24時間対応を実現したい '],
                                ['detail' => '・AIを導入したいが、コストや技術的なハードルが高いと感じる '],
                                ['detail' => '・問い合わせに至らない”潜在ニーズ”を把握したい '],
                            ];
                        @endphp
                        @foreach ($serviceDetails as $serviceDetail)
                            <li>
                                <p class="txt-16 line-30">{{ $serviceDetail['detail'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="sky-circle-s">

            </div>
        </div>
        <form action="register" method="get" class="ft-btn">
            無料体験する <div><img src="./images/gp 1854.png" alt=""></div>
        </form>
        <div class="circle-gray-l">
        </div>
    </section>
    <section class="container flex-col direct mt-75" id="direct">
        <div class="sky-circle-m">
        </div>
        <h1 class="heading mt-50">HOW TO IT WORKS</h1>
        <h2 class=" txt-42 txt-c">使い方</h2>
        <p class="wf txt-24 txt-c font-w-600">わずか3ステップで<br class="title-description">AIチャットボットを導入</p>
        <div class="breadcrumbs">
            @php
                $steps = ['アカウント作成', 'FAQを入力', 'コードをコピー & ペースト'];
            @endphp
            @foreach ($steps as $step)
                <div class="breadcrumbs__item" class="flex-direction:column:">
                    <div class="step-indicator">
                        <p>{{ 'step' . $loop->iteration }}</p>
                    </div>
                    <p class="txt-16 txt-c color-white mt-10">{{ $step }}</p>
                </div>
            @endforeach
        </div>
        <div class="steps-container">
            @php
                $steps = ['LiveAIに無料登録', 'テキストベースの簡単な登録', 'サイトに挿入するだけです'];
            @endphp
            @foreach ($steps as $step)
                <div class="step">
                    <div class="step-description">
                        <img src="{{ asset('./images/Vector.png') }}" alt="">
                        <p class="txt-c">{{ $step }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="sp_breadcrumbs">
            <div class="sp_breadcrumb_item">
                <div class="sp-breadcrumb">
                    <div class="sp_step-indicator">
                        <p>step1</p>
                    </div>
                    <p class="sp_step_title txt-12">アカウント作成</p>
                </div>
                <div class="sp_step_description txt-12">
                    <p>LiveAIに無料登録</p>
                </div>
            </div>
            <div class="sp_breadcrumb_item">
                <div class="sp-breadcrumb">
                    <div class="sp_step-indicator">
                        <p>step2</p>
                    </div>
                    <p class="sp_step_title txt-12">FAQを入力</p>
                </div>
                <div class="sp_step_description txt-12">
                    <p>テキストベースの簡単な登録</p>
                </div>
            </div>
            <div class="sp_breadcrumb_item">
                <div class="sp-breadcrumb">
                    <div class="sp_step-indicator">
                        <p>step2</p>
                    </div>
                    <p class="sp_step_title txt-12">コードをコピー & ペースト</p>
                </div>
                <div class="sp_step_description">
                    <p class="txt-12">サイトに挿入するだけです</p>
                </div>
            </div>
        </div>
        <p class="txt-24 txt-c mt-50 font-w-600">
            すぐに顧客対応を自動化する<br class="title-description">ことができます！
        </p>
        <form action="register" method="get" class="ft-btn">
            無料体験する <div><img src="./images/gp 1854.png" alt=""></div>
        </form>
        <div class="sky-circle-m-l"></div>
    </section>
    <section class="container flex-col benifits mt-50" id="benifits">
        <div class="green-circle-s"></div>
        <h1 class="heading">BENIFITS</h1>
        <h2 class=" txt-42 txt-c">導入メリット </h2>
        <div class="wf benifits">
            <div class="w-40 flex-col benifits_img">
                <img src="./images/2106.i201.007.F.m004.c9.call center technical support isometric 1.png"
                    alt="">
                <p class="txt-36 txt-c font-w-600" style="color: #56585c;">LiveAIが選ばれる理由</p>
            </div>
            <div class="w-40 flex-col benifits_s">
                @php
                    $benifits = [
                        ['title' => '時間・コスト削減', 'content' => '簡単に導入でき、カスタマー対応の手間を削減'],
                        ['title' => '誰でも使えるシンプル設計', 'content' => 'コード知識不要、管理画面も直感的'],
                        ['title' => '最新のAI技術を活用', 'content' => 'ChatGPT APIで日々進化するAI'],
                        ['title' => '完全無料で利用可能！', 'content' => '今すぐ試せる'],
                    ];
                @endphp
                @foreach ($benifits as $benifit)
                    <div class=" d-flex benefits-container">
                        <div class="benifits-check-ico">
                            <img src="{{ asset('./images/Vector (1).png') }}" alt="">
                        </div>
                        <div class="benifits-description">
                            <p class="txt-26 blod">{{ $benifit['title'] }}</p>
                            <p class="txt-16">{{ $benifit['content'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <form action="login" method="get" class="ft-btn">
            無料体験する <div><img src="./images/gp 1854.png" alt=""></div>
        </form>
    </section>
    <section class="container flex-col mt-75" id="plan">
        <div class="plan-circle-m-l"></div>
        <h1 class="heading">PLAN</h1>
        <h2 class=" txt-42 txt-c"> 料金プラン</h2>
        <div class="w-70 mt-50">
            <div class="speech-bubble">
                <p class="txt-s txt-16 m-0 ">無料で今すぐ使えます！</p>
                <p class="txt-s txt-16 m-0 mt-10">LiveAIは完全無料で利用可能！ </p>
                <p class="txt-s txt-16 m-0 mt-10">難しい設定や高額な料金は不要。今すぐ始めましょう。</p>
            </div>
        </div>
        <div class="bubble-bot ">
            <img src="./images/GIU AMA 255-06 1.png" alt="">
        </div>
        <form action="register" method="get" class="ft-btn plan-btn">
            無料体験する <div><img src="./images/gp 1854.png" alt=""></div>
        </form>
    </section>
    <section class="container flex-col mt-75" id="faq">
        <h1 class="heading">FAQ</h1>
        <h2 class=" txt-42 txt-c">よくある質問</h2>
        <div class="wf flex-col faqs">
            @php
                $faqs = $faqs ?? collect();
            @endphp
            @if ($faqs->isEmpty())
                <p>No FAQs available.</p>
            @else
                @foreach ($faqs as $faq)
                    <div class="wf faq">
                        <button type="button" class="collapsible ">
                            <p class="txt-24 m-0">
                                Q: {{ $faq['question'] }}
                            </p>
                            <div class="faq-status">
                                <img src="{{ asset('./images/plus (1).png') }}" alt="">
                            </div>
                        </button>
                        <div class="answer">
                            <p>A: {{ $faq['answer'] }} </p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <p class="txt-24 txt-c w-55 mt-50 font-w-600">「あなたのサイトにも、<br class="title-description"> AIチャットボットを！」<br>
            今すぐ無料で試してみませんか？</p>
        <form action="register" method="get" class="ft-btn">
            無料体験する <div><img src="./images/gp 1854.png" alt=""></div>
        </form>
    </section>

    <footer class="footer mt-75">
        <svg class="curve-container" viewBox="0 0 1440 320">
            <path fill="white" d="M0,0 C480,250 960,250 1440,0 L1440,0 L0,0 Z"></path>
        </svg>
        <div class="footer-content">
            <div class="footer-nav">
                <div class="footer-nav-left">
                    <div class="footer-logo-div">
                        <img src="{{ asset('/images/logo_2.png') }}" alt="Logo" class="footer-logo">
                    </div>
                    <ul class="footer-left">
                        @php
                            $bottomItems = [
                                ['name' => '会社概要', 'link' => 'company'],
                                ['name' => '利用規約', 'link' => 'policy'],
                                ['name' => 'プライバシーポリシー', 'link' => 'privacy-policy'],
                            ];
                        @endphp
                        @foreach ($bottomItems as $bottomItem)
                            <li class="m-0">
                                <form action="">
                                    <a href="{{ $bottomItem['link'] }}" class="m-0">{{ $bottomItem['name'] }}</a>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="footer-nav-right">
                    <ul class="footer-right">
                        @php
                            $bottomItems = [
                                ['name' => 'サービスの概要', 'link' => '#service'],
                                ['name' => '使い方', 'link' => '#direct'],
                                ['name' => '導入メリット', 'link' => '#benifits'],
                                ['name' => '料金プラン', 'link' => '#plan'],
                                ['name' => 'よくある質問', 'link' => '#faq'],
                            ];
                        @endphp
                        @foreach ($bottomItems as $bottomItem)
                            <li class="m-0">
                                <a href="{{ $bottomItem['link'] }}" class="m-0 txt-s">{{ $bottomItem['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <p class="copyright">© 2024 LiveAi.jp, All Rights Reserved</p>
    </footer>
</body>
<script src="{{ asset('./js/index.js') }}"></script>

</html>
