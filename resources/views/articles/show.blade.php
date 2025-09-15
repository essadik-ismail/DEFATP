@extends('layouts.app')

@section('title', 'Détails de l\'Article - SylvaNet')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
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
                           class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            Modifier
                        </a>
                        <a href="{{ route('articles.index') }}" 
                           class="px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-8">
            
            <!-- Section 1: Informations de Base -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-info-circle"></i>
                        Section 1: Informations de Base
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-blue-500 mr-2"></i>Année
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->annee ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-gavel text-green-500 mr-2"></i>Date d'Adjudication
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->date_adjudication ? $article->date_adjudication->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-hashtag text-purple-500 mr-2"></i>Lot
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->lot ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-barcode text-indigo-500 mr-2"></i>Numéro d'Article
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->numero ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Localisation -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-map-marker-alt"></i>
                        Section 2: Localisation
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map text-green-500 mr-2"></i>Localisation
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->localisation ? $article->localisation->CODE . ' - ' . $article->localisation->DRANEF . ' - ' . $article->localisation->ENTITE : 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-building text-green-500 mr-2"></i>Situation Administrative
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->situationAdministrative ? $article->situationAdministrative->commune . ' - ' . $article->situationAdministrative->province : 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tree text-green-500 mr-2"></i>Forêt
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->foret->foret ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-balance-scale text-green-500 mr-2"></i>Nature Juridique
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->nature_juridique ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-pin text-green-500 mr-2"></i>Parcelle
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->parcelle ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-compass text-green-500 mr-2"></i>Coordonnées
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                @if($article->lat && $article->log)
                                    {{ $article->lat }}, {{ $article->log }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Détails Techniques -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-calculator"></i>
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
                                {{ $article->essence->essence ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-cut text-purple-500 mr-2"></i>Nature de Coupe
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->natureDeCoupe->nature_de_coupe ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-ruler text-purple-500 mr-2"></i>Superficie
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->superficie ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-cube text-purple-500 mr-2"></i>BO (m³)
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->bo_m3 ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-cube text-purple-500 mr-2"></i>BI (m³)
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->bi_m3 ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-cube text-purple-500 mr-2"></i>BF/ST
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->bf_st ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-weight text-purple-500 mr-2"></i>Tanin (tonnes)
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->tanin_t ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-weight text-purple-500 mr-2"></i>Fleur d'Acacia (tonnes)
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->fleur_acacia_t ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-weight text-purple-500 mr-2"></i>Caroube (tonnes)
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->caroube_t ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-weight text-purple-500 mr-2"></i>Romarin (tonnes)
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->romarin_t ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-weight text-purple-500 mr-2"></i>PS (tonnes)
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->ps_t ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-weight text-purple-500 mr-2"></i>Liège (stères)
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->liége_st ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-weight text-purple-500 mr-2"></i>Charbon de Bois (ox)
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium">
                                {{ $article->charbon_bois_ox ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Section 4: Produits -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-box"></i>
                        Section 4: Produits
                    </h2>
                </div>
                <div class="p-6">
                    @if($article->products && $article->products->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gradient-to-r from-indigo-50 to-indigo-100">
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-indigo-800 border-b border-indigo-200">
                                            <i class="fas fa-box mr-2"></i>Nom du Produit
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-indigo-800 border-b border-indigo-200">
                                            <i class="fas fa-hashtag mr-2"></i>Quantité
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-indigo-100">
                                    @foreach($article->products as $product)
                                        <tr class="hover:bg-indigo-50 transition-colors duration-200">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-box text-indigo-600 text-sm"></i>
                                                    </div>
                                                    <span class="text-gray-800 font-medium">{{ $product->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                                    <i class="fas fa-hashtag mr-1"></i>{{ $product->quantity }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-box text-indigo-500 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucun produit associé</h3>
                            <p class="text-gray-500">Cet article n'a pas de produits associés.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section 5: Emplacements -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-map-marker-alt"></i>
                        Section 5: Emplacements
                    </h2>
                </div>
                <div class="p-6">
                    @if($article->locations && $article->locations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gradient-to-r from-emerald-50 to-emerald-100">
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-emerald-800 border-b border-emerald-200">
                                            <i class="fas fa-tag mr-2"></i>Matériel/Référence
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-emerald-800 border-b border-emerald-200">
                                            <i class="fas fa-crosshairs mr-2"></i>Coordonnée X
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-emerald-800 border-b border-emerald-200">
                                            <i class="fas fa-crosshairs mr-2"></i>Coordonnée Y
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-emerald-800 border-b border-emerald-200">
                                            <i class="fas fa-map-pin mr-2"></i>Position
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-emerald-100">
                                    @foreach($article->locations as $location)
                                        <tr class="hover:bg-emerald-50 transition-colors duration-200">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-tag text-emerald-600 text-sm"></i>
                                                    </div>
                                                    <span class="text-gray-800 font-medium">{{ $location->mat ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($location->x)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-crosshairs mr-1"></i>{{ number_format($location->x, 6) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($location->y)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-crosshairs mr-1"></i>{{ number_format($location->y, 6) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-500">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($location->x && $location->y)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                                        <i class="fas fa-map-pin mr-1"></i>({{ number_format($location->x, 2) }}, {{ number_format($location->y, 2) }})
                                                    </span>
                                                @else
                                                    <span class="text-gray-500">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-map-marker-alt text-emerald-500 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucun emplacement associé</h3>
                            <p class="text-gray-500">Cet article n'a pas d'emplacements associés.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section 6: Observations et Validation -->
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-clipboard"></i>
                        Section 6: Observations et Validation
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sticky-note text-gray-500 mr-2"></i>Observations
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-800 font-medium min-h-[100px]">
                                {{ $article->observations ?? 'Aucune observation' }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-check-circle text-gray-500 mr-2"></i>Statut de Validation
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                                @if($article->is_validated)
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>Article validé
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-2"></i>En attente de validation
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-between">
            <a href="{{ route('articles.index') }}" 
               class="px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
            </a>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('articles.edit', $article) }}" 
                   class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fas fa-edit"></i>
                    Modifier l'Article
                </a>
                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 w-full">
                        <i class="fas fa-trash"></i>
                        Supprimer
                    </button>
                </form>
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
    
    /* Custom scrollbar for tables */
    .overflow-x-auto::-webkit-scrollbar {
        height: 8px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Table styling */
    table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    table th:first-child {
        border-top-left-radius: 12px;
    }
    
    table th:last-child {
        border-top-right-radius: 12px;
    }
    
    table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 12px;
    }
    
    table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 12px;
    }
</style>
@endsection