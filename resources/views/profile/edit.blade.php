<x-sidebar>
    <div class="bg-blue-600 p-6">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center mb-6 justify-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-white">
                    {{ __('アカウント情報') }}
                </h1>
            </div>
            
            @include('profile.partials.update-profile-information-form')
            
            @include('profile.partials.update-password-form')
            
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-sidebar>
