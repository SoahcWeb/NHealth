<x-nhealth.section eyebrow="Shortcuts" title="Quick access" description="Jump directly into the core NHealth workflows.">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-nhealth.shortcut-card
            href="{{ route('nhealth.profile.edit') }}"
            eyebrow="Profile"
            title="Health profile"
            description="Maintain baseline health information."
        />
        <x-nhealth.shortcut-card
            href="{{ route('nhealth.goals.index') }}"
            eyebrow="Goals"
            title="Transformation goals"
            description="Create, review and adjust active targets."
        />
        <x-nhealth.shortcut-card
            href="{{ route('nhealth.weight.index') }}"
            eyebrow="Weight"
            title="Weight history"
            description="Track dedicated weigh-ins over time."
        />
        <x-nhealth.shortcut-card
            href="{{ route('nhealth.reminders.index') }}"
            eyebrow="Reminders"
            title="Internal reminders"
            description="Enable or disable simple private routine prompts."
        />
        <x-nhealth.shortcut-card
            href="{{ route('check-ins.index') }}"
            eyebrow="Journal"
            title="Daily check-ins"
            description="Capture sleep, mood, stress and notes."
        />
    </div>
</x-nhealth.section>
