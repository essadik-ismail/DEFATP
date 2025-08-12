@props([
    'title' => 'Filtres Avancés',
    'collapsible' => true,
    'collapsed' => false,
    'id' => 'filter-section'
])

<div class="filter-section-wrapper">
    @if($collapsible)
        <div class="filter-header">
            <button type="button" 
                    class="filter-toggle {{ $collapsed ? 'collapsed' : '' }}"
                    onclick="toggleFilterSection('{{ $id }}')"
                    title="{{ $collapsed ? 'Afficher les filtres' : 'Masquer les filtres' }}"
            >
                <i class="material-icons filter-icon" id="{{ $id }}-icon">
                    {{ $collapsed ? 'expand_more' : 'expand_less' }}
                </i>
                <span class="filter-title">{{ $title }}</span>
            </button>
        </div>
    @endif

    <div class="filter-content {{ $collapsible ? 'filter-collapsible' : '' }} {{ $collapsed ? 'collapsed' : '' }}" 
         id="{{ $id }}-content">
        {{ $slot }}
    </div>
</div>

@push('styles')
<style>
    .filter-section-wrapper {
        margin-bottom: 1.5rem;
    }

    .filter-header {
        margin-bottom: 1rem;
    }

    .filter-toggle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: none;
        border: none;
        padding: 0.5rem;
        cursor: pointer;
        color: #6b7280;
        font-weight: 500;
        transition: all 0.2s ease;
        border-radius: 0.5rem;
    }

    .filter-toggle:hover {
        background-color: #f3f4f6;
        color: #374151;
    }

    .filter-toggle.collapsed .filter-icon {
        transform: rotate(-90deg);
    }

    .filter-icon {
        transition: transform 0.3s ease;
        font-size: 1.25rem;
    }

    .filter-title {
        font-size: 1rem;
        font-weight: 600;
    }

    .filter-content {
        transition: all 0.3s ease;
    }

    .filter-content.filter-collapsible {
        overflow: hidden;
    }

    .filter-content.filter-collapsible.collapsed {
        max-height: 0;
        opacity: 0;
        margin: 0;
        padding: 0;
    }

    .filter-content:not(.collapsed) {
        max-height: 2000px;
        opacity: 1;
    }

    /* Filter grid layout */
    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-grid.cols-2 {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }

    .filter-grid.cols-3 {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }

    .filter-grid.cols-4 {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .filter-actions .btn {
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .filter-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleFilterSection(sectionId) {
        const content = document.getElementById(sectionId + '-content');
        const toggle = document.querySelector(`[onclick="toggleFilterSection('${sectionId}')"]`);
        const icon = document.getElementById(sectionId + '-icon');
        
        if (content.classList.contains('collapsed')) {
            // Expand
            content.classList.remove('collapsed');
            toggle.classList.remove('collapsed');
            icon.textContent = 'expand_less';
            localStorage.setItem(`filter_${sectionId}`, 'expanded');
        } else {
            // Collapse
            content.classList.add('collapsed');
            toggle.classList.add('collapsed');
            icon.textContent = 'expand_more';
            localStorage.setItem(`filter_${sectionId}`, 'collapsed');
        }
    }

    // Initialize filter section state from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const filterSections = document.querySelectorAll('.filter-section-wrapper');
        filterSections.forEach(section => {
            const content = section.querySelector('.filter-content');
            const toggle = section.querySelector('.filter-toggle');
            const icon = section.querySelector('.filter-icon');
            const sectionId = content.id.replace('-content', '');
            
            if (content && toggle && icon) {
                const isCollapsed = localStorage.getItem(`filter_${sectionId}`) === 'collapsed';
                if (isCollapsed) {
                    content.classList.add('collapsed');
                    toggle.classList.add('collapsed');
                    icon.textContent = 'expand_more';
                }
            }
        });
    });
</script>
@endpush
