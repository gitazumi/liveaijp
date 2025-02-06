@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'block mt-1 text-black w-full rounded-md form-input border-none focus:border-none focus:ring-0']) !!}>
