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
    $classes = 'alert ' . ($typeClasses[$type] ?? $typeClasses['info']) . ' px-4 py-3 rounded-xl mb-4 transition-all duration-300';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} 
     @if($autoHide) data-auto-hide="{{ $duration }}" @endif
     @if($dismissible) data-dismissible="true" @endif
>
    <div class="flex items-center gap-3">
        <i class="{{ $iconClasses[$type] }} text-base flex-shrink-0"></i>
        <div class="flex-1">
            @if($title)
                <span class="font-semibold text-sm">{{ $title }} — </span>
            @endif
            <span class="text-sm">{{ $slot }}</span>
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

@once
@push('styles')
<style>
    .alert { position: relative; overflow: hidden; }
    .alert-theme-success { background: #f0fdf7; border: 1px solid rgba(5,150,105,0.2); border-left: 3px solid #059669; color: #065f46; border-radius: 0.625rem; }
    .alert-theme-error   { background: #fef2f2; border: 1px solid rgba(220,38,38,0.2);  border-left: 3px solid #dc2626; color: #991b1b; border-radius: 0.625rem; }
    .alert-theme-warning { background: #fffbeb; border: 1px solid rgba(217,119,6,0.2);  border-left: 3px solid #d97706; color: #92400e; border-radius: 0.625rem; }
    .alert-theme-info    { background: #eff6ff; border: 1px solid rgba(37,99,235,0.2);  border-left: 3px solid #2563eb; color: #1e40af; border-radius: 0.625rem; }
    .alert-theme-success i, .alert-theme-success .font-semibold { color: #059669; }
    .alert-theme-error i, .alert-theme-error .font-semibold { color: #dc2626; }
    .alert-theme-warning i, .alert-theme-warning .font-semibold { color: #d97706; }
    .alert-theme-info i, .alert-theme-info .font-semibold { color: #2563eb; }
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
@endonce

@once
@push('scripts')
<script>
    (function() {
        function initializeAutoHideAlerts() {
            document.querySelectorAll('[data-auto-hide]').forEach((alert) => {
                if (alert.dataset.alertInitialized === 'true') {
                    return;
                }

                alert.dataset.alertInitialized = 'true';

                const duration = parseInt(alert.dataset.autoHide, 10) || 5000;
                alert._hideTimer = setTimeout(() => {
                    window.hideAlert(alert);
                }, duration);
            });
        }

        window.hideAlert = function(alert) {
            if (!alert || alert.classList.contains('alert-hidden')) {
                return;
            }

            if (alert._hideTimer) {
                clearTimeout(alert._hideTimer);
            }

            alert.classList.add('alert-hiding');

            setTimeout(() => {
                alert.classList.add('alert-hidden');
            }, 300);
        };

        window.dismissAlert = function(alert) {
            window.hideAlert(alert);
        };

        window.showAlert = function(type, message, title = null, options = {}) {
            if (window.UXUtils && typeof window.UXUtils.showToast === 'function') {
                return window.UXUtils.showToast(message, type, {
                    title: title,
                    duration: options.duration || 5000,
                    closable: options.dismissible !== false,
                    dedupe: options.dedupe !== false,
                    sound: options.sound === true
                });
            }

            return null;
        };

        window.showSuccessAlert = (message, title, options) => window.showAlert('success', message, title, options);
        window.showErrorAlert = (message, title, options) => window.showAlert('error', message, title, options);
        window.showWarningAlert = (message, title, options) => window.showAlert('warning', message, title, options);
        window.showInfoAlert = (message, title, options) => window.showAlert('info', message, title, options);

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeAutoHideAlerts);
        } else {
            initializeAutoHideAlerts();
        }
    })();
</script>
@endpush
@endonce
