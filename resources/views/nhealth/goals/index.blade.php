<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header eyebrow="Objectifs NHealth" title="Objectifs">
            <x-slot name="action">
                <a href="{{ route('nhealth.goals.create') }}" class="nhealth-accent-link">Créer un objectif</a>
            </x-slot>
        </x-nhealth.page-header>
    </x-slot>

    <div class="nhealth-shell-content">
        <x-nhealth.flash :message="session('status')" title="Objectifs mis à jour" />

        <section class="grid gap-4 md:grid-cols-3">
            <x-nhealth.stat-card label="Total des objectifs" :value="$goals->total()" hint="Tous les objectifs visibles dans votre suivi privé." />
            <x-nhealth.stat-card
                label="Objectifs actifs"
                :value="$goals->getCollection()->where('status', 'active')->count()"
                hint="Objectifs qui guident actuellement votre transformation."
                variant="accent"
            />
            <x-nhealth.stat-card
                label="Étape suivante"
                value="Affiner le cap"
                hint="Gardez un objectif actif clair et archivez les cibles dépassées."
                variant="secondary"
            />
        </section>

        <x-nhealth.section eyebrow="Bibliothèque" title="Suivi des objectifs">
            <x-nhealth.table>
                <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-400">
                    <tr>
                        <th class="px-4 py-3">Titre</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3">Cible</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 bg-slate-950/60 text-slate-200">
                    @forelse ($goals as $goal)
                        <tr>
                            <td class="px-4 py-4">
                                <p class="font-medium text-white">{{ $goal->title }}</p>
                                @if ($goal->description)
                                    <p class="mt-1 text-xs text-slate-400">{{ $goal->description }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-4">{{ $goal->goal_type }}</td>
                            <td class="px-4 py-4">
                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.25em] text-slate-300">
                                    {{ $goal->status }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                {{ $goal->target_value ? number_format((float) $goal->target_value, 2) . ' ' . $goal->unit : '—' }}
                            </td>
                            <td class="px-4 py-4">{{ optional($goal->target_date)?->translatedFormat('d M Y') ?: '—' }}</td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('nhealth.goals.edit', $goal) }}" class="nhealth-ghost-link">Modifier</a>
                                    <form method="POST" action="{{ route('nhealth.goals.destroy', $goal) }}">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button>Supprimer</x-danger-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-slate-400">
                                Aucun objectif pour le moment. Créez votre première cible pour structurer votre transformation.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-nhealth.table>

            <div class="mt-5">
                {{ $goals->links() }}
            </div>
        </x-nhealth.section>
    </div>
</x-app-layout>
