@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 rounded-xl bg-success-50 px-4 py-3 text-theme-sm font-medium text-success-700 ring-1 ring-success-100']) }}>
        <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg>
        {{ $status }}
    </div>
@endif
