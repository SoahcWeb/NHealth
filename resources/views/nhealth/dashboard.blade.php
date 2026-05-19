<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="Cockpit NHealth"
            title="Tableau de bord personnel"
            description="Votre vue privée du profil santé, des objectifs, du suivi du poids et du journal quotidien."
        >
            <x-slot name="action">
                <a href="{{ route('nhealth.export.summary') }}" class="nhealth-ghost-link">Exporter mon bilan</a>
                <a href="{{ route('check-ins.index') }}" class="nhealth-accent-link">Ouvrir le journal quotidien</a>
            </x-slot>
        </x-nhealth.page-header>
    </x-slot>

    <div class="nhealth-shell">
        <x-nhealth.flash :message="session('status')" title="Statut du module mis à jour" />

        @include('nhealth.dashboard.module-status')

        @include('nhealth.dashboard.core-stats')

        @include('nhealth.dashboard.reminders')

        @include('nhealth.dashboard.badges')

        <section class="grid gap-6 xl:grid-cols-[1.15fr,0.85fr]">
            @include('nhealth.dashboard.health-profile')

            @include('nhealth.dashboard.active-goal')
        </section>

        <section class="grid gap-6 xl:grid-cols-[0.9fr,1.1fr]">
            @include('nhealth.dashboard.recent-evolution')

            @include('nhealth.dashboard.daily-rhythm')
        </section>

        @include('nhealth.dashboard.shortcuts')
    </div>
</x-app-layout>
