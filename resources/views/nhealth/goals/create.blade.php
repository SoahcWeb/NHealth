<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header eyebrow="Objectifs NHealth" title="Créer un objectif" :description="'Objectifs actifs : ' . $activeGoalsCount">
            <x-slot name="action">
                <a href="{{ route('nhealth.goals.index') }}" class="nhealth-ghost-link">Retour à la liste</a>
            </x-slot>
        </x-nhealth.page-header>
    </x-slot>

    <div class="nhealth-shell-form">
        @if ($errors->any())
            <x-nhealth.alert type="error" title="Problème de validation">
                L’objectif n’a pas encore pu être enregistré. Vérifiez les champs en surbrillance puis réessayez.
            </x-nhealth.alert>
        @endif

        <x-nhealth.section eyebrow="Construction de l’objectif" title="Paramétrage de l’objectif">
            <form method="POST" action="{{ route('nhealth.goals.store') }}" class="nhealth-form-stack">
                @csrf

                <div>
                    <x-input-label for="title" value="Titre" />
                    <x-text-input id="title" name="title" type="text" class="mt-2 block w-full" :value="old('title', $goal->title)" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="4" class="nhealth-textarea">{{ old('description', $goal->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="nhealth-form-grid">
                    <div>
                        <x-input-label for="goal_type" value="Type d’objectif" />
                        <x-text-input id="goal_type" name="goal_type" type="text" class="mt-2 block w-full" :value="old('goal_type', $goal->goal_type)" required />
                        <x-input-error :messages="$errors->get('goal_type')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Statut" />
                        <select id="status" name="status" class="nhealth-select">
                            @foreach (['draft' => 'Brouillon', 'active' => 'Actif', 'paused' => 'En pause', 'completed' => 'Terminé', 'archived' => 'Archivé'] as $status => $label)
                                <option value="{{ $status }}" @selected(old('status', $goal->status ?? 'active') === $status)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="direction" value="Direction" />
                        <select id="direction" name="direction" class="nhealth-select">
                            <option value="">Aucune</option>
                            @foreach (['increase' => 'Augmenter', 'decrease' => 'Diminuer', 'maintain' => 'Maintenir'] as $direction => $label)
                                <option value="{{ $direction }}" @selected(old('direction', $goal->direction) === $direction)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('direction')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="unit" value="Unité" />
                        <x-text-input id="unit" name="unit" type="text" class="mt-2 block w-full" :value="old('unit', $goal->unit)" />
                        <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="start_value" value="Valeur de départ" />
                        <x-text-input id="start_value" name="start_value" type="number" step="0.01" min="0" class="mt-2 block w-full" :value="old('start_value', $goal->start_value)" />
                        <x-input-error :messages="$errors->get('start_value')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="target_value" value="Valeur cible" />
                        <x-text-input id="target_value" name="target_value" type="number" step="0.01" min="0" class="mt-2 block w-full" :value="old('target_value', $goal->target_value)" />
                        <x-input-error :messages="$errors->get('target_value')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="starts_on" value="Date de début" />
                        <x-text-input id="starts_on" name="starts_on" type="date" class="mt-2 block w-full" :value="old('starts_on', optional($goal->starts_on)->toDateString())" />
                        <x-input-error :messages="$errors->get('starts_on')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="target_date" value="Date cible" />
                        <x-text-input id="target_date" name="target_date" type="date" class="mt-2 block w-full" :value="old('target_date', optional($goal->target_date)->toDateString())" />
                        <x-input-error :messages="$errors->get('target_date')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('nhealth.goals.index') }}" class="nhealth-ghost-link">Retour</a>
                    <x-primary-button>Créer l’objectif</x-primary-button>
                </div>
            </form>
        </x-nhealth.section>
    </div>
</x-app-layout>
