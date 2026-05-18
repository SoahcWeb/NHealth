<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">NHealth goals</p>
                <h2 class="text-3xl font-semibold text-white">Goals</h2>
            </div>
            <a href="{{ route('nhealth.goals.create') }}" class="inline-flex items-center rounded-full border border-ankhor-400/40 bg-ankhor-500/10 px-4 py-2 text-sm font-medium text-ankhor-100 transition hover:border-ankhor-300 hover:bg-ankhor-500/20">
                Create goal
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="panel">
            <div class="overflow-hidden rounded-2xl border border-white/10">
                <table class="min-w-full divide-y divide-white/10 text-sm">
                    <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-400">
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Target</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 bg-slate-950/60 text-slate-200">
                        @forelse ($goals as $goal)
                            <tr>
                                <td class="px-4 py-4">
                                    <p class="font-medium text-white">{{ $goal->title }}</p>
                                    @if ($goal->description)
                                        <p class="mt-1 text-xs text-slate-400">{{ $goal->description }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4">{{ $goal->goal_type }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.25em] text-slate-300">
                                        {{ $goal->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    @if ($goal->target_value)
                                        {{ number_format((float) $goal->target_value, 2) }} {{ $goal->unit }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-4">{{ optional($goal->target_date)->format('d M Y') ?: '—' }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('nhealth.goals.edit', $goal) }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-100 transition hover:bg-white/10">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('nhealth.goals.destroy', $goal) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button>Delete</x-danger-button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-slate-400">
                                    No goals yet. Create your first target to start structuring the transformation.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $goals->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
