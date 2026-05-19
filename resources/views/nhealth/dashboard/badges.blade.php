<x-nhealth.section
    eyebrow="Badges de progression"
    title="Paliers de régularité"
    :description="$badges['unlocked_count'] . ' badges débloqués sur ' . $badges['total_count'] . ' grâce à votre activité privée NHealth.'"
>
    <div class="mb-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <x-nhealth.widget-card
            label="Badges débloqués"
            :value="$badges['unlocked_count'] . ' / ' . $badges['total_count']"
            hint="Les badges sont calculés à partir de vos données NHealth actuelles sans créer de table de récompenses supplémentaire."
            variant="accent"
        />
        <x-nhealth.widget-card
            label="Logique des badges"
            value="Progression cumulative"
            hint="Les paliers du journal et du poids sont basés sur le total de vos entrées privées, pour rester pertinents dans le temps."
            variant="secondary"
        />
        <x-nhealth.widget-card
            label="Prochain focus"
            :value="$badges['unlocked_count'] < $badges['total_count'] ? 'Continuer à enregistrer' : 'Tous les badges du MVP sont débloqués'"
            hint="La régularité dans les check-ins, les pesées et les objectifs actifs remplit progressivement votre cockpit."
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
                        {{ $badge['is_unlocked'] ? 'Débloqué' : 'Verrouillé' }}
                    </span>
                </div>

                <p class="mt-3 text-sm leading-6 text-slate-300">{{ $badge['description'] }}</p>

                <div class="mt-5 rounded-2xl border border-white/10 bg-slate-950/45 p-4">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Progression</p>
                    <p class="mt-2 text-lg font-semibold text-white">{{ $badge['progress_label'] }}</p>
                    <p class="mt-2 text-sm leading-6 {{ $badge['is_unlocked'] ? 'text-nethra-200' : 'text-slate-400' }}">
                        {{ $badge['achieved_label'] }}
                    </p>
                </div>
            </article>
        @endforeach
    </div>
</x-nhealth.section>
