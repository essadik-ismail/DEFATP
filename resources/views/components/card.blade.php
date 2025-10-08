@props([
    'title' => null,
    'subtitle' => null,
    'headerActions' => null,
    'footer' => null,
    'collapsible' => false,
    'collapsed' => false,
    'id' => null,
    'variant' => 'default', // default, gradient, colored, minimal
    'color' => 'green', // green, blue, purple, orange, red, gray
    'icon' => null,
    'iconColor' => null,
    'padding' => 'normal' // normal, compact, spacious
])

@php
    $cardClasses = [
        'card',
        'mb-4',
        'card-' . $variant,
        'card-' . $color,
        'card-padding-' . $padding
    ];
    
    $headerClasses = [
        'card-header',
        'card-header-' . $variant,
        'card-header-' . $color
    ];
@endphp

<div class="{{ implode(' ', $cardClasses) }}" {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if($title || $subtitle || $headerActions || $icon)
        <div class="{{ implode(' ', $headerClasses) }}">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($collapsible)
                        <button 
                            type="button" 
                            class="collapse-toggle {{ $collapsed ? 'collapsed' : '' }}"
                            onclick="toggleCollapse('{{ $id }}-content')"
                            title="{{ $collapsed ? 'Expandir' : 'Réduire' }}"
                        >
                            <i class="fas fa-chevron-down" id="{{ $id }}-icon"></i>
                        </button>
                    @endif
                    
                    @if($icon)
                        <div class="card-icon card-icon-{{ $color }}">
                            <i class="{{ $icon }}"></i>
                        </div>
                    @endif
                    
                    <div>
                        @if($title)
                            <h5 class="card-title">{{ $title }}</h5>
                        @endif
                        @if($subtitle)
                            <p class="card-subtitle">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>
                @if($headerActions)
                    <div class="flex items-center gap-2">
                        {{ $headerActions }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="card-body {{ $collapsible ? 'collapse-content' : '' }} {{ $collapsed ? 'collapsed' : '' }}" 
         id="{{ $collapsible ? $id . '-content' : '' }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>

@push('styles')
<style>
    /* Base Card Styles */
    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    /* Card Variants */
    .card-gradient {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.8) 100%);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .card-colored {
        border-left: 4px solid var(--primary-color);
    }

    .card-minimal {
        background: rgba(255, 255, 255, 0.7);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Color Variants */
    .card-green {
        --card-color: #059669;
        --card-color-light: #10b981;
        --card-bg: rgba(16, 185, 129, 0.05);
    }

    .card-blue {
        --card-color: #2563eb;
        --card-color-light: #3b82f6;
        --card-bg: rgba(59, 130, 246, 0.05);
    }

    .card-purple {
        --card-color: #7c3aed;
        --card-color-light: #8b5cf6;
        --card-bg: rgba(139, 92, 246, 0.05);
    }

    .card-orange {
        --card-color: #ea580c;
        --card-color-light: #f97316;
        --card-bg: rgba(249, 115, 22, 0.05);
    }

    .card-red {
        --card-color: #dc2626;
        --card-color-light: #ef4444;
        --card-bg: rgba(239, 68, 68, 0.05);
    }

    .card-gray {
        --card-color: #6b7280;
        --card-color-light: #9ca3af;
        --card-bg: rgba(156, 163, 175, 0.05);
    }

    /* Header Styles */
    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.8) 0%, rgba(255, 255, 255, 0.9) 100%);
        position: relative;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, var(--card-bg) 0%, rgba(255, 255, 255, 0.9) 100%);
    }

    .card-header-colored {
        background: linear-gradient(135deg, var(--card-bg) 0%, rgba(255, 255, 255, 0.95) 100%);
        border-bottom: 1px solid var(--card-color);
    }

    .card-header-minimal {
        background: rgba(255, 255, 255, 0.8);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Card Icon */
    .card-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .card-icon-green {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .card-icon-blue {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .card-icon-purple {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }

    .card-icon-orange {
        background: linear-gradient(135deg, #f97316, #ea580c);
        color: white;
    }

    .card-icon-red {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .card-icon-gray {
        background: linear-gradient(135deg, #9ca3af, #6b7280);
        color: white;
    }

    /* Typography */
    .card-title {
        margin: 0;
        font-weight: 700;
        color: #1f2937;
        font-size: 1.125rem;
        line-height: 1.4;
    }

    .card-subtitle {
        margin: 0.25rem 0 0 0;
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Body and Footer */
    .card-body {
        position: relative;
    }

    .card-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        background: rgba(248, 250, 252, 0.5);
    }

    /* Padding Variants */
    .card-padding-compact .card-header {
        padding: 1rem 1.5rem;
    }

    .card-padding-compact .card-body {
        padding: 1rem 1.5rem;
    }

    .card-padding-compact .card-footer {
        padding: 1rem 1.5rem;
    }

    .card-padding-spacious .card-header {
        padding: 2rem 2.5rem;
    }

    .card-padding-spacious .card-body {
        padding: 2rem 2.5rem;
    }

    .card-padding-spacious .card-footer {
        padding: 2rem 2.5rem;
    }

    .card-padding-normal .card-body {
        padding: 1.5rem 2rem;
    }

    /* Collapse Toggle */
    .collapse-toggle {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #6b7280;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .collapse-toggle:hover {
        background: rgba(255, 255, 255, 1);
        border-color: var(--card-color, #059669);
        color: var(--card-color, #059669);
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .collapse-toggle i {
        transition: transform 0.3s ease;
        font-size: 1.25rem;
    }

    .collapse-toggle.collapsed i {
        transform: rotate(180deg);
    }

    /* Collapse Content */
    .collapse-content {
        max-height: 2000px;
        opacity: 1;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .collapse-content.collapsed {
        max-height: 0;
        opacity: 0;
        padding-top: 0;
        padding-bottom: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .card {
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }

        .card-header {
            padding: 1rem 1.5rem;
        }

        .card-padding-normal .card-body {
            padding: 1rem 1.5rem;
        }

        .card-padding-compact .card-header {
            padding: 0.75rem 1rem;
        }

        .card-padding-compact .card-body {
            padding: 0.75rem 1rem;
        }

        .card-padding-spacious .card-header {
            padding: 1.5rem 2rem;
        }

        .card-padding-spacious .card-body {
            padding: 1.5rem 2rem;
        }

        .card-footer {
            padding: 1rem 1.5rem;
        }

        .card-icon {
            width: 2rem;
            height: 2rem;
        }

        .card-title {
            font-size: 1rem;
        }

        .card-subtitle {
            font-size: 0.8125rem;
        }
    }

    @media (max-width: 480px) {
        .card {
            border-radius: 0.5rem;
        }

        .card-header {
            padding: 0.75rem 1rem;
        }

        .card-padding-normal .card-body {
            padding: 0.75rem 1rem;
        }

        .card-footer {
            padding: 0.75rem 1rem;
        }

        .card-icon {
            width: 1.75rem;
            height: 1.75rem;
        }

        .collapse-toggle {
            width: 1.75rem;
            height: 1.75rem;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .card {
            background: rgba(31, 41, 55, 0.95);
            border: 1px solid rgba(75, 85, 99, 0.3);
            color: #f9fafb;
        }

        .card-header {
            background: linear-gradient(135deg, rgba(55, 65, 81, 0.8) 0%, rgba(31, 41, 55, 0.9) 100%);
            border-bottom: 1px solid rgba(75, 85, 99, 0.3);
        }

        .card-title {
            color: #f9fafb;
        }

        .card-subtitle {
            color: #d1d5db;
        }

        .card-footer {
            background: rgba(55, 65, 81, 0.5);
            border-top: 1px solid rgba(75, 85, 99, 0.3);
        }
    }

    /* Reduced Motion Support */
    @media (prefers-reduced-motion: reduce) {
        .card,
        .collapse-toggle,
        .collapse-content {
            transition: none;
        }

        .card:hover {
            transform: none;
        }

        .collapse-toggle:hover {
            transform: none;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function toggleCollapse(contentId) {
    const content = document.getElementById(contentId);
    const icon = document.getElementById(contentId.replace('-content', '-icon'));
    
    if (content && icon) {
        content.classList.toggle('collapsed');
        icon.parentElement.classList.toggle('collapsed');
        
        // Update aria-expanded for accessibility
        const isCollapsed = content.classList.contains('collapsed');
        icon.parentElement.setAttribute('aria-expanded', !isCollapsed);
    }
}
</script>
@endpush
