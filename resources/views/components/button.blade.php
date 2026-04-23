@props([
    'type'      => 'button',
    'variant'   => 'primary',   // primary | secondary | outline | danger | warning | ghost | white
    'size'      => 'md',        // xs | sm | md | lg | xl
    'icon'      => null,
    'iconRight' => null,
    'href'      => null,
    'disabled'  => false,
])

@php
    $variantClass = match($variant) {
        'secondary' => 'btn-secondary',
        'outline'   => 'btn-outline',
        'danger'    => 'btn-danger',
        'warning'   => 'btn-warning',
        'ghost'     => 'btn-ghost',
        'white'     => 'btn-white',
        default     => 'btn-primary',
    };

    $sizeClass = match($size) {
        'xs' => 'btn-xs',
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        'xl' => 'btn-xl',
        default => '',
    };

    $classes = trim('btn ' . $variantClass . ' ' . $sizeClass);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)<i class="{{ $icon }}"></i>@endif
        {{ $slot }}
        @if($iconRight)<i class="{{ $iconRight }}"></i>@endif
    </a>
@else
    <button type="{{ $type }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)<i class="{{ $icon }}"></i>@endif
        {{ $slot }}
        @if($iconRight)<i class="{{ $iconRight }}"></i>@endif
    </button>
@endif
