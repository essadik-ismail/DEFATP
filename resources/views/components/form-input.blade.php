@props([
    'name',
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'required' => false,
    'value' => null,
    'min' => null,
    'max' => null,
    'step' => null,
    'rows' => 3,
    'borderColor' => 'gray', // gray, green, blue, purple, yellow
    'focusColor' => 'green'
])

@php
    $borderColors = [
        'gray' => 'border-gray-300 focus:border-gray-400 focus:ring-gray-100',
        'green' => 'border-green-200 focus:border-green-400 focus:ring-green-100',
        'blue' => 'border-blue-200 focus:border-blue-400 focus:ring-blue-100',
        'purple' => 'border-purple-200 focus:border-purple-400 focus:ring-purple-100',
        'yellow' => 'border-yellow-200 focus:border-yellow-400 focus:ring-yellow-100',
    ];
    
    $focusColors = [
        'green' => 'focus:border-green-400 focus:ring-green-100',
        'blue' => 'focus:border-blue-400 focus:ring-blue-100',
        'purple' => 'focus:border-purple-400 focus:ring-purple-100',
        'gray' => 'focus:border-gray-400 focus:ring-gray-100',
        'yellow' => 'focus:border-yellow-400 focus:ring-yellow-100',
    ];
    
    $borderClass = $borderColors[$borderColor] ?? $borderColors['gray'];
    $focusClass = $focusColors[$focusColor] ?? $focusColors['green'];
@endphp

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    @if($type === 'textarea')
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}" 
            rows="{{ $rows }}"
            class="w-full border {{ $borderClass }} {{ $focusClass }} rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >{{ $value ?? old($name) }}</textarea>
    @elseif($type === 'select')
        <select 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="w-full border {{ $borderClass }} {{ $focusClass }} rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >
            {{ $slot }}
        </select>
    @elseif($type === 'checkbox')
        <div class="flex items-center pt-8">
            <input 
                type="checkbox" 
                name="{{ $name }}" 
                id="{{ $name }}" 
                value="1"
                class="w-5 h-5 text-{{ $focusColor }}-500 border-2 border-gray-300 rounded focus:ring-{{ $focusColor }}-400"
                {{ old($name, $value) ? 'checked' : '' }}
                {{ $attributes }}
            >
            <label for="{{ $name }}" class="ml-3 text-sm font-semibold text-gray-700">
                {{ $label }}
            </label>
        </div>
    @else
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="w-full border {{ $borderClass }} {{ $focusClass }} rounded-xl px-4 py-3 focus:outline-none focus:ring-2 transition-all"
            placeholder="{{ $placeholder }}"
            value="{{ $value ?? old($name) }}"
            @if($min) min="{{ $min }}" @endif
            @if($max) max="{{ $max }}" @endif
            @if($step) step="{{ $step }}" @endif
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
        >
    @endif
    
    @error($name)
        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>
