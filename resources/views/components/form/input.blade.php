@props([
    'type' => 'text',
    'name',
    'label' => null,
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'icon' => null,
    'help' => null,
    'helpText' => null,
    'showHelpIcon' => true,
    'disabled' => false,
    'readonly' => false,
    'min' => null,
    'max' => null,
    'step' => null,
    'rows' => 3,
    'options' => [],
    'selected' => null,
    'multiple' => false,
    'accept' => null,
    'autocomplete' => null,
    'pattern' => null,
    'maxlength' => null,
    'showPasswordToggle' => false,
    'validation' => null,
    'loading' => false
])

<div class="form-group">
    @if($label)
        <label for="{{ $name }}" class="form-label flex items-center gap-2">
            <span>{{ $label }}</span>
            @if($required)
                <span class="text-red-500">*</span>
            @endif
            @if($showHelpIcon && ($helpText || $help))
                <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" 
                   title="{{ $helpText ?? $help }}"
                   data-tooltip="{{ $helpText ?? $help }}"></i>
            @endif
        </label>
    @endif

    <div class="input-wrapper {{ $icon ? 'has-icon' : '' }} {{ $showPasswordToggle ? 'has-password-toggle' : '' }}">
        @if($icon)
            <div class="input-icon">
                <i class="{{ $icon }}"></i>
            </div>
        @endif
        
        @if($loading)
            <div class="input-loading">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
        @endif

        @if($type === 'textarea')
            <textarea
                name="{{ $name }}"
                id="{{ $name }}"
                rows="{{ $rows }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                {{ $attributes->merge(['class' => 'form-control ' . ($icon ? 'pl-10' : '')]) }}
            >{{ $value ?? old($name) }}</textarea>
        @elseif($type === 'select')
            <select
                name="{{ $name }}"
                id="{{ $name }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $multiple ? 'multiple' : '' }}
                {{ $attributes->merge(['class' => 'form-select ' . ($icon ? 'pl-10' : '')]) }}
            >
                @if(!$multiple)
                    <option value="">{{ $placeholder ?? 'Sélectionner...' }}</option>
                @endif
                @foreach($options as $option)
                    @if(is_array($option))
                        <option value="{{ $option['value'] }}" {{ $selected == $option['value'] ? 'selected' : '' }}>
                            {{ $option['label'] }}
                        </option>
                    @else
                        <option value="{{ $option }}" {{ $selected == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endif
                @endforeach
            </select>
        @elseif($type === 'checkbox')
            <div class="checkbox-wrapper">
                <input
                    type="checkbox"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    value="1"
                    {{ $value ? 'checked' : '' }}
                    {{ $disabled ? 'disabled' : '' }}
                    {{ $attributes->merge(['class' => 'form-checkbox']) }}
                >
                <label for="{{ $name }}" class="checkbox-label">{{ $placeholder }}</label>
            </div>
        @else
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $name }}"
                value="{{ $value ?? old($name) }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                @if($min) min="{{ $min }}" @endif
                @if($max) max="{{ $max }}" @endif
                @if($step) step="{{ $step }}" @endif
                @if($accept) accept="{{ $accept }}" @endif
                @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
                @if($pattern) pattern="{{ $pattern }}" @endif
                @if($maxlength) maxlength="{{ $maxlength }}" @endif
                {{ $attributes->merge(['class' => 'form-control ' . ($icon ? 'pl-10' : '') . ($showPasswordToggle ? ' pr-10' : '')]) }}
            >
        @endif
        
    </div>

    @if($help)
        <p class="form-help">{{ $help }}</p>
    @endif

    @error($name)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
