<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-full border border-rose-400/40 bg-rose-500/90 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-white transition duration-150 ease-in-out hover:bg-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2 focus:ring-offset-slate-950 active:bg-rose-600']) }}>
    {{ $slot }}
</button>
