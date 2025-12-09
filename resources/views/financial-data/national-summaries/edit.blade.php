@extends('layouts.app')

@section('title', 'Modifier Résumé National')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-flag text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                    Modifier Résumé National
                </h1>
                <p class="text-gray-600 text-lg mt-2">Modifiez les informations du résumé national</p>
            </div>
        </div>
    </div>

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
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Formulaire de modification</h2>
                <p class="text-gray-600">Modifiez les informations du résumé national</p>
            </div>
        </div>

        <form action="{{ route('financial-data.national-summaries.update', $nationalSummary) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Year -->
            <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations de Base</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">
                            Année <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="year" id="year" value="{{ old('year', $nationalSummary->year) }}" 
                               min="1900" max="2100" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
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
                        <input type="number" name="budget_general_frais_adjudication" id="budget_general_frais_adjudication" value="{{ old('budget_general_frais_adjudication', $nationalSummary->budget_general_frais_adjudication ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="budget_general_ta" class="block text-sm font-semibold text-gray-700 mb-2">
                            TA (DH)
                        </label>
                        <input type="number" name="budget_general_ta" id="budget_general_ta" value="{{ old('budget_general_ta', $nationalSummary->budget_general_ta ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="budget_general_taxe_reconnaissance" class="block text-sm font-semibold text-gray-700 mb-2">
                            Taxe Reconnaissance (DH)
                        </label>
                        <input type="number" name="budget_general_taxe_reconnaissance" id="budget_general_taxe_reconnaissance" value="{{ old('budget_general_taxe_reconnaissance', $nationalSummary->budget_general_taxe_reconnaissance ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="budget_general_total" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Budget Général (DH)
                        </label>
                        <input type="number" name="budget_general_total" id="budget_general_total" value="{{ old('budget_general_total', $nationalSummary->budget_general_total ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>
            </div>

            <!-- Recette Items -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">les compte spéciaux</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="lf_2009" class="block text-sm font-semibold text-gray-700 mb-2">
                            LF_2009 (DH)
                        </label>
                        <input type="number" name="lf_2009" id="lf_2009" value="{{ old('lf_2009', $nationalSummary->lf_2009 ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="remboursement_drs" class="block text-sm font-semibold text-gray-700 mb-2">
                            Remboursement_DRS (DH)
                        </label>
                        <input type="number" name="remboursement_drs" id="remboursement_drs" value="{{ old('remboursement_drs', $nationalSummary->remboursement_drs ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="remboursement_fnf_et_autres" class="block text-sm font-semibold text-gray-700 mb-2">
                            Remboursement_FNF_et_autres (DH)
                        </label>
                        <input type="number" name="remboursement_fnf_et_autres" id="remboursement_fnf_et_autres" value="{{ old('remboursement_fnf_et_autres', $nationalSummary->remboursement_fnf_et_autres ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="taxe_fnf_20_percent" class="block text-sm font-semibold text-gray-700 mb-2">
                            Taxe_FNF_20% (DH)
                        </label>
                        <input type="number" name="taxe_fnf_20_percent" id="taxe_fnf_20_percent" value="{{ old('taxe_fnf_20_percent', $nationalSummary->taxe_fnf_20_percent ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="taxe_de_mise_en_charge" class="block text-sm font-semibold text-gray-700 mb-2">
                            Taxe_de_mise_en_charge (DH)
                        </label>
                        <input type="number" name="taxe_de_mise_en_charge" id="taxe_de_mise_en_charge" value="{{ old('taxe_de_mise_en_charge', $nationalSummary->taxe_de_mise_en_charge ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="total_fnf" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total_FNF (DH)
                        </label>
                        <input type="number" name="total_fnf" id="total_fnf" value="{{ old('total_fnf', $nationalSummary->total_fnf ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="chasse_et_peche" class="block text-sm font-semibold text-gray-700 mb-2">
                            Chasse_et_pêche (DH)
                        </label>
                        <input type="number" name="chasse_et_peche" id="chasse_et_peche" value="{{ old('chasse_et_peche', $nationalSummary->chasse_et_peche ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>
            </div>

            <!-- Communes -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Par des comumnes</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="communes_bois_tanin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Bois/Tanin (DH)
                        </label>
                        <input type="number" name="communes_bois_tanin" id="communes_bois_tanin" value="{{ old('communes_bois_tanin', $nationalSummary->communes_bois_tanin ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="communes_liege" class="block text-sm font-semibold text-gray-700 mb-2">
                            Liège (DH)
                        </label>
                        <input type="number" name="communes_liege" id="communes_liege" value="{{ old('communes_liege', $nationalSummary->communes_liege ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="communes_pam_produits_divers" class="block text-sm font-semibold text-gray-700 mb-2">
                            PAM Produits Divers (DH)
                        </label>
                        <input type="number" name="communes_pam_produits_divers" id="communes_pam_produits_divers" value="{{ old('communes_pam_produits_divers', $nationalSummary->communes_pam_produits_divers ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="communes_redevances_parcours" class="block text-sm font-semibold text-gray-700 mb-2">
                            Redevances Parcours (DH)
                        </label>
                        <input type="number" name="communes_redevances_parcours" id="communes_redevances_parcours" value="{{ old('communes_redevances_parcours', $nationalSummary->communes_redevances_parcours ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="communes_occupations_temporaires" class="block text-sm font-semibold text-gray-700 mb-2">
                            Occupations Temporaires (DH)
                        </label>
                        <input type="number" name="communes_occupations_temporaires" id="communes_occupations_temporaires" value="{{ old('communes_occupations_temporaires', $nationalSummary->communes_occupations_temporaires ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="communes_autres_taxes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Autres Taxes (DH)
                        </label>
                        <input type="number" name="communes_autres_taxes" id="communes_autres_taxes" value="{{ old('communes_autres_taxes', $nationalSummary->communes_autres_taxes ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="communes_total" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Communes (DH)
                        </label>
                        <input type="number" name="communes_total" id="communes_total" value="{{ old('communes_total', $nationalSummary->communes_total ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>
            </div>

            <!-- Provinces -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Par des Provinces</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="provinces_bois_tanin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Bois/Tanin (DH)
                        </label>
                        <input type="number" name="provinces_bois_tanin" id="provinces_bois_tanin" value="{{ old('provinces_bois_tanin', $nationalSummary->provinces_bois_tanin ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="provinces_liege" class="block text-sm font-semibold text-gray-700 mb-2">
                            Liège (DH)
                        </label>
                        <input type="number" name="provinces_liege" id="provinces_liege" value="{{ old('provinces_liege', $nationalSummary->provinces_liege ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="provinces_pam_produits_divers" class="block text-sm font-semibold text-gray-700 mb-2">
                            PAM Produits Divers (DH)
                        </label>
                        <input type="number" name="provinces_pam_produits_divers" id="provinces_pam_produits_divers" value="{{ old('provinces_pam_produits_divers', $nationalSummary->provinces_pam_produits_divers ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="provinces_interets_retard" class="block text-sm font-semibold text-gray-700 mb-2">
                            Intérêts Retard (DH)
                        </label>
                        <input type="number" name="provinces_interets_retard" id="provinces_interets_retard" value="{{ old('provinces_interets_retard', $nationalSummary->provinces_interets_retard ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="provinces_total" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Provinces (DH)
                        </label>
                        <input type="number" name="provinces_total" id="provinces_total" value="{{ old('provinces_total', $nationalSummary->provinces_total ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label for="total_general" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total Général (DH)
                        </label>
                        <input type="number" name="total_general" id="total_general" value="{{ old('total_general', $nationalSummary->total_general ?? 0) }}" 
                               step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 font-semibold">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Mettre à jour</span>
                </button>
                <a href="{{ route('financial-data.index', ['tab' => 'national-summaries']) }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection


