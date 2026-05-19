<x-nhealth.section eyebrow="Raccourcis" title="Accès rapide" description="Accédez directement aux parcours clés de NHealth.">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-nhealth.shortcut-card
            href="{{ route('nhealth.profile.edit') }}"
            eyebrow="Profil"
            title="Profil santé"
            description="Maintenez vos informations de santé de base."
        />
        <x-nhealth.shortcut-card
            href="{{ route('nhealth.goals.index') }}"
            eyebrow="Objectifs"
            title="Objectifs de transformation"
            description="Créez, relisez et ajustez vos cibles actives."
        />
        <x-nhealth.shortcut-card
            href="{{ route('nhealth.weight.index') }}"
            eyebrow="Poids"
            title="Historique du poids"
            description="Suivez vos pesées dédiées dans le temps."
        />
        <x-nhealth.shortcut-card
            href="{{ route('nhealth.reminders.index') }}"
            eyebrow="Rappels"
            title="Rappels internes"
            description="Activez ou désactivez de simples repères privés de routine."
        />
        <x-nhealth.shortcut-card
            href="{{ route('check-ins.index') }}"
            eyebrow="Journal"
            title="Check-ins quotidiens"
            description="Enregistrez sommeil, humeur, stress et notes."
        />
    </div>
</x-nhealth.section>
