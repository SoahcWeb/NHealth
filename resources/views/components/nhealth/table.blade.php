<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-3xl border border-white/10 bg-slate-950/60 shadow-xl shadow-black/10']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-white/10 text-sm">
            {{ $slot }}
        </table>
    </div>
</div>
