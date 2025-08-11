@extends('layouts.app')

@section('title', 'Articles - Exploitation')

@section('page-actions')
    <div class="page-actions">
        <button class="btn-primary" onclick="window.location.href='{{ route('articles.create') }}'">
            <i class="fas fa-plus mr-2"></i>
            Nouvel Article
        </button>
        <button class="btn-secondary" onclick="window.location.href='{{ route('articles.export') }}'">
            <i class="fas fa-download mr-2"></i>
            Exporter
        </button>
    </div>
@endsection

@section('content')
    <!-- Enhanced Statistics Cards -->
    <div class="stats-grid mb-8">
        <div class="stat-card purple">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ $articles->total() }}</h3>
                    <p class="stat-label">Total Articles</p>
                </div>
            </div>
        </div>
        
        <div class="stat-card green">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ $articles->where('invendu', false)->count() }}</h3>
                    <p class="stat-label">Articles Vendus</p>
                </div>
            </div>
        </div>
        
        <div class="stat-card orange">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ $articles->where('invendu', true)->count() }}</h3>
                    <p class="stat-label">Articles Invendus</p>
                </div>
            </div>
        </div>
        
        <div class="stat-card blue">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ number_format($articles->sum('prix_vente'), 2) }} DH</h3>
                    <p class="stat-label">Valeur Totale</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filter Card -->
    <div class="dashboard-card mb-8">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-filter text-white"></i>
                </div>
                <div>
                    <h5 class="card-title">Filtres de Recherche</h5>
                    <p class="text-sm text-gray-600">Affinez votre recherche d'articles</p>
                </div>
            </div>
            <button class="collapse-toggle" onclick="toggleCollapse('filter-section')" id="filter-toggle">
                <i class="fas fa-chevron-down" id="filter-icon"></i>
            </button>
        </div>
        <div class="card-body collapse-content" id="filter-section">
            <form method="GET" action="{{ route('articles.index') }}" class="filter-form">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div class="form-group">
                        <label for="annee" class="form-label">Année</label>
                        <input type="number" name="annee" id="annee" 
                               value="{{ request('annee') }}" min="2000" max="2100" 
                               placeholder="Ex: 2024" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label for="foret_id" class="form-label">Forêt</label>
                        <select name="foret_id" id="foret_id" class="form-select">
                            <option value="">Toutes les forêts</option>
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ request('foret_id') == $foret->id ? 'selected' : '' }}>
                                    {{ $foret->foret }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="essence_id" class="form-label">Essence</label>
                        <select name="essence_id" id="essence_id" class="form-select">
                            <option value="">Toutes les essences</option>
                            @foreach($essences as $essence)
                                <option value="{{ $essence->id }}" {{ request('essence_id') == $essence->id ? 'selected' : '' }}>
                                    {{ $essence->essence }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="invendu" class="form-label">Statut</label>
                        <select name="invendu" id="invendu" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="0" {{ request('invendu') === '0' ? 'selected' : '' }}>Vendus</option>
                            <option value="1" {{ request('invendu') === '1' ? 'selected' : '' }}>Invendus</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="numero" class="form-label">Numéro</label>
                        <input type="text" name="numero" id="numero" 
                               value="{{ request('numero') }}" 
                               placeholder="Ex: 001, 002..." class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label for="sort" class="form-label">Tri</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Plus récents</option>
                            <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Plus anciens</option>
                            <option value="prix_desc" {{ request('sort') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            <option value="prix_asc" {{ request('sort') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="numero_asc" {{ request('sort') == 'numero_asc' ? 'selected' : '' }}>Numéro (A-Z)</option>
                            <option value="numero_desc" {{ request('sort') == 'numero_desc' ? 'selected' : '' }}>Numéro (Z-A)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="per_page" class="form-label">Affichage</label>
                        <select name="per_page" id="per_page" class="form-select">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 par page</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 par page</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 par page</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 par page</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 mt-6">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-search mr-2"></i>
                        Appliquer les filtres
                    </button>
                    <button type="button" class="btn-secondary" onclick="window.location.href='{{ route('articles.index') }}'">
                        <i class="fas fa-times mr-2"></i>
                        Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Import/Export Card -->
    <div class="dashboard-card mb-8">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-upload text-white"></i>
                </div>
                <div>
                    <h5 class="card-title">Import/Export</h5>
                    <p class="text-sm text-gray-600">Gérez vos données d'articles</p>
                </div>
            </div>
            <button class="collapse-toggle" onclick="toggleCollapse('import-export-section')" id="import-export-toggle">
                <i class="fas fa-chevron-down" id="import-export-icon"></i>
            </button>
        </div>
        <div class="card-body collapse-content" id="import-export-section">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="export-section">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-download text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Exporter les données</h4>
                    </div>
                    <p class="text-gray-600 mb-4">Téléchargez tous les articles au format Excel avec les filtres actuels appliqués</p>
                    <a href="{{ route('articles.export', request()->query()) }}" class="btn-primary">
                        <i class="fas fa-file-download mr-2"></i>
                        Exporter (.xlsx)
                    </a>
                </div>
                
                <div class="import-section">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-upload text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900">Importer des données</h4>
                    </div>
                    <p class="text-gray-600 mb-4">Importez des articles depuis un fichier Excel</p>
                    <form action="{{ route('articles.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
                        @csrf
                        <div class="flex items-center gap-4">
                            <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" required 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-cloud-upload-alt mr-2"></i>
                                Importer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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

    <!-- Data Table Card -->
    <div class="dashboard-card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-table text-white"></i>
                </div>
                <div>
                    <h5 class="card-title">Liste des Articles</h5>
                    <p class="text-sm text-gray-600">{{ $articles->total() }} article(s) trouvé(s)</p>
                </div>
            </div>
            <button class="collapse-toggle" onclick="toggleCollapse('datatable-section')" id="datatable-toggle">
                <i class="fas fa-chevron-down" id="datatable-icon"></i>
            </button>
        </div>
        <div class="card-body collapse-content" id="datatable-section">
            @if($articles->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Année</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forêt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Essence</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Vente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($articles as $article)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $article->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $article->annee }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $article->numero }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article->foret->foret ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $article->essence->essence ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ number_format($article->prix_vente, 2) }} DH
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($article->invendu)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Invendu
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>
                                                Vendu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('articles.show', $article) }}" class="text-blue-600 hover:text-blue-900" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('articles.edit', $article) }}" class="text-indigo-600 hover:text-indigo-900" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $articles->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Aucun article trouvé</p>
                    <p class="text-gray-400 text-sm">Essayez de modifier vos filtres ou ajoutez un nouvel article</p>
                </div>
            @endif
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

    .dashboard-card {
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

    .dashboard-card:hover {
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

    .btn-secondary {
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

    .btn-secondary:hover {
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
    function toggleCollapse(sectionId) {
        const content = document.getElementById(sectionId);
        const toggle = document.getElementById(sectionId.replace('-section', '-toggle'));
        const icon = document.getElementById(sectionId.replace('-section', '-icon'));
        
        if (content.classList.contains('collapsed')) {
            // Expand
            content.classList.remove('collapsed');
            toggle.classList.remove('collapsed');
            icon.style.transform = 'rotate(0deg)';
        } else {
            // Collapse
            content.classList.add('collapsed');
            toggle.classList.add('collapsed');
            icon.style.transform = 'rotate(-90deg)';
        }
    }

    // Initialize collapse state from localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const sections = ['filter-section', 'import-export-section', 'datatable-section'];
        
        sections.forEach(sectionId => {
            const isCollapsed = localStorage.getItem(`collapse_${sectionId}`) === 'true';
            if (isCollapsed) {
                const content = document.getElementById(sectionId);
                const toggle = document.getElementById(sectionId.replace('-section', '-toggle'));
                const icon = document.getElementById(sectionId.replace('-section', '-icon'));
                
                content.classList.add('collapsed');
                toggle.classList.add('collapsed');
                icon.style.transform = 'rotate(-90deg)';
            }
        });
    });

    // Save collapse state to localStorage
    function toggleCollapse(sectionId) {
        const content = document.getElementById(sectionId);
        const toggle = document.getElementById(sectionId.replace('-section', '-toggle'));
        const icon = document.getElementById(sectionId.replace('-section', '-icon'));
        
        if (content.classList.contains('collapsed')) {
            // Expand
            content.classList.remove('collapsed');
            toggle.classList.remove('collapsed');
            icon.style.transform = 'rotate(0deg)';
            localStorage.setItem(`collapse_${sectionId}`, 'false');
        } else {
            // Collapse
            content.classList.add('collapsed');
            toggle.classList.add('collapsed');
            icon.style.transform = 'rotate(-90deg)';
            localStorage.setItem(`collapse_${sectionId}`, 'true');
        }
    }
</script>
@endpush 