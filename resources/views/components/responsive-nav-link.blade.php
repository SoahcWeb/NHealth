@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-nethra-400 bg-nethra-500/10 py-2 pe-4 ps-3 text-start text-base font-medium text-nethra-100 focus:border-nethra-300 focus:bg-nethra-500/10 focus:text-nethra-50 focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full border-l-4 border-transparent py-2 pe-4 ps-3 text-start text-base font-medium text-slate-300 hover:border-ankhor-400/40 hover:bg-white/5 hover:text-white focus:border-ankhor-400/40 focus:bg-white/5 focus:text-white focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
