@props(['disabled' => false, 'withIcon' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl border-gray-300 bg-white px-4 py-3 text-theme-sm text-gray-900 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-500 focus:ring-brand-500']) }}>
