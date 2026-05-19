<x-nhealth.section eyebrow="Health profile" title="Your baseline">
    <x-slot name="action">
        <a href="{{ route('nhealth.profile.edit') }}" class="nhealth-accent-link">Edit profile</a>
    </x-slot>

    @if ($healthProfile)
        <div class="grid gap-4 md:grid-cols-2">
            <x-nhealth.widget-card label="Date of birth" :value="optional($healthProfile->date_of_birth)->format('d M Y') ?: '—'" />
            <x-nhealth.widget-card label="Height" :value="$healthProfile->height_cm ? number_format((float) $healthProfile->height_cm, 2) . ' cm' : '—'" />
            <x-nhealth.widget-card label="Activity level" :value="$healthProfile->activity_level ?: '—'" />
            <x-nhealth.widget-card label="Unit system" :value="ucfirst($healthProfile->unit_system)" />
        </div>

        <x-nhealth.widget-card class="mt-4" label="Health notes" :value="$healthProfile->health_notes ?: 'No health notes saved yet.'" />
    @else
        <x-nhealth.empty-state
            title="Profile still empty"
            message="Add your baseline information to personalize the cockpit and improve the meaning of your stats."
        />
    @endif
</x-nhealth.section>
