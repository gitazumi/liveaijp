@section('title', ' Google Calendar Sync')
<x-app-layout>
    <div class="flex justify-center">
        <div class="w-full rounded-xl p-[20px] sm:p-[50px] bg-[#E9F2FF]">
            <div class="flex items-center mb-5">
                <a href="{{ route('dashboard') }}">
                    <svg width="24" height="14" viewBox="0 0 24 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8 14C8 13.258 7.267 12.15 6.525 11.22C5.571 10.02 4.431 8.973 3.124 8.174C2.144 7.575 0.956 7 -3.0598e-07 7M-3.0598e-07 7C0.956 7 2.145 6.425 3.124 5.826C4.431 5.026 5.571 3.979 6.525 2.781C7.267 1.85 8 0.74 8 -3.49691e-07M-3.0598e-07 7L24 7"
                            stroke="black" stroke-width="2" />
                    </svg>
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"
                    fill="currentColor" class="mx-2 sm:mx-5">
                    <path
                        d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
                </svg>


                <span class="text-[20px] sm:text-[28.95px] font-semibold">
                    Google Calendar Sync
                </span>
            </div>           

            <a href="{{route('google.redirect')}}" class="mt-10 border border-[#173F74] py-2 px-5 text-center bg-[#173F74] rounded-full text-white text-[12px] sm:text-[16px] hover:bg-[#1f559c] font-semibold !px-5">
                Connect With Google Calendar
            </a>

        </div>
    </div>
</x-app-layout>
