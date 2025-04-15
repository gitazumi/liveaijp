<button {{ $attributes->merge(['type' => 'submit', 'class' => 'border border-[#173F74] py-2 px-5 text-center bg-[#173F74] rounded-full text-black text-[16px] hover:bg-[#1f559c] font-semibold']) }}>
    {{ $slot }}
</button>
