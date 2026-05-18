@props([
    'eyebrow',
    'title',
    'description',
    'href' => '#',
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'nhealth-shortcut block']) }}>
    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $eyebrow }}</p>
    <p class="mt-3 text-lg font-semibold text-white">{{ $title }}</p>
    <p class="mt-2 text-sm text-slate-400">{{ $description }}</p>

    @if (trim((string) $slot) !== '')
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</a>
