@extends('company_description.description_layout')
@section('title', 'パスワードをお忘れですか？ | LiveAI')
@section('content')
    <div class="flex flex-col items-center justify-center p-6 max-w-md mx-auto">
        <p class="font-semibold text-center text-[16px] mt-3">
            パスワードをお忘れですか？
        </p>

        <div class="my-4 text-[16px] text-center">
            パスワードリセットリンクを取得するためにメールアドレスを入力してください
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="mt-4 w-full">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('メールアドレス')" />
                <x-text-input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    placeholder="info@example.com" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="w-full text-white">
                    {{ __('パスワードリセット') }}
                </x-primary-button>
            </div>
        </form>
        <p class="text-[16px] font-normal text-center mt-5">
            パスワードを思い出しましたか？
            <a href="{{ route('login') }}" class="text-[#173F74] hover:underline font-bold">
                ログインに戻る
            </a>
        </p>
    </div>
@endsection
