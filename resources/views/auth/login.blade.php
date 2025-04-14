@extends('company_description.description_layout')
@section('title', 'ログイン | LiveAI')
@section('content')
    <div class="flex flex-col items-center justify-center p-6 max-w-md mx-auto">
        <p class="font-semibold text-center text-[16px] mt-3">
            アカウントにログイン
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-4 w-full">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('メールアドレス')" />
                <div class="relative flex items-center">
                    <x-text-input type="email" placeholder="info@example.com" name="email" id="email"
                        value="{{ old('email') }}" class="pr-[45px]" required autofocus />
                    <div class="absolute right-0 rounded top-1 h-[42px] px-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px"
                            fill="#173F74">
                            <path
                                d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z" />
                        </svg>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-3">
                <x-input-label for="password" :value="__('パスワード')" />
                <div class="relative">
                    <x-text-input type="password" placeholder="••••••••" name="password" id="password" required
                        autocomplete="current-password" />
                    <button type="button" onclick="togglePassword(this, 'password')" data-status="text"
                        class="absolute inset-y-0 end-0 flex items-center z-20 px-3 cursor-pointer rounded-e-md focus:outline-none text-[#173F74] focus:text-[#173F74]">
                        <i class="fa-regular fa-eye-slash" id="icon-password"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-between mt-4">
                <div>
                </div>

                <div>
                    @if (Route::has('password.request'))
                        <a class="block text-[#173F74] hover:underline text-[14px]"
                            href="{{ route('password.request') }}">{{ __('パスワードをお忘れですか？') }}</a>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full">
                    {{ __('ログイン') }}
                </x-primary-button>
            </div>
            <div class="flex items-center justify-between space-x-4 my-5">
                <hr class="flex-grow border-t border-[#173F74]">
                <span class="text-[#173F74] text-[14.14px]">または</span>
                <hr class="flex-grow border-t border-[#173F74]">
            </div>


        </form>
        <div class="mt-6 w-full">
            <a href="{{ url('auth/google') }}"
                class="flex justify-center items-center w-full py-2 px-4 text-center bg-[#173F74] rounded-full text-white text-sm hover:bg-[#1f559c]">

                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M5.32142 0.433474C3.72278 0.98806 2.3441 2.04068 1.38791 3.43673C0.431722 4.83277 -0.051587 6.49865 0.00897668 8.18968C0.0695404 9.88071 0.670784 11.5077 1.72439 12.8318C2.77801 14.1559 4.22845 15.1072 5.86267 15.546C7.18758 15.8878 8.57569 15.9029 9.90767 15.5897C11.1143 15.3187 12.2299 14.7389 13.1452 13.9072C14.0978 13.0152 14.7892 11.8803 15.1452 10.6247C15.5321 9.25931 15.6009 7.82339 15.3464 6.42722H7.90642V9.51347H12.2152C12.1291 10.0057 11.9445 10.4755 11.6726 10.8948C11.4007 11.314 11.047 11.6741 10.6327 11.9535C10.1065 12.3015 9.51342 12.5357 8.89142 12.641C8.2676 12.757 7.62774 12.757 7.00392 12.641C6.37166 12.5103 5.77355 12.2493 5.24767 11.8747C4.40286 11.2767 3.76852 10.4271 3.43517 9.44722C3.09619 8.44897 3.09619 7.36673 3.43517 6.36847C3.67246 5.66874 4.06472 5.03164 4.58267 4.50472C5.17541 3.89066 5.92584 3.45172 6.75162 3.23607C7.5774 3.02042 8.44662 3.03639 9.26392 3.28222C9.90238 3.47821 10.4862 3.82065 10.9689 4.28222C11.4548 3.79889 11.9398 3.31431 12.4239 2.82847C12.6739 2.56722 12.9464 2.31847 13.1927 2.05097C12.4559 1.36532 11.591 0.831795 10.6477 0.480974C8.9298 -0.142788 7.05015 -0.159552 5.32142 0.433474Z"
                        fill="white" />
                    <path
                        d="M5.32152 0.433471C7.0501 -0.159957 8.92976 -0.143636 10.6478 0.479721C11.5913 0.832926 12.4558 1.36902 13.1915 2.05722C12.9415 2.32472 12.6778 2.57472 12.4228 2.83472C11.9378 3.31889 11.4532 3.80139 10.969 4.28222C10.4863 3.82064 9.90248 3.47821 9.26402 3.28222C8.44699 3.03552 7.5778 3.01863 6.7518 3.2334C5.9258 3.44817 5.17491 3.8863 4.58152 4.49972C4.06357 5.02664 3.67131 5.66374 3.43402 6.36347L0.842773 4.35722C1.77028 2.51792 3.37621 1.111 5.32152 0.433471Z"
                        fill="white" />
                    <path
                        d="M0.154996 6.34468C0.294273 5.65442 0.525501 4.98596 0.842496 4.35718L3.43375 6.36843C3.09476 7.36669 3.09476 8.44892 3.43375 9.44718C2.57041 10.1138 1.70666 10.7838 0.842496 11.4572C0.0489348 9.87757 -0.193088 8.07781 0.154996 6.34468Z"
                        fill="white" />
                    <path
                        d="M7.90632 6.42603H15.3463C15.6008 7.82219 15.532 9.25811 15.1451 10.6235C14.7891 11.8791 14.0977 13.014 13.1451 13.906C12.3088 13.2535 11.4688 12.606 10.6326 11.9535C11.0472 11.6739 11.401 11.3134 11.673 10.8937C11.9449 10.474 12.1293 10.0037 12.2151 9.51103H7.90632C7.90507 8.48353 7.90632 7.45478 7.90632 6.42603Z"
                        fill="white" />
                    <path
                        d="M0.841309 11.4573C1.70548 10.7906 2.56923 10.1206 3.43256 9.44727C3.76657 10.4275 4.40181 11.2771 5.24756 11.8748C5.77507 12.2476 6.37447 12.5064 7.00756 12.6348C7.63138 12.7508 8.27124 12.7508 8.89506 12.6348C9.51705 12.5295 10.1102 12.2953 10.6363 11.9473C11.4726 12.5998 12.3126 13.2473 13.1488 13.8998C12.2337 14.7319 11.1181 15.3121 9.91131 15.5835C8.57932 15.8966 7.19121 15.8816 5.86631 15.5398C4.81844 15.26 3.83965 14.7668 2.99131 14.091C2.09339 13.3781 1.36 12.4797 0.841309 11.4573Z"
                        fill="white" />
                </svg>
                <span class="ml-[10px]">
                    Googleでログイン
                </span>
            </a>
        </div>
        <p class="text-[16px] font-normal text-center mt-5">
            アカウントをお持ちでない方は <a href="{{ route('register') }}" class="text-[#173F74] hover:underline font-bold">新規登録</a>
        </p>
    </div>

    <!-- ログイン向けのスタイルを追加 -->
    <style>
        .desription_container {
            margin-bottom: 50px;
        }
    </style>
@endsection
