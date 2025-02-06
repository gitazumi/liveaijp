@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-semibold text-sm text-[#173F74]']) }}>
        {{ $status }}
    </div>
@endif
