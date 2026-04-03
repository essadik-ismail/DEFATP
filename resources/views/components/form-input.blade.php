@props([
    'name',
    'label'       => null,
    'type'        => 'text',
    'placeholder' => '',
    'required'    => false,
    'value'       => null,
    'min'         => null,
    'max'         => null,
    'step'        => null,
    'rows'        => 3,
    'helper'      => null,   // hint text shown below the field
    'disabled'    => false,
    'readonly'    => false,
])

@php
    $hasError  = $errors->has($name);

    $inputBase = 'w-full rounded-xl px-4 py-3 text-sm border transition-all focus:outline-none focus:ring-2';

    if ($hasError) {
        $stateClass = 'border-red-400 bg-red-50 text-red-900 focus:border-red-500 focus:ring-red-100 placeholder:text-red-300';
    } elseif ($disabled || $readonly) {
        $stateClass = 'border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed';
    } else {
        $stateClass = 'border-gray-300 bg-white text-gray-900 focus:border-emerald-400 focus:ring-emerald-100 placeholder:text-gray-400';
    }
@endphp

<div>
    {{-- Label (skipped for checkbox since it's rendered inline) --}}
    @if($label && $type !== 'checkbox')
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if($required)<span class="text-red-500 ml-0.5">*</span>@endif
        </label>
    @endif

    @if($type === 'textarea')
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            class="{{ $inputBase }} {{ $stateClass }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes }}
        >{{ old($name, $value) }}</textarea>

    @elseif($type === 'select')
        <select
            name="{{ $name }}"
            id="{{ $name }}"
            class="{{ $inputBase }} {{ $stateClass }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes }}
        >{{ $slot }}</select>

    @elseif($type === 'checkbox')
        <div class="flex items-center gap-3">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $name }}"
                value="1"
                class="w-4 h-4 rounded text-emerald-600 border-gray-300 focus:ring-emerald-400 transition
                       {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                {{ old($name, $value) ? 'checked' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $attributes }}
            >
            @if($label)
                <label for="{{ $name }}" class="text-sm font-medium text-gray-700 {{ $disabled ? 'opacity-50' : '' }}">
                    {{ $label }}
                    @if($required)<span class="text-red-500 ml-0.5">*</span>@endif
                </label>
            @endif
        </div>

    @else
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            class="{{ $inputBase }} {{ $stateClass }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            @if($min  !== null) min="{{ $min }}"   @endif
            @if($max  !== null) max="{{ $max }}"   @endif
            @if($step !== null) step="{{ $step }}" @endif
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes }}
        >
    @endif

    {{-- Helper text (hidden when there is an error to avoid clutter) --}}
    @if($helper && !$hasError)
        <p class="mt-1.5 text-xs text-gray-500">{{ $helper }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
            <i class="fas fa-exclamation-circle flex-shrink-0"></i>
            {{ $message }}
        </p>
    @enderror
</div>
