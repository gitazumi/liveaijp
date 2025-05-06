@extends('company_description.description_layout')
@section('title', '新規登録 | LiveAI')
@section('content')
    <div class="flex flex-col items-center justify-center p-6 max-w-md mx-auto">
        <p class="font-semibold text-center text-[16px] mt-3">
            新規アカウント登録
        </p>

        <form method="POST" action="{{ route('register') }}" class="mt-4 w-full">
            @csrf

            <!-- Email Address -->
            <div class="mt-3">
                <x-input-label for="email" :value="__('メールアドレス')" />
                <x-text-input type="email" name="email" id="email" value="{{ old('email') }}" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-3">
                <x-input-label for="password" :value="__('パスワード')" />
                <div class="relative">
                    <x-text-input type="password" name="password" id="password" required autocomplete="current-password" />
                    <button type="button" onclick="togglePassword(this, 'password')" data-status="text"
                        class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                        <i class="fa-regular fa-eye-slash" id="icon-password"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-3">
                <x-input-label for="password_confirmation" :value="__('パスワード（確認）')" />
                <div class="relative">
                    <x-text-input type="password" name="password_confirmation" id="password_confirmation" required /><button
                        type="button" onclick="togglePassword(this, 'password_confirmation')" data-status="text"
                        class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                        <i class="fa-regular fa-eye-slash" id="icon-password_confirmation"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-4 text-center text-sm">
                <p>登録することで、<a href="{{ url('policy') }}" class="text-[#173F74] hover:underline">利用規約</a>および<a href="{{ url('privacy-policy') }}" class="text-[#173F74] hover:underline">プライバシーポリシー</a>に同意したことになります。</p>
            </div>

            <!-- Google Event Snippet -->
            <script>
                gtag('event', 'conversion', {
                    'send_to': 'AW-16623256919/pksLCKPNjMEaENeKy_Y9', // これをあなたのコードに変更
                    'value': 1.0,
                    'currency': 'USD'
                });
            </script>

            <div class="flex flex-col items-end mt-4">
                <x-primary-button class="w-full">
                    {{ __('登録する') }}
                </x-primary-button>
            </div>
            <p class="mt-3 text-center text-[16px]">
                すでにアカウントをお持ちの方は
                <a class="text-[#173F74] hover:underline font-bold" href="{{ route('login') }}">
                    ログイン
                </a>
            </p>
        </form>
    </div>

    <!-- 登録ページ向けのスタイルを追加 -->
    <style>
        .desription_container {
            margin-bottom: 50px;
        }
    </style>
@endsection
