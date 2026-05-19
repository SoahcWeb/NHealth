<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header eyebrow="Poids NHealth" title="Modifier l’entrée de poids">
            <x-slot name="action">
                <a href="{{ route('nhealth.weight.index') }}" class="nhealth-ghost-link">Retour à la liste</a>
            </x-slot>
        </x-nhealth.page-header>
    </x-slot>

    <div class="nhealth-shell-form">
        <x-nhealth.flash :message="session('status')" title="Entrée de poids mise à jour" />

        @if ($errors->any())
            <x-nhealth.alert type="error" title="Problème de validation">
                Les modifications du poids ne sont pas encore complètes. Vérifiez les champs en surbrillance avant d’enregistrer.
            </x-nhealth.alert>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr,auto]">
            <x-nhealth.section eyebrow="Construction du poids" title="Mettre à jour l’entrée de poids">
                <form method="POST" action="{{ route('nhealth.weight.update', $weightEntry) }}" class="grid gap-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="recorded_on" value="Date" />
                        <x-text-input id="recorded_on" name="recorded_on" type="date" class="mt-2 block w-full" :value="old('recorded_on', $weightEntry->recorded_on->toDateString())" required />
                        <x-input-error :messages="$errors->get('recorded_on')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="weight_kg" value="Poids (kg)" />
                        <x-text-input id="weight_kg" name="weight_kg" type="number" step="0.01" min="0" class="mt-2 block w-full" :value="old('weight_kg', $weightEntry->weight_kg)" required />
                        <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="source" value="Source" />
                        <select id="source" name="source" class="nhealth-select">
                            @foreach (['manual' => 'Manuel', 'scale' => 'Balance', 'import' => 'Import'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('source', $weightEntry->source) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('source')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4" class="nhealth-textarea">{{ old('notes', $weightEntry->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Enregistrer les modifications</x-primary-button>
                    </div>
                </form>
            </x-nhealth.section>

            <div class="panel h-fit lg:w-64">
                <p class="text-sm font-semibold text-white">Zone sensible</p>
                <p class="mt-2 text-sm leading-6 text-slate-400">
                    Supprimez cette pesée uniquement si elle est incorrecte et ne doit pas influencer votre historique privé.
                </p>
                <form method="POST" action="{{ route('nhealth.weight.destroy', $weightEntry) }}">
                    @csrf
                    @method('DELETE')
                    <div class="mt-4">
                        <x-danger-button>Supprimer l’entrée</x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
