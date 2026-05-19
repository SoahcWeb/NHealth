<section class="grid gap-4 xl:grid-cols-[0.75fr,1.25fr]">
    <x-nhealth.widget-card
        label="Statut du module"
        :value="$isNhealthModuleActive ? 'Actif' : 'Inactif'"
        :hint="$isNhealthModuleActive
            ? 'Nethra Health est activé pour votre compte. Le désactiver ne supprime aucune donnée de santé.'
            : 'Nethra Health est actuellement désactivé pour votre compte. Vous pouvez le réactiver à tout moment sans perdre de données.'"
        :variant="$isNhealthModuleActive ? 'accent' : 'secondary'"
    >
        <form method="POST" action="{{ route('nhealth.module.toggle') }}">
            @csrf
            <button type="submit" class="{{ $isNhealthModuleActive ? 'nhealth-ghost-link' : 'nhealth-accent-link' }}">
                {{ $isNhealthModuleActive ? 'Désactiver NHealth' : 'Activer NHealth' }}
            </button>
        </form>
    </x-nhealth.widget-card>

    <x-nhealth.widget-card
        label="Identité du module"
        :value="($favoriteModule?->module_name ?? 'Nethra Health') . ' · nhealth'"
        :hint="$favoriteModule?->last_toggled_at
            ? 'Dernière mise à jour le ' . $favoriteModule->last_toggled_at->translatedFormat('d M Y H:i')
            : 'Aucune préférence personnelle de module n’est encore enregistrée. La première activation la crée.'"
    />
</section>
