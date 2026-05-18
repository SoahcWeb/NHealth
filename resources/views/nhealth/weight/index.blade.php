<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">NHealth weight</p>
                <h2 class="text-3xl font-semibold text-white">Weight history</h2>
            </div>
            <p class="max-w-2xl text-sm text-slate-400">
                Log your dedicated weight entries separately from daily check-ins.
            </p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[0.9fr,1.1fr]">
            <div class="panel">
                <h3 class="text-xl font-semibold text-white">Add weight entry</h3>

                <form method="POST" action="{{ route('nhealth.weight.store') }}" class="mt-6 grid gap-5">
                    @csrf

                    <div>
                        <x-input-label for="recorded_on" value="Date" />
                        <x-text-input id="recorded_on" name="recorded_on" type="date" class="mt-2 block w-full" :value="old('recorded_on', $defaultRecordedOn)" required />
                        <x-input-error :messages="$errors->get('recorded_on')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="weight_kg" value="Weight (kg)" />
                        <x-text-input id="weight_kg" name="weight_kg" type="number" step="0.01" min="0" class="mt-2 block w-full" :value="old('weight_kg')" required />
                        <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="source" value="Source" />
                        <select id="source" name="source" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-slate-100 focus:border-ankhor-400 focus:ring-ankhor-400">
                            @foreach (['manual' => 'Manual', 'scale' => 'Scale', 'import' => 'Import'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('source', 'manual') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('source')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-ankhor-400 focus:ring-ankhor-400">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Save entry</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="panel">
                <div class="overflow-hidden rounded-2xl border border-white/10">
                    <table class="min-w-full divide-y divide-white/10 text-sm">
                        <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-400">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Weight</th>
                                <th class="px-4 py-3">Source</th>
                                <th class="px-4 py-3">Notes</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 bg-slate-950/60 text-slate-200">
                            @forelse ($weightEntries as $weightEntry)
                                <tr class="align-top">
                                    <td class="px-4 py-4 font-medium text-white">{{ $weightEntry->recorded_on->format('d M Y') }}</td>
                                    <td class="px-4 py-4">{{ number_format((float) $weightEntry->weight_kg, 2) }} kg</td>
                                    <td class="px-4 py-4">{{ ucfirst($weightEntry->source) }}</td>
                                    <td class="px-4 py-4 text-slate-400">{{ $weightEntry->notes ?: '—' }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('nhealth.weight.edit', $weightEntry) }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-100 transition hover:bg-white/10">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('nhealth.weight.destroy', $weightEntry) }}">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button>Delete</x-danger-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-slate-400">
                                        No weight entries yet. Start logging to build your history.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{ $weightEntries->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
