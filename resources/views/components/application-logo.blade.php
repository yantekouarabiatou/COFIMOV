@props(['size' => 'h-11 w-11', 'padding' => 'p-1.5'])

<div {{ $attributes->merge(['class' => "{$size} {$padding} rounded-lg bg-white shadow-sm flex items-center justify-center shrink-0"]) }}>
    <img src="{{ asset('logocofima.png') }}" alt="COFIMA" class="h-full w-full object-contain" />
</div>
