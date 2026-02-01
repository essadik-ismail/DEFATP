@extends('layouts.app')

@section('title', 'Articles Forestiers - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item active">Articles</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <!-- Header Section -->
    <x-page-header 
        title="Articles Forestiers"
        subtitle="Gérez et consultez tous les articles forestiers du système"
        icon="fas fa-file-alt"
    >
        <x-slot name="actions">
            <a href="{{ route('articles.create') }}" 
               class="btn btn-primary inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-plus"></i>
                <span>Nouvel Article</span>
            </a>
        </x-slot>
    </x-page-header>

    <!-- Alert Messages -->
    @if(session('success'))
        <x-alert type="success" title="Succès!" dismissible class="mb-4">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error" title="Erreur!" dismissible class="mb-4">
            {{ session('error') }}
        </x-alert>
    @endif

    <!-- Filters and Search Area -->
    <!-- <x-filters-card 
        title="Filtres et Recherche"
        icon="fas fa-filter"
        :action="route('articles.index')"
        formId="filterForm"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-4"
    >
        <x-form-input
            type="select"
            name="year"
            label="Année"
            id="year"
        >
            <option value="">Toutes les années</option>
            @foreach($availableYears ?? [] as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </x-form-input>

        <x-form-input
            type="date"
            name="adjudication_date"
            label="Date d'Adjudication"
            id="adjudication_date"
            :value="request('adjudication_date')"
        />

        <x-form-input
            type="select"
            name="type"
            label="Type"
            id="type"
        >
            <option value="">Tous les types</option>
            @foreach($availableTypes ?? [] as $type)
                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                    {{ $type == 'appel_doffre' ? 'Appel d\'Offre' : ucfirst($type) }}
                </option>
            @endforeach
        </x-form-input>

        <div class="form-group">
            <label for="search" class="block text-xs font-medium text-gray-700 mb-1.5">
                <i class="fas fa-search mr-1"></i>Recherche Globale
            </label>
            <div class="relative">
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="{{ request('search') }}"
                       placeholder="Rechercher..."
                       class="form-input w-full px-3 py-2 pl-9 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-0"
                       style="border-color: rgba(154,179,163,0.5);">
                <i class="fas fa-search absolute left-2.5 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
            </div>
        </div>

        <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
    </x-filters-card> -->

    <!-- Articles Data Table -->
    <div class="rounded-2xl border max-w-full overflow-hidden" style="background: #FFFFFF; border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.03);">
        <!-- Per Page Selector -->
        <div class="px-5 py-3 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2" style="border-color: rgba(154,179,163,0.4);">
            <div class="flex items-center gap-2">
                <label for="perPageSelect" class="text-xs font-medium" style="color: #6B7C72;">Articles par page:</label>
                <select class="form-input px-2 py-1 border rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-offset-0"
                        style="border-color: rgba(154,179,163,0.5);"
                        id="perPageSelect" onchange="changePerPage()">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto max-w-full" style="-webkit-overflow-scrolling: touch;">
            <table id="articlesTable" class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Numéro d'Article</span>
                                <button class="filter-btn ml-2 text-gray-400 hover:text-gray-600" data-column="0" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Date d'Adjudication</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="1" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>DRANEF</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="2" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>DPANEF</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="3" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>ZDTF</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="4" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Type</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="5" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider relative">
                            <div class="flex items-center justify-between">
                                <span>Statut</span>
                                <button class="filter-btn ml-1 text-gray-400 hover:text-gray-600" data-column="6" title="Filtrer">
                                    <i class="fas fa-filter text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($articles as $article)
                        @php
                            $contractVente = $article->contractVentes->first();
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-3 py-2 whitespace-nowrap text-xs font-medium text-gray-900">
                                @if($article->numero)
                                    <span class="badge text-xs px-2 py-0.5 bg-secondary">{{ $article->numero }}</span>
                                @else
                                    <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                @if($article->date_adjudication)
                                    {{ \Carbon\Carbon::parse($article->date_adjudication)->format('d/m/Y') }}
                                @else
                                    <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                @if($article->dranef)
                                    {{ $article->dranef->dranef }}
                                @else
                                    <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                @if($article->dpanef)
                                    {{ $article->dpanef->dpanef }}
                                @else
                                    <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                @if($article->zdtf)
                                    {{ $article->zdtf->zdtf }}
                                @else
                                    <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                @if($article->type)
                                    @php
                                        $typeLabels = [
                                            'appel_doffre' => 'Appel d\'Offre',
                                            'adjudication' => 'Adjudication',
                                            'marche_negocié' => 'Marché Négocié',
                                        ];
                                        $typeLabel = $typeLabels[$article->type] ?? ucfirst(str_replace('_', ' ', $article->type));
                                        $typeClass = $article->type == 'appel_doffre' ? 'bg-info' : ($article->type == 'adjudication' ? 'bg-success' : 'bg-primary');
                                    @endphp
                                    <span class="badge text-xs px-2 py-0.5 {{ $typeClass }}">
                                        {{ $typeLabel }}
                                    </span>
                                @else
                                    <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                <div class="flex flex-col gap-0.5">
                                    @php
                                        $steps = [
                                            'cahier_affiche' => ['label' => 'Cahier affiche', 'class' => 'bg-green-100 text-green-800'],
                                            'contrat_vente' => ['label' => 'Contrat de vente', 'class' => 'bg-green-100 text-green-800'],
                                            'paiement_charges' => ['label' => 'Paiement des charges', 'class' => 'bg-amber-100 text-amber-800'],
                                            'paiement_tranches' => ['label' => 'Paiement des tranches', 'class' => 'bg-amber-100 text-amber-800'],
                                            'recollement' => ['label' => 'Récolement', 'class' => 'bg-teal-100 text-teal-800'],
                                            'main_levee' => ['label' => 'Main levée', 'class' => 'bg-emerald-100 text-emerald-800'],
                                        ];
                                        $currentStep = $article->current_step ?? null;
                                        $stepInfo = $currentStep && isset($steps[$currentStep])
                                            ? $steps[$currentStep]
                                            : ['label' => $currentStep ? ucfirst(str_replace('_', ' ', $currentStep)) : 'Non défini', 'class' => 'bg-gray-100 text-gray-800'];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $stepInfo['class'] }}">
                                        {{ $stepInfo['label'] }}
                                    </span>
                                    @if($article->invendu)
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-ban mr-0.5 text-xs"></i>Invendu
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                <div class="flex items-center justify-center gap-0.5">
                                    <!-- View Action -->
                                    <a href="{{ route('articles.show', $article) }}" 
                                       class="inline-flex items-center justify-center w-7 h-7 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded transition-colors duration-200" 
                                       title="Voir les détails">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    
                                    <!-- Edit Action -->
                                    <a href="{{ route('articles.edit', $article) }}" 
                                       class="inline-flex items-center justify-center w-7 h-7 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded transition-colors duration-200" 
                                       title="Modifier l'article">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    
                                    <!-- Invendu Action -->
                                    <form action="{{ route('articles.toggle-invendu', $article) }}" method="POST" style="display: contents;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                onclick="return confirm('{{ $article->invendu ? 'Marquer cet article comme vendu ?' : 'Marquer cet article comme invendu ?' }}')"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded transition-colors duration-200 {{ $article->invendu ? 'invendu-btn' : 'no-invendu-btn' }}"
                                                title="{{ $article->invendu ? 'Marquer comme vendu' : 'Marquer comme invendu' }}">
                                            <i class="fas {{ $article->invendu ? 'fa-check-circle' : 'fa-ban' }} text-xs"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Action -->
                                    @can('articles.delete')
                                    <form action="{{ route('articles.destroy', $article) }}" method="POST" style="display: contents;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')"
                                                class="inline-flex items-center justify-center w-7 h-7 bg-red-100 hover:bg-red-200 text-red-600 rounded transition-colors duration-200"
                                                title="Supprimer l'article">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6">
                                <div class="text-gray-500">
                                    <i class="fas fa-file-alt text-4xl mb-2 d-block"></i>
                                    <p class="h5 mb-2">Aucun article trouvé</p>
                                    <p class="text-muted mb-3">Aucun article ne correspond à vos critères de recherche</p>
                                    <a href="{{ route('articles.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Créer le Premier Article
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-2">
                    <div class="text-xs text-gray-600">
                        Affichage de {{ $articles->firstItem() ?? 0 }} à {{ $articles->lastItem() ?? 0 }} 
                        sur {{ $articles->total() }} articles
                    </div>
                    <div class="pagination-controls">
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $articles->perPage() }} par page
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
<style>
.invendu-btn { background: rgba(46,82,57,0.2); color: #2E5239; }
.invendu-btn:hover { background: rgba(46,82,57,0.35); }
.no-invendu-btn { background: rgba(154,179,163,0.3); color: #1F2D24; }
.no-invendu-btn:hover { background: rgba(154,179,163,0.45); }
.filter-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 2px 4px;
    transition: all 0.2s;
    border-radius: 4px;
}

.filter-btn:hover {
    background-color: rgba(46, 82, 57, 0.08);
    color: #2E5239;
}

.filter-btn.active {
    color: #2E5239;
    background-color: rgba(46, 82, 57, 0.12);
}

/* Column Filter Dropdown */
.column-filter-dropdown {
    position: absolute;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    display: none;
    min-width: 250px;
    max-width: 400px;
    max-height: 400px;
    overflow: hidden;
}

.column-filter-dropdown.show {
    display: flex;
    flex-direction: column;
}

.filter-dropdown-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.filter-dropdown-header .close-filter {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s;
}

.filter-dropdown-header .close-filter:hover {
    background: #e5e7eb;
    color: #374151;
}

.filter-dropdown-content {
    padding: 8px;
    overflow-y: auto;
    max-height: 300px;
}

.filter-search-input {
    width: 100%;
    padding: 6px 8px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    margin-bottom: 8px;
    font-size: 13px;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.filter-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.filter-option:hover {
    background-color: #f3f4f6;
}

.filter-option input[type="checkbox"] {
    cursor: pointer;
    width: 16px;
    height: 16px;
}

.filter-option span {
    flex: 1;
    font-size: 14px;
    color: #374151;
}

.filter-dropdown-footer {
    display: flex;
    gap: 8px;
    padding: 12px 16px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
}

.btn-clear-filter,
.btn-apply-filter {
    flex: 1;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-clear-filter {
    background: #f3f4f6;
    color: #374151;
}

.btn-clear-filter:hover {
    background: #e5e7eb;
}

.btn-apply-filter {
    background: #2E5239;
    color: #FFFFFF;
}

.btn-apply-filter:hover {
    background: #3E6A4B;
}
</style>
@endpush

@push('scripts')
<script>
function changePerPage() {
    const perPage = document.getElementById('perPageSelect').value;
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Reset to first page
    window.location.href = currentUrl.toString();
}

function clearFilters() {
    // Clear all filter inputs
    document.getElementById('year').value = '';
    document.getElementById('adjudication_date').value = '';
    document.getElementById('type').value = '';
    document.getElementById('search').value = '';
    
    // Submit form to reset filters
    document.getElementById('filterForm').submit();
}

// Auto-submit form when filters change (with debounce for search)
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const yearSelect = document.getElementById('year');
    const adjudicationDateInput = document.getElementById('adjudication_date');
    const typeSelect = document.getElementById('type');
    
    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Auto-submit on search (with debounce)
    if (searchInput) {
        const debouncedSearch = debounce(function() {
            document.getElementById('filterForm').submit();
        }, 500);
        
        searchInput.addEventListener('input', debouncedSearch);
    }
    
    // Auto-submit on other filter changes
    if (yearSelect) {
        yearSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    if (adjudicationDateInput) {
        adjudicationDateInput.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    // Initialize column filters
    initializeColumnFilters();
});

// Column filter functionality
let columnFilters = {};
let filterDropdowns = {};

function initializeColumnFilters() {
    // Handle filter button clicks
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const columnIndex = parseInt(this.getAttribute('data-column'));
            const th = this.closest('th');
            const columnName = th.querySelector('span').textContent.trim();
            
            // Close other dropdowns
            document.querySelectorAll('.column-filter-dropdown').forEach(dd => {
                dd.classList.remove('show');
            });
            
            // Create or show dropdown
            let dropdown = filterDropdowns[columnIndex];
            if (!dropdown) {
                dropdown = createColumnFilterDropdown(columnIndex, columnName);
                filterDropdowns[columnIndex] = dropdown;
                document.body.appendChild(dropdown);
            }
            
            // Position dropdown
            const thRect = th.getBoundingClientRect();
            dropdown.style.top = (thRect.bottom + window.scrollY + 5) + 'px';
            dropdown.style.left = (thRect.left + window.scrollX) + 'px';
            dropdown.style.width = Math.max(250, thRect.width) + 'px';
            
            // Show dropdown
            dropdown.classList.add('show');
            
            // Update checkboxes based on current filter
            const selectedValues = columnFilters[columnIndex] || [];
            dropdown.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = selectedValues.includes(checkbox.value);
            });
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.filter-btn, .column-filter-dropdown')) {
            document.querySelectorAll('.column-filter-dropdown').forEach(dd => {
                dd.classList.remove('show');
            });
        }
    });
}

