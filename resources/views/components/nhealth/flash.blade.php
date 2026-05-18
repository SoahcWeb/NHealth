@props([
    'type' => 'success',
    'message' => null,
])

@php
    $palette = match ($type) {
        'error' => 'border-rose-400/30 bg-rose-500/10 text-rose-200',
        'warning' => 'border-amber-400/30 bg-amber-500/10 text-amber-100',
        default => 'border-emerald-400/30 bg-emerald-500/10 text-emerald-200',
    };
@endphp

@if ($message)
    <div {{ $attributes->merge(['class' => "rounded-2xl border px-4 py-3 text-sm shadow-lg shadow-black/10 {$palette}"]) }}>
        {{ $message }}
    </div>
@endif
