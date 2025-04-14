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
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
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
    <header class="bg-white" id="#top">
        <figure>
            <form action="">
                <a href="/">
                    <img src="./images/logo .png" alt="logo.png">
                </a>
            </form>
        </figure>
        <div class="h-access m-0">
            <div class="h-menu m-0">

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
    <section class="desription_container flex-col mt-50">
        @yield('content')
    </section>


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
                menuIcon.src = "./images/close.png";
            } else {
                menuIcon.src = "./images/menu.png";
            }
        });

        document.addEventListener("click", function(event) {
            if (!toggle.contains(event.target) && !overlay.contains(event.target)) {
                overlay.classList.remove("open");
                menuIcon.src = "./images/menu.png";
            }
        });

        const menuItems = document.querySelectorAll('.side-list');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                overlay.classList.remove("open");
                menuIcon.src = "./images/menu.png";
            });
        });
    });
</script>

</html>
