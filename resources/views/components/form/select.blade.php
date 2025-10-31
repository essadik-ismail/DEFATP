@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Sélectionner...',
    'required' => false,
    'disabled' => false,
    'class' => '',
    'id' => null
])

@php
    $id = $id ?? $name;
    $hasError = $errors->has($name);
    $inputClass = 'form-select w-full px-4 py-3 border rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500';
    
    if ($hasError) {
        $inputClass .= ' border-red-500 focus:ring-red-500 focus:border-red-500';
    } else {
        $inputClass .= ' border-gray-300 hover:border-gray-400';
    }
    
    if ($class) {
        $inputClass .= ' ' . $class;
    }
    
    if ($disabled) {
        $inputClass .= ' bg-gray-100 cursor-not-allowed';
    }
@endphp

<div class="form-group mb-4">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select 
        name="{{ $name }}" 
        id="{{ $id }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => $inputClass]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        
        @foreach($options as $option)
            @if(is_array($option))
                <option 
                    value="{{ $option['value'] }}" 
                    {{ $selected == $option['value'] ? 'selected' : '' }}
                >
                    {{ $option['label'] }}
                </option>
            @else
                <option 
                    value="{{ $option }}" 
                    {{ $selected == $option ? 'selected' : '' }}
                >
                    {{ $option }}
                </option>
            @endif
        @endforeach
    </select>
    
    @error($name)
        <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            {{ $message }}
        </div>
    @enderror
    
    @if(!$hasError && !$errors->has($name))
        <div class="text-gray-500 text-xs mt-1">
            <i class="fas fa-info-circle"></i>
            Sélectionnez une option
        </div>
    @endif
</div>

<style>
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        appearance: none;
    }
    
    .form-select:focus {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    }
    
    .form-select:disabled {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23d1d5db' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    }
    
</style>
