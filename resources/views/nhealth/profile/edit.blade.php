<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="NHealth profile"
            title="Health profile"
            description="Your main health reference data, private to your account."
        />
    </x-slot>

    <div class="nhealth-shell-form">
        <x-nhealth.flash :message="session('status')" title="Profile updated" />

        @if ($errors->any())
            <x-nhealth.alert type="error" title="Validation issue">
                Please review the highlighted profile fields before saving again.
            </x-nhealth.alert>
        @endif

        <x-nhealth.section eyebrow="Baseline data" title="Profile details">
            <form method="POST" action="{{ route('nhealth.profile.update') }}" class="nhealth-form-stack">
                @csrf
                @method('PATCH')

                <div class="nhealth-form-grid">
                    <div>
                        <x-input-label for="date_of_birth" value="Date of birth" />
                        <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-2 block w-full" :value="old('date_of_birth', optional($healthProfile?->date_of_birth)->toDateString())" />
                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="biological_sex" value="Biological sex" />
                        <select id="biological_sex" name="biological_sex" class="nhealth-select">
                            <option value="">Select an option</option>
                            @foreach (['female' => 'Female', 'male' => 'Male', 'intersex' => 'Intersex', 'other' => 'Other', 'prefer_not_to_say' => 'Prefer not to say'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('biological_sex', $healthProfile?->biological_sex) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('biological_sex')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="height_cm" value="Height (cm)" />
                        <x-text-input id="height_cm" name="height_cm" type="number" min="0" step="0.01" class="mt-2 block w-full" :value="old('height_cm', $healthProfile?->height_cm)" />
                        <x-input-error :messages="$errors->get('height_cm')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="activity_level" value="Activity level" />
                        <select id="activity_level" name="activity_level" class="nhealth-select">
                            <option value="">Select a level</option>
                            @foreach (['sedentary' => 'Sedentary', 'light' => 'Light', 'moderate' => 'Moderate', 'active' => 'Active', 'athlete' => 'Athlete'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('activity_level', $healthProfile?->activity_level) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('activity_level')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="unit_system" value="Unit system" />
                        <select id="unit_system" name="unit_system" class="nhealth-select">
                            @foreach (['metric' => 'Metric', 'imperial' => 'Imperial'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('unit_system', $healthProfile?->unit_system ?? 'metric') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('unit_system')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="health_notes" value="Health notes" />
                    <textarea id="health_notes" name="health_notes" rows="5" class="nhealth-textarea" placeholder="Allergies, ongoing context, constraints, observations...">{{ old('health_notes', $healthProfile?->health_notes) }}</textarea>
                    <x-input-error :messages="$errors->get('health_notes')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button>Save profile</x-primary-button>
                </div>
            </form>
        </x-nhealth.section>
    </div>
</x-app-layout>
