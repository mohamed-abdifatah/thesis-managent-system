@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label fw-semibold mb-1']) }}>
    {{ $value ?? $slot }}
</label>
