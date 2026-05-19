<x-nhealth.section eyebrow="Objectif actif" title="Cible principale">
    <x-slot name="action">
        <a href="{{ route('nhealth.goals.index') }}" class="nhealth-ghost-link">Gérer les objectifs</a>
    </x-slot>

    @if ($activeGoal)
        <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $activeGoal->goal_type }}</p>
                    <p class="mt-3 text-2xl font-semibold text-white">{{ $activeGoal->title }}</p>
                    <p class="nhealth-copy mt-2">{{ $activeGoal->description ?: 'Aucune description fournie.' }}</p>
                </div>
                <div class="rounded-full border border-white/10 bg-slate-950/70 px-3 py-1 text-xs uppercase tracking-[0.25em] text-slate-300">
                    {{ $activeGoal->status }}
                </div>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <x-nhealth.widget-card variant="secondary" label="Cible" :value="$activeGoal->target_value ? number_format((float) $activeGoal->target_value, 2) . ' ' . $activeGoal->unit : '—'" />
                <x-nhealth.widget-card variant="secondary" label="Date cible" :value="optional($activeGoal->target_date)?->translatedFormat('d M Y') ?: '—'" />
            </div>

            <div class="mt-4 rounded-2xl border border-ankhor-400/20 bg-slate-950/50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-medium text-white">Progression estimée</p>
                    @if ($progress['is_estimable'])
                        <span class="text-sm text-nethra-200">{{ $progress['percentage'] }}%</span>
                    @else
                        <span class="text-sm text-slate-400">En attente</span>
                    @endif
                </div>

                @if ($progress['is_estimable'])
                    <div class="mt-3 h-3 overflow-hidden rounded-full bg-white/10">
                        <div class="h-full rounded-full bg-gradient-to-r from-nethra-400 to-ankhor-400" style="width: {{ $progress['percentage'] }}%"></div>
                    </div>
                    <div class="mt-3 flex items-center justify-between gap-3 text-sm">
                        <span class="text-slate-300">{{ $progress['remaining_label'] }}</span>
                        <span class="text-slate-400">{{ $stats['current_weight_kg'] !== null ? number_format($stats['current_weight_kg'], 2) . ' kg actuellement' : 'Aucun poids actuel' }}</span>
                    </div>
                @else
                    <p class="mt-3 text-sm text-slate-400">{{ $progress['message'] }}</p>
                @endif
            </div>
        </div>
    @else
        <x-nhealth.empty-state
            title="Aucun objectif actif"
            message="Créez une cible active pour débloquer la visibilité de progression et donner un cap clair au cockpit."
        >
            <a href="{{ route('nhealth.goals.create') }}" class="nhealth-accent-link">Créer un objectif</a>
        </x-nhealth.empty-state>
    @endif
</x-nhealth.section>
