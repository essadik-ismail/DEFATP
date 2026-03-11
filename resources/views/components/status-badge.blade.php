@props([
    'type' => 'default',
    'icon' => null,
    'size' => 'md'
])

@php
    $typeClasses = [
        'success' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20',
        'warning' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20',
        'danger'  => 'bg-red-50 text-red-700 ring-1 ring-red-600/20',
        'info'    => 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20',
        'pending' => 'bg-gray-50 text-gray-600 ring-1 ring-gray-500/20',
        'default' => 'bg-gray-50 text-gray-700 ring-1 ring-gray-500/20',
    ];
    
    $dotColors = [
        'success' => 'bg-emerald-500',
        'warning' => 'bg-amber-500',
        'danger'  => 'bg-red-500',
        'info'    => 'bg-blue-500',
        'pending' => 'bg-gray-400',
        'default' => 'bg-gray-400',
    ];
    
    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
    ];
    
    $classes = ($typeClasses[$type] ?? $typeClasses['default']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
    $dot = $dotColors[$type] ?? $dotColors['default'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full font-medium {$classes}"]) }}>
    @if($icon)
        <i class="{{ $icon }} text-[0.65rem]"></i>
    @else
        <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
    @endif
    {{ $slot }}
</span>
