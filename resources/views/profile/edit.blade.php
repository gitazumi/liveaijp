@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

<x-sidebar>
    <div class="profile-container">
        <div class="w-full rounded-xl p-[30px] sm:p-[50px] bg-white">
            <div class="flex items-center mb-5 justify-center">
                <span class="text-[20px] sm:text-[28.95px] font-semibold text-gray-900">
                    {{ __('アカウント情報') }}
                </span>
            </div>
            <div class="space-y-8">
                <div class="profile-form-section">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="profile-form-section">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="profile-form-section">
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
