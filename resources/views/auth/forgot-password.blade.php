@section('title', 'Forgot password')
<x-guest-layout>
    <div class="flex justify-center items-center">
        <img src="{{ asset('logo.png') }}" alt="" srcset="">
    </div>
    <p class="font-semibold text-center text-[16px] my-3">
        Forgot password
    </p>

    <div class="my-4 text-[16px] text-white text-center">
        Enter your email to get reset password link
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
            placeholder="info@xyz.com" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="w-full">
                {{ __('Reset Link') }}
            </x-primary-button>
        </div>
    </form>
    <p class="text-[16px] font-normal text-center py-3">
        Remembered your password?
        <a href="{{ route('login') }}" class="hover:underline font-extrabold">
            Back to Login
        </a>
    </p>
</x-guest-layout>
