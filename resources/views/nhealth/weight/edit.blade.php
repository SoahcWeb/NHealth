<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">NHealth weight</p>
                <h2 class="text-3xl font-semibold text-white">Edit weight entry</h2>
            </div>
            <a href="{{ route('nhealth.weight.index') }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-100 transition hover:bg-white/10">
                Back to list
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr,auto]">
            <div class="panel">
                <form method="POST" action="{{ route('nhealth.weight.update', $weightEntry) }}" class="grid gap-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="recorded_on" value="Date" />
                        <x-text-input id="recorded_on" name="recorded_on" type="date" class="mt-2 block w-full" :value="old('recorded_on', $weightEntry->recorded_on->toDateString())" required />
                        <x-input-error :messages="$errors->get('recorded_on')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="weight_kg" value="Weight (kg)" />
                        <x-text-input id="weight_kg" name="weight_kg" type="number" step="0.01" min="0" class="mt-2 block w-full" :value="old('weight_kg', $weightEntry->weight_kg)" required />
                        <x-input-error :messages="$errors->get('weight_kg')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="source" value="Source" />
                        <select id="source" name="source" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-slate-100 focus:border-ankhor-400 focus:ring-ankhor-400">
                            @foreach (['manual' => 'Manual', 'scale' => 'Scale', 'import' => 'Import'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('source', $weightEntry->source) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('source')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notes" />
                        <textarea id="notes" name="notes" rows="4" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-ankhor-400 focus:ring-ankhor-400">{{ old('notes', $weightEntry->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Save changes</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="panel h-fit">
                <form method="POST" action="{{ route('nhealth.weight.destroy', $weightEntry) }}">
                    @csrf
                    @method('DELETE')
                    <x-danger-button>Delete entry</x-danger-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
