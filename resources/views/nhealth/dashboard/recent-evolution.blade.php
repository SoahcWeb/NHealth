<x-nhealth.section eyebrow="Recent evolution" title="Latest movement">
    <div class="grid gap-4">
        <x-nhealth.widget-card
            label="Latest dedicated weight"
            :value="$latestWeightEntry ? number_format((float) $latestWeightEntry->weight_kg, 2) . ' kg' : '—'"
            :hint="$latestWeightEntry ? 'Recorded on ' . $latestWeightEntry->recorded_on->format('d M Y') : 'No dedicated weight entry yet.'"
        />
        <x-nhealth.widget-card
            label="Recent change"
            :value="$stats['recent_weight_change_kg'] !== null ? (($stats['recent_weight_change_kg'] > 0 ? '+' : '') . number_format($stats['recent_weight_change_kg'], 2) . ' kg') : '—'"
            hint="Difference between your latest and previous usable weight points."
        />
    </div>

    <div class="nhealth-chart-frame mt-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-white">Weight trend</p>
                <p class="mt-1 text-sm text-slate-400">Last 30 private weight entries.</p>
            </div>
            <a href="{{ route('nhealth.weight.index') }}" class="nhealth-ghost-link">View all</a>
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
                    message="Add at least two weight entries to unlock the evolution chart."
                />
            </div>
        @endif
    </div>
</x-nhealth.section>
