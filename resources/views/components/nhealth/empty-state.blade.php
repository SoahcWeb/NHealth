@props([
    'title' => null,
    'message',
])

<div {{ $attributes->merge(['class' => 'nhealth-empty']) }}>
    @if ($title)
        <p class="text-base font-medium text-white">{{ $title }}</p>
        <p class="mt-2">{{ $message }}</p>
    @else
        {{ $message }}
    @endif

    @if (trim((string) $slot) !== '')
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
