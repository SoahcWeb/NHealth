@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<section {{ $attributes->merge(['class' => 'panel']) }}>
    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
        <div class="max-w-2xl">
            @if ($eyebrow)
                <p class="nhealth-eyebrow">{{ $eyebrow }}</p>
            @endif

            <h3 class="nhealth-section-title">{{ $title }}</h3>

            @if ($description)
                <p class="mt-2 text-sm leading-6 text-slate-400">{{ $description }}</p>
            @endif
        </div>

        @isset($action)
            <div class="flex w-full flex-wrap gap-3 md:w-auto md:justify-end">
                {{ $action }}
            </div>
        @endisset
    </div>

    <div class="mt-6">
        {{ $slot }}
    </div>
</section>
