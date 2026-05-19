<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="NHealth cockpit"
            title="Personal dashboard"
            description="Your private overview of health profile, goals, weight tracking and daily journal."
        >
            <x-slot name="action">
                <a href="{{ route('nhealth.export.summary') }}" class="nhealth-ghost-link">Exporter mon bilan</a>
                <a href="{{ route('check-ins.index') }}" class="nhealth-accent-link">Open daily journal</a>
            </x-slot>
        </x-nhealth.page-header>
    </x-slot>

    <div class="nhealth-shell">
        <x-nhealth.flash :message="session('status')" title="Module status updated" />

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
