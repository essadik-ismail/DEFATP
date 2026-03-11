@props([
    'icon' => 'fas fa-inbox',
    'title' => 'Aucune donnée',
    'message' => null,
    'color' => 'gray'
])

@php
    $colorClasses = [
        'gray'   => ['icon' => 'bg-gray-100 text-gray-400',   'title' => 'text-gray-700', 'msg' => 'text-gray-500'],
        'blue'   => ['icon' => 'bg-blue-100 text-blue-500',   'title' => 'text-blue-800', 'msg' => 'text-blue-600'],
        'green'  => ['icon' => 'bg-emerald-100 text-emerald-500', 'title' => 'text-emerald-800', 'msg' => 'text-emerald-600'],
        'purple' => ['icon' => 'bg-purple-100 text-purple-500', 'title' => 'text-purple-800', 'msg' => 'text-purple-600'],
        'red'    => ['icon' => 'bg-red-100 text-red-500',     'title' => 'text-red-800',  'msg' => 'text-red-600'],
        'yellow' => ['icon' => 'bg-amber-100 text-amber-500', 'title' => 'text-amber-800','msg' => 'text-amber-600'],
    ];
    
    $c = $colorClasses[$color] ?? $colorClasses['gray'];
@endphp

<div {{ $attributes->merge(['class' => 'text-center py-12']) }}>
    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full {{ $c['icon'] }} mb-4">
        <i class="{{ $icon }} text-3xl"></i>
    </div>
    <h3 class="text-lg font-semibold {{ $c['title'] }} mb-2">{{ $title }}</h3>
    @if($message)
        <p class="text-sm {{ $c['msg'] }}">{{ $message }}</p>
    @endif
    
    @if($slot->isNotEmpty())
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
