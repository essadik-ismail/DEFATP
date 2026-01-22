@props([
    'icon' => 'fas fa-inbox',
    'title' => 'Aucune donnée',
    'message' => null,
    'color' => 'gray'
])

@php
    $colorClasses = [
        'gray' => 'bg-green-100 text-green-500',
        'blue' => 'bg-blue-100 text-blue-500',
        'green' => 'bg-green-100 text-green-500',
        'purple' => 'bg-purple-100 text-purple-500',
        'red' => 'bg-red-100 text-red-500',
        'yellow' => 'bg-yellow-100 text-yellow-500',
    ];
    
    $iconColor = $colorClasses[$color] ?? $colorClasses['green'];
@endphp

<div {{ $attributes->merge(['class' => 'text-center py-12']) }}>
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full {{ $iconColor }} mb-4">
        <i class="{{ $icon }} text-3xl"></i>
    </div>
    <h3 class="text-lg font-semibold text-green-800 mb-2">{{ $title }}</h3>
    @if($message)
        <p class="text-sm text-blue-600">{{ $message }}</p>
    @endif
    
    @if($slot->isNotEmpty())
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
