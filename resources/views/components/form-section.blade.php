@props([
    'title',
    'icon' => null,
    'color' => 'green', // green, blue, purple, orange, red, yellow
    'columns' => 2
])

@php
    $colorClasses = [
        'green' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-200',
            'text' => 'text-green-500',
            'title' => 'text-gray-800'
        ],
        'blue' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'text' => 'text-blue-500',
            'title' => 'text-gray-800'
        ],
        'purple' => [
            'bg' => 'bg-purple-50',
            'border' => 'border-purple-200',
            'text' => 'text-purple-500',
            'title' => 'text-gray-800'
        ],
        'orange' => [
            'bg' => 'bg-orange-50',
            'border' => 'border-orange-200',
            'text' => 'text-orange-500',
            'title' => 'text-gray-800'
        ],
        'red' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-200',
            'text' => 'text-red-500',
            'title' => 'text-gray-800'
        ],
        'yellow' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-200',
            'text' => 'text-yellow-500',
            'title' => 'text-gray-800'
        ],
    ];
    
    $colors = $colorClasses[$color] ?? $colorClasses['green'];
    $gridCols = 'grid-cols-1 md:grid-cols-' . $columns;
@endphp

<div class="{{ $colors['bg'] }} border-2 {{ $colors['border'] }} rounded-xl p-6">
    @if($title)
        <h3 class="text-lg font-bold {{ $colors['title'] }} mb-4 flex items-center gap-2">
            @if($icon)
                <i class="{{ $icon }} {{ $colors['text'] }}"></i>
            @endif
            {{ $title }}
        </h3>
    @endif
    
    <div class="grid {{ $gridCols }} gap-4">
        {{ $slot }}
    </div>
</div>
