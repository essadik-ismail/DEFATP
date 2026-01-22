@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'gradient' => null,
    'href' => null
])

@php
    $variantClasses = [
        'primary' => 'text-white shadow-lg hover:shadow-xl transform hover:scale-105',
        'secondary' => 'bg-blue-100 hover:bg-blue-200 text-blue-700',
        'success' => 'text-white shadow-lg hover:shadow-xl transform hover:scale-105',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-lg hover:shadow-xl',
        'outline' => 'border border-green-300 text-green-700 hover:bg-green-50',
    ];
    
    $sizeClasses = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-3',
        'lg' => 'px-8 py-4 text-lg',
    ];
    
    $gradients = [
        'primary' => 'linear-gradient(135deg, #059669, #047857)',
        'success' => 'linear-gradient(135deg, #059669, #047857)',
        'blue' => 'linear-gradient(135deg, #3b82f6, #2563eb)',
        'purple' => 'linear-gradient(135deg, #9333ea, #7e22ce)',
    ];
    
    $classes = ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
    $classes .= ' inline-flex items-center gap-2 rounded-xl font-semibold transition-all duration-300';
    
    $style = null;
    if ($gradient) {
        $style = 'background: ' . ($gradients[$gradient] ?? $gradient);
    } elseif ($variant === 'primary' || $variant === 'success') {
        $style = 'background: ' . $gradients['primary'];
    }
@endphp

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge(['class' => $classes]) }}
       @if($style) style="{{ $style }}" @endif>
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" 
            {{ $attributes->merge(['class' => $classes]) }}
            @if($style) style="{{ $style }}" @endif>
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $slot }}
    </button>
@endif
