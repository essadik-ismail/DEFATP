@props([
    'icon'      => null,
    'color'     => 'blue',
    'title'     => '',
    'ariaLabel' => null,
    'href'      => null,
])

@php
    $colorMap = [
        'blue'   => ['text-blue-600',   'hover:bg-blue-50'],
        'green'  => ['text-green-700',  'hover:bg-green-50'],
        'red'    => ['text-red-600',    'hover:bg-red-50'],
        'yellow' => ['text-yellow-600', 'hover:bg-yellow-50'],
        'purple' => ['text-purple-600', 'hover:bg-purple-50'],
        'gray'   => ['text-green-700',  'hover:bg-green-50'],
    ];
    [$textClass, $hoverClass] = $colorMap[$color] ?? $colorMap['green'];
    $classes = "$textClass $hoverClass p-2 rounded-lg transition-colors";
    $label   = $ariaLabel ?? $title;
@endphp

@if($href)
    <a href="{{ $href }}"
       {{ $attributes->merge(['class' => $classes]) }}
       @if($title) title="{{ $title }}" @endif
       @if($label) aria-label="{{ $label }}" @endif>
        @if($icon)<i class="{{ $icon }}" aria-hidden="true"></i>@endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}
            @if($title) title="{{ $title }}" @endif
            @if($label) aria-label="{{ $label }}" @endif>
        @if($icon)<i class="{{ $icon }}" aria-hidden="true"></i>@endif
        {{ $slot }}
    </button>
@endif
