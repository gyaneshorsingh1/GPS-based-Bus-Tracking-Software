@props(['value'])

<label {{ $attributes->merge(['class' => 'mb-1.5 block text-theme-sm font-semibold text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
