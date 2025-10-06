@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'fullWidth' => false,
    'href' => null,
    'target' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variantClasses = [
        'primary' => 'btn-primary-gradient',
        'secondary' => 'btn-secondary-soft',
        'success' => 'btn-success-gradient',
        'danger' => 'btn-danger-soft',
        'warning' => 'btn-warning-soft',
        'info' => 'btn-info-soft',
        'outline' => 'btn-outline',
        'ghost' => 'btn-ghost',
        'link' => 'btn-link'
    ];
    
    $sizeClasses = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg'
    ];
    
    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size];
    
    if ($fullWidth) {
        $classes .= ' w-full';
    }
    
    if ($loading) {
        $classes .= ' cursor-wait';
    }
@endphp

@if($href)
    <a href="{{ $href }}" 
       @if($target) target="{{ $target }}" @endif
       {{ $attributes->merge(['class' => $classes]) }}
       @if($disabled) aria-disabled="true" @endif
    >
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" 
            {{ $attributes->merge(['class' => $classes]) }}
            @if($disabled) disabled @endif
    >
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </button>
@endif

@push('styles')
<style>
    .btn-loading {
        position: relative;
        color: transparent;
    }
    /* Gradients to match article design */
    .btn-primary-gradient { background-image: linear-gradient(135deg, #2563eb, #4f46e5); color: #fff; padding: 0.75rem 1.25rem; border-radius: 0.75rem; }
    .btn-primary-gradient:hover { background-image: linear-gradient(135deg, #1d4ed8, #4338ca); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(79,70,229,.25); }

    .btn-success-gradient { background-image: linear-gradient(135deg, #10b981, #059669); color: #fff; padding: 0.75rem 1.25rem; border-radius: 0.75rem; }
    .btn-success-gradient:hover { background-image: linear-gradient(135deg, #059669, #047857); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(5,150,105,.25); }

    .btn-secondary-soft { background: #f3f4f6; color: #374151; padding: 0.75rem 1.25rem; border-radius: 0.75rem; }
    .btn-secondary-soft:hover { background: #e5e7eb; }

    .btn-danger-soft { background: #fee2e2; color: #b91c1c; padding: 0.75rem 1.25rem; border-radius: 0.75rem; }
    .btn-warning-soft { background: #fef3c7; color: #b45309; padding: 0.75rem 1.25rem; border-radius: 0.75rem; }
    .btn-info-soft { background: #dbeafe; color: #1d4ed8; padding: 0.75rem 1.25rem; border-radius: 0.75rem; }

    .btn-outline { background: transparent; color: #4f46e5; border: 2px solid #4f46e5; padding: 0.75rem 1.25rem; border-radius: 0.75rem; }
    .btn-outline:hover { background: #4f46e5; color: #fff; }

    .btn-ghost { background: transparent; color: #374151; padding: 0.75rem 1.25rem; border-radius: 0.75rem; }
    .btn-link { background: transparent; color: #4f46e5; text-decoration: none; padding: 0.75rem 1rem; }

    .btn-full-mobile { width: 100%; }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid transparent;
        border-top-color: currentColor;
        border-radius: 50%;
        animation: button-loading-spinner 1s ease infinite;
    }

    @keyframes button-loading-spinner {
        from {
            transform: rotate(0turn);
        }
        to {
            transform: rotate(1turn);
        }
    }

    /* Icon button variant */
    .btn-icon {
        padding: 0.5rem;
        min-width: 2.5rem;
        height: 2.5rem;
    }

    .btn-icon.btn-sm {
        padding: 0.375rem;
        min-width: 2rem;
        height: 2rem;
    }

    .btn-icon.btn-lg {
        padding: 0.75rem;
        min-width: 3rem;
        height: 3rem;
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .btn-full-mobile {
            width: 100%;
        }
    }
</style>
@endpush
