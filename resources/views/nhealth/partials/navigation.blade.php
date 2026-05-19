<div class="border-b border-white/5 bg-slate-950/70 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-3">
            <a
                href="{{ route('nhealth.dashboard') }}"
                @class([
                    'rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] transition',
                    'border-nethra-400/50 bg-nethra-500/10 text-nethra-100' => request()->routeIs('nhealth.dashboard'),
                    'border-white/10 bg-white/5 text-slate-200 hover:bg-white/10' => ! request()->routeIs('nhealth.dashboard'),
                ])
            >
                Dashboard
            </a>
            <a
                href="{{ route('nhealth.profile.edit') }}"
                @class([
                    'rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] transition',
                    'border-nethra-400/50 bg-nethra-500/10 text-nethra-100' => request()->routeIs('nhealth.profile.*'),
                    'border-white/10 bg-white/5 text-slate-200 hover:bg-white/10' => ! request()->routeIs('nhealth.profile.*'),
                ])
            >
                Profil sante
            </a>
            <a
                href="{{ route('nhealth.statistics.index') }}"
                @class([
                    'rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] transition',
                    'border-nethra-400/50 bg-nethra-500/10 text-nethra-100' => request()->routeIs('nhealth.statistics.*'),
                    'border-white/10 bg-white/5 text-slate-200 hover:bg-white/10' => ! request()->routeIs('nhealth.statistics.*'),
                ])
            >
                Statistiques
            </a>
            <a
                href="{{ route('nhealth.reminders.index') }}"
                @class([
                    'rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] transition',
                    'border-nethra-400/50 bg-nethra-500/10 text-nethra-100' => request()->routeIs('nhealth.reminders.*'),
                    'border-white/10 bg-white/5 text-slate-200 hover:bg-white/10' => ! request()->routeIs('nhealth.reminders.*'),
                ])
            >
                Rappels
            </a>
            <a
                href="{{ route('nhealth.goals.index') }}"
                @class([
                    'rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] transition',
                    'border-nethra-400/50 bg-nethra-500/10 text-nethra-100' => request()->routeIs('nhealth.goals.*'),
                    'border-white/10 bg-white/5 text-slate-200 hover:bg-white/10' => ! request()->routeIs('nhealth.goals.*'),
                ])
            >
                Objectifs
            </a>
            <a
                href="{{ route('nhealth.weight.index') }}"
                @class([
                    'rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] transition',
                    'border-nethra-400/50 bg-nethra-500/10 text-nethra-100' => request()->routeIs('nhealth.weight.*'),
                    'border-white/10 bg-white/5 text-slate-200 hover:bg-white/10' => ! request()->routeIs('nhealth.weight.*'),
                ])
            >
                Poids
            </a>
            <a
                href="{{ route('check-ins.index') }}"
                @class([
                    'rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] transition',
                    'border-nethra-400/50 bg-nethra-500/10 text-nethra-100' => request()->routeIs('check-ins.*'),
                    'border-white/10 bg-white/5 text-slate-200 hover:bg-white/10' => ! request()->routeIs('check-ins.*'),
                ])
            >
                Journal
            </a>
        </div>
    </div>
</div>