function createColumnFilterDropdown(columnIndex, columnName) {
    const dropdown = document.createElement('div');
    dropdown.className = 'column-filter-dropdown';
    dropdown.innerHTML = `
        <div class="filter-dropdown-header">
            <span class="font-semibold">Filtrer: ${columnName}</span>
            <button type="button" class="close-filter" onclick="this.closest('.column-filter-dropdown').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="filter-dropdown-content">
            <input type="text" 
                   class="filter-search-input" 
                   placeholder="Rechercher..." 
                   onkeyup="filterDropdownOptions(this, ${columnIndex})">
            <div class="filter-options" id="filter-options-${columnIndex}">
                <!-- Options will be populated dynamically -->
            </div>
        </div>
        <div class="filter-dropdown-footer">
            <button type="button" class="btn-clear-filter" onclick="clearColumnFilter(${columnIndex})">
                <i class="fas fa-times"></i> Effacer
            </button>
            <button type="button" class="btn-apply-filter" onclick="applyColumnFilter(${columnIndex})">
                <i class="fas fa-check"></i> Appliquer
            </button>
        </div>
    `;
    
    // Get unique values from column
    const table = document.getElementById('articlesTable');
    const rows = table.querySelectorAll('tbody tr');
    const values = new Set();
    
    rows.forEach(row => {
        const cell = row.cells[columnIndex];
        if (cell) {
            const text = cell.textContent.trim();
            if (text && text !== '-') {
                values.add(text);
            }
        }
    });
    
    // Create checkboxes for each unique value
    const optionsContainer = dropdown.querySelector('.filter-options');
    const sortedValues = Array.from(values).sort();
    
    if (sortedValues.length === 0) {
        optionsContainer.innerHTML = '<p class="text-gray-500 text-sm p-2">Aucune valeur disponible</p>';
    } else {
        sortedValues.forEach(value => {
            const label = document.createElement('label');
            label.className = 'filter-option';
            label.innerHTML = `
                <input type="checkbox" value="${value.replace(/"/g, '&quot;').replace(/'/g, '&#39;')}">
                <span>${value}</span>
            `;
            optionsContainer.appendChild(label);
        });
    }
    
    return dropdown;
}

