@extends('layouts.app')

@section('title', 'Détails du Contrat - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('contracts.index') }}">Contrats</a></li>
<li class="bc-item active">Détail</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; padding:1.25rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-700 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-handshake text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                                Détails du Contrat #{{ $contract->contarct ?? $contract->id }}
                            </h1>
                            <p class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-green-500"></i>
                                Créé le {{ $contract->created_at ? $contract->created_at->format('d/m/Y à H:i') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('contracts.avenants.create', ['contract_id' => $contract->id]) }}" 
                           class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-plus"></i>
                            <span>Créer un Avenant</span>
                        </a>
                        <a href="{{ route('entity-data.index', ['tab' => 'coperatives']) }}" 
                           class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-arrow-left"></i>
                            <span>Retour</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-8">
            
            <!-- Section 1: Informations Générales -->
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-info-circle"></i>
                        Informations Générales
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-green-500 mr-2"></i>Année
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($contract->annee)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ $contract->annee }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Non spécifiée</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file-contract text-indigo-500 mr-2"></i>Contrat
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->contarct ?? 'Non spécifié' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>Localisation
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($contract->localisation)
                                    {{ $contract->localisation->CODE }} - {{ $contract->localisation->DRANEF }}
                                @else
                                    <span class="text-gray-500">Non spécifiée</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-building text-purple-500 mr-2"></i>Situation Administrative
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($contract->situationAdministrative)
                                    {{ $contract->situationAdministrative->commune }} - {{ $contract->situationAdministrative->province }}
                                @else
                                    <span class="text-gray-500">Non spécifiée</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-leaf text-green-500 mr-2"></i>Essences
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($contract->essences && $contract->essences->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($contract->essences as $essence)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                {{ $essence->essence }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">Aucune essence spécifiée</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tree text-green-500 mr-2"></i>Forêts
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($contract->forets && $contract->forets->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($contract->forets as $foret)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                {{ $foret->foret }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">Aucune forêt spécifiée</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-users text-indigo-500 mr-2"></i>Coopérative
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($contract->coperative)
                                    {{ $contract->coperative->nom }}
                                @else
                                    <span class="text-gray-500">Non spécifiée</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-ruler-combined text-orange-500 mr-2"></i>Superficie
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->superficie ?? 'Non spécifiée' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Prestations -->
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-info-circle"></i>
                        Prestations
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-shield-alt text-blue-500 mr-2"></i>Gardiennage
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->gardiennage ?? 'Non spécifié' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-fire text-red-500 mr-2"></i>Prévention contre les Incendies
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->prevention_contre_les_incendies ?? 'Non spécifié' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2.5: Valeurs Financières -->
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-coins"></i>
                        Valeurs Financières
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-box text-purple-500 mr-2"></i>Valeurs des Produits
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->valeurs_des_produits ?? 'Non spécifié' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tasks text-blue-500 mr-2"></i>Valeur des Prestations
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->valeur_des_prestations ?? 'Non spécifié' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-coins text-yellow-500 mr-2"></i>Redevances
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->redevances ?? 'Non spécifié' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-receipt text-red-500 mr-2"></i>Taxes
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->taxes ?? 'Non spécifié' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file-alt text-purple-500 mr-2"></i>Total contract
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->total_avenant ?? 'Non spécifié' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Produits du Contrat -->
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-boxes"></i>
                        Produits du Contrat
                    </h2>
                </div>
                <div class="p-6">
                    @if($contract->products && $contract->products->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($contract->products as $product)
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-bold text-gray-800">{{ $product->name }}</h4>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                            <i class="fas fa-hashtag mr-1"></i>
                                            {{ $product->pivot->quantity ?? $product->quantity ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-xl p-8 text-center border border-gray-200">
                            <i class="fas fa-box text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600 font-medium">Aucun produit associé</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section 3.6: Prestations du Contrat -->
            @if($contract->prestations && $contract->prestations->count() > 0)
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-tasks"></i>
                        Prestations du Contrat
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($contract->prestations as $prestation)
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-bold text-gray-800">{{ $prestation->name }}</h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        <i class="fas fa-hashtag mr-1"></i>
                                        {{ $prestation->pivot->quantity ?? $prestation->quantity }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Section 4: Résiliation -->
            @if($contract->resiliation || $contract->date_resiliation)
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-ban"></i>
                        Résiliation
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-check-circle text-red-500 mr-2"></i>Statut de Résiliation
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($contract->resiliation)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-ban mr-2"></i>
                                        Résilié
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-2"></i>
                                        Actif
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($contract->date_resiliation)
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-red-500 mr-2"></i>Date de Résiliation
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ \Carbon\Carbon::parse($contract->date_resiliation)->format('d/m/Y') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Section 5: Données des Entités -->
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-database"></i>
                        Données des Entités
                    </h2>
                </div>
                <div class="p-6">
                    <!-- Essences -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-leaf text-green-600"></i>
                            Essences
                        </h3>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-tree text-green-500 mr-2"></i>Essences
                                    </label>
                                    <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                        @if($contract->essences && $contract->essences->count() > 0)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($contract->essences as $essence)
                                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                                        <i class="fas fa-leaf mr-2"></i>
                                                        {{ $essence->essence }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-500">Aucune essence spécifiée</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>Informations
                                    </label>
                                    <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                        @if($contract->essences && $contract->essences->count() > 0)
                                            <p class="text-sm">{{ $contract->essences->count() }} essence(s) associée(s) au contrat</p>
                                            @if($contract->essences->first()->created_at)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Première essence créée: {{ $contract->essences->first()->created_at->format('d/m/Y') }}
                                                </p>
                                            @endif
                                        @else
                                            <span class="text-gray-500">Aucune information disponible</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Avenants -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-file-contract text-indigo-600"></i>
                                Avenants
                            </h3>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                    {{ ($avenants ?? collect())->count() }} avenant(s) trouvé(s)
                                </span>
                                <a href="{{ route('contracts.avenants.create', ['contract_id' => $contract->id]) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-plus"></i>
                                    <span>Nouvel Avenant</span>
                                </a>
                            </div>
                        </div>
                        
                        @if(($avenants ?? collect())->count() > 0)
                            <div class="space-y-4">
                                @foreach($avenants as $avenant)
                                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200 hover:shadow-lg transition-shadow">
                                        <div class="flex items-start justify-between mb-4">
                                            <div>
                                                <h4 class="text-md font-bold text-gray-800 flex items-center gap-2">
                                                    <i class="fas fa-file-alt text-indigo-600"></i>
                                                    Avenant #{{ $avenant->id }}
                                                </h4>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    Date: {{ $avenant->date ? $avenant->date->format('d/m/Y') : 'N/A' }}
                                                    | Année: {{ $avenant->annee }}
                                                </p>
                                            </div>
                                            @if($avenant->total_avenant)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800">
                                                    {{ number_format($avenant->total_avenant, 2) }} DH
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                            @if($avenant->superficie)
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Superficie</label>
                                                    <div class="text-sm font-medium text-gray-800">{{ number_format($avenant->superficie, 2) }}</div>
                                                </div>
                                            @endif
                                            @if($avenant->gardiennage)
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Gardiennage</label>
                                                    <div class="text-sm font-medium text-gray-800">{{ number_format($avenant->gardiennage, 2) }}</div>
                                                </div>
                                            @endif
                                            @if($avenant->prevention_incendies)
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Prévention Incendies</label>
                                                    <div class="text-sm font-medium text-gray-800">{{ number_format($avenant->prevention_incendies, 2) }}</div>
                                                </div>
                                            @endif
                                            @if($avenant->redevances)
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Redevances</label>
                                                    <div class="text-sm font-medium text-gray-800">{{ number_format($avenant->redevances, 2) }}</div>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($avenant->coperative)
                                            <div class="mt-4 pt-4 border-t border-indigo-200">
                                                <label class="block text-xs font-semibold text-gray-600 mb-1">Coopérative</label>
                                                <div class="text-sm font-medium text-gray-800">
                                                    {{ $avenant->coperative->nom ?? 'N/A' }}
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($avenant->products && $avenant->products->count() > 0)
                                            <div class="mt-4 pt-4 border-t border-indigo-200">
                                                <label class="block text-xs font-semibold text-gray-600 mb-2">Produits</label>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($avenant->products as $product)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                            <i class="fas fa-box mr-1"></i>
                                                            {{ $product->name }} ({{ $product->pivot->quantity ?? $product->quantity ?? 0 }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($avenant->prestations && $avenant->prestations->count() > 0)
                                            <div class="mt-4 pt-4 border-t border-indigo-200">
                                                <label class="block text-xs font-semibold text-gray-600 mb-2">Prestations</label>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($avenant->prestations as $prestation)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-tasks mr-1"></i>
                                                            {{ $prestation->name }} ({{ $prestation->quantity }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-8 text-center border border-gray-200">
                                <i class="fas fa-file-contract text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-600 font-medium">Aucun avenant trouvé pour ce contrat</p>
                                <p class="text-sm text-gray-500 mt-2">Les avenants sont liés au contrat #{{ $contract->id }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
