@props([
    'type' => 'default',
    'icon' => null,
    'size' => 'md'
])

@php
    $typeClasses = [
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-blue-100 text-blue-800',
        'pending' => 'bg-blue-100 text-blue-800',
        'default' => 'bg-green-100 text-green-800',
    ];
    
    $sizeClasses = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-3 py-1.5 text-sm',
        'lg' => 'px-4 py-2 text-base',
    ];
    
    $classes = ($typeClasses[$type] ?? $typeClasses['default']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full font-medium {$classes}"]) }}>
    @if($icon)
        <i class="{{ $icon }} mr-1"></i>
    @endif
    {{ $slot }}
</span>
