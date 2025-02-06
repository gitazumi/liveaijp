@section('title','New Password')
<x-guest-layout>
    <div class="flex justify-center items-center">
        <img src="{{ asset('logo.png') }}" alt="" srcset="">
    </div>
    <p class="font-semibold text-center text-[16px] my-3">
        New Password
    </p>
    <div class="my-4 text-[16px] text-white text-center">
        Set the new password for your account so you can login and access all featuress.
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        {{-- <div>
            <x-input-label for="email" :value="__('Email')"/> --}}
        <x-text-input type="hidden" name="email" id="email" value="{{ old('email', $request->email) }}"
            required />
        {{-- <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div> --}}

        <!-- Password -->
        <div class="mt-3">
            <x-input-label for="password" :value="__('Enter New Password')" />
            <div class="relative">
                <x-text-input type="password" placeholder="xxxxxxxx" name="password" id="password" placeholder="8 characters at Least"
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
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input type="password" placeholder="xxxxxxxx" name="password_confirmation" id="password_confirmation"
                    placeholder="8 characters at Least" required />
                <button type="button" onclick="togglePassword(this, 'password_confirmation')" data-status="text"
                    class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                    <i class="fa-regular fa-eye-slash" id="icon-password_confirmation"></i>
                </button>
            </div> <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="w-full">
                {{ __('Update Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
