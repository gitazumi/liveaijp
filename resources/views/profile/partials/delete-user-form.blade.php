<section class="profile-form-section space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('アカウント削除') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('アカウントを削除すると、すべてのリソースとデータが完全に削除されます。アカウントを削除する前に、保持したいデータや情報をダウンロードしてください。') }}
        </p>
    </header>

    <div class="profile-button-container">
        <x-danger-button x-data="" class="profile-danger-button"
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('アカウント削除') }}</x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white text-gray-900 profile-form">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('本当にアカウントを削除しますか？') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('アカウントを削除すると、すべてのリソースとデータが完全に削除されます。アカウントを完全に削除することを確認するために、パスワードを入力してください。') }}
            </p>

            <div class="mt-6 profile-form">
                <x-input-label for="password" value="{{ __('パスワード') }}" class="sr-only" />

                <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4 profile-input" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 profile-button-container">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('キャンセル') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 profile-danger-button">
                    {{ __('アカウント削除') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
