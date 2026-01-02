@extends('layouts.app')

@section('title', 'Détails de l\'Article - DEFATP')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-file-alt text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                                Détails de l'Article #{{ $article->numero ?? $article->id }}
                            </h1>
                            <p class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                Créé le {{ $article->created_at ? $article->created_at->format('d/m/Y à H:i') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('articles.edit', $article) }}" 
                           class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-edit"></i>
                            <span>Modifier</span>
                        </a>
                        <a href="{{ route('articles.index') }}" 
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
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-info-circle"></i>
                        Section 1: Informations Générales
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-blue-500 mr-2"></i>Année
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($article->annee)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $article->annee }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Non spécifiée</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-hashtag text-indigo-500 mr-2"></i>Numéro
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($article->numero)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        {{ $article->numero }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Non spécifié</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-gavel text-green-500 mr-2"></i>Date d'Adjudication
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->date_adjudication ? $article->date_adjudication->format('d/m/Y') : 'Non spécifiée' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tag text-purple-500 mr-2"></i>Lot
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->lot ?? 'Non spécifié' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-barcode text-orange-500 mr-2"></i>Numéro d'Adjudication
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->numero_adjudication ?? 'Non spécifié' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-layer-group text-teal-500 mr-2"></i>Type
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($article->type)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800">
                                        {{ ucfirst(str_replace('_', ' ', $article->type)) }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Non spécifié</span>
                                @endif
                            </div>
                        </div>
                        <!-- Removed: Nature Juridique - column was removed -->
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-edit text-gray-500 mr-2"></i>Dernière modification
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Forêt -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-tree"></i>
                        Section 2: Forêt
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-building text-green-500 mr-2"></i>Situation Administrative
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if(method_exists($article, 'situationsAdministratives') && $article->situationsAdministratives && $article->situationsAdministratives->count())
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($article->situationsAdministratives as $situation)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                {{ $situation->commune }}@if(!empty($situation->province)) — {{ $situation->province }}@endif
                                            </span>
                                        @endforeach
                                    </div>
                                @elseif($article->situationsAdministratives && $article->situationsAdministratives->count())
                                    <div class="space-y-2">
                                        @foreach($article->situationsAdministratives as $situation)
                                            <div class="border-b border-gray-200 pb-2 last:border-b-0">
                                                <div><strong>Commune:</strong> {{ $situation->commune }}</div>
                                                <div><strong>Province:</strong> {{ $situation->province ?? 'N/A' }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">Non spécifiée</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tree text-green-500 mr-2"></i>Forêt
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if(method_exists($article, 'forets') && $article->forets && $article->forets->count())
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($article->forets as $foret)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                {{ $foret->foret }}
                                            </span>
                                        @endforeach
                                    </div>
                                @elseif($article->forets && $article->forets->count())
                                    <div class="space-y-2">
                                        @foreach($article->forets as $foret)
                                            <div class="border-b border-gray-200 pb-2 last:border-b-0">
                                                <div><strong>Nom:</strong> {{ $foret->foret }}</div>
                                                @if($foret->lat && $foret->log)
                                                    <div><strong>Coordonnées:</strong> {{ $foret->lat }}, {{ $foret->log }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">Non spécifiée</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-pin text-green-500 mr-2"></i>Parcelle
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->parcelle ?? 'Non spécifiée' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-compass text-green-500 mr-2"></i>Coordonnées
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($article->lat && $article->log)
                                    <div class="space-y-1">
                                        <div><strong>Latitude:</strong> {{ $article->lat }}</div>
                                        <div><strong>Longitude:</strong> {{ $article->log }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-500">Non spécifiées</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Détails Techniques -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-cogs"></i>
                        Section 3: Détails Techniques
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-seedling text-purple-500 mr-2"></i>Essence
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if(method_exists($article, 'essences') && $article->essences && $article->essences->count())
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($article->essences as $essence)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                {{ $essence->essence }}
                                            </span>
                                        @endforeach
                                    </div>
                                @elseif($article->essences && $article->essences->count())
                                    <div class="space-y-2">
                                        @foreach($article->essences as $essence)
                                            <div class="border-b border-gray-200 pb-2 last:border-b-0">
                                                <div><strong>Nom:</strong> {{ $essence->essence }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">Non spécifiée</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-cut text-purple-500 mr-2"></i>Nature de Coupe
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if(method_exists($article, 'naturesDeCoupe') && $article->naturesDeCoupe && $article->naturesDeCoupe->count())
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($article->naturesDeCoupe as $nature)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-pink-100 text-pink-800">
                                                {{ $nature->nature_de_coupe }}
                                            </span>
                                        @endforeach
                                    </div>
                                @elseif($article->naturesDeCoupe && $article->naturesDeCoupe->count())
                                    <div class="space-y-2">
                                        @foreach($article->naturesDeCoupe as $natureDeCoupe)
                                            <div class="border-b border-gray-200 pb-2 last:border-b-0">
                                                <div><strong>Type:</strong> {{ $natureDeCoupe->nature_de_coupe }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">Non spécifiée</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-ruler text-purple-500 mr-2"></i>Superficie
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->superficie ? number_format($article->superficie, 2) . ' ha' : 'Non spécifiée' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-truck text-purple-500 mr-2"></i>Fourniture mise en charge
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->fourniture_mise_charge ? number_format($article->fourniture_mise_charge, 2) : 'Non spécifiée' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-road text-purple-500 mr-2"></i>Taxe refection chemins
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->taxe_refection_chemins ? number_format($article->taxe_refection_chemins, 2) : 'Non spécifiée' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-handshake text-purple-500 mr-2"></i>Service rendu ANEF
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->service_rendu_anef ? number_format($article->service_rendu_anef, 2) : 'Non spécifiée' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-fire text-purple-500 mr-2"></i>Bois chauffage volume
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->bois_chauffage_volume ? number_format($article->bois_chauffage_volume, 2) : 'Non spécifiée' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-purple-500 mr-2"></i>Bois chauffage destination
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->bois_chauffage_destination ?? 'Non spécifiée' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-check text-purple-500 mr-2"></i>Date payement service ANEF
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->date_payement_service_anef ? $article->date_payement_service_anef->format('d/m/Y') : 'Non spécifiée' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-truck text-purple-500 mr-2"></i>Date livraison mise en charge BF
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->date_livaison_mise_en_charge_bf ? $article->date_livaison_mise_en_charge_bf->format('d/m/Y') : 'Non spécifiée' }}
                            </div>
                        </div>
                        @if($article->zdtf)
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map text-purple-500 mr-2"></i>ZDTF
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->zdtf->name }}@if($article->zdtf->dpanef) - {{ $article->zdtf->dpanef->name }}@endif
                            </div>
                        </div>
                        @endif
                        @if($article->modeExploitations && $article->modeExploitations->count() > 0)
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tools text-purple-500 mr-2"></i>Mode d'Exploitation
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($article->modeExploitations as $modeExploitation)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            {{ $modeExploitation->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Produits -->
                    @if($article->products && $article->products->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-box text-purple-500"></i>
                            Produits
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($article->products as $product)
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
                    </div>
                    @else
                    <div class="mt-6">
                        <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-200">
                            <i class="fas fa-box text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-600 font-medium">Aucun produit associé</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Section 4: Informations Financières -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-money-bill-wave"></i>
                        Section 4: Informations Financières
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Removed: Prix de Retrait, Prix de Vente, Statut de Vente - columns were removed -->
                    </div>
                </div>
            </div>

            <!-- Section 5: Exploitant -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-user-tie"></i>
                        Section 5: Exploitant
                    </h2>
                </div>
                <div class="p-6">
                    @if($article->exploitant)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-hashtag text-indigo-500 mr-2"></i>Numéro
                                </label>
                                <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                    {{ $article->exploitant->numero ?? 'Non spécifié' }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-indigo-500 mr-2"></i>Nom Complet
                                </label>
                                <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                    {{ $article->exploitant->nom_complet ?? 'Non spécifié' }}
                                </div>
                            </div>
                            @if($article->exploitant->categorie === 'societe')
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-building text-indigo-500 mr-2"></i>Raison Sociale
                                    </label>
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                        {{ $article->exploitant->raison_sociale ?? 'Non spécifiée' }}
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-indigo-500 mr-2"></i>Email
                                </label>
                                <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                    {{ $article->exploitant->email ?? 'Non spécifié' }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone text-indigo-500 mr-2"></i>Téléphone
                                </label>
                                <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                    {{ $article->exploitant->telephone ?? 'Non spécifié' }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-indigo-500 mr-2"></i>Adresse
                                </label>
                                <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                    {{ $article->exploitant->adresse ?? 'Non spécifiée' }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user-slash text-indigo-500 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucun exploitant associé</h3>
                            <p class="text-gray-500">Cet article n'a pas d'exploitant associé.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section 6: Statut et Validation -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-500 to-slate-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-clipboard-check"></i>
                        Section 6: Statut et Validation
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Removed: DC, RC - columns were removed -->
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-trash text-gray-500 mr-2"></i>Supprimé
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($article->is_deleted)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-trash mr-1"></i>Oui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Non
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-edit text-gray-500 mr-2"></i>Dernière modification
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
</style>
@endsection