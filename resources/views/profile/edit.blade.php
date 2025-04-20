<x-sidebar>
    <div class="flex justify-center">
        <div class="w-full rounded-xl p-[30px] sm:p-[50px] bg-white">
            <div class="flex items-center mb-5">
                <span class="text-[20px] sm:text-[28.95px] font-semibold text-gray-900">
                    {{ __('アカウント情報') }}
                </span>
            </div>
            <div class="space-y-8">
                <div class="bg-white rounded-lg">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="bg-white rounded-lg">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="bg-white rounded-lg">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-sidebar>
