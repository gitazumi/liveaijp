@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

<x-sidebar>
    <div class="bg-white p-6 rounded-lg">
        <div class="w-full rounded-xl p-[30px] sm:p-[50px] bg-white">
            <div class="flex items-center mb-8 justify-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    {{ __('アカウント情報') }}
                </h1>
            </div>
            <div class="space-y-8">
                <div>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div>
                    @include('profile.partials.update-password-form')
                </div>

                <div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-sidebar>

@push('script')
<script>
    document.body.classList.add('profile-page');
    
    document.addEventListener('DOMContentLoaded', function() {
        const layoutContent = document.querySelector('.layout-content');
        if (layoutContent) {
            layoutContent.style.backgroundColor = 'white';
        }
        
        const sections = document.querySelector('.sections');
        if (sections) {
            sections.style.backgroundColor = 'white';
        }
    });
</script>
@endpush
