@extends('layouts.app')

@section('title', 'Données Financières - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #7c3aed, #6366f1);">
                <i class="fas fa-chart-line text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #7c3aed, #6366f1); -webkit-background-clip: text; background-clip: text;">
                    Données Financières
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gérez les données financières et budgétaires</p>
            </div>
        </div>
    </div>

    <!-- Financial Data Management Section -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <!-- Modern Tabs Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50/80 to-gray-50/80 backdrop-blur-sm border-b border-white/20 p-2">
                    <div class="flex flex-wrap gap-2">
                        <button class="tab-button {{ $tab === 'province-annual-shares' ? 'active' : '' }} group" data-tab="province-annual-shares">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-map-marked-alt text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Parts Annuelles</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Provinces</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button {{ $tab === 'regional-budgets' ? 'active' : '' }} group" data-tab="regional-budgets">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-coins text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Budgets Régionaux</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Régions</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button {{ $tab === 'monthly-revenues' ? 'active' : '' }} group" data-tab="monthly-revenues">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-calendar-alt text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Revenus Mensuels</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Mensuel</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button {{ $tab === 'national-summaries' ? 'active' : '' }} group" data-tab="national-summaries">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-flag text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Résumés Nationaux</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">National</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="tab-content" id="financialTabContent">
                        @include('financial-data.partials.province-annual-shares-tab')
                        @include('financial-data.partials.regional-budgets-tab')
                        @include('financial-data.partials.monthly-revenues-tab')
                        @include('financial-data.partials.national-summaries-tab')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tab-button {
    @apply relative px-4 py-3 rounded-xl transition-all duration-300 flex items-center gap-3 text-gray-700 hover:bg-white/60 hover:shadow-md;
    border: 1px solid transparent;
}

.tab-button.active {
    @apply bg-white shadow-lg border-purple-200;
    background: linear-gradient(to right, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
}

.tab-button.active .tab-indicator {
    @apply absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-t-full;
}

.tab-content .tab-pane {
    display: none;
}

.tab-content .tab-pane.active {
    display: block;
    animation: fadeIn 0.3s ease-in;
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
</style>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">

<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

<script>
// Tab switching functionality
$(document).ready(function() {
    // Handle tab clicks
    $('.tab-button').on('click', function() {
        const tabId = $(this).data('tab');
        
        // Update active state
        $('.tab-button').removeClass('active');
        $(this).addClass('active');
        
        // Update URL without reload
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.history.pushState({}, '', url);
        
        // Show/hide tab panes
        $('.tab-pane').removeClass('active show');
        $('#' + tabId).addClass('active show');
        
        // Initialize DataTable for the active tab
        setTimeout(function() {
            initializeDataTable(tabId);
        }, 300);
    });
    
    // Initialize DataTable for active tab on page load
    const activeTab = '{{ $tab }}' || 'province-annual-shares';
    setTimeout(function() {
        initializeDataTable(activeTab);
    }, 500);
    
    function initializeDataTable(tabId) {
        const tableIdMap = {
            'province-annual-shares': 'provinceAnnualSharesTable',
            'regional-budgets': 'regionalBudgetsTable',
            'monthly-revenues': 'monthlyRevenuesTable',
            'national-summaries': 'nationalSummariesTable'
        };
        
        const tableId = tableIdMap[tabId];
        if (tableId && !$.fn.DataTable.isDataTable('#' + tableId)) {
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
            
            // Initialize DataTables for all cases
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
        }
    }
});
</script>
@endsection

