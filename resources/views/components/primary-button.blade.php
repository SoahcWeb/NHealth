<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-full border border-nethra-300/40 bg-nethra-400 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-950 transition duration-150 ease-in-out hover:bg-nethra-300 focus:bg-nethra-300 focus:outline-none focus:ring-2 focus:ring-nethra-200 focus:ring-offset-2 focus:ring-offset-slate-950 active:bg-nethra-500']) }}>
    {{ $slot }}
</button>
