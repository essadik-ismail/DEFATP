@props([
    'icon' => null,
    'color' => 'blue',
    'title' => '',
    'href' => null
])

@php
    $colorClasses = [
        'blue' => 'text-blue-600 hover:bg-blue-50',
        'green' => 'text-green-600 hover:bg-green-50',
        'red' => 'text-red-600 hover:bg-red-50',
        'yellow' => 'text-yellow-600 hover:bg-yellow-50',
        'purple' => 'text-purple-600 hover:bg-purple-50',
        'gray' => 'text-green-600 hover:bg-green-50',
    ];
    
    $classes = ($colorClasses[$color] ?? $colorClasses['green']) . ' p-2 rounded-lg transition-colors';
@endphp

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge(['class' => $classes]) }}
       @if($title) title="{{ $title }}" @endif>
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}
            @if($title) title="{{ $title }}" @endif>
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $slot }}
    </button>
@endif
