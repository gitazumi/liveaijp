<x-guest-layout>
    <div class="flex justify-center">
        <svg width="164" height="164" viewBox="0 0 164 164" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle opacity="0.5" cx="82" cy="82" r="77" fill="#2A9EFE" stroke="#173F74" stroke-width="10" />
            <path d="M43 91.5545C43 91.5545 51.4643 91.5545 62.75 108.613C62.75 108.613 94.1186 63.9339 122 55"
                stroke="#173F74" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    <h1 class="text-white text-[45px] text-center my-5">
        Successfully
    </h1>
    <p class="text-[18px] text-center mb-5">
        Your password has been reset successfully
    </p>

    <a href="{{route('login')}}" class="flex justify-center items-center w-full py-2 px-4 text-center bg-[#173F74] rounded-full text-white text-sm hover:bg-[#1f559c]">
        <span class="ml-[10px]">
            Continue
        </span>
    </a>
</x-guest-layout>
