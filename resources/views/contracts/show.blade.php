@extends('layouts.app')

@section('title', 'Détails du Contrat - DEFATP')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6">
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
                        <a href="{{ route('contracts.edit', $contract) }}" 
                           class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-edit"></i>
                            <span>Modifier</span>
                        </a>
                        <a href="{{ route('contracts.index') }}" 
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
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
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
                                <i class="fas fa-leaf text-green-500 mr-2"></i>Espèces
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($contract->especes && $contract->especes->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($contract->especes as $espece)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                {{ $espece->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">Aucune espèce spécifiée</span>
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

            <!-- Section 2: Informations Complémentaires -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-info-circle"></i>
                        Informations Complémentaires
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
                                <i class="fas fa-cut text-green-500 mr-2"></i>Elagage
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->elagage ?? 'Non spécifié' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tree text-emerald-500 mr-2"></i>Eclaircie
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->eclaircie ?? 'Non spécifié' }}
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
                                <i class="fas fa-file-alt text-purple-500 mr-2"></i>Total Avenant
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->total_avenant ?? 'Non spécifié' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Produits -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-boxes"></i>
                        Produits
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">BO (m³)</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->bo_m3 ?? '-' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">BI (m³)</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->bi_m3 ?? '-' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">BF (st)</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->bf_st ?? '-' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanin (t)</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->tanin_t ?? '-' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Fleur Acacia (t)</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->fleur_acacia_t ?? '-' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Caroube (t)</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->caroube_t ?? '-' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Romarin (t)</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->romarin_t ?? '-' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">PS (t)</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $contract->ps_t ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Données des Entités -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-database"></i>
                        Données des Entités
                    </h2>
                </div>
                <div class="p-6">
                    <!-- Espèces -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-leaf text-green-600"></i>
                            Espèces
                        </h3>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-tree text-green-500 mr-2"></i>Espèces
                                    </label>
                                    <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                        @if($contract->especes && $contract->especes->count() > 0)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($contract->especes as $espece)
                                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                                        <i class="fas fa-leaf mr-2"></i>
                                                        {{ $espece->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-500">Aucune espèce spécifiée</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>Informations
                                    </label>
                                    <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                        @if($contract->especes && $contract->especes->count() > 0)
                                            <p class="text-sm">{{ $contract->especes->count() }} espèce(s) associée(s) au contrat</p>
                                            @if($contract->especes->first()->created_at)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Première espèce créée: {{ $contract->especes->first()->created_at->format('d/m/Y') }}
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
                            <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                {{ count($avenants ?? []) }} avenant(s) trouvé(s)
                            </span>
                        </div>
                        
                        @if(isset($avenants) && $avenants->count() > 0)
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
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-8 text-center border border-gray-200">
                                <i class="fas fa-file-contract text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-600 font-medium">Aucun avenant trouvé pour cette année</p>
                                <p class="text-sm text-gray-500 mt-2">Les avenants sont liés par l'année du contrat ({{ $contract->annee }})</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
