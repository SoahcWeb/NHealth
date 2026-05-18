@props([
    'eyebrow',
    'title',
    'description' => null,
])

<div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div class="max-w-3xl">
        <p class="nhealth-eyebrow">{{ $eyebrow }}</p>
        <h2 class="nhealth-page-title">{{ $title }}</h2>
    </div>

    <div class="flex w-full max-w-2xl flex-col items-start gap-3 text-sm text-slate-400 lg:items-end lg:text-right">
        @if ($description)
            <p class="max-w-2xl text-sm leading-6 text-slate-400">{{ $description }}</p>
        @endif

        @isset($action)
            <div class="flex w-full flex-wrap gap-3 lg:w-auto lg:justify-end">
                {{ $action }}
            </div>
        @endisset
    </div>
</div>
