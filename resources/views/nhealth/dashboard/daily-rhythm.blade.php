<x-nhealth.section eyebrow="Rythme quotidien" title="Dernier journal et habitudes">
    @if ($latestCheckIn)
        <div class="grid gap-4 md:grid-cols-2">
            <x-nhealth.widget-card label="Date" :value="$latestCheckIn->recorded_on->translatedFormat('d M Y')" />
            <x-nhealth.widget-card label="Sommeil" :value="$latestCheckIn->sleep_hours ? number_format((float) $latestCheckIn->sleep_hours, 2) . ' h' : '—'" />
            <x-nhealth.widget-card label="Énergie / Humeur" :value="($latestCheckIn->energy_level ?? '—') . ' / ' . ($latestCheckIn->mood_level ?? '—')" />
            <x-nhealth.widget-card label="Stress" :value="$latestCheckIn->stress_level ?? '—'" />
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <x-nhealth.widget-card label="Eau consommée" :value="$latestCheckIn->water_intake_liters ? number_format((float) $latestCheckIn->water_intake_liters, 1) . ' L' : '—'" />
            <x-nhealth.widget-card label="Pas" :value="$latestCheckIn->steps ? number_format($latestCheckIn->steps) : '—'" />
        </div>

        <x-nhealth.widget-card class="mt-4" label="Notes" :value="$latestCheckIn->notes ?: 'Aucune note enregistrée pour le dernier check-in.'" />
    @else
        <x-nhealth.empty-state
            title="Aucune donnée de journal pour le moment"
            message="Commencez le journal quotidien pour alimenter cette section du cockpit avec l’humeur, l’énergie et le contexte de récupération."
        >
            <a href="{{ route('check-ins.index') }}" class="nhealth-accent-link">Commencer un check-in</a>
        </x-nhealth.empty-state>
    @endif

    <div class="nhealth-chart-frame mt-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-white">Résumé des habitudes</p>
                <p class="mt-1 text-sm text-slate-400">Instantané rapide de vos comportements santé sur 7 jours.</p>
            </div>
            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.25em] text-slate-300">
                7 jours
            </span>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            @foreach ($habitSummary as $item)
                <x-nhealth.widget-card :label="$item['label']" :value="$item['value']" :hint="$item['hint']" />
            @endforeach
        </div>
    </div>
</x-nhealth.section>
