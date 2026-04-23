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
    'rows'        => 4,
    'helper'      => null,
    'disabled'    => false,
    'readonly'    => false,
    'icon'        => null,
])

@php
    $hasError = $errors->has($name);

    if ($hasError) {
        $inputClass = 'form-input is-invalid';
    } elseif ($disabled || $readonly) {
        $inputClass = 'form-input';
    } else {
        $inputClass = 'form-input';
    }
@endphp

<div>
    @if($label && $type !== 'checkbox')
        <label for="{{ $name }}" class="form-label {{ $required ? 'form-label-required' : '' }}">
            {{ $label }}
        </label>
    @endif

    @if($type === 'textarea')
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            rows="{{ $rows }}"
            class="{{ $inputClass }}"
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
            class="form-select {{ $hasError ? 'is-invalid' : '' }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes }}
        >{{ $slot }}</select>

    @elseif($type === 'checkbox')
        <div style="display:flex;align-items:center;gap:0.625rem;">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $name }}"
                value="1"
                class="form-checkbox"
                {{ old($name, $value) ? 'checked' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $attributes }}
            >
            @if($label)
                <label for="{{ $name }}"
                       class="form-label"
                       style="margin:0;font-weight:500;{{ $disabled ? 'opacity:0.55;' : '' }}">
                    {{ $label }}
                    @if($required)<span style="color:#DC2626;"> *</span>@endif
                </label>
            @endif
        </div>

    @else
        @if($icon)
            <div class="field-wrap">
                <i class="{{ $icon }} field-icon-left" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);color:#A8C4B4;font-size:0.8125rem;pointer-events:none;"></i>
                <input
                    type="{{ $type }}"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    class="{{ $inputClass }}"
                    style="padding-left:2.25rem;"
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
            </div>
        @else
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $name }}"
                class="{{ $inputClass }}"
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
    @endif

    @if($helper && !$hasError)
        <p class="form-hint">{{ $helper }}</p>
    @endif

    @error($name)
        <p class="form-error">
            <i class="fas fa-exclamation-circle" style="font-size:0.6875rem;flex-shrink:0;"></i>
            {{ $message }}
        </p>
    @enderror
</div>
