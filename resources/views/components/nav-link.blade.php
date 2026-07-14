@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-4 py-2 bg-white text-[#034578] rounded-md text-sm font-medium transition duration-150 ease-in-out'
    : 'inline-flex items-center px-4 py-2 text-white hover:bg-white/10 hover:text-white rounded-md text-sm font-medium transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
