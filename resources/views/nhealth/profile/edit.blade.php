<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="Profil NHealth"
            title="Profil santé"
            description="Vos principales données de référence santé, privées à votre compte."
        />
    </x-slot>

    <div class="nhealth-shell-form">
        <x-nhealth.flash :message="session('status')" title="Profil mis à jour" />

        @if ($errors->any())
            <x-nhealth.alert type="error" title="Problème de validation">
                Merci de vérifier les champs du profil mis en évidence avant d’enregistrer à nouveau.
            </x-nhealth.alert>
        @endif

        <x-nhealth.section eyebrow="Données de base" title="Détails du profil">
            <form method="POST" action="{{ route('nhealth.profile.update') }}" class="nhealth-form-stack">
                @csrf
                @method('PATCH')

                <div class="nhealth-form-grid">
                    <div>
                        <x-input-label for="date_of_birth" value="Date de naissance" />
                        <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-2 block w-full" :value="old('date_of_birth', optional($healthProfile?->date_of_birth)->toDateString())" />
                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="biological_sex" value="Sexe biologique" />
                        <select id="biological_sex" name="biological_sex" class="nhealth-select">
                            <option value="">Sélectionnez une option</option>
                            @foreach (['female' => 'Femme', 'male' => 'Homme', 'intersex' => 'Intersexe', 'other' => 'Autre', 'prefer_not_to_say' => 'Préfère ne pas répondre'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('biological_sex', $healthProfile?->biological_sex) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('biological_sex')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="height_cm" value="Taille (cm)" />
                        <x-text-input id="height_cm" name="height_cm" type="number" min="0" step="0.01" class="mt-2 block w-full" :value="old('height_cm', $healthProfile?->height_cm)" />
                        <x-input-error :messages="$errors->get('height_cm')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="activity_level" value="Niveau d’activité" />
                        <select id="activity_level" name="activity_level" class="nhealth-select">
                            <option value="">Sélectionnez un niveau</option>
                            @foreach (['sedentary' => 'Sédentaire', 'light' => 'Léger', 'moderate' => 'Modéré', 'active' => 'Actif', 'athlete' => 'Athlète'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('activity_level', $healthProfile?->activity_level) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('activity_level')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="unit_system" value="Système d’unités" />
                        <select id="unit_system" name="unit_system" class="nhealth-select">
                            @foreach (['metric' => 'Métrique', 'imperial' => 'Impérial'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('unit_system', $healthProfile?->unit_system ?? 'metric') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('unit_system')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="health_notes" value="Notes de santé" />
                    <textarea id="health_notes" name="health_notes" rows="5" class="nhealth-textarea" placeholder="Allergies, contexte actuel, contraintes, observations...">{{ old('health_notes', $healthProfile?->health_notes) }}</textarea>
                    <x-input-error :messages="$errors->get('health_notes')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button>Enregistrer le profil</x-primary-button>
                </div>
            </form>
        </x-nhealth.section>
    </div>
</x-app-layout>
