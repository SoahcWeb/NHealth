<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">Cockpit Nethra</p>
                <h2 class="text-3xl font-semibold text-white leading-tight">
                    Tableau de bord santé personnel
                </h2>
            </div>
            <p class="max-w-2xl text-sm text-slate-400">
                Un centre de pilotage privé pour votre transformation, vos habitudes et votre récupération.
            </p>
        </div>
    </x-slot>

    <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="stat-card">
                <p class="stat-label">Total des check-ins</p>
                <p class="stat-value">{{ $stats['total_check_ins'] }}</p>
                <p class="stat-hint">Entrées privées enregistrées au fil du temps.</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Série actuelle</p>
                <p class="stat-value">{{ $stats['current_streak'] }} jours</p>
                <p class="stat-hint">Jours consécutifs jusqu’à votre dernière entrée.</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Dernier poids</p>
                <p class="stat-value">
                    {{ $stats['latest_weight_kg'] !== null ? number_format((float) $stats['latest_weight_kg'], 1) . ' kg' : '—' }}
                </p>
                <p class="stat-hint">Poids le plus récemment enregistré.</p>
            </article>

            <article class="stat-card">
                <p class="stat-label">Sommeil moyen sur 7 jours</p>
                <p class="stat-value">
                    {{ $stats['average_sleep_hours'] !== null ? number_format($stats['average_sleep_hours'], 1) . ' h' : '—' }}
                </p>
                <p class="stat-hint">Sommeil moyen sur les 7 derniers jours.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.3fr,0.7fr]">
            <article class="panel">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">Tendance du poids</p>
                        <h3 class="mt-2 text-xl font-semibold text-white">Dernière évolution enregistrée</h3>
                    </div>
                    <a href="{{ route('check-ins.index') }}" class="inline-flex items-center rounded-full border border-ankhor-400/40 bg-ankhor-500/10 px-4 py-2 text-sm font-medium text-ankhor-100 transition hover:border-ankhor-300 hover:bg-ankhor-500/20">
                        Ouvrir le journal
                    </a>
                </div>

                <div class="mt-6 rounded-3xl border border-white/10 bg-slate-950/60 p-4">
                    @if (count($chart['labels']) > 1)
                        <canvas
                            data-weight-chart
                            data-labels='@json($chart['labels'])'
                            data-values='@json($chart['weights'])'
                            class="h-72 w-full"
                        ></canvas>
                    @else
                        <div class="flex h-72 items-center justify-center rounded-2xl border border-dashed border-white/10 bg-white/5 text-center text-sm text-slate-400">
                            Ajoutez au moins deux entrées de poids pour débloquer le graphique de progression.
                        </div>
                    @endif
                </div>
            </article>

            <article class="panel">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">Dernier état</p>
                <h3 class="mt-2 text-xl font-semibold text-white">Vue rapide du jour</h3>

                @if ($latestCheckIn)
                    <div class="mt-6 grid gap-4">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Enregistré le</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $latestCheckIn->recorded_on->translatedFormat('d M Y') }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-sm text-slate-400">Énergie</p>
                                <p class="mt-2 text-2xl font-semibold text-white">{{ $latestCheckIn->energy_level ?? '—' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-sm text-slate-400">Humeur</p>
                                <p class="mt-2 text-2xl font-semibold text-white">{{ $latestCheckIn->mood_level ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm text-slate-400">Notes</p>
                            <p class="mt-2 text-sm leading-6 text-slate-200">{{ $latestCheckIn->notes ?: 'Aucune note enregistrée pour la dernière entrée.' }}</p>
                        </div>
                    </div>
                @else
                    <div class="mt-6 rounded-3xl border border-dashed border-white/10 bg-white/5 p-6 text-sm text-slate-400">
                        Aucune donnée de santé pour le moment. Commencez par votre premier check-in quotidien pour activer le cockpit.
                    </div>
                @endif
            </article>
        </section>

        <section class="panel">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-ankhor-300">Activité récente</p>
                    <h3 class="mt-2 text-xl font-semibold text-white">Dernières entrées privées</h3>
                </div>
                <a href="{{ route('check-ins.index') }}" class="text-sm font-medium text-nethra-200 transition hover:text-nethra-100">
                    Gérer tous les check-ins
                </a>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                @forelse ($recentCheckIns as $checkIn)
                    <article class="rounded-3xl border border-white/10 bg-white/5 p-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $checkIn->recorded_on->translatedFormat('d M') }}</p>
                        <p class="mt-3 text-lg font-semibold text-white">
                            {{ $checkIn->weight_kg ? number_format((float) $checkIn->weight_kg, 1) . ' kg' : 'Aucun poids' }}
                        </p>
                        <div class="mt-4 space-y-2 text-sm text-slate-300">
                            <p>Sommeil : {{ $checkIn->sleep_hours ? number_format((float) $checkIn->sleep_hours, 1) . ' h' : '—' }}</p>
                            <p>Pas : {{ $checkIn->steps ? number_format($checkIn->steps) : '—' }}</p>
                            <p>Énergie : {{ $checkIn->energy_level ?? '—' }}</p>
                        </div>
                    </article>
                @empty
                    <article class="rounded-3xl border border-dashed border-white/10 bg-white/5 p-6 text-sm text-slate-400 md:col-span-2 xl:col-span-5">
                        Vos entrées récentes apparaîtront ici dès que vous commencerez votre suivi.
                    </article>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
