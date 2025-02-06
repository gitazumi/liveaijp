@section('title','Sign up')
<x-guest-layout>
    <div class="flex justify-center items-center">
        <img src="{{ asset('logo.png') }}" alt="" srcset="">
    </div>
    <p class="font-semibold text-center text-[16px] my-3">
        Sign up
    </p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Email Address -->
        <div class="mt-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input type="email" placeholder="info@xyz.com" name="email" id="email" value="{{ old('email') }}" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-3">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input type="password" placeholder="xxxxxxxx" name="password" id="password" required autocomplete="current-password" />
                <button type="button" onclick="togglePassword(this, 'password')" data-status="text"
                    class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                    <i class="fa-regular fa-eye-slash" id="icon-password"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-3">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input type="password" placeholder="xxxxxxxx" name="password_confirmation" id="password_confirmation" required /><button
                    type="button" onclick="togglePassword(this, 'password_confirmation')" data-status="text"
                    class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                    <i class="fa-regular fa-eye-slash" id="icon-password_confirmation"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col items-end mt-4">
            <x-primary-button class="w-full">
                {{ __('Sign up') }}
            </x-primary-button>

        </div>
        <p class="mt-3 text-center text-white text-[16px]">
            Have an account?
            <a class="hover:underline font-bold" href="{{ route('login') }}">
                Log in
            </a>
        </p>
    </form>
</x-guest-layout>
