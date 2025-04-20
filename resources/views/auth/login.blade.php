@extends('company_description.description_layout')
@section('title', 'ログイン | LiveAI')
@section('content')
    <div class="flex flex-col items-center justify-center p-6 max-w-md mx-auto">
        <p class="font-semibold text-center text-[16px] mt-3">
            アカウントにログイン
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-4 w-full">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('メールアドレス')" />
                <div class="relative flex items-center">
                    <x-text-input type="email" name="email" id="email"
                        value="{{ old('email') }}" class="pr-[45px]" required autofocus />
                    <div class="absolute right-0 rounded top-1 h-[42px] px-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px"
                            fill="#173F74">
                            <path
                                d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z" />
                        </svg>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-3">
                <x-input-label for="password" :value="__('パスワード')" />
                <div class="relative">
                    <x-text-input type="password" name="password" id="password" required
                        autocomplete="current-password" />
                    <button type="button" onclick="togglePassword(this, 'password')" data-status="text"
                        class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                        <i class="fa-regular fa-eye-slash" id="icon-password"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-between mt-4">
                <div>
                </div>

                <div>
                    @if (Route::has('password.request'))
                        <a class="block text-[#173F74] hover:underline text-[14px]"
                            href="{{ route('password.request') }}">{{ __('パスワードをお忘れですか？') }}</a>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full">
                    {{ __('ログイン') }}
                </x-primary-button>
            </div>
            <div class="flex items-center justify-between space-x-4 my-5">
                <hr class="flex-grow border-t border-[#173F74]">
                <span class="text-[#173F74] text-[14.14px]"></span>
                <hr class="flex-grow border-t border-[#173F74]">
            </div>
        </form>
        <p class="text-[16px] font-normal text-center mt-5">
            アカウントをお持ちでない方は <a href="{{ route('register') }}" class="text-[#173F74] hover:underline font-bold">新規登録</a>
        </p>
    </div>

    <!-- ログイン向けのスタイルを追加 -->
    <style>
        .desription_container {
            margin-bottom: 50px;
        }
    </style>
@endsection
