<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="Poids NHealth"
            title="Historique du poids"
            description="Enregistrez vos entrées de poids dédiées séparément des check-ins quotidiens."
        />
    </x-slot>

    <div class="nhealth-shell-content">
        <x-nhealth.flash :message="session('status')" title="Entrée de poids mise à jour" />

        @if ($errors->any())
            <x-nhealth.alert type="error" title="Problème de validation">
                L’entrée de poids n’a pas encore pu être enregistrée. Vérifiez les champs en surbrillance puis réessayez.
            </x-nhealth.alert>
        @endif

        <div class="grid gap-6 lg:grid-cols-[0.9fr,1.1fr]">
            <x-nhealth.section eyebrow="Saisie" title="Ajouter une entrée de poids">
                <form method="POST" action="{{ route('nhealth.weight.store') }}" class="grid gap-5">
                    @csrf

                    <div>
                        <x-input-label for="recorded_on" value="Date" />
                        <x-text-input id="recorded_on" name="recorded_on" type="date" class="mt-2 block w-full" :value="old('recorded_on', $defaultRecordedOn)" required />
                        <x-input-error :messages="$errors->get('recorded_on')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="weight_kg" value="Poids (kg)" />
                        <x-text-input id="weight_kg" name="weight_kg" type="number" step="0.01" min="0" class="mt-2 block w-full" :value="old('weight_kg')" required />
                        <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="source" value="Source" />
                        <select id="source" name="source" class="nhealth-select">
                            @foreach (['manual' => 'Manuel', 'scale' => 'Balance', 'import' => 'Import'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('source', 'manual') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('source')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4" class="nhealth-textarea">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Enregistrer l’entrée</x-primary-button>
                    </div>
                </form>
            </x-nhealth.section>

            <x-nhealth.section eyebrow="Historique" title="Pesées récentes">
                <div class="mb-5 grid gap-4 sm:grid-cols-2">
                    <x-nhealth.widget-card
                        label="Entrées enregistrées"
                        :value="$weightEntries->total()"
                        hint="Mesures de poids dédiées enregistrées dans votre historique privé."
                    />
                    <x-nhealth.widget-card
                        label="Rythme de saisie"
                        value="Suivi régulier"
                        hint="Utilisez si possible la même balance et le même moment de la journée pour des tendances plus propres."
                        variant="secondary"
                    />
                </div>

                <x-nhealth.table>
                    <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Poids</th>
                            <th class="px-4 py-3">Source</th>
                            <th class="px-4 py-3">Notes</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-slate-950/60 text-slate-200">
                        @forelse ($weightEntries as $weightEntry)
                            <tr class="align-top">
                                <td class="px-4 py-4 font-medium text-white">{{ $weightEntry->recorded_on->translatedFormat('d M Y') }}</td>
                                <td class="px-4 py-4">{{ number_format((float) $weightEntry->weight_kg, 2) }} kg</td>
                                <td class="px-4 py-4">{{ ucfirst(match ($weightEntry->source) { 'manual' => 'manuel', 'scale' => 'balance', 'import' => 'import', default => $weightEntry->source }) }}</td>
                                <td class="px-4 py-4 text-slate-400">{{ $weightEntry->notes ?: '—' }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('nhealth.weight.edit', $weightEntry) }}" class="nhealth-ghost-link">Modifier</a>
                                        <form method="POST" action="{{ route('nhealth.weight.destroy', $weightEntry) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button>Supprimer</x-danger-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-slate-400">
                                Aucune entrée de poids pour le moment. Commencez à enregistrer vos mesures pour construire votre historique.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </x-nhealth.table>

                <div class="mt-5">
                    {{ $weightEntries->links() }}
                </div>
            </x-nhealth.section>
        </div>
    </div>
</x-app-layout>
