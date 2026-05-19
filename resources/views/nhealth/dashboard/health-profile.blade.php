<x-nhealth.section eyebrow="Profil santé" title="Votre base">
    <x-slot name="action">
        <a href="{{ route('nhealth.profile.edit') }}" class="nhealth-accent-link">Modifier le profil</a>
    </x-slot>

    @if ($healthProfile)
        <div class="grid gap-4 md:grid-cols-2">
            <x-nhealth.widget-card label="Date de naissance" :value="optional($healthProfile->date_of_birth)?->translatedFormat('d M Y') ?: '—'" />
            <x-nhealth.widget-card label="Taille" :value="$healthProfile->height_cm ? number_format((float) $healthProfile->height_cm, 2) . ' cm' : '—'" />
            <x-nhealth.widget-card label="Niveau d’activité" :value="$healthProfile->activity_level ?: '—'" />
            <x-nhealth.widget-card label="Système d’unités" :value="ucfirst($healthProfile->unit_system)" />
        </div>

        <x-nhealth.widget-card class="mt-4" label="Notes de santé" :value="$healthProfile->health_notes ?: 'Aucune note de santé enregistrée pour le moment.'" />
    @else
        <x-nhealth.empty-state
            title="Profil encore vide"
            message="Ajoutez vos informations de base pour personnaliser le cockpit et donner plus de sens à vos statistiques."
        />
    @endif
</x-nhealth.section>
