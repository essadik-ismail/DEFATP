@props([
    'name' => '',
    'id' => '',
    'label' => '',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Sélectionner une option...',
    'searchPlaceholder' => 'Rechercher...',
    'multiple' => false,
    'required' => false,
    'disabled' => false,
    'class' => '',
    'error' => null,
    'helpText' => null,
    'minSearchLength' => 0,
    'allowClear' => true,
    'maxItems' => null
])

@php
    $id = $id ?: $name;
    $inputClass = 'form-select-search ' . $class;
    if ($error) {
        $inputClass .= ' is-invalid';
    }
@endphp

<div class="select-search-wrapper" id="select-wrapper-{{ $id }}">
    @if($label)
    <label for="{{ $id }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    @endif

    <div class="select-search-container">
        <div class="select-search-display" onclick="toggleSelectSearch('{{ $id }}')">
            <div class="selected-items">
                @if($multiple && is_array($selected) && count($selected) > 0)
                    @foreach($selected as $item)
                        <span class="selected-item">
                            {{ $item }}
                            <button type="button" class="remove-item" onclick="removeSelectedItem('{{ $id }}', '{{ $item }}')">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                    @endforeach
                @elseif(!$multiple && $selected)
                    <span class="selected-text">{{ $selected }}</span>
                @else
                    <span class="placeholder-text">{{ $placeholder }}</span>
                @endif
            </div>
            <div class="select-arrow">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>

        <div class="select-search-dropdown" id="dropdown-{{ $id }}" style="display: none;">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    class="search-input" 
                    placeholder="{{ $searchPlaceholder }}"
                    onkeyup="filterSelectOptions('{{ $id }}', this.value)"
                    onfocus="this.select()"
                >
            </div>
            
            <div class="options-list" id="options-{{ $id }}">
                @foreach($options as $value => $label)
                    @php
                        $isSelected = false;
                        if ($multiple && is_array($selected)) {
                            $isSelected = in_array($value, $selected);
                        } else {
                            $isSelected = $selected == $value;
                        }
                    @endphp
                    <div class="option-item {{ $isSelected ? 'selected' : '' }}" 
                         data-value="{{ $value }}" 
                         data-label="{{ $label }}"
                         onclick="selectOption('{{ $id }}', '{{ $value }}', '{{ $label }}', {{ $multiple ? 'true' : 'false' }})">
                        <span class="option-text">{{ $label }}</span>
                        @if($isSelected)
                            <i class="fas fa-check selected-icon"></i>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($allowClear && !$multiple && $selected)
            <div class="clear-selection">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSelection('{{ $id }}')">
                    <i class="fas fa-times me-1"></i>Effacer la sélection
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Hidden input for form submission -->
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ $multiple ? (is_array($selected) ? implode(',', $selected) : '') : $selected }}" {{ $required ? 'required' : '' }}>

    @if($error)
        <div class="invalid-feedback d-block">
            {{ $error }}
        </div>
    @endif

    @if($helpText)
        <div class="form-text">
            {{ $helpText }}
        </div>
    @endif
</div>

<style>
    .select-search-wrapper {
        position: relative;
        margin-bottom: 1rem;
    }

    .select-search-container {
        position: relative;
        width: 100%;
    }

    .select-search-display {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        background-color: #fff;
        cursor: pointer;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        min-height: 38px;
    }

    .select-search-display:hover {
        border-color: #86b7fe;
    }

    .select-search-display:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: 0;
    }

    .select-search-display.open {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .selected-items {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
        flex: 1;
        min-width: 0;
    }

    .selected-item {
        display: inline-flex;
        align-items: center;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.125rem 0.5rem;
        font-size: 0.875rem;
        color: #495057;
        max-width: 200px;
    }

    .remove-item {
        background: none;
        border: none;
        color: #6c757d;
        margin-left: 0.25rem;
        padding: 0;
        cursor: pointer;
        font-size: 0.75rem;
    }

    .remove-item:hover {
        color: #dc3545;
    }

    .selected-text {
        color: #495057;
    }

    .placeholder-text {
        color: #6c757d;
        font-style: italic;
    }

    .select-arrow {
        color: #6c757d;
        transition: transform 0.2s ease;
    }

    .select-search-display.open .select-arrow {
        transform: rotate(180deg);
    }

    .select-search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ced4da;
        border-top: none;
        border-radius: 0 0 0.375rem 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 1050;
        max-height: 300px;
        overflow: hidden;
    }

    .search-input-wrapper {
        position: relative;
        padding: 0.75rem;
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        z-index: 1;
    }

    .search-input {
        width: 100%;
        padding: 0.5rem 0.5rem 0.5rem 2rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        background-color: white;
    }

    .search-input:focus {
        outline: none;
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .options-list {
        max-height: 200px;
        overflow-y: auto;
    }

    .option-item {
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background-color 0.15s ease;
        border-bottom: 1px solid #f8f9fa;
    }

    .option-item:hover {
        background-color: #f8f9fa;
    }

    .option-item.selected {
        background-color: #e7f1ff;
        color: #0d6efd;
    }

    .option-item.hidden {
        display: none;
    }

    .option-text {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .selected-icon {
        color: #0d6efd;
        margin-left: 0.5rem;
    }

    .clear-selection {
        padding: 0.75rem;
        border-top: 1px solid #e9ecef;
        text-align: center;
        background-color: #f8f9fa;
    }

    .clear-selection .btn {
        width: 100%;
    }

    /* Scrollbar styling */
    .options-list::-webkit-scrollbar {
        width: 6px;
    }

    .options-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .options-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .options-list::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .select-search-dropdown {
            max-height: 250px;
        }

        .options-list {
            max-height: 150px;
        }

        .selected-item {
            max-width: 150px;
        }
    }

    /* Focus states for accessibility */
    .select-search-display:focus-within {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Disabled state */
    .select-search-display.disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
        opacity: 0.65;
    }

    .select-search-display.disabled:hover {
        border-color: #ced4da;
    }
</style>

<!-- JavaScript functions are now loaded globally from partials/scripts.blade.php -->
