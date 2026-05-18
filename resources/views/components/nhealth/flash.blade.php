@props([
    'type' => 'success',
    'message' => null,
    'title' => null,
])

@if ($message)
    <x-nhealth.alert :type="$type" :title="$title" {{ $attributes }}>
        {{ $message }}
    </x-nhealth.alert>
@endif
