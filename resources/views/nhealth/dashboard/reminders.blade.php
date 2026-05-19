<x-nhealth.section
    eyebrow="Rappels internes"
    title="Rester régulier"
    description="Rappels privés simples pour garder votre journal, vos pesées et votre objectif actif visibles dans votre routine."
>
    <x-slot name="action">
        <a href="{{ route('nhealth.reminders.index') }}" class="nhealth-accent-link">Gérer les rappels</a>
    </x-slot>

    @if (count($activeReminders) > 0)
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($activeReminders as $reminder)
                <x-nhealth.widget-card
                    :label="$reminder['title']"
                    value="Actif"
                    :hint="$reminder['description']"
                    variant="secondary"
                />
            @endforeach
        </div>
    @else
        <x-nhealth.empty-state
            title="Aucun rappel actif"
            message="Activez un ou plusieurs rappels internes si vous voulez que le cockpit garde ces habitudes visibles."
        >
            <a href="{{ route('nhealth.reminders.index') }}" class="nhealth-accent-link">Configurer les rappels</a>
        </x-nhealth.empty-state>
    @endif
</x-nhealth.section>
