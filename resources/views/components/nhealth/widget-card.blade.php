@props([
    'label',
    'value',
    'hint' => null,
    'variant' => 'default',
])

@php
    $variantClasses = match ($variant) {
        'accent' => 'border-nethra-400/20 bg-nethra-500/10',
        'secondary' => 'border-ankhor-400/20 bg-ankhor-500/10',
        default => 'border-white/10 bg-white/5',
    };
@endphp

<div {{ $attributes->merge(['class' => "nhealth-widget {$variantClasses}"]) }}>
    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">{{ $label }}</p>
    <p class="mt-2 text-lg font-semibold text-white">{{ $value }}</p>

    @if ($hint)
        <p class="mt-2 text-sm text-slate-400">{{ $hint }}</p>
    @endif

    @if (trim((string) $slot) !== '')
        <div class="mt-3">
            {{ $slot }}
        </div>
    @endif
</div>
