<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-medium text-gray-900 mb-2">
        {{ __('アカウント削除') }}
    </h2>

    <p class="text-sm text-gray-600 mb-4">
        {{ __('アカウントを削除すると、すべてのリソースとデータが完全に削除されます。アカウントを削除する前に、保持したいデータや情報をダウンロードしてください。') }}
    </p>

    <div class="mt-4">
        <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium"
            x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('アカウント削除') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white text-gray-900">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 mb-2">
                {{ __('本当にアカウントを削除しますか？') }}
            </h2>

            <p class="text-sm text-gray-600 mb-4">
                {{ __('アカウントを削除すると、すべてのリソースとデータが完全に削除されます。アカウントを完全に削除することを確認するために、パスワードを入力してください。') }}
            </p>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('パスワード') }}</label>
                <input id="password" name="password" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900">
                
                @error('password', 'userDeletion')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md text-sm font-medium" x-on:click="$dispatch('close')">
                    {{ __('キャンセル') }}
                </button>

                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium">
                    {{ __('アカウント削除') }}
                </button>
            </div>
        </form>
    </x-modal>
</div>
