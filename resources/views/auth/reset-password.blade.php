@extends('company_description.description_layout')
@section('title', '新しいパスワード | LiveAI')
@section('content')
    <div class="flex flex-col items-center justify-center p-6 max-w-md mx-auto">
        <p class="font-semibold text-center text-[16px] mt-3">
            新しいパスワード
        </p>
        <div class="my-4 text-[16px] text-center">
            アカウントの新しいパスワードを設定して、ログインしてすべての機能にアクセスできるようにしてください。
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="mt-4 w-full">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <x-text-input type="hidden" name="email" id="email" value="{{ old('email', $request->email) }}"
                required />

            <!-- Password -->
            <div class="mt-3">
                <x-input-label for="password" :value="__('新しいパスワードを入力')" />
                <div class="relative">
                    <x-text-input type="password" name="password" id="password" placeholder="8文字以上"
                        required autocomplete="current-password" />
                    <button type="button" onclick="togglePassword(this, 'password')" data-status="text"
                        class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                        <i class="fa-regular fa-eye-slash" id="icon-password"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-3">
                <x-input-label for="password_confirmation" :value="__('パスワードを確認')" />
                <div class="relative">
                    <x-text-input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="8文字以上" required />
                    <button type="button" onclick="togglePassword(this, 'password_confirmation')" data-status="text"
                        class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                        <i class="fa-regular fa-eye-slash" id="icon-password_confirmation"></i>
                    </button>
                </div> <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="w-full text-white">
                    {{ __('パスワードを更新') }}
                </x-primary-button>
            </div>
        </form>
    </div>
@endsection
