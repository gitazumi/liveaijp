<section class="profile-form-section">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('アカウント情報') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("アカウントのメールアドレスを更新します。") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6 profile-form">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="email" :value="__('メールアドレス')" />
            <div class="flex flex-col mt-1">
                <x-text-input id="email" name="email" type="email" class="block w-full profile-input" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="profile-button-container mt-4">
            <x-primary-button class="profile-button">{{ __('保存') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 ml-3"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
