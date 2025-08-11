@extends('layouts.app')

@section('title', 'Nature de Coupes')

@section('page-actions')
    <div class="flex items-center gap-3">
        <button onclick="showCreateForm()" class="btn-primary">
            <i class="fas fa-plus mr-2 text-base"></i>
            Nouvelle Nature de Coupe
        </button>
        <!-- <button onclick="toggleImportExport()" class="btn-outline">
            <i class="fas fa-download mr-2 text-base"></i>
            Import/Export
        </button> -->
    </div>
@endsection

@section('content')
    <!-- Enhanced Statistics Cards -->
    <div class="stats-grid" style="margin-bottom: 1rem;">
        <div class="stat-card purple">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ $natureDeCoupes->total() }}</h3>
                    <p class="stat-label">Total Natures</p>
                </div>
            </div>
        </div>
        
        <div class="stat-card green">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ $natureDeCoupes->where('deleted_at', null)->count() }}</h3>
                    <p class="stat-label">Natures Actives</p>
                </div>
            </div>
        </div>
        
        <div class="stat-card orange">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ $natureDeCoupes->where('created_at', '>=', now()->startOfMonth())->count() }}</h3>
                    <p class="stat-label">Ajoutées ce mois</p>
                </div>
            </div>
        </div>
        
        <div class="stat-card blue">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ $natureDeCoupes->unique('nature_de_coupe')->count() }}</h3>
                    <p class="stat-label">Types Uniques</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Form Section (Hidden by default) -->
    <div id="createFormSection" class="card" style="margin-bottom: 1rem;">
        <div class="card-header">
            <h5 class="card-title">Ajouter une Nouvelle Nature de Coupe</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.nature-de-coupes.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <div class="form-group">
                            <label for="new-nature" class="form-label"> <i class="fas fa-cut h-5 w-5 text-gray-400"></i> Nature de Coupe
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                </div>
                                <input type="text" class="form-control" name="nature_de_coupe" id="new-nature" 
                                       value="{{ old('nature_de_coupe') }}" 
                                       class="form-input pl-10"
                                       placeholder="Ex: Coupe rase, Coupe sélective..." required>
                            </div>
                            @error('nature_de_coupe')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-end" style="margin-top:10px";>
                        <button type="submit" class="btn-primary w-full">
                            <i class="fas fa-check mr-2"></i>
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced Advanced Filter Section -->
    <div class="card" style="margin-bottom: 1rem;">
        <div class="card-header">
            <h5 class="card-title">Filtres Avancés</h5>
        </div>
        <div id="filter-content" class="card-body">
            <form method="GET" action="{{ route('settings.nature-de-coupes') }}" class="space-y-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3 col-6">
                        <label for="search" class="form-label">Recherche</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search h-5 w-5 text-gray-400"></i>
                            </div>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                   class="form-input pl-10"
                                   placeholder="Rechercher une nature de coupe...">
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-6">
                        <label for="status" class="form-label">Statut</label>
                        <select name="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                            <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Supprimées</option>
                            <option value="recent" {{ request('status') == 'recent' ? 'selected' : '' }}>Récentes</option>
                        </select>
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="date_from" class="form-label">Date de début</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="date_to" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}" class="form-input">
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="sort" class="form-label">Trier par</label>
                        <select name="sort" class="form-select">
                            <option value="nature_de_coupe" {{ request('sort') == 'nature_de_coupe' ? 'selected' : '' }}>Nature de coupe</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                            <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>Date de modification</option>
                            <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID</option>
                        </select>
                    </div>

                    <div class="col-md-3 col-6">
                        <label for="direction" class="form-label">Direction</label>
                        <select name="direction" class="form-select">
                            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Croissant</option>
                            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        </select>
                    </div>
                        
                    <div class="col-md-3 col-6">
                        <label for="per_page" class="form-label">Par page</label>
                        <select name="per_page" class="form-select">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div class="col-md-3 col-6 d-flex gap-3 align-items-center">
                        <button type="submit" class="btn-primary d-flex">
                            <i class="fas fa-filter mr-2 text-xs"></i>
                            Appliquer
                        </button>
                        <!-- <a href="{{ route('settings.nature-de-coupes') }}" class="btn-outline d-flex">
                            <i class="fas fa-times mr-2 text-xs"></i>
                            Réinitialiser
                        </a> -->
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 p-6 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-lg">Succès!</h3>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 p-6 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-lg">Erreur!</h3>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Enhanced DataTable -->
    <div class="card" style="margin-bottom: 1rem;">
        <div class="card-header">
            <h5 class="card-title">Liste des Natures de Coupe</h5>
            <p class="text-sm text-gray-600">{{ $natureDeCoupes->total() }} nature(s) trouvée(s)</p>
        </div>
        <div class="card-body" id="datatable-section">
            @if($natureDeCoupes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead class="table-header">
                            <tr>
                                <th class="table-header-cell">ID</th>
                                <th class="table-header-cell">Nature de Coupe</th>
                                <th class="table-header-cell">Créé le</th>
                                <th class="table-header-cell">Mis à jour le</th>
                                <th class="table-header-cell">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            @foreach($natureDeCoupes as $natureDeCoupe)
                                <tr class="table-row">
                                    <td class="table-cell">#{{ $natureDeCoupe->id }}</td>
                                    <td class="table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $natureDeCoupe->nature_de_coupe }}
                                        </span>
                                    </td>
                                    <td class="table-cell">{{ $natureDeCoupe->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="table-cell">{{ $natureDeCoupe->updated_at?->format('d/m/Y H:i') }}</td>
                                    <td class="table-cell">
                                        <div class="flex items-center gap-2">
                                                <i nclick="editNature({{ $natureDeCoupe->id }}, '{{ $natureDeCoupe->nature_de_coupe }}')" 
                                                title="Modifier" class="fas fa-edit text-base"></i>
                                                <i onclick="deleteNature({{ $natureDeCoupe->id }})" 
                                                title="Supprimer" class="fas fa-trash text-base"></i>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($natureDeCoupes->hasPages())
                    <div class="mt-6">
                        {{ $natureDeCoupes->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Aucune nature de coupe trouvée</p>
                    <p class="text-gray-400 text-sm">Essayez de modifier vos filtres ou ajoutez votre première nature de coupe</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Enhanced Import/Export Section -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Import/Export de Données</h5>
        </div>
        <div id="import-export-content" class="card-body hidden">
            <div class="d-flex mx-2">
                <!-- Export Section -->
                <div class="col-md-6 col-6 px-2 mb-4 md:mb-0">
                    <div class="h-full p-4 flex flex-col">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-blue-100 p-2 rounded-full">
                                <i class="fas fa-download text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-800">Exporter les données</h3>
                                <p class="text-sm text-gray-500">Téléchargez toutes les natures de coupe</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center text-sm text-blue-700">
                                <i class="fas fa-check mr-2"></i>
                                Format Excel (.xlsx)
                            </div>
                            <div class="flex items-center text-sm text-blue-700">
                                <i class="fas fa-check mr-2"></i>
                                Inclut toutes les colonnes
                            </div>
                            <div class="flex items-center text-sm text-blue-700">
                                <i class="fas fa-check mr-2"></i>
                                Mise en forme automatique
                            </div>
                        </div>

                        <a href="{{ route('settings.nature-de-coupes.export') }}" class="mt-4 btn-primary w-full text-center">
                            <i class="fas fa-download mr-2"></i>
                            Exporter (.xlsx)
                        </a>
                    </div>
                </div>

                <!-- Import Section -->
                <div class="col-md-6 col-6 px-2">
                    <div class="h-full flex flex-col">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-green-100 p-2 rounded-full">
                                <i class="fas fa-upload text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-800">Importer des données</h3>
                                <p class="text-sm text-gray-500">Importez depuis un fichier Excel</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('settings.nature-de-coupes.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="space-y-3">
                                <div class="flex items-center text-sm text-green-700">
                                    <i class="fas fa-check mr-2"></i>
                                    Formats supportés: .xlsx, .xls, .csv
                                </div>
                                <div class="flex items-center text-sm text-green-700">
                                    <i class="fas fa-check mr-2"></i>
                                    Taille max: 10 MB
                                </div>
                                <div class="flex items-center text-sm text-green-700">
                                    <i class="fas fa-check mr-2"></i>
                                    Validation automatique des données
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="relative">
                                    <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required 
                                           class="hidden" onchange="updateFileName(this)">
                                    <label for="file" class="flex items-center justify-center w-full px-4 py-2 border-2 border-dashed border-green-300 rounded-lg cursor-pointer hover:border-green-400 transition-colors duration-200">
                                        <div class="text-center">
                                            <i class="fas fa-upload mx-auto h-8 w-8 text-green-400"></i>
                                            <p class="mt-1 text-sm text-green-600">
                                                <span id="fileName" class="font-medium">Cliquez pour sélectionner un fichier</span>
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            
                                <button type="submit" class="w-full btn-primary py-2">
                                    <i class="fas fa-upload mr-2"></i>
                                    Importer le fichier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.1),
                0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--purple-color), var(--accent-color));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 
                0 16px 48px rgba(0, 0, 0, 0.15),
                0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card.purple::before {
            background: linear-gradient(90deg, #7c3aed, #8b5cf6);
        }

        .stat-card.blue::before {
            background: linear-gradient(90deg, #2563eb, #3b82f6);
        }

        .stat-card.orange::before {
            background: linear-gradient(90deg, #ea580c, #f97316);
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, #059669, #10b981);
        }

        .stat-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon i {
            color: white;
            font-size: 1.25rem;
        }

        .stat-info {
            flex: 1;
            min-width: 0;
        }

        .stat-number {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.25rem 0;
            line-height: 1;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin: 0;
            font-weight: 500;
        }

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
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .form-input, .form-select {
            padding: 0.75rem 1rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 124, 89, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.875rem;
            color: white;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(74, 124, 89, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            box-shadow: 0 8px 24px rgba(74, 124, 89, 0.4);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.875rem;
            color: var(--text-primary);
            cursor: pointer;
            backdrop-filter: blur(10px);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 1);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .icon-button {
            padding: 0.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .icon-button-primary {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .icon-button-primary:hover {
            background: rgba(59, 130, 246, 0.2);
            transform: scale(1.05);
        }

        .icon-button-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .icon-button-danger:hover {
            background: rgba(239, 68, 68, 0.2);
            transform: scale(1.05);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            background: rgba(248, 250, 252, 0.8);
        }

        .table-header-cell {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table-body {
            background: white;
        }

        .table-row {
            transition: background-color 0.2s ease;
        }

        .table-row:hover {
            background-color: rgba(248, 250, 252, 0.5);
        }

        .table-cell {
            padding: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .card-header {
                padding: 1rem 1.5rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Toggle filter section
        function toggleFilters() {
            const filterContent = document.getElementById('filter-content');
            const toggle = document.getElementById('filter-toggle');
            const icon = document.getElementById('filter-icon');
            
            if (filterContent.classList.contains('hidden')) {
                filterContent.classList.remove('hidden');
                toggle.classList.remove('collapsed');
                icon.style.transform = 'rotate(0deg)';
            } else {
                filterContent.classList.add('hidden');
                toggle.classList.add('collapsed');
                icon.style.transform = 'rotate(-90deg)';
            }
        }

        // Toggle import/export section
        function toggleImportExport() {
            const importExportContent = document.getElementById('import-export-content');
            const toggle = document.getElementById('import-export-toggle');
            const icon = document.getElementById('import-export-icon');
            
            if (importExportContent.classList.contains('hidden')) {
                importExportContent.classList.remove('hidden');
                toggle.classList.remove('collapsed');
                icon.style.transform = 'rotate(0deg)';
            } else {
                importExportContent.classList.add('hidden');
                toggle.classList.add('collapsed');
                icon.style.transform = 'rotate(-90deg)';
            }
        }

        // Show create form
        function showCreateForm() {
            document.getElementById('createFormSection').classList.remove('hidden');
        }

        // Hide create form
        function hideCreateForm() {
            document.getElementById('createFormSection').classList.add('hidden');
        }

        // Update file name display
        function updateFileName(input) {
            const fileName = input.files[0]?.name || 'Cliquez pour sélectionner un fichier';
            document.getElementById('fileName').textContent = fileName;
        }

        // Edit nature function
        function editNature(id, nature) {
            // Implement edit functionality
            console.log('Edit nature:', id, nature);
            // You can implement a modal or redirect to edit page
        }

        // Delete nature function
        function deleteNature(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette nature de coupe ?')) {
                // Implement delete functionality
                console.log('Delete nature:', id);
                // You can implement AJAX delete or redirect to delete route
            }
        }

        // Auto-hide success/error messages
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.querySelector('.bg-gradient-to-r.from-green-50');
            const errorMessage = document.querySelector('.bg-gradient-to-r.from-red-50');
            
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    setTimeout(() => successMessage.remove(), 300);
                }, 5000);
            }
            
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.opacity = '0';
                    setTimeout(() => errorMessage.remove(), 300);
                }, 5000);
            }
        });

        // Real-time search functionality
        let searchTimeout;
        document.querySelector('input[name="search"]').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    </script>
@endpush 