<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-medium text-gray-900 mb-2">
        {{ __('パスワード更新') }}
    </h2>

    <p class="text-sm text-gray-600 mb-4">
        {{ __('安全のため、長くてランダムなパスワードを使用してください。') }}
    </p>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-4">
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('現在のパスワード') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900" autocomplete="current-password">
            
            @error('current_password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('新しいパスワード') }}</label>
            <input id="update_password_password" name="password" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900" autocomplete="new-password">
            
            @error('password', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('パスワード（確認）') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900" autocomplete="new-password">
            
            @error('password_confirmation', 'updatePassword')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium">
                {{ __('保存') }}
            </button>

            @if (session('status') === 'password-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 ml-3"
                >{{ __('保存しました。') }}</span>
            @endif
        </div>
    </form>
</div>
