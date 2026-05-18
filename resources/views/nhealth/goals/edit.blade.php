<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-nethra-300">NHealth goals</p>
                <h2 class="text-3xl font-semibold text-white">Edit goal</h2>
            </div>
            <a href="{{ route('nhealth.goals.index') }}" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-slate-100 transition hover:bg-white/10">
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
                <form method="POST" action="{{ route('nhealth.goals.update', $goal) }}" class="grid gap-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="title" value="Title" />
                        <x-text-input id="title" name="title" type="text" class="mt-2 block w-full" :value="old('title', $goal->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-sm text-slate-100 placeholder:text-slate-500 focus:border-ankhor-400 focus:ring-ankhor-400">{{ old('description', $goal->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <x-input-label for="goal_type" value="Goal type" />
                            <x-text-input id="goal_type" name="goal_type" type="text" class="mt-2 block w-full" :value="old('goal_type', $goal->goal_type)" required />
                            <x-input-error :messages="$errors->get('goal_type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-slate-100 focus:border-ankhor-400 focus:ring-ankhor-400">
                                @foreach (['draft', 'active', 'paused', 'completed', 'archived'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $goal->status) === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="direction" value="Direction" />
                            <select id="direction" name="direction" class="mt-2 block w-full rounded-2xl border border-white/10 bg-slate-950/70 px-4 py-3 text-slate-100 focus:border-ankhor-400 focus:ring-ankhor-400">
                                <option value="">None</option>
                                @foreach (['increase', 'decrease', 'maintain'] as $direction)
                                    <option value="{{ $direction }}" @selected(old('direction', $goal->direction) === $direction)>{{ ucfirst($direction) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('direction')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="unit" value="Unit" />
                            <x-text-input id="unit" name="unit" type="text" class="mt-2 block w-full" :value="old('unit', $goal->unit)" />
                            <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="start_value" value="Start value" />
                            <x-text-input id="start_value" name="start_value" type="number" step="0.01" min="0" class="mt-2 block w-full" :value="old('start_value', $goal->start_value)" />
                            <x-input-error :messages="$errors->get('start_value')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="target_value" value="Target value" />
                            <x-text-input id="target_value" name="target_value" type="number" step="0.01" min="0" class="mt-2 block w-full" :value="old('target_value', $goal->target_value)" />
                            <x-input-error :messages="$errors->get('target_value')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="starts_on" value="Starts on" />
                            <x-text-input id="starts_on" name="starts_on" type="date" class="mt-2 block w-full" :value="old('starts_on', optional($goal->starts_on)->toDateString())" />
                            <x-input-error :messages="$errors->get('starts_on')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="target_date" value="Target date" />
                            <x-text-input id="target_date" name="target_date" type="date" class="mt-2 block w-full" :value="old('target_date', optional($goal->target_date)->toDateString())" />
                            <x-input-error :messages="$errors->get('target_date')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>Save changes</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="panel h-fit">
                <form method="POST" action="{{ route('nhealth.goals.destroy', $goal) }}">
                    @csrf
                    @method('DELETE')
                    <x-danger-button>Delete goal</x-danger-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
