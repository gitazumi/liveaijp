<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/base_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
    <style>
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #b8d7ff;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #0081eb;
        }
    </style>

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- トップページと同じヘッダー -->
    <header class="bg-white" id="#top">
        <figure>
            <form action="">
                <a href="/">
                    <img src="{{ asset('images/logo .png') }}" alt="logo.png">
                </a>
            </form>
        </figure>
        <div class="h-access m-0">
            <div class="h-menu m-0">
                @php
                    $topItems = [
                        ['name' => 'サービスの概要', 'link' => '/#service'],
                        ['name' => '使い方', 'link' => '/#direct'],
                        ['name' => '導入メリット', 'link' => '/#benifits'],
                        ['name' => '料金プラン', 'link' => '/#plan'],
                        ['name' => 'よくある質問', 'link' => '/#faq'],
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
                    <div class="button_container" id="toggle"><img src="{{ asset('images/menu.png') }}" alt=""></div>
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
            <img src="{{ asset('images/up.png') }}" alt="">
        </a>
    </div>
    
    <section class="desription_container flex-col mt-50">
        @yield('content')
    </section>

    <!-- トップページと同じフッター -->
    <footer class="footer mt-75">
        <svg class="curve-container" viewBox="0 0 1440 320">
            <path fill="white" d="M0,0 C480,250 960,250 1440,0 L1440,0 L0,0 Z"></path>
        </svg>
        <div class="footer-content">
            <div class="footer-nav">
                <div class="footer-nav-left">
                    <div class="footer-logo-div">
                        <img src="{{ asset('images/logo_2.png') }}" alt="Logo" class="footer-logo">
                    </div>
                    <ul class="footer-left">
                        @php
                            $bottomItems = [
                                ['name' => '会社概要', 'link' => 'company'],
                                ['name' => '利用規約', 'link' => 'policy'],
                                ['name' => 'プライバシーポリシー', 'link' => 'privacy-policy'],
                                ['name' => 'お問い合わせ', 'link' => 'contact'],
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
                                ['name' => 'サービスの概要', 'link' => '/#service'],
                                ['name' => '使い方', 'link' => '/#direct'],
                                ['name' => '導入メリット', 'link' => '/#benifits'],
                                ['name' => '料金プラン', 'link' => '/#plan'],
                                ['name' => 'よくある質問', 'link' => '/#faq'],
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
        </div>
    </footer>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggle = document.getElementById("toggle");
        const overlay = document.getElementById("overlay");
        const menuIcon = toggle.querySelector("img");

        toggle.addEventListener("click", function(event) {
            event.stopPropagation();
            overlay.classList.toggle("open");
            if (overlay.classList.contains("open")) {
                menuIcon.src = "{{ asset('images/close.png') }}";
            } else {
                menuIcon.src = "{{ asset('images/menu.png') }}";
            }
        });

        document.addEventListener("click", function(event) {
            if (!toggle.contains(event.target) && !overlay.contains(event.target)) {
                overlay.classList.remove("open");
                menuIcon.src = "{{ asset('images/menu.png') }}";
            }
        });

        const menuItems = document.querySelectorAll('.side-list');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                overlay.classList.remove("open");
                menuIcon.src = "{{ asset('images/menu.png') }}";
            });
        });
        
        window.addEventListener('scroll', function() {
            const fadeUp = document.getElementById('fadeUp');
            if (window.scrollY > 300) {
                fadeUp.style.display = 'block';
            } else {
                fadeUp.style.display = 'none';
            }
        });
    });
</script>
<script src="{{ asset('js/index.js') }}"></script>
</html>
