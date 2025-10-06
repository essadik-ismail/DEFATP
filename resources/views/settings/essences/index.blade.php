@extends('layouts.app')

@section('title', 'Gestion des Essences')

@section('content')
    <!-- Modern Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-leaf text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                    Gestion des Essences
                </h1>
                <p class="text-gray-600 text-lg mt-2">Administrez les essences forestières du système</p>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Import/Export Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20 mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-download text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Import/Export des Essences</h2>
                <p class="text-gray-600">Gérez les données des essences forestières</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-download text-green-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-green-900">Exporter</h3>
                        <p class="text-sm text-green-700">Télécharger au format Excel</p>
                    </div>
                </div>
                <a href="{{ route('settings.essences.export') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                    <i class="fas fa-download"></i>
                    <span>Exporter les Essences</span>
                </a>
            </div>
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-upload text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900">Importer</h3>
                        <p class="text-sm text-blue-700">Depuis un fichier Excel</p>
                    </div>
                </div>
                <a href="{{ route('excel.import.essences') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300">
                    <i class="fas fa-upload"></i>
                    <span>Importer des Essences</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Liste des Essences</h2>
                    <p class="text-gray-600">Gérez et consultez toutes les essences forestières</p>
                </div>
            </div>
            <a href="{{ route('settings.essences.create') }}" 
               class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-plus"></i>
                <span class="font-semibold">Nouvelle Essence</span>
            </a>
        </div>

        @if($essences->count() > 0)
            <!-- Search and Filter Section -->
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl p-6 border border-gray-200 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-gray-500 to-slate-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-search text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Recherche et Filtres</h3>
                </div>
                <form method="GET" action="{{ route('settings.essences') }}" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-group">
                            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-search text-blue-500 mr-1"></i>Rechercher
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       class="form-input w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       name="search" 
                                       id="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Nom de l'essence...">
                                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-check-circle text-orange-500 mr-1"></i>Statut
                            </label>
                            <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400" 
                                    name="status" id="status">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                                <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Supprimées</option>
                                <option value="recent" {{ request('status') == 'recent' ? 'selected' : '' }}>Récentes</option>
                            </select>
                        </div>
                        
                        <div class="form-group flex items-end">
                            <div class="flex gap-3 w-full">
                                <button type="submit" 
                                        class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-filter"></i>
                                    <span>Filtrer</span>
                                </button>
                                <button type="button" 
                                        onclick="clearFilters()"
                                        class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                                        title="Effacer les filtres">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            @php
                $headers = ['ID', "Nom de l\'Essence", 'Statut', 'Date de Création', 'Actions'];
                $rows = [];
            @endphp
            @foreach($essences as $essence)
                @php
                    $statusBadge = $essence->deleted_at
                        ? '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Supprimée</span>'
                        : '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Active</span>';
                    $nameCell = '<div class="flex items-center gap-3">'
                        . '<div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">'
                        . '<i class="fas fa-leaf text-green-600 text-sm"></i>'
                        . '</div>'
                        . '<span class="font-medium">' . e($essence->essence) . '</span>'
                        . '</div>';
                    $actionsHtml = '<div class="flex items-center gap-2">'
                        . '<a href="' . e(route('settings.essences.edit', $essence)) . '" class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-sm" title="Modifier">'
                        . '<i class="fas fa-edit text-sm"></i>'
                        . '</a>'
                        . '<form action="' . e(route('settings.essences.destroy', $essence)) . '" method="POST" class="inline" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer cette essence ?\')">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-sm" title="Supprimer">'
                        . '<i class="fas fa-trash text-sm"></i>'
                        . '</button>'
                        . '</form>'
                        . '</div>';
                    $rows[] = [
                        '<span class="badge bg-secondary">' . e($essence->id) . '</span>',
                        $nameCell,
                        $statusBadge,
                        '<small class="text-muted">' . e($essence->created_at?->format('d/m/Y') ?? 'N/A') . '</small>',
                        $actionsHtml,
                    ];
                @endphp
            @endforeach
            <x-data-table :headers="$headers" :rows="$rows" :pagination="$essences->appends(request()->query())->links()" />
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-leaf text-4xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune essence trouvée</h3>
                <p class="text-gray-600 mb-6">Commencez par ajouter une nouvelle essence forestière.</p>
                <a href="{{ route('settings.essences.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter une Essence</span>
                </a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    // Clear filters function
    function clearFilters() {
        document.getElementById('search').value = '';
        document.getElementById('status').value = '';
        document.getElementById('filterForm').submit();
    }

    // Auto-submit form when filters change
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const statusFilter = document.getElementById('status');
        
        if (searchInput) {
            const debouncedSearch = function() {
                setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 500);
            };
            
            searchInput.addEventListener('input', debouncedSearch);
        }
        
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }
    });
</script>
@endpush

@push('styles')
<style>
    .form-input {
        @apply w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400;
    }
    
    .table th {
        @apply px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider;
    }
    
    .table td {
        @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900;
    }
    
    .table tbody tr {
        @apply hover:bg-gray-50 transition-colors duration-200;
    }
    
    .pagination .page-link {
        @apply px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-all duration-200;
    }
    
    .pagination .page-item.active .page-link {
        @apply bg-blue-600 text-white border-blue-600;
    }
    
    .pagination .page-item.disabled .page-link {
        @apply text-gray-400 bg-gray-100 border-gray-200 cursor-not-allowed;
    }
</style>
@endpush 