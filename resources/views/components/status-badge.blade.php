@props([
    'type' => 'neutral',  // success | warning | danger | info | neutral | green | earth
    'icon' => null,
    'dot'  => true,
    'size' => 'md',       // sm | md | lg
])

@php
    $classes = match($type) {
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger'  => 'badge-danger',
        'info'    => 'badge-info',
        'green'   => 'badge-green',
        'earth'   => 'badge-earth',
        default   => 'badge-neutral',
    };

    $dotColor = match($type) {
        'success' => '#16A34A',
        'warning' => '#D97706',
        'danger'  => '#DC2626',
        'info'    => '#2563EB',
        'green'   => '#00bc7d',
        'earth'   => '#B7791F',
        default   => '#7A9B8A',
    };

    $sizeStyle = match($size) {
        'sm' => 'padding:0.125rem 0.5rem;font-size:0.625rem;',
        'lg' => 'padding:0.3125rem 0.875rem;font-size:0.75rem;',
        default => '',
    };
@endphp

<span {{ $attributes->merge(['class' => $classes]) }} style="{{ $sizeStyle }}">
    @if($icon)
        <i class="{{ $icon }}" style="font-size:0.6rem;"></i>
    @elseif($dot)
        <span class="badge-dot" style="background:{{ $dotColor }};"></span>
    @endif
    {{ $slot }}
</span>
