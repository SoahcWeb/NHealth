@props([
    'type' => 'info',
    'title' => null,
])

@php
    $palette = match ($type) {
        'success' => 'border-emerald-400/30 bg-emerald-500/10 text-emerald-100',
        'error' => 'border-rose-400/30 bg-rose-500/10 text-rose-100',
        'warning' => 'border-amber-400/30 bg-amber-500/10 text-amber-100',
        default => 'border-ankhor-400/30 bg-ankhor-500/10 text-ankhor-200',
    };
@endphp

<div {{ $attributes->merge(['class' => "rounded-2xl border px-4 py-3 shadow-lg shadow-black/10 {$palette}"]) }}>
    @if ($title)
        <p class="text-sm font-semibold">{{ $title }}</p>
    @endif

    <div class="{{ $title ? 'mt-1 text-sm opacity-90' : 'text-sm' }}">
        {{ $slot }}
    </div>
</div>
