<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">Journal quotidien</p>
            <h1 class="text-2xl font-semibold text-white sm:text-3xl">Check-ins quotidiens</h1>
            <p class="max-w-3xl text-sm text-slate-300">
                Une entrée de journal privée par jour, liée uniquement à votre compte et réutilisable pour la mise à jour du jour.
            </p>
        </div>
    </x-slot>

    <div class="nhealth-shell">
        @if (session('status'))
            <x-nhealth.flash :message="session('status')" title="Journal mis à jour" />
        @endif

        @if ($errors->any())
            <x-nhealth.alert type="error" title="Problème de validation">
                <p class="font-medium">Merci de corriger les champs en surbrillance puis de réessayer.</p>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-rose-100/90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-nhealth.alert>
        @endif

        <section class="grid gap-6 lg:grid-cols-[1.15fr,0.85fr]">
            <div class="rounded-3xl border border-white/10 bg-slate-900/75 p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">
                            {{ $todayCheckIn ? 'Aujourd’hui déjà enregistré' : 'Aujourd’hui pas encore enregistré' }}
                        </p>
                        <h2 class="mt-2 text-2xl font-semibold text-white">
                            {{ $todayCheckIn ? "Mettre à jour le journal d’aujourd’hui" : "Enregistrer le journal d’aujourd’hui" }}
                        </h2>
                        <p class="mt-2 text-sm text-slate-300">
                            Une entrée par date. Enregistrer une date existante met à jour cette entrée du journal au lieu de créer un doublon.
                        </p>
                    </div>

                    <div class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">
                        Une entrée par date
                    </div>
                </div>

                <form method="POST" action="{{ route('check-ins.store') }}" class="mt-6 grid gap-5">
                    @csrf

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <x-input-label for="recorded_on" value="Date" />
                            <x-text-input
                                id="recorded_on"
                                name="recorded_on"
                                type="date"
                                class="mt-2 block w-full"
                                :value="old('recorded_on', optional($todayCheckIn?->recorded_on)->toDateString() ?? $defaultRecordedOn)"
                                required
                            />
                            <x-input-error :messages="$errors->get('recorded_on')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="weight_kg" value="Poids (kg)" />
                            <x-text-input
                                id="weight_kg"
                                name="weight_kg"
                                type="number"
                                step="0.01"
                                min="0"
                                class="mt-2 block w-full"
                                :value="old('weight_kg', $todayCheckIn?->weight_kg)"
                            />
                            <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sleep_hours" value="Sommeil (heures)" />
                            <x-text-input
                                id="sleep_hours"
                                name="sleep_hours"
                                type="number"
                                step="0.25"
                                min="0"
                                max="24"
                                class="mt-2 block w-full"
                                :value="old('sleep_hours', $todayCheckIn?->sleep_hours)"
                            />
                            <x-input-error :messages="$errors->get('sleep_hours')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="steps" value="Pas" />
                            <x-text-input
                                id="steps"
                                name="steps"
                                type="number"
                                min="0"
                                max="100000"
                                class="mt-2 block w-full"
                                :value="old('steps', $todayCheckIn?->steps)"
                            />
                            <x-input-error :messages="$errors->get('steps')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="energy_level" value="Énergie (1-10)" />
                            <x-text-input
                                id="energy_level"
                                name="energy_level"
                                type="number"
                                min="1"
                                max="10"
                                class="mt-2 block w-full"
                                :value="old('energy_level', $todayCheckIn?->energy_level)"
                            />
                            <x-input-error :messages="$errors->get('energy_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="mood_level" value="Humeur (1-10)" />
                            <x-text-input
                                id="mood_level"
                                name="mood_level"
                                type="number"
                                min="1"
                                max="10"
                                class="mt-2 block w-full"
                                :value="old('mood_level', $todayCheckIn?->mood_level)"
                            />
                            <x-input-error :messages="$errors->get('mood_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="stress_level" value="Stress (1-10)" />
                            <x-text-input
                                id="stress_level"
                                name="stress_level"
                                type="number"
                                min="1"
                                max="10"
                                class="mt-2 block w-full"
                                :value="old('stress_level', $todayCheckIn?->stress_level)"
                            />
                            <x-input-error :messages="$errors->get('stress_level')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="water_intake_liters" value="Eau (litres)" />
                            <x-text-input
                                id="water_intake_liters"
                                name="water_intake_liters"
                                type="number"
                                step="0.1"
                                min="0"
                                max="20"
                                class="mt-2 block w-full"
                                :value="old('water_intake_liters', $todayCheckIn?->water_intake_liters)"
                            />
                            <x-input-error :messages="$errors->get('water_intake_liters')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4" class="nhealth-textarea" placeholder="Entraînement, repas, récupération, stress, victoires...">{{ old('notes', $todayCheckIn?->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-slate-400">
                            Votre entrée reste privée sur votre compte et peut être mise à jour plus tard en sélectionnant la même date.
                        </p>
                        <x-primary-button>
                            {{ $todayCheckIn ? 'Mettre à jour le check-in' : 'Enregistrer le check-in' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <div class="rounded-3xl border border-white/10 bg-slate-900/75 p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">Vue du jour</p>
                <h2 class="mt-2 text-2xl font-semibold text-white">Statut du journal</h2>
                <p class="mt-2 text-sm text-slate-300">
                    Gardez un aperçu privé et simple de la façon dont la journée évolue.
                </p>

                <div class="mt-6 grid gap-4">
                    <div class="rounded-2xl border border-ankhor-400/20 bg-ankhor-500/10 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-ankhor-300">Statut</p>
                        <p class="mt-2 text-lg font-semibold text-white">
                            {{ $todayCheckIn ? 'Aujourd’hui est déjà enregistré' : 'Aucune entrée pour aujourd’hui' }}
                        </p>
                        <p class="mt-2 text-sm text-slate-300">
                            {{ $todayCheckIn ? "Le formulaire est prérempli avec les données d’aujourd’hui pour une mise à jour rapide." : "Créez l’entrée d’aujourd’hui pour garder votre série et votre contexte à jour." }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Confidentialité</p>
                        <p class="mt-2 text-lg font-semibold text-white">Isolé par utilisateur</p>
                        <p class="mt-2 text-sm text-slate-300">
                            Chaque check-in appartient uniquement à votre compte.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-4">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Suivi du jour</p>
                        <p class="mt-2 text-lg font-semibold text-white">8 signaux quotidiens</p>
                        <p class="mt-2 text-sm text-slate-300">
                            Date, poids, sommeil, pas, énergie, humeur, stress, eau et notes.
                        </p>
                    </div>

                    @if ($todayCheckIn)
                        <div class="rounded-2xl border border-nethra-400/20 bg-nethra-500/10 p-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-nethra-100">Instantané actuel du jour</p>
                            <div class="mt-3 grid grid-cols-2 gap-3 text-sm text-slate-200">
                                <p>Poids : {{ $todayCheckIn->weight_kg ? number_format((float) $todayCheckIn->weight_kg, 2) . ' kg' : '—' }}</p>
                                <p>Sommeil : {{ $todayCheckIn->sleep_hours ? number_format((float) $todayCheckIn->sleep_hours, 2) . ' h' : '—' }}</p>
                                <p>Énergie : {{ $todayCheckIn->energy_level ?? '—' }}</p>
                                <p>Humeur : {{ $todayCheckIn->mood_level ?? '—' }}</p>
                                <p>Stress : {{ $todayCheckIn->stress_level ?? '—' }}</p>
                                <p>Eau : {{ $todayCheckIn->water_intake_liters ? number_format((float) $todayCheckIn->water_intake_liters, 1) . ' L' : '—' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-white/10 bg-slate-900/75 p-6 shadow-2xl shadow-black/20 backdrop-blur-xl">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">Historique</p>
                    <h2 class="mt-2 text-2xl font-semibold text-white">Entrées récentes du journal</h2>
                    <p class="mt-2 text-sm text-slate-300">
                        Les plus récentes d’abord, privées à votre compte, une journée par carte.
                    </p>
                </div>
            </div>

            <div class="mt-6 grid gap-4">
                @forelse ($checkIns as $checkIn)
                    <article class="rounded-3xl border border-white/10 bg-slate-950/60 p-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Date de l’entrée</p>
                                <h3 class="mt-2 text-xl font-semibold text-white">
                                    {{ $checkIn->recorded_on->translatedFormat('d M Y') }}
                                </h3>
                            </div>

                            <div class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">
                                {{ $checkIn->recorded_on->isToday() ? 'Aujourd’hui' : 'Entrée du journal' }}
                            </div>
                        </div>

                        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Poids</p>
                                <p class="mt-2 text-lg font-semibold text-white">
                                    {{ $checkIn->weight_kg ? number_format((float) $checkIn->weight_kg, 2) . ' kg' : '—' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Sommeil</p>
                                <p class="mt-2 text-lg font-semibold text-white">
                                    {{ $checkIn->sleep_hours ? number_format((float) $checkIn->sleep_hours, 2) . ' h' : '—' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Énergie / Humeur</p>
                                <p class="mt-2 text-lg font-semibold text-white">
                                    {{ ($checkIn->energy_level ?? '—') . ' / ' . ($checkIn->mood_level ?? '—') }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Stress / Eau</p>
                                <p class="mt-2 text-lg font-semibold text-white">
                                    {{ ($checkIn->stress_level ?? '—') . ' / ' . ($checkIn->water_intake_liters ? number_format((float) $checkIn->water_intake_liters, 1) . ' L' : '—') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-[0.35fr,1fr]">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Pas</p>
                                <p class="mt-2 text-lg font-semibold text-white">
                                    {{ $checkIn->steps ? number_format($checkIn->steps) : '—' }}
                                </p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Notes</p>
                                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-200">
                                    {{ $checkIn->notes ?: 'Aucune note pour cette journée.' }}
                                </p>
                            </div>
                        </div>
                    </article>
                @empty
                    <x-nhealth.empty-state
                        class="p-8 text-center"
                        message="Aucun check-in pour le moment. Ajoutez votre première entrée privée pour commencer votre historique quotidien."
                    />
                @endforelse
            </div>

            <div class="mt-6">
                {{ $checkIns->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
