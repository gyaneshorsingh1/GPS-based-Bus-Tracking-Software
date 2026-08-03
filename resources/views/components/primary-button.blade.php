<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-full bg-brand-500 px-6 py-3 text-theme-sm font-semibold text-white shadow-theme-md transition hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 active:bg-brand-700']) }}>
    {{ $slot }}
</button>
