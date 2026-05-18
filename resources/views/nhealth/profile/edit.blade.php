<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">NHealth profile</p>
                <h2 class="text-3xl font-semibold text-white">Health profile</h2>
            </div>
            <p class="max-w-2xl text-sm text-slate-400">
                Your main health reference data, private to your account.
            </p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="panel">
            <form method="POST" action="{{ route('nhealth.profile.update') }}" class="grid gap-6">
                @csrf
                @method('PATCH')

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-input-label for="date_of_birth" value="Date of birth" />
                        <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-2 block w-full" :value="old('date_of_birth', optional($healthProfile?->date_of_birth)->toDateString())" />
                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="biological_sex" value="Biological sex" />
                        <select id="biological_sex" name="biological_sex" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-slate-100 focus:border-ankhor-400 focus:ring-ankhor-400">
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
                        <select id="activity_level" name="activity_level" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-slate-100 focus:border-ankhor-400 focus:ring-ankhor-400">
                            <option value="">Select a level</option>
                            @foreach (['sedentary' => 'Sedentary', 'light' => 'Light', 'moderate' => 'Moderate', 'active' => 'Active', 'athlete' => 'Athlete'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('activity_level', $healthProfile?->activity_level) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('activity_level')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="unit_system" value="Unit system" />
                        <select id="unit_system" name="unit_system" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-slate-100 focus:border-ankhor-400 focus:ring-ankhor-400">
                            @foreach (['metric' => 'Metric', 'imperial' => 'Imperial'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('unit_system', $healthProfile?->unit_system ?? 'metric') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('unit_system')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="health_notes" value="Health notes" />
                    <textarea id="health_notes" name="health_notes" rows="5" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-ankhor-400 focus:ring-ankhor-400" placeholder="Allergies, ongoing context, constraints, observations...">{{ old('health_notes', $healthProfile?->health_notes) }}</textarea>
                    <x-input-error :messages="$errors->get('health_notes')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button>Save profile</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
