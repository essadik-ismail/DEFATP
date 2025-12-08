@extends('layouts.app')

@section('title', 'Nouveau Résumé National')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Nouveau Résumé National
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez un nouveau résumé national</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium">Veuillez corriger les erreurs suivantes :</h3>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <form action="{{ route('financial-data.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations de Base</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">
                            Année <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="year" id="year" value="{{ old('year', date('Y')) }}" 
                               min="1900" max="2100" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="month" class="block text-sm font-semibold text-gray-700 mb-2">
                            Mois
                        </label>
                        <select name="month" id="month" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Sélectionner un mois</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('month') == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->locale('fr')->monthName }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="situation_administrative_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Situation Administrative
                        </label>
                        <select name="situation_administrative_id" id="situation_administrative_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Sélectionner</option>
                            @foreach($situationsAdministratives as $situation)
                                <option value="{{ $situation->id }}" {{ old('situation_administrative_id') == $situation->id ? 'selected' : '' }}>
                                    {{ $situation->commune }} - {{ $situation->province }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Localisation
                        </label>
                        <select name="localisation_id" id="localisation_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Sélectionner</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->CODE }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Budget Général -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Budget Général</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="budget_general_frais_adjudication" class="block text-sm font-semibold text-gray-700 mb-2">
                            Frais Adjudication (DH)
                        </label>
                        <input type="number" name="budget_general_frais_adjudication" id="budget_general_frais_adjudication" value="{{ old('budget_general_frais_adjudication', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="budget_general_taj" class="block text-sm font-semibold text-gray-700 mb-2">
                            TAJ (DH)
                        </label>
                        <input type="number" name="budget_general_taj" id="budget_general_taj" value="{{ old('budget_general_taj', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="budget_general_taxe_reconnaissance" class="block text-sm font-semibold text-gray-700 mb-2">
                            Taxe Reconnaissance (DH)
                        </label>
                        <input type="number" name="budget_general_taxe_reconnaissance" id="budget_general_taxe_reconnaissance" value="{{ old('budget_general_taxe_reconnaissance', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="budget_general_total" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Budget Général (DH)
                        </label>
                        <input type="number" name="budget_general_total" id="budget_general_total" value="{{ old('budget_general_total', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Part État et CAS -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Part État et CAS</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="part_etat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Part État (DH)
                        </label>
                        <input type="number" name="part_etat" id="part_etat" value="{{ old('part_etat', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="cas_fnf_total" class="block text-sm font-semibold text-gray-700 mb-2">
                            CAS FNF Total (DH)
                        </label>
                        <input type="number" name="cas_fnf_total" id="cas_fnf_total" value="{{ old('cas_fnf_total', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="cas_chasse_peche_total" class="block text-sm font-semibold text-gray-700 mb-2">
                            CAS Chasse/Pêche Total (DH)
                        </label>
                        <input type="number" name="cas_chasse_peche_total" id="cas_chasse_peche_total" value="{{ old('cas_chasse_peche_total', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Communes -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Communes</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="communes_bois_tanin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Bois/Tanin (DH)
                        </label>
                        <input type="number" name="communes_bois_tanin" id="communes_bois_tanin" value="{{ old('communes_bois_tanin', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="communes_liege" class="block text-sm font-semibold text-gray-700 mb-2">
                            Liège (DH)
                        </label>
                        <input type="number" name="communes_liege" id="communes_liege" value="{{ old('communes_liege', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="communes_pam_produits_divers" class="block text-sm font-semibold text-gray-700 mb-2">
                            PAM Produits Divers (DH)
                        </label>
                        <input type="number" name="communes_pam_produits_divers" id="communes_pam_produits_divers" value="{{ old('communes_pam_produits_divers', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="communes_redevances_parcours" class="block text-sm font-semibold text-gray-700 mb-2">
                            Redevances Parcours (DH)
                        </label>
                        <input type="number" name="communes_redevances_parcours" id="communes_redevances_parcours" value="{{ old('communes_redevances_parcours', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="communes_occupations_temporaires" class="block text-sm font-semibold text-gray-700 mb-2">
                            Occupations Temporaires (DH)
                        </label>
                        <input type="number" name="communes_occupations_temporaires" id="communes_occupations_temporaires" value="{{ old('communes_occupations_temporaires', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="communes_autres_taxes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Autres Taxes (DH)
                        </label>
                        <input type="number" name="communes_autres_taxes" id="communes_autres_taxes" value="{{ old('communes_autres_taxes', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="communes_total" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Communes (DH)
                        </label>
                        <input type="number" name="communes_total" id="communes_total" value="{{ old('communes_total', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Provinces -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Provinces</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="provinces_liege" class="block text-sm font-semibold text-gray-700 mb-2">
                            Liège (DH)
                        </label>
                        <input type="number" name="provinces_liege" id="provinces_liege" value="{{ old('provinces_liege', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="provinces_bois_tanin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Bois/Tanin (DH)
                        </label>
                        <input type="number" name="provinces_bois_tanin" id="provinces_bois_tanin" value="{{ old('provinces_bois_tanin', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="provinces_Alfa" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alfa (DH)
                        </label>
                        <input type="number" name="provinces_Alfa" id="provinces_Alfa" value="{{ old('provinces_Alfa', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="provinces_pam_produits_divers" class="block text-sm font-semibold text-gray-700 mb-2">
                            PAM Produits Divers (DH)
                        </label>
                        <input type="number" name="provinces_pam_produits_divers" id="provinces_pam_produits_divers" value="{{ old('provinces_pam_produits_divers', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="provinces_interets_retard" class="block text-sm font-semibold text-gray-700 mb-2">
                            Intérêts Retard (DH)
                        </label>
                        <input type="number" name="provinces_interets_retard" id="provinces_interets_retard" value="{{ old('provinces_interets_retard', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="provinces_total" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Provinces (DH)
                        </label>
                        <input type="number" name="provinces_total" id="provinces_total" value="{{ old('provinces_total', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="total_general" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Général (DH)
                        </label>
                        <input type="number" name="total_general" id="total_general" value="{{ old('total_general', 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Créer</span>
                </button>
                <a href="{{ route('financial-data.index') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

