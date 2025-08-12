@props([
    'title' => null,
    'subtitle' => null,
    'headerActions' => null,
    'footer' => null,
    'collapsible' => false,
    'collapsed' => false,
    'id' => null
])

<div class="card" {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if($title || $subtitle || $headerActions)
        <div class="card-header">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($collapsible)
                        <button 
                            type="button" 
                            class="collapse-toggle {{ $collapsed ? 'collapsed' : '' }}"
                            onclick="toggleCollapse('{{ $id }}-content')"
                            title="{{ $collapsed ? 'Expandir' : 'Réduire' }}"
                        >
                            <i class="material-icons" id="{{ $id }}-icon">expand_more</i>
                        </button>
                    @endif
                    <div>
                        @if($title)
                            <h5 class="card-title">{{ $title }}</h5>
                        @endif
                        @if($subtitle)
                            <p class="text-sm text-gray-600">{{ $subtitle }}</p>
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
    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.1),
            0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.3);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 
            0 16px 48px rgba(0, 0, 0, 0.15),
            0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.8) 0%, rgba(255, 255, 255, 0.9) 100%);
    }

    .card-title {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1.125rem;
    }

    .card-body {
        padding: 2rem;
    }

    .card-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        background: rgba(248, 250, 252, 0.5);
    }

    .collapse-toggle {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--text-secondary);
    }

    .collapse-toggle:hover {
        background: rgba(255, 255, 255, 1);
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: scale(1.05);
    }

    .collapse-toggle i {
        transition: transform 0.3s ease;
    }

    .collapse-toggle.collapsed i {
        transform: rotate(-90deg);
    }

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

    @media (max-width: 768px) {
        .card-header {
            padding: 1rem 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-footer {
            padding: 1rem 1.5rem;
        }
    }
</style>
@endpush
