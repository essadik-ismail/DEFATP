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
    /* Base Card - white, 16px radius, subtle elevation */
    .card {
        background: #FFFFFF;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(154, 179, 163, 0.4);
        overflow: hidden;
        transition: box-shadow 0.2s ease;
        position: relative;
    }

    .card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .card-gradient, .card-minimal {
        background: #FFFFFF;
        border: 1px solid rgba(154, 179, 163, 0.4);
    }

    .card-colored {
        border-left: 4px solid #2E5239;
    }

    /* Color variants - palette only */
    .card-green, .card-blue, .card-purple, .card-orange, .card-red, .card-gray {
        --card-color: #2E5239;
        --card-color-light: #3E6A4B;
        --card-bg: rgba(46, 82, 57, 0.06);
    }

    /* Header Styles */
    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(154, 179, 163, 0.5);
        background: #F2F6F3;
        position: relative;
    }

    .card-header-gradient, .card-header-colored, .card-header-minimal {
        background: #F2F6F3;
        border-bottom: 1px solid rgba(154, 179, 163, 0.5);
    }

    .card-header-colored {
        border-bottom-color: #2E5239;
    }

    /* Card Icon - palette only */
    .card-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .card-icon-green, .card-icon-blue, .card-icon-purple, .card-icon-orange, .card-icon-red, .card-icon-gray {
        background: #2E5239;
        color: #FFFFFF;
    }

    /* Typography */
    .card-title {
        margin: 0;
        font-weight: 600;
        color: #1F2D24;
        font-size: 1rem;
        line-height: 1.5;
    }

    .card-subtitle {
        margin: 0.25rem 0 0 0;
        font-size: 0.875rem;
        color: #6B7C72;
        font-weight: 400;
    }

    /* Body and Footer */
    .card-body {
        position: relative;
    }

    .card-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid rgba(154, 179, 163, 0.4);
        background: #FFFFFF;
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
        background: #FFFFFF;
        border: 1px solid rgba(154, 179, 163, 0.4);
        border-radius: 0.75rem;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #1F2D24;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .collapse-toggle:hover {
        border-color: #2E5239;
        color: #2E5239;
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
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
