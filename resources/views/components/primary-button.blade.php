<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-cofima border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-cofima-dark focus:bg-cofima-dark active:bg-cofima-dark focus:outline-none focus:ring-2 focus:ring-cofima focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
