@extends('layouts.app')

@section('title', 'Données des Entités - DEFATP')

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
    }
});
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
</style>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">

<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

<script>
// Auto-initialize DataTables for entity-data tabs
$(document).ready(function() {
    // Helper function to initialize a DataTable with empty state handling
    function initializeDataTable(tableId) {
        if (!tableId || $.fn.DataTable.isDataTable('#' + tableId)) {
            return;
        }
        
        const table = $('#' + tableId);
        const tbodyRows = table.find('tbody tr');
        
        // Check if table has data rows (not just empty state with colspan)
        const hasDataRows = tbodyRows.length > 0 && 
                           !tbodyRows.first().find('td[colspan]').length;
        
        // If table only has empty state with colspan, remove it
        // DataTables will show its own empty message
        if (!hasDataRows && tbodyRows.length > 0) {
            const emptyRow = tbodyRows.first();
            if (emptyRow.find('td[colspan]').length) {
                emptyRow.remove();
            }
        }
        
        // Initialize DataTables
        $('#' + tableId).DataTable({
            processing: false,
            serverSide: false,
            order: [[0, 'desc']],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Tous']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json',
                emptyTable: 'Aucune donnée disponible dans le tableau'
            }
        });
        
        if (typeof ExcelFilters !== 'undefined') {
            ExcelFilters.init(tableId);
        }
    }
    
    // Initialize tables when tabs are shown
    $('.tab-link').on('click', function() {
        var tabId = $(this).data('tab');
        setTimeout(function() {
            var tableId = getTableIdForTab(tabId);
            initializeDataTable(tableId);
        }, 300);
    });
    
    // Also initialize active tab on page load
    var activeTab = $('.tab-link.active').data('tab');
    if (activeTab) {
        setTimeout(function() {
            var tableId = getTableIdForTab(activeTab);
            initializeDataTable(tableId);
        }, 500);
    }
    
    function getTableIdForTab(tabId) {
        var tableIdMap = {
            'essences': 'essencesTable',
            'forets': 'foretsTable',
            'situations': 'situationsTable',
            'vocations': 'vocationsTable',
            'coperatives': 'coperativesTable',
            'avenants': 'avenantsTable',
            'natures-coupe': 'naturesCoupeTable',
            'exploitants': 'entityExploitantsTable',
            'products': 'productsTable',
            'prestations': 'prestationsTable'
        };
        return tableIdMap[tabId];
    }
});
</script>
@endpush

