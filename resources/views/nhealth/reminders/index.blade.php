<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="Rappels NHealth"
            title="Rappels internes"
            description="Petits rappels privés pour vous aider à rester régulier sans e-mails ni notifications externes."
        />
    </x-slot>

    <div class="nhealth-shell-content">
        <x-nhealth.flash :message="session('status')" title="Rappels mis à jour" />

        @if ($errors->any())
            <x-nhealth.alert type="error" title="Problème de validation">
                Les réglages des rappels n’ont pas encore pu être enregistrés. Vérifiez le formulaire puis réessayez.
            </x-nhealth.alert>
        @endif

        <section class="grid gap-4 md:grid-cols-3">
            <x-nhealth.stat-card
                label="Rappels actifs"
                :value="$activeRemindersCount . ' / ' . count($reminders)"
                hint="Rappels internes actuellement activés pour votre cockpit privé."
                variant="accent"
            />
            <x-nhealth.stat-card
                label="Type de rappel"
                value="Interne uniquement"
                hint="Aucun e-mail ou notification externe n’est envoyé à cette étape."
            />
            <x-nhealth.stat-card
                label="Sécurité des données"
                value="Basé sur les préférences"
                hint="Désactiver un rappel modifie seulement le bouton. Vos données de santé restent intactes."
                variant="secondary"
            />
        </section>

        <x-nhealth.section
            eyebrow="Réglages des rappels"
            title="Gérer votre routine"
            description="Activez ou désactivez chaque rappel selon les habitudes que vous voulez renforcer actuellement."
        >
            <form method="POST" action="{{ route('nhealth.reminders.update') }}" class="grid gap-5">
                @csrf
                @method('PATCH')

                <div class="grid gap-4 lg:grid-cols-3">
                    @foreach ($reminders as $reminder)
                        <label class="flex h-full cursor-pointer flex-col justify-between rounded-3xl border border-white/10 bg-slate-950/60 p-5 transition hover:border-ankhor-400/30 hover:bg-white/5">
                            <div>
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $reminder['is_enabled'] ? 'Activé' : 'Désactivé' }}</p>
                                        <h3 class="mt-3 text-xl font-semibold text-white">{{ $reminder['title'] }}</h3>
                                    </div>

                                    <span class="{{ $reminder['is_enabled'] ? 'border-nethra-300/30 bg-nethra-500/15 text-nethra-200' : 'border-white/10 bg-white/5 text-slate-400' }} rounded-full border px-3 py-1 text-xs uppercase tracking-[0.25em]">
                                        {{ $reminder['is_enabled'] ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>

                                <p class="mt-4 text-sm leading-6 text-slate-300">{{ $reminder['description'] }}</p>
                            </div>

                            <div class="mt-5 flex items-center justify-between gap-4 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                    <div>
                                    <p class="text-sm font-medium text-white">Activer ce rappel</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.25em] text-slate-400">Bascule privée</p>
                                </div>

                                <input
                                    type="checkbox"
                                    name="reminders[{{ $reminder['key'] }}][is_enabled]"
                                    value="1"
                                    @checked($reminder['is_enabled'])
                                    class="h-5 w-5 rounded border-white/20 bg-slate-950 text-nethra-400 focus:ring-ankhor-400"
                                >
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-400">
                        Ces rappels restent dans votre espace NHealth privé et ne déclenchent aucun e-mail.
                    </p>
                    <x-primary-button>Enregistrer les rappels</x-primary-button>
                </div>
            </form>
        </x-nhealth.section>
    </div>
</x-app-layout>
