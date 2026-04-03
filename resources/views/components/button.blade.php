@props([
    'type'      => 'button',
    'variant'   => 'primary',  // primary | secondary | success | danger | outline | ghost | white
    'size'      => 'md',       // xs | sm | md | lg
    'icon'      => null,
    'iconRight' => null,
    'href'      => null,
    'disabled'  => false,
])

@php
    $sizeClasses = [
        'xs' => 'px-3 py-1.5 text-xs gap-1.5',
        'sm' => 'px-4 py-2 text-sm gap-2',
        'md' => 'px-5 py-2.5 text-sm gap-2',
        'lg' => 'px-6 py-3 text-base gap-2',
    ];

    // Map variant names to the global btn-* classes already defined in app.css
    $variantMap = [
        'primary'   => 'btn-primary',
        'secondary' => 'btn-secondary',
        'success'   => 'btn-success',
        'danger'    => 'btn-danger',
        'outline'   => 'btn-outline',
        'ghost'     => 'btn-white',
        'white'     => 'btn-white',
    ];

    $sizeClass    = $sizeClasses[$size]    ?? $sizeClasses['md'];
    $variantClass = $variantMap[$variant]  ?? $variantMap['primary'];
    $base = 'inline-flex items-center justify-center rounded-xl font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed ' . $sizeClass . ' ' . $variantClass;
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $base]) }}>
        @if($icon)<i class="{{ $icon }}"></i>@endif
        {{ $slot }}
        @if($iconRight)<i class="{{ $iconRight }}"></i>@endif
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => $base]) }}
    >
        @if($icon)<i class="{{ $icon }}"></i>@endif
        {{ $slot }}
        @if($iconRight)<i class="{{ $iconRight }}"></i>@endif
    </button>
@endif
