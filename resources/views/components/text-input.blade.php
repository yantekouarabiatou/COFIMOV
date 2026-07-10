@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 focus:border-cofima focus:ring-cofima/20 rounded-lg shadow-sm']) }}>
