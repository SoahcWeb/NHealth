<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="NHealth reminders"
            title="Internal reminders"
            description="Simple private nudges to help you stay regular without emails or external notifications."
        />
    </x-slot>

    <div class="nhealth-shell-content">
        <x-nhealth.flash :message="session('status')" title="Reminders updated" />

        @if ($errors->any())
            <x-nhealth.alert type="error" title="Validation issue">
                The reminder settings could not be saved yet. Review the form and try again.
            </x-nhealth.alert>
        @endif

        <section class="grid gap-4 md:grid-cols-3">
            <x-nhealth.stat-card
                label="Active reminders"
                :value="$activeRemindersCount . ' / ' . count($reminders)"
                hint="Internal reminders currently enabled for your private cockpit."
                variant="accent"
            />
            <x-nhealth.stat-card
                label="Reminder style"
                value="Internal only"
                hint="No emails or external notifications are sent at this stage."
            />
            <x-nhealth.stat-card
                label="Data safety"
                value="Preference based"
                hint="Disabling a reminder only changes the toggle. Your health data stays untouched."
                variant="secondary"
            />
        </section>

        <x-nhealth.section
            eyebrow="Reminder settings"
            title="Manage your routine"
            description="Turn each reminder on or off depending on the habits you want to reinforce right now."
        >
            <form method="POST" action="{{ route('nhealth.reminders.update') }}" class="grid gap-5">
                @csrf
                @method('PATCH')

                <div class="grid gap-4 lg:grid-cols-3">
                    @foreach ($reminders as $reminder)
                        <label class="flex h-full cursor-pointer flex-col justify-between rounded-3xl border border-white/10 bg-slate-950/60 p-5 transition hover:border-ankhor-400/30 hover:bg-white/5">
                            <div>
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $reminder['is_enabled'] ? 'Enabled' : 'Disabled' }}</p>
                                        <h3 class="mt-3 text-xl font-semibold text-white">{{ $reminder['title'] }}</h3>
                                    </div>

                                    <span class="{{ $reminder['is_enabled'] ? 'border-nethra-300/30 bg-nethra-500/15 text-nethra-200' : 'border-white/10 bg-white/5 text-slate-400' }} rounded-full border px-3 py-1 text-xs uppercase tracking-[0.25em]">
                                        {{ $reminder['is_enabled'] ? 'Active' : 'Off' }}
                                    </span>
                                </div>

                                <p class="mt-4 text-sm leading-6 text-slate-300">{{ $reminder['description'] }}</p>
                            </div>

                            <div class="mt-5 flex items-center justify-between gap-4 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                <div>
                                    <p class="text-sm font-medium text-white">Activate this reminder</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.25em] text-slate-400">Private toggle</p>
                                </div>

                                <input
                                    type="checkbox"
                                    name="reminders[{{ $reminder['key'] }}][is_enabled]"
                                    value="1"
                                    @checked($reminder['is_enabled'])
                                    class="h-5 w-5 rounded border-white/20 bg-slate-950 text-nethra-400 focus:ring-ankhor-400"
                                >
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-400">
                        These reminders stay inside your private NHealth space and do not trigger email delivery.
                    </p>
                    <x-primary-button>Save reminders</x-primary-button>
                </div>
            </form>
        </x-nhealth.section>
    </div>
</x-app-layout>
