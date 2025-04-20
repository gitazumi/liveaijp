<section class="profile-form-section">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('パスワード更新') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('安全のため、長くてランダムなパスワードを使用してください。') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6 profile-form">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('現在のパスワード')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full profile-input" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('新しいパスワード')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full profile-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('パスワード（確認）')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full profile-input" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="profile-button-container">
            <x-primary-button class="profile-button">{{ __('保存') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 ml-3"
                >{{ __('保存しました。') }}</p>
            @endif
        </div>
    </form>
</section>
