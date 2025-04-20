<section class="bg-white rounded-lg shadow p-6">
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">
            {{ __('アカウント削除') }}
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            {{ __('アカウントを削除すると、すべてのリソースとデータが完全に削除されます。アカウントを削除する前に、保持したいデータや情報をダウンロードしてください。') }}
        </p>
    </header>

    <div class="flex items-center justify-center mt-4">
        <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('アカウント削除') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white text-gray-900">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                {{ __('本当にアカウントを削除しますか？') }}
            </h2>

            <p class="text-sm text-gray-600 mb-6">
                {{ __('アカウントを削除すると、すべてのリソースとデータが完全に削除されます。アカウントを完全に削除することを確認するために、パスワードを入力してください。') }}
            </p>

            <div class="mb-6">
                <x-input-label for="password" value="{{ __('パスワード') }}" class="block mb-2 text-sm font-medium text-gray-900" />
                <x-text-input id="password" name="password" type="password" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <div class="flex items-center justify-end space-x-4">
                <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center" x-on:click="$dispatch('close')">
                    {{ __('キャンセル') }}
                </button>

                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    {{ __('アカウント削除') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
