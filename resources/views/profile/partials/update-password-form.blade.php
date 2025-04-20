<section class="bg-white rounded-lg shadow p-6">
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">
            {{ __('パスワード更新') }}
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            {{ __('安全のため、長くてランダムなパスワードを使用してください。') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="mb-6">
            <x-input-label for="update_password_current_password" :value="__('現在のパスワード')" class="block mb-2 text-sm font-medium text-gray-900" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mb-6">
            <x-input-label for="update_password_password" :value="__('新しいパスワード')" class="block mb-2 text-sm font-medium text-gray-900" />
            <x-text-input id="update_password_password" name="password" type="password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="mb-6">
            <x-input-label for="update_password_password_confirmation" :value="__('パスワード（確認）')" class="block mb-2 text-sm font-medium text-gray-900" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="flex items-center justify-center mt-8">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                {{ __('保存') }}
            </button>

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
