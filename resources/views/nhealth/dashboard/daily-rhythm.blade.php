<x-nhealth.section eyebrow="Daily rhythm" title="Latest journal and habits">
    @if ($latestCheckIn)
        <div class="grid gap-4 md:grid-cols-2">
            <x-nhealth.widget-card label="Date" :value="$latestCheckIn->recorded_on->format('d M Y')" />
            <x-nhealth.widget-card label="Sleep" :value="$latestCheckIn->sleep_hours ? number_format((float) $latestCheckIn->sleep_hours, 2) . ' h' : '—'" />
            <x-nhealth.widget-card label="Energy / Mood" :value="($latestCheckIn->energy_level ?? '—') . ' / ' . ($latestCheckIn->mood_level ?? '—')" />
            <x-nhealth.widget-card label="Stress" :value="$latestCheckIn->stress_level ?? '—'" />
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <x-nhealth.widget-card label="Water intake" :value="$latestCheckIn->water_intake_liters ? number_format((float) $latestCheckIn->water_intake_liters, 1) . ' L' : '—'" />
            <x-nhealth.widget-card label="Steps" :value="$latestCheckIn->steps ? number_format($latestCheckIn->steps) : '—'" />
        </div>

        <x-nhealth.widget-card class="mt-4" label="Notes" :value="$latestCheckIn->notes ?: 'No notes saved for the latest check-in.'" />
    @else
        <x-nhealth.empty-state
            title="No journal data yet"
            message="Start the daily journal to populate this cockpit section with mood, energy and recovery context."
        >
            <a href="{{ route('check-ins.index') }}" class="nhealth-accent-link">Start check-in</a>
        </x-nhealth.empty-state>
    @endif

    <div class="nhealth-chart-frame mt-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-white">Habit summary</p>
                <p class="mt-1 text-sm text-slate-400">Quick 7-day health behavior snapshot.</p>
            </div>
            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.25em] text-slate-300">
                7 days
            </span>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            @foreach ($habitSummary as $item)
                <x-nhealth.widget-card :label="$item['label']" :value="$item['value']" :hint="$item['hint']" />
            @endforeach
        </div>
    </div>
</x-nhealth.section>