function filterDropdownOptions(inputEl, columnIndex) {
    const filter = inputEl.value.toLowerCase();
    const dropdown = filterDropdowns[columnIndex];
    if (!dropdown) return;
    
    const options = dropdown.querySelectorAll('.filter-option');
    options.forEach(option => {
        const text = option.textContent.toLowerCase();
        const match = text.indexOf(filter) !== -1;
        option.style.display = match ? '' : 'none';
    });
}

function applyColumnFilter(columnIndex) {
    const dropdown = filterDropdowns[columnIndex];
    if (!dropdown) return;
    
    const checkboxes = dropdown.querySelectorAll('input[type="checkbox"]:checked');
    const selectedValues = Array.from(checkboxes).map(cb => cb.value);
    
    if (selectedValues.length === 0) {
        delete columnFilters[columnIndex];
    } else {
        columnFilters[columnIndex] = selectedValues;
    }
    
    // Apply filters to table
    filterTableByColumns();
    
    // Update button appearance
    const filterBtn = document.querySelector(`.filter-btn[data-column="${columnIndex}"]`);
    if (filterBtn) {
        if (selectedValues.length > 0) {
            filterBtn.classList.add('active');
            filterBtn.title = `${selectedValues.length} filtre(s) actif(s)`;
        } else {
            filterBtn.classList.remove('active');
            filterBtn.title = 'Filtrer';
        }
    }
    
    // Close dropdown
    dropdown.classList.remove('show');
}

