@extends('layouts.app')

@section('title', 'Données des Entités - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-database text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                    Données des Entités
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gérez toutes les données de base du système</p>
            </div>
        </div>
    </div>

    <!-- Entity Data Management Section -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <!-- Modern Tabs Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50/80 to-gray-50/80 backdrop-blur-sm border-b border-white/20 p-2">
                    <div class="flex flex-wrap gap-2">
                        <!-- Articles Entities Tabs -->
                        <button class="tab-button active group" data-tab="essences">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-leaf text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Essences</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Articles</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button group" data-tab="forets">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-tree text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Forêts</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Articles</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button group" data-tab="localisations">
                            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-map-marker-alt text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Localisations</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Articles</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button group" data-tab="situations">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-building text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Situations</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Articles</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button group" data-tab="natures-coupe">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-cut text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Natures</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Articles</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <!-- Contracts Entities Tabs -->
                        <button class="tab-button group" data-tab="vocations">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-briefcase text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Vocations</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Contrats</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <button class="tab-button group" data-tab="coperatives">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-users-cog text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Coopératives</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Contrats</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <!-- ODF Entities Tab -->
                        <button class="tab-button group" data-tab="odf-entites">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">ODF Entités</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">ODF</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <!-- Products Tab -->
                        <button class="tab-button group" data-tab="products">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-box text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Produits</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Articles/Contrats</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                        <!-- Prestations Tab -->
                        <button class="tab-button group" data-tab="prestations">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-sky-600 rounded-xl flex items-center justify-center mr-3 shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-tasks text-white text-sm"></i>
                            </div>
                            <div class="text-left">
                                <span class="block font-semibold">Prestations</span>
                                <span class="text-xs text-gray-500 group-hover:text-gray-700">Contrats/Avenants</span>
                            </div>
                            <div class="tab-indicator"></div>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="tab-content" id="entitiesTabContent">
                        @include('entity-data.partials.essences-tab')
                        @include('entity-data.partials.forets-tab')
                        @include('entity-data.partials.localisations-tab')
                        @include('entity-data.partials.situations-tab')
                        @include('entity-data.partials.natures-coupe-tab')
                        @include('entity-data.partials.vocations-tab')
                        @include('entity-data.partials.coperatives-tab')
                        @include('entity-data.partials.odf-entites-tab')
                        @include('entity-data.partials.products-tab')
                        @include('entity-data.partials.prestations-tab')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')z
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            tabPanes.forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Add active class to clicked button and corresponding pane
            this.classList.add('active');
            const targetPane = document.getElementById(targetTab);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
        });
    });

    // Show the tab from URL parameter or default to first
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam) {
        const tabButton = document.querySelector(`[data-tab="${tabParam}"]`);
        if (tabButton) {
            tabButton.click();
        }
    }
});

// Function to handle localisation import
function importLocalisations(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const formData = new FormData();
        formData.append('file', file);
        
        fetch('{{ route("settings.localisations.import") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Import réussi ! ' + data.message);
                location.reload();
            } else {
                alert('Erreur lors de l\'import : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'import');
        });
        
        // Reset input
        input.value = '';
    }
}
</script>
@endpush

@push('styles')
<style>
.tab-button {
    position: relative;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: white;
    border: 1px solid #e5e7eb;
    cursor: pointer;
}

.tab-button:hover {
    border-color: #d1d5db;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    transform: scale(1.05);
}

.tab-button.active {
    background: linear-gradient(to right, #f0fdf4, #d1fae5);
    border-color: #86efac;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.tab-pane {
    display: none;
}

.tab-pane.show.active {
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
// Auto-initialize DataTables for entity-data tabs
$(document).ready(function() {
    // Initialize tables when tabs are shown
    $('.tab-button').on('click', function() {
        var tabId = $(this).data('tab');
        setTimeout(function() {
            var tableId = getTableIdForTab(tabId);
            if (tableId && !$.fn.DataTable.isDataTable('#' + tableId)) {
                var table = $('#' + tableId).DataTable({
                    processing: false,
                    serverSide: false,
                    order: [[0, 'desc']],
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Tous']],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
                    }
                });
                
                if (typeof ExcelFilters !== 'undefined') {
                    ExcelFilters.init(tableId);
                }
            }
        }, 300);
    });
    
    // Also initialize active tab on page load
    var activeTab = $('.tab-button.active').data('tab');
    if (activeTab) {
        setTimeout(function() {
            var tableId = getTableIdForTab(activeTab);
            if (tableId && !$.fn.DataTable.isDataTable('#' + tableId)) {
                var table = $('#' + tableId).DataTable({
                    processing: false,
                    serverSide: false,
                    order: [[0, 'desc']],
                    pageLength: 25,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Tous']],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
                    }
                });
                
                if (typeof ExcelFilters !== 'undefined') {
                    ExcelFilters.init(tableId);
                }
            }
        }, 500);
    }
    
    function getTableIdForTab(tabId) {
        var tableIdMap = {
            'essences': 'essencesTable',
            'forets': 'foretsTable',
            'localisations': 'localisationsTable',
            'situations': 'situationsTable',
            'vocations': 'vocationsTable',
            'coperatives': 'coperativesTable',
            'avenants': 'avenantsTable',
            'natures-coupe': 'naturesCoupeTable',
            'odf-entites': 'odfEntitesTable',
            'exploitants': 'entityExploitantsTable',
            'products': 'productsTable',
            'prestations': 'prestationsTable'
        };
        return tableIdMap[tabId];
    }
});
</script>
@endpush

