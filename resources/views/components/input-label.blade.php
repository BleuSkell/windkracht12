@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-lg text-white']) }}>
    {{ $value ?? $slot }}
</label>
