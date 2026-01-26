@extends('layouts.app')

@section('title', 'Données des Entités - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item active">Données des entités</li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Simple Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-database text-emerald-600"></i>
            Données des Entités
        </h1>
        <p class="text-gray-600 mt-1">Gérez toutes les données de base du système</p>
    </div>

    <!-- Simple Tabs Container -->
    <div class="bg-white rounded-lg shadow">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex flex-wrap -mb-px" aria-label="Tabs">
                <a href="#" class="tab-link active group border-b-2 border-emerald-500 py-4 px-6 inline-flex items-center gap-2 font-medium text-sm" data-tab="essences">
                    <i class="fas fa-leaf text-emerald-600 group-hover:scale-110 transition-transform"></i>
                    <span class="text-emerald-600">Essences</span>
                </a>
                
                <a href="#" class="tab-link group border-b-2 border-transparent py-4 px-6 inline-flex items-center gap-2 font-medium text-sm hover:text-gray-700 hover:border-gray-300 transition-colors" data-tab="forets">
                    <i class="fas fa-tree text-gray-500 group-hover:text-blue-600 group-hover:scale-110 transition-all"></i>
                    <span class="text-gray-500 group-hover:text-gray-700">Forêts</span>
                </a>
                
                <a href="#" class="tab-link group border-b-2 border-transparent py-4 px-6 inline-flex items-center gap-2 font-medium text-sm hover:text-gray-700 hover:border-gray-300 transition-colors" data-tab="situations">
                    <i class="fas fa-map-marked-alt text-gray-500 group-hover:text-purple-600 group-hover:scale-110 transition-all"></i>
                    <span class="text-gray-500 group-hover:text-gray-700">Situations</span>
                </a>
                
                <a href="#" class="tab-link group border-b-2 border-transparent py-4 px-6 inline-flex items-center gap-2 font-medium text-sm hover:text-gray-700 hover:border-gray-300 transition-colors" data-tab="natures-coupe">
                    <i class="fas fa-cut text-gray-500 group-hover:text-orange-600 group-hover:scale-110 transition-all"></i>
                    <span class="text-gray-500 group-hover:text-gray-700">Natures</span>
                </a>
                
                <a href="#" class="tab-link group border-b-2 border-transparent py-4 px-6 inline-flex items-center gap-2 font-medium text-sm hover:text-gray-700 hover:border-gray-300 transition-colors" data-tab="vocations">
                    <i class="fas fa-briefcase text-gray-500 group-hover:text-rose-600 group-hover:scale-110 transition-all"></i>
                    <span class="text-gray-500 group-hover:text-gray-700">Vocations</span>
                </a>
                
                <a href="#" class="tab-link group border-b-2 border-transparent py-4 px-6 inline-flex items-center gap-2 font-medium text-sm hover:text-gray-700 hover:border-gray-300 transition-colors" data-tab="coperatives">
                    <i class="fas fa-users-cog text-gray-500 group-hover:text-cyan-600 group-hover:scale-110 transition-all"></i>
                    <span class="text-gray-500 group-hover:text-gray-700">Coopératives</span>
                </a>
                
                <a href="#" class="tab-link group border-b-2 border-transparent py-4 px-6 inline-flex items-center gap-2 font-medium text-sm hover:text-gray-700 hover:border-gray-300 transition-colors" data-tab="products">
                    <i class="fas fa-box text-gray-500 group-hover:text-indigo-600 group-hover:scale-110 transition-all"></i>
                    <span class="text-gray-500 group-hover:text-gray-700">Produits</span>
                </a>
                
                <a href="#" class="tab-link group border-b-2 border-transparent py-4 px-6 inline-flex items-center gap-2 font-medium text-sm hover:text-gray-700 hover:border-gray-300 transition-colors" data-tab="prestations">
                    <i class="fas fa-tasks text-gray-500 group-hover:text-sky-600 group-hover:scale-110 transition-all"></i>
                    <span class="text-gray-500 group-hover:text-gray-700">Prestations</span>
                </a>
            </nav>
        </div>
        
        <!-- Tab Content -->
        <div class="p-6">
            <div class="tab-content" id="entitiesTabContent">
                @include('entity-data.partials.essences-tab')
                @include('entity-data.partials.forets-tab')
                @include('entity-data.partials.situations-tab')
                @include('entity-data.partials.natures-coupe-tab')
                @include('entity-data.partials.vocations-tab')
                @include('entity-data.partials.coperatives-tab')
                @include('entity-data.partials.products-tab')
                @include('entity-data.partials.prestations-tab')
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function changePerPage(tab, perPage) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.set('tab', tab);
    currentUrl.searchParams.delete('page'); // Reset to first page
    window.location.href = currentUrl.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active classes from all tabs
            tabLinks.forEach(tab => {
                tab.classList.remove('active', 'border-emerald-500');
                tab.classList.add('border-transparent');
                const icon = tab.querySelector('i');
                const span = tab.querySelector('span');
                icon.classList.remove('text-emerald-600', 'text-blue-600', 'text-purple-600', 'text-orange-600', 'text-rose-600', 'text-cyan-600', 'text-indigo-600', 'text-sky-600');
                icon.classList.add('text-gray-500');
                span.classList.remove('text-emerald-600');
                span.classList.add('text-gray-500');
            });
            
            // Hide all panes
            tabPanes.forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active', 'border-emerald-500');
            this.classList.remove('border-transparent');
            const icon = this.querySelector('i');
            const span = this.querySelector('span');
            icon.classList.remove('text-gray-500');
            icon.classList.add('text-emerald-600');
            span.classList.remove('text-gray-500');
            span.classList.add('text-emerald-600');
            
            // Show target pane
            const targetPane = document.getElementById(targetTab);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
            
            // Initialize column filters for the active tab
            setTimeout(() => {
                initializeColumnFilters();
            }, 100);
        });
    });

    // Show tab from URL parameter or default to first
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
        const tabLink = document.querySelector(`[data-tab="${tabParam}"]`);
        if (tabLink) {
            tabLink.click();
        }
    } else {
        // Initialize column filters for default tab
        setTimeout(() => {
            initializeColumnFilters();
        }, 500);
    }
});

