<x-nhealth.section
    eyebrow="Internal reminders"
    title="Stay regular"
    description="Simple private reminders to keep your journal, weigh-ins and active goal visible in your routine."
>
    <x-slot name="action">
        <a href="{{ route('nhealth.reminders.index') }}" class="nhealth-accent-link">Manage reminders</a>
    </x-slot>

    @if (count($activeReminders) > 0)
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($activeReminders as $reminder)
                <x-nhealth.widget-card
                    :label="$reminder['title']"
                    value="Active"
                    :hint="$reminder['description']"
                    variant="secondary"
                />
            @endforeach
        </div>
    @else
        <x-nhealth.empty-state
            title="No reminders active"
            message="Enable one or more internal reminders if you want the cockpit to keep these habits visible."
        >
            <a href="{{ route('nhealth.reminders.index') }}" class="nhealth-accent-link">Set reminders</a>
        </x-nhealth.empty-state>
    @endif
</x-nhealth.section>
