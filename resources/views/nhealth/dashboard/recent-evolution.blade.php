<x-nhealth.section eyebrow="Évolution récente" title="Dernier mouvement">
    <div class="grid gap-4">
        <x-nhealth.widget-card
            label="Dernier poids dédié"
            :value="$latestWeightEntry ? number_format((float) $latestWeightEntry->weight_kg, 2) . ' kg' : '—'"
            :hint="$latestWeightEntry ? 'Enregistré le ' . $latestWeightEntry->recorded_on->translatedFormat('d M Y') : 'Aucune entrée de poids dédiée pour le moment.'"
        />
        <x-nhealth.widget-card
            label="Variation récente"
            :value="$stats['recent_weight_change_kg'] !== null ? (($stats['recent_weight_change_kg'] > 0 ? '+' : '') . number_format($stats['recent_weight_change_kg'], 2) . ' kg') : '—'"
            hint="Différence entre vos deux derniers points de poids exploitables."
        />
    </div>

    <div class="nhealth-chart-frame mt-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-white">Tendance du poids</p>
                <p class="mt-1 text-sm text-slate-400">30 dernières entrées de poids privées.</p>
            </div>
            <a href="{{ route('nhealth.weight.index') }}" class="nhealth-ghost-link">Voir tout</a>
        </div>

        @if (count($weightChart['labels']) > 1)
            <div class="mt-4 h-72 sm:h-80">
                <canvas
                    data-weight-chart
                    data-labels='@json($weightChart['labels'])'
                    data-values='@json($weightChart['weights'])'
                    class="h-full w-full"
                ></canvas>
            </div>
        @else
            <div class="mt-4">
                <x-nhealth.empty-state
                    class="flex h-72 items-center justify-center text-center"
                    message="Ajoutez au moins deux entrées de poids pour débloquer le graphique d’évolution."
                />
            </div>
        @endif
    </div>
</x-nhealth.section>