// Column filter functionality (same as articles.index)
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
    
    // Get unique values from column - find the active table
    const activeTab = document.querySelector('.tab-pane.show.active');
    if (!activeTab) return dropdown;
    
    const table = activeTab.querySelector('table');
    if (!table) return dropdown;
    
    const rows = table.querySelectorAll('tbody tr');
    const values = new Set();
    
    rows.forEach(row => {
        const cell = row.cells[columnIndex];
        if (cell) {
            const text = cell.textContent.trim();
            if (text && text !== '-' && !text.includes('Aucune')) {
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
    const activeTab = document.querySelector('.tab-pane.show.active');
    if (!activeTab) return;
    
    const table = activeTab.querySelector('table');
    if (!table) return;
    
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
}
</script>
@endpush

@push('styles')
<style>
/* Simple Tab Styles */
.tab-link {
    text-decoration: none;
    transition: all 0.2s ease;
}

.tab-link.active {
    border-bottom-color: #10b981 !important;
}

.tab-pane {
    display: none;
}

.tab-pane.show.active {
    display: block;
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Smooth scrollbar for tables */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #10b981;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #059669;
}

/* Column Filter Styles (from articles.index) */
.filter-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 2px 4px;
    transition: all 0.2s;
    border-radius: 4px;
}

.filter-btn:hover {
    background-color: rgba(0, 0, 0, 0.05);
    color: #059669;
}

.filter-btn.active {
    color: #059669;
    background-color: rgba(5, 150, 105, 0.1);
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
    background: linear-gradient(to right, #059669, #047857);
    color: white;
}

.btn-apply-filter:hover {
    background: linear-gradient(to right, #047857, #065f46);
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(5, 150, 105, 0.3);
}
</style>
@endpush

