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

@push('styles')
<style>
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .input-wrapper {
        position: relative;
        width: 100%;
        display: block;
    }

    .input-wrapper.has-icon {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        z-index: 10;
        pointer-events: none;
        font-size: 1rem;
        width: 1rem;
        height: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-control, .form-select, .form-textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        background-color: white;
        transition: all 0.2s ease;
        line-height: 1.5;
    }

    .form-control:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .form-control:disabled, .form-select:disabled, .form-textarea:disabled {
        background-color: #f9fafb;
        color: #6b7280;
        cursor: not-allowed;
    }

    .form-control.pl-10, .form-select.pl-10, .form-textarea.pl-10 {
        padding-left: 2.5rem;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-checkbox {
        width: 1rem;
        height: 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.25rem;
        background-color: white;
        cursor: pointer;
    }

    .form-checkbox:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .checkbox-label {
        font-size: 0.875rem;
        color: #374151;
        cursor: pointer;
    }

    .form-help {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0;
    }

    .form-error {
        font-size: 0.75rem;
        color: #dc2626;
        margin: 0;
    }

    .form-control.error, .form-select.error, .form-textarea.error {
        border-color: #dc2626;
    }

    .form-control.error:focus, .form-select.error:focus, .form-textarea.error:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }


    .input-loading {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #059669;
        z-index: 10;
    }

    .form-control.pr-10 {
        padding-right: 2.5rem;
    }

    /* Validation states */
    .form-control.is-valid {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .valid-feedback {
        font-size: 0.75rem;
        color: #10b981;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .invalid-feedback {
        font-size: 0.75rem;
        color: #ef4444;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Enhanced focus states */
    .form-control:focus, .form-select:focus, .form-textarea:focus {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        transform: translateY(-1px);
    }

    /* Loading state */
    .form-group.loading .form-control {
        opacity: 0.7;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .form-control, .form-select, .form-textarea {
            padding: 0.625rem 0.875rem;
            font-size: 1rem;
        }
        
        .form-control.pl-10, .form-select.pl-10, .form-textarea.pl-10 {
            padding-left: 2.25rem;
        }
        
        .input-icon {
            left: 0.625rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>

// Enhanced form validation
function validateFormField(field) {
    const value = field.value.trim();
    const fieldName = field.name;
    const fieldType = field.type;
    
    // Remove existing validation classes
    field.classList.remove('is-valid', 'is-invalid');
    
    // Required field validation
    if (field.hasAttribute('required') && value === '') {
        field.classList.add('is-invalid');
        return false;
    }
    
    // Type-specific validations
    if (fieldType === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    if (fieldType === 'number' && value) {
        const num = parseFloat(value);
        if (isNaN(num)) {
            field.classList.add('is-invalid');
            return false;
        }
        
        if (field.hasAttribute('min') && num < parseFloat(field.getAttribute('min'))) {
            field.classList.add('is-invalid');
            return false;
        }
        
        if (field.hasAttribute('max') && num > parseFloat(field.getAttribute('max'))) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    if (fieldType === 'date' && value) {
        const date = new Date(value);
        if (isNaN(date.getTime())) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    // Pattern validation
    if (field.hasAttribute('pattern') && value) {
        const pattern = new RegExp(field.getAttribute('pattern'));
        if (!pattern.test(value)) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    // If we get here and there's a value, it's valid
    if (value !== '') {
        field.classList.add('is-valid');
    }
    
    return true;
}

// Auto-format number inputs
function formatNumberInput(input) {
    if (input.type === 'number' && input.value) {
        const value = parseFloat(input.value);
        if (!isNaN(value)) {
            // Store original value for form submission
            input.setAttribute('data-original-value', input.value);
        }
    }
}

// Initialize enhanced form inputs
document.addEventListener('DOMContentLoaded', function() {
    // Add real-time validation to all form inputs
    document.querySelectorAll('.form-control, .form-select, .form-textarea').forEach(field => {
        // Validation on blur
        field.addEventListener('blur', function() {
            validateFormField(this);
        });
        
        // Clear validation errors on input
        field.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateFormField(this);
            }
            formatNumberInput(this);
        });
        
        // Enhanced focus effects
        field.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        field.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // Enhanced form submission
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const fields = this.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            fields.forEach(field => {
                if (!validateFormField(field)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                const firstInvalid = this.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                if (typeof UXUtils !== 'undefined') {
                    UXUtils.showToast('Veuillez corriger les erreurs dans le formulaire', 'error');
                }
            }
        });
    });
});
</script>
@endpush
