@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => false,
    'autoHide' => false,
    'duration' => 5000
])

@php
    $typeClasses = [
        'success' => 'alert-theme-success',
        'error' => 'alert-theme-error',
        'warning' => 'alert-theme-warning',
        'info' => 'alert-theme-info'
    ];
    $iconClasses = [
        'success' => 'fas fa-check-circle',
        'error' => 'fas fa-exclamation-triangle',
        'warning' => 'fas fa-exclamation-triangle',
        'info' => 'fas fa-info-circle'
    ];
    $classes = 'alert ' . ($typeClasses[$type] ?? $typeClasses['info']) . ' p-6 rounded-xl mb-6 shadow-lg transition-all duration-300';
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
                    class="alert-dismiss-btn transition-colors"
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
    .alert { position: relative; overflow: hidden; }
    .alert-theme-success { background: #FFFFFF; border-left: 4px solid #2E5239; color: #1F2D24; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-radius: 1rem; }
    .alert-theme-error { background: #FFFFFF; border-left: 4px solid #1F2D24; color: #1F2D24; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-radius: 1rem; }
    .alert-theme-warning { background: #FFFFFF; border-left: 4px solid #9AB3A3; color: #1F2D24; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-radius: 1rem; }
    .alert-theme-info { background: #FFFFFF; border-left: 4px solid #9AB3A3; color: #1F2D24; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-radius: 1rem; }
    .alert-theme-success i, .alert-theme-success .font-semibold { color: #2E5239; }
    .alert-theme-error i, .alert-theme-error .font-semibold { color: #1F2D24; }
    .alert-theme-warning i, .alert-theme-warning .font-semibold { color: #1F2D24; }
    .alert-theme-info i, .alert-theme-info .font-semibold { color: #2E5239; }
    .alert::before { display: none; }
    .alert-dismiss-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        color: #9AB3A3;
    }
    .alert-dismiss-btn:hover {
        color: #1F2D24;
        background-color: rgba(46, 82, 57, 0.08);
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
