@extends('layouts.app')

@section('title', 'Détails Résumé National - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Détails Résumé National
                    </h1>
                    <p class="text-gray-600 text-lg mt-2">Informations détaillées du résumé national</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('financial-data.edit', $nationalSummary) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                <a href="{{ route('financial-data.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations de Base</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Année
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ $nationalSummary->year ?? 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Mois
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ $nationalSummary->month ? \Carbon\Carbon::create()->month($nationalSummary->month)->locale('fr')->monthName : 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Situation Administrative
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ $nationalSummary->situationAdministrative ? $nationalSummary->situationAdministrative->commune . ' - ' . $nationalSummary->situationAdministrative->province : 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Localisation
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ $nationalSummary->localisation ? $nationalSummary->localisation->CODE . ' - ' . $nationalSummary->localisation->ENTITE : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Général -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Budget Général</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Frais Adjudication (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->budget_general_frais_adjudication ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            TAJ (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->budget_general_taj ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Taxe Reconnaissance (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->budget_general_taxe_reconnaissance ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Budget Général (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-semibold">
                            {{ number_format($nationalSummary->budget_general_total ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recette Items -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">les compte spéciaux</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            LF_2009 (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->lf_2009 ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Remboursement_DRS (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->remboursement_drs ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Remboursement_FNF_et_autres (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->remboursement_fnf_et_autres ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Taxe_FNF_20% (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->taxe_fnf_20_percent ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Taxe_de_mise_en_charge (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->taxe_de_mise_en_charge ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Total_FNF (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->total_fnf ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Chasse_et_pêche (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->chasse_et_peche ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Communes -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">par des comumnes</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Bois/Tanin (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->communes_bois_tanin ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Liège (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->communes_liege ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            PAM Produits Divers (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->communes_pam_produits_divers ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Redevances Parcours (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->communes_redevances_parcours ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Occupations Temporaires (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->communes_occupations_temporaires ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Autres Taxes (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->communes_autres_taxes ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Communes (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-semibold">
                            {{ number_format($nationalSummary->communes_total ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Provinces -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">par des Provinces</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Liège (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->provinces_liege ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Bois/Tanin (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->provinces_bois_tanin ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Alfa (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->provinces_Alfa ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            PAM Produits Divers (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->provinces_pam_produits_divers ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Intérêts Retard (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                            {{ number_format($nationalSummary->provinces_interets_retard ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Provinces (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-semibold">
                            {{ number_format($nationalSummary->provinces_total ?? 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Général (DH)
                        </label>
                        <div class="w-full px-4 py-3 bg-white border-2 border-indigo-500 rounded-xl text-indigo-800 font-bold text-lg">
                            {{ number_format($nationalSummary->total_general ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('financial-data.edit', $nationalSummary) }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-edit"></i>
                    <span class="font-semibold">Modifier</span>
                </a>
                <a href="{{ route('financial-data.index') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

