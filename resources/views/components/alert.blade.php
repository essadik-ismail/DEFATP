@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => false,
    'autoHide' => false,
    'duration' => 5000
])

@php
    $typeClasses = [
        'success' => 'bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700',
        'error' => 'bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700',
        'warning' => 'bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 text-yellow-700',
        'info' => 'bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 text-blue-700'
    ];
    
    $iconClasses = [
        'success' => 'fas fa-check-circle text-green-600',
        'error' => 'fas fa-exclamation-triangle text-red-600',
        'warning' => 'fas fa-exclamation-triangle text-yellow-600',
        'info' => 'fas fa-info-circle text-blue-600'
    ];
    
    $classes = $typeClasses[$type] . ' p-6 rounded-xl mb-6 shadow-lg transition-all duration-300';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} 
     @if($autoHide) data-auto-hide="{{ $duration }}" @endif
     @if($dismissible) data-dismissible="true" @endif
>
    <div class="flex items-center gap-3">
        <i class="{{ $iconClasses[$type] }} text-2xl"></i>
        <div class="flex-1">
            @if($title)
                <h3 class="font-semibold text-lg mb-1">{{ $title }}</h3>
            @endif
            <p class="text-sm">{{ $slot }}</p>
        </div>
        
        @if($dismissible)
            <button type="button" 
                    class="alert-dismiss-btn text-gray-400 hover:text-gray-600 transition-colors"
                    onclick="dismissAlert(this.closest('.alert'))"
                    title="Fermer"
            >
                <i class="fas fa-times text-lg"></i>
            </button>
        @endif
    </div>
</div>

@push('styles')
<style>
    .alert {
        position: relative;
        overflow: hidden;
    }

    .alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: currentColor;
        opacity: 0.3;
    }

    .alert-dismiss-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .alert-dismiss-btn:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .alert.alert-hiding {
        opacity: 0;
        transform: translateY(-10px);
    }

    .alert.alert-hidden {
        display: none;
    }

    /* Auto-hide animation */
    .alert[data-auto-hide] {
        animation: alert-slide-in 0.3s ease-out;
    }

    @keyframes alert-slide-in {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .alert .flex {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .alert-dismiss-btn {
            align-self: flex-end;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-hide functionality
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('[data-auto-hide]');
        alerts.forEach(alert => {
            const duration = parseInt(alert.dataset.autoHide) || 5000;
            setTimeout(() => {
                hideAlert(alert);
            }, duration);
        });
    });

    // Dismiss alert functionality
    function dismissAlert(alert) {
        hideAlert(alert);
    }

    function hideAlert(alert) {
        alert.classList.add('alert-hiding');
        setTimeout(() => {
            alert.classList.add('alert-hidden');
        }, 300);
    }

    // Global alert functions
    window.showAlert = function(type, message, title = null, options = {}) {
        const alertHtml = `
            <div class="alert ${typeClasses[type]} p-6 rounded-xl mb-6 shadow-lg transition-all duration-300" 
                 data-auto-hide="${options.autoHide || false}" 
                 data-dismissible="${options.dismissible || true}">
                <div class="flex items-center gap-3">
                    <i class="${iconClasses[type]} text-2xl"></i>
                    <div class="flex-1">
                        ${title ? `<h3 class="font-semibold text-lg mb-1">${title}</h3>` : ''}
                        <p class="text-sm">${message}</p>
                    </div>
                    ${options.dismissible !== false ? `
                        <button type="button" 
                                class="alert-dismiss-btn text-gray-400 hover:text-gray-600 transition-colors"
                                onclick="dismissAlert(this.closest('.alert'))"
                                title="Fermer">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    ` : ''}
                </div>
            </div>
        `;
        
        // Insert at the top of the content area
        const contentArea = document.querySelector('main') || document.querySelector('.content') || document.body;
        contentArea.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto-hide if enabled
        if (options.autoHide) {
            setTimeout(() => {
                const newAlert = contentArea.querySelector('.alert');
                if (newAlert) hideAlert(newAlert);
            }, options.duration || 5000);
        }
    };

    // Predefined alert types
    window.showSuccessAlert = (message, title, options) => showAlert('success', message, title, options);
    window.showErrorAlert = (message, title, options) => showAlert('error', message, title, options);
    window.showWarningAlert = (message, title, options) => showAlert('warning', message, title, options);
    window.showInfoAlert = (message, title, options) => showAlert('info', message, title, options);
</script>
@endpush
