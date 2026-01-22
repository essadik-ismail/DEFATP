@props([
    'headers' => [],
    'color' => 'green' // green, blue, purple
])

@php
    $colorClasses = [
        'green' => [
            'header' => 'bg-green-50 border-green-100',
            'icon' => 'text-green-500',
            'row' => 'hover:bg-green-50',
            'border' => 'border-green-100'
        ],
        'blue' => [
            'header' => 'bg-blue-50 border-blue-100',
            'icon' => 'text-blue-500',
            'row' => 'hover:bg-blue-50',
            'border' => 'border-blue-100'
        ],
        'purple' => [
            'header' => 'bg-purple-50 border-purple-100',
            'icon' => 'text-purple-500',
            'row' => 'hover:bg-purple-50',
            'border' => 'border-purple-100'
        ],
    ];
    
    $colors = $colorClasses[$color];
@endphp

<div class="overflow-x-auto">
    <table class="w-full bg-white rounded-lg overflow-hidden">
        <thead>
            <tr class="{{ $colors['header'] }} border-b-2 {{ $colors['border'] }}">
                @foreach($headers as $header)
                    <th class="px-4 py-3 text-left text-sm font-bold text-gray-800">
                        @if(isset($header['icon']))
                            <i class="{{ $header['icon'] }} {{ $colors['icon'] }} mr-2"></i>
                        @endif
                        {{ $header['label'] }}
                        @if(isset($header['required']) && $header['required'])
                            <span class="text-red-500">*</span>
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
