@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success py-2 mb-0']) }}>
        {{ $status }}
    </div>
@endif
