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

<article {{ $attributes->merge(['class' => "stat-card h-full {$variantClasses}"]) }}>
    <p class="stat-label">{{ $label }}</p>
    <div class="stat-value">{{ $value }}</div>

    @if ($hint)
        <p class="stat-hint max-w-xs leading-6">{{ $hint }}</p>
    @endif

    @isset($footer)
        <div class="mt-4">
            {{ $footer }}
        </div>
    @endisset
</article>
