<x-sidebar>
    <div class="section bg-white p-4 rounded-lg">
        <h2 class="mb-4 text-2xl font-semibold">{{ __('アカウント情報') }}</h2>
        <div class="overflow-hidden shadow-sm sm:rounded-lg space-y-6">
            <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-sidebar>
