@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-slate-100 shadow-sm placeholder:text-slate-500 focus:border-ankhor-400 focus:ring-ankhor-400']) }}>
