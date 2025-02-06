@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[16] text-[#FFFFF]']) }}>
    {{ $value ?? $slot }}
</label>
