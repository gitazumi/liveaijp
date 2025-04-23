@section('title', 'ユーザー管理')
<x-app-layout>
    <div class="flex justify-between">
        <h1 class="text-white font-semibold text-[28px]">
            ユーザー管理
        </h1>
    </div>

    <div class="w-full p-5 bg-[#E9F2FF] rounded-lg overflow-x-hidden mt-7">
        <a href="{{ route('users.index') }}">
            <svg width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M8 14C8 13.258 7.267 12.15 6.525 11.22C5.571 10.02 4.431 8.973 3.124 8.174C2.144 7.575 0.956 7 -3.0598e-07 7M-3.0598e-07 7C0.956 7 2.145 6.425 3.124 5.826C4.431 5.026 5.571 3.979 6.525 2.781C7.267 1.85 8 0.74 8 -3.49691e-07M-3.0598e-07 7L24 7"
                    stroke="black" stroke-width="2" />
            </svg>
        </a>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 mt-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6 mt-6">
            <!-- Left column - User actions -->
            <div class="w-full md:w-1/3 bg-white p-5 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">アクション</h2>
                
                <div class="mb-6">
                    <h3 class="font-medium mb-2">アカウントステータス</h3>
                    <div class="flex items-center">
                        <span class="mr-3">現在:</span>
                        <span class="bg-[#173F74] text-white rounded p-1 px-3">{{ $user->status }}</span>
                    </div>
                </div>

                <div class="mb-6">
                    <a href="{{ url('users/auto-login/' . $user->id) }}" class="block w-full bg-[#173F74] hover:bg-[#1f559c] text-white font-bold py-2 px-4 rounded text-center">
                        このユーザーとしてログイン
                    </a>
                    <p class="text-sm text-gray-600 mt-2">ユーザーの使用状況を確認するために、このアカウントでログインします。</p>
                </div>

                <div class="mb-6">
                    <h3 class="font-medium mb-2">利用制限</h3>
                    <div class="flex flex-col gap-2">
                        <div>
                            <span class="mr-3">FAQ登録数:</span>
                            <span class="font-bold">{{ $user->faq_limit === null ? '無制限' : $user->faq_limit }}</span>
                        </div>
                        <div>
                            <span class="mr-3">APIリクエスト数:</span>
                            <span class="font-bold">{{ $user->api_request_limit === null ? '無制限' : $user->api_request_limit }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right column - User form -->
            <div class="w-full md:w-2/3 bg-white p-5 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">ユーザー情報の編集</h2>
                
                <form action="{{ route('users.update-manage', ['id' => $user->id]) }}" method="post">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                            class="w-full rounded border-gray-300 focus:border-[#344EAF] focus:ring-[#344EAF]">
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">ステータス</label>
                        <select name="status" id="status" class="w-full rounded border-gray-300 focus:border-[#344EAF] focus:ring-[#344EAF]">
                            <option value="unverified" @selected(old('status', $user->status) == 'unverified')>メール未認証</option>
                            <option value="registered" @selected(old('status', $user->status) == 'registered')>登録済み（未使用）</option>
                            <option value="active" @selected(old('status', $user->status) == 'active')>利用中</option>
                            <option value="inactive" @selected(old('status', $user->status) == 'inactive')>退会済み</option>
                        </select>
                        @error('status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">新しいパスワード（変更する場合のみ）</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="xxxxxxx"
                                class="w-full rounded border-gray-300 focus:border-[#344EAF] focus:ring-[#344EAF]">
                            <button type="button" onclick="togglePassword(this, 'password')" data-status="text"
                                class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                                <i class="fa-regular fa-eye-slash" id="icon-password"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">パスワード確認</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="xxxxxxx"
                                class="w-full rounded border-gray-300 focus:border-[#344EAF] focus:ring-[#344EAF]">
                            <button type="button" onclick="togglePassword(this, 'password_confirmation')" data-status="text"
                                class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                                <i class="fa-regular fa-eye-slash" id="icon-password_confirmation"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <h3 class="font-medium mb-3">利用制限の設定</h3>
                        
                        <div class="mb-4">
                            <label for="faq_limit_type" class="block text-sm font-medium text-gray-700 mb-1">FAQ登録数の制限タイプ</label>
                            <select name="faq_limit_type" id="faq_limit_type" class="w-full rounded border-gray-300 focus:border-[#344EAF] focus:ring-[#344EAF] mb-2">
                                <option value="unlimited" @selected($user->faq_limit === null)>無制限</option>
                                <option value="limited" @selected($user->faq_limit !== null)>制限あり</option>
                            </select>
                            
                            <div id="faq_limit_container" @class(['hidden' => $user->faq_limit === null])>
                                <label for="faq_limit" class="block text-sm font-medium text-gray-700 mb-1">FAQ登録数の制限</label>
                                <input type="number" name="faq_limit" id="faq_limit" value="{{ old('faq_limit', $user->faq_limit) }}" min="0"
                                    class="w-full rounded border-gray-300 focus:border-[#344EAF] focus:ring-[#344EAF]">
                                @error('faq_limit')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="api_request_limit_type" class="block text-sm font-medium text-gray-700 mb-1">APIリクエスト数の制限タイプ</label>
                            <select name="api_request_limit_type" id="api_request_limit_type" class="w-full rounded border-gray-300 focus:border-[#344EAF] focus:ring-[#344EAF] mb-2">
                                <option value="unlimited" @selected($user->api_request_limit === null)>無制限</option>
                                <option value="limited" @selected($user->api_request_limit !== null)>制限あり</option>
                            </select>
                            
                            <div id="api_request_limit_container" @class(['hidden' => $user->api_request_limit === null])>
                                <label for="api_request_limit" class="block text-sm font-medium text-gray-700 mb-1">APIリクエスト数の制限</label>
                                <input type="number" name="api_request_limit" id="api_request_limit" value="{{ old('api_request_limit', $user->api_request_limit) }}" min="0"
                                    class="w-full rounded border-gray-300 focus:border-[#344EAF] focus:ring-[#344EAF]">
                                @error('api_request_limit')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#173F74] hover:bg-[#1f559c] text-white font-bold py-2 px-6 rounded">
                            保存
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        function togglePassword(button, inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('icon-' + inputId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        $(document).ready(function() {
            $('#faq_limit_type').on('change', function() {
                $('#faq_limit_container').toggleClass('hidden', $(this).val() === 'unlimited');
            });
            
            $('#api_request_limit_type').on('change', function() {
                $('#api_request_limit_container').toggleClass('hidden', $(this).val() === 'unlimited');
            });
        });
    </script>
    @endpush
</x-app-layout>
