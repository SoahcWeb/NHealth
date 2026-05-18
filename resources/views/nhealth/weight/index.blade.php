<x-app-layout>
    <x-slot name="header">
        <x-nhealth.page-header
            eyebrow="NHealth weight"
            title="Weight history"
            description="Log your dedicated weight entries separately from daily check-ins."
        />
    </x-slot>

    <div class="nhealth-shell-content">
        <x-nhealth.flash :message="session('status')" title="Weight entry updated" />

        @if ($errors->any())
            <x-nhealth.alert type="error" title="Validation issue">
                The weight entry could not be saved yet. Review the highlighted fields and try again.
            </x-nhealth.alert>
        @endif

        <div class="grid gap-6 lg:grid-cols-[0.9fr,1.1fr]">
            <x-nhealth.section eyebrow="Capture" title="Add weight entry">
                <form method="POST" action="{{ route('nhealth.weight.store') }}" class="grid gap-5">
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
                        <select id="source" name="source" class="nhealth-select">
                            @foreach (['manual' => 'Manual', 'scale' => 'Scale', 'import' => 'Import'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('source', 'manual') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('source')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4" class="nhealth-textarea">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Save entry</x-primary-button>
                    </div>
                </form>
            </x-nhealth.section>

            <x-nhealth.section eyebrow="History" title="Recent weigh-ins">
                <div class="mb-5 grid gap-4 sm:grid-cols-2">
                    <x-nhealth.widget-card
                        label="Logged entries"
                        :value="$weightEntries->total()"
                        hint="Dedicated weight measurements stored in your private history."
                    />
                    <x-nhealth.widget-card
                        label="Capture rhythm"
                        value="Consistent logging"
                        hint="Use the same scale and time of day when possible for cleaner trends."
                        variant="secondary"
                    />
                </div>

                <x-nhealth.table>
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
                                        <a href="{{ route('nhealth.weight.edit', $weightEntry) }}" class="nhealth-ghost-link">Edit</a>
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
                </x-nhealth.table>

                <div class="mt-5">
                    {{ $weightEntries->links() }}
                </div>
            </x-nhealth.section>
        </div>
    </div>
</x-app-layout>
