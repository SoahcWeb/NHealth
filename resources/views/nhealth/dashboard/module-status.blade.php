<section class="grid gap-4 xl:grid-cols-[0.75fr,1.25fr]">
    <x-nhealth.widget-card
        label="Module status"
        :value="$isNhealthModuleActive ? 'Active' : 'Inactive'"
        :hint="$isNhealthModuleActive
            ? 'Nethra Health is enabled for your account. Disabling it does not delete any health data.'
            : 'Nethra Health is currently disabled for your account. You can reactivate it anytime without losing data.'"
        :variant="$isNhealthModuleActive ? 'accent' : 'secondary'"
    >
        <form method="POST" action="{{ route('nhealth.module.toggle') }}">
            @csrf
            <button type="submit" class="{{ $isNhealthModuleActive ? 'nhealth-ghost-link' : 'nhealth-accent-link' }}">
                {{ $isNhealthModuleActive ? 'Deactivate NHealth' : 'Activate NHealth' }}
            </button>
        </form>
    </x-nhealth.widget-card>

    <x-nhealth.widget-card
        label="Module identity"
        :value="($favoriteModule?->module_name ?? 'Nethra Health') . ' · nhealth'"
        :hint="$favoriteModule?->last_toggled_at
            ? 'Last updated on ' . $favoriteModule->last_toggled_at->format('d M Y H:i')
            : 'No personal module preference saved yet. The first activation creates it.'"
    />
</section>