function clearColumnFilter(columnIndex) {
    delete columnFilters[columnIndex];
    
    // Clear checkboxes
    const dropdown = filterDropdowns[columnIndex];
    if (dropdown) {
        dropdown.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
        });
    }
    
    // Apply filters
    filterTableByColumns();
    
    // Update button appearance
    const filterBtn = document.querySelector(`.filter-btn[data-column="${columnIndex}"]`);
    if (filterBtn) {
        filterBtn.classList.remove('active');
        filterBtn.title = 'Filtrer';
    }
}

function filterTableByColumns() {
    const table = document.getElementById('articlesTable');
    const rows = table.querySelectorAll('tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        let showRow = true;
        
        // Check each column filter
        Object.keys(columnFilters).forEach(columnIndex => {
            const selectedValues = columnFilters[columnIndex];
            if (selectedValues && selectedValues.length > 0) {
                const cell = row.cells[parseInt(columnIndex)];
                if (cell) {
                    const cellText = cell.textContent.trim();
                    if (!selectedValues.includes(cellText)) {
                        showRow = false;
                    }
                }
            }
        });
        
        row.style.display = showRow ? '' : 'none';
        if (showRow) visibleCount++;
    });
    
    // Update result count if element exists
    const resultInfo = document.querySelector('.text-gray-600');
    if (resultInfo && visibleCount > 0) {
        // You can update the count display here if needed
    }
}
</script>
@endpush
