<x-nhealth.section
    eyebrow="Progress badges"
    title="Consistency milestones"
    :description="$badges['unlocked_count'] . ' of ' . $badges['total_count'] . ' badges unlocked from your private NHealth activity.'"
>
    <div class="mb-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <x-nhealth.widget-card
            label="Unlocked badges"
            :value="$badges['unlocked_count'] . ' / ' . $badges['total_count']"
            hint="Badges are computed from your current NHealth data without creating extra reward tables."
            variant="accent"
        />
        <x-nhealth.widget-card
            label="Badge logic"
            value="Cumulative progress"
            hint="Journal and weight milestones are based on total private entries, so unlocked badges stay meaningful over time."
            variant="secondary"
        />
        <x-nhealth.widget-card
            label="Next focus"
            :value="$badges['unlocked_count'] < $badges['total_count'] ? 'Keep logging' : 'All MVP badges unlocked'"
            hint="Consistency in check-ins, weigh-ins and active goals gradually fills your cockpit."
        />
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($badges['items'] as $badge)
            <article class="nhealth-badge {{ $badge['is_unlocked'] ? 'nhealth-badge-unlocked' : 'nhealth-badge-locked' }}">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] {{ $badge['is_unlocked'] ? 'text-nethra-200' : 'text-slate-400' }}">
                            {{ $badge['status_label'] }}
                        </p>
                        <h3 class="mt-3 text-xl font-semibold text-white">{{ $badge['title'] }}</h3>
                    </div>

                    <span class="rounded-full border px-3 py-1 text-xs uppercase tracking-[0.25em] {{ $badge['is_unlocked'] ? 'border-nethra-300/30 bg-nethra-400/15 text-nethra-200' : 'border-white/10 bg-white/5 text-slate-400' }}">
                        {{ $badge['is_unlocked'] ? 'Unlocked' : 'Locked' }}
                    </span>
                </div>

                <p class="mt-3 text-sm leading-6 text-slate-300">{{ $badge['description'] }}</p>

                <div class="mt-5 rounded-2xl border border-white/10 bg-slate-950/45 p-4">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Progress</p>
                    <p class="mt-2 text-lg font-semibold text-white">{{ $badge['progress_label'] }}</p>
                    <p class="mt-2 text-sm leading-6 {{ $badge['is_unlocked'] ? 'text-nethra-200' : 'text-slate-400' }}">
                        {{ $badge['achieved_label'] }}
                    </p>
                </div>
            </article>
        @endforeach
    </div>
</x-nhealth.section>
