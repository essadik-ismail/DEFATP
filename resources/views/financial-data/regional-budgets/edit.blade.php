@extends('layouts.app')

@section('title', 'Modifier Budget Régional')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-coins text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Modifier Budget Régional
                </h1>
                <p class="text-gray-600 text-lg mt-2">Modifiez les informations du budget régional</p>
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
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Formulaire de modification</h2>
                <p class="text-gray-600">Modifiez les informations du budget régional</p>
            </div>
        </div>

        <form action="{{ route('financial-data.regional-budgets.update', $regionalBudget) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="situation_administrative_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Situation Administrative
                    </label>
                    <select name="situation_administrative_id" id="situation_administrative_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Sélectionner...</option>
                        @foreach($situationsAdministratives as $situation)
                            <option value="{{ $situation->id }}" {{ old('situation_administrative_id', $regionalBudget->situation_administrative_id) == $situation->id ? 'selected' : '' }}>
                                {{ $situation->region ?? $situation->province }} - {{ $situation->commune }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">
                        Année <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="year" id="year" value="{{ old('year', $regionalBudget->year) }}" 
                           min="1900" max="2100" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="taxe_adjudication_1_6" class="block text-sm font-semibold text-gray-700 mb-2">
                        Taxe Adjudication 1/6 (DH)
                    </label>
                    <input type="number" name="taxe_adjudication_1_6" id="taxe_adjudication_1_6" value="{{ old('taxe_adjudication_1_6', $regionalBudget->taxe_adjudication_1_6 ?? 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="taxe_reconnaissance_interets" class="block text-sm font-semibold text-gray-700 mb-2">
                        Taxe Reconnaissance Intérêts (DH)
                    </label>
                    <input type="number" name="taxe_reconnaissance_interets" id="taxe_reconnaissance_interets" value="{{ old('taxe_reconnaissance_interets', $regionalBudget->taxe_reconnaissance_interets ?? 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="ta_saisie_caution" class="block text-sm font-semibold text-gray-700 mb-2">
                        TA Saisie Caution (DH)
                    </label>
                    <input type="number" name="ta_saisie_caution" id="ta_saisie_caution" value="{{ old('ta_saisie_caution', $regionalBudget->ta_saisie_caution ?? 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="budget_fmf" class="block text-sm font-semibold text-gray-700 mb-2">
                        Budget FMF (DH)
                    </label>
                    <input type="number" name="budget_fmf" id="budget_fmf" value="{{ old('budget_fmf', $regionalBudget->budget_fmf ?? 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="remboursement_drs" class="block text-sm font-semibold text-gray-700 mb-2">
                        Remboursement DRS (DH)
                    </label>
                    <input type="number" name="remboursement_drs" id="remboursement_drs" value="{{ old('remboursement_drs', $regionalBudget->remboursement_drs ?? 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="remboursement_fmf_autres" class="block text-sm font-semibold text-gray-700 mb-2">
                        Remboursement FMF Autres (DH)
                    </label>
                    <input type="number" name="remboursement_fmf_autres" id="remboursement_fmf_autres" value="{{ old('remboursement_fmf_autres', $regionalBudget->remboursement_fmf_autres ?? 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="taxe_fmf_20" class="block text-sm font-semibold text-gray-700 mb-2">
                        Taxe FMF 20% (DH)
                    </label>
                    <input type="number" name="taxe_fmf_20" id="taxe_fmf_20" value="{{ old('taxe_fmf_20', $regionalBudget->taxe_fmf_20 ?? 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="taxe_mise_en_charge" class="block text-sm font-semibold text-gray-700 mb-2">
                        Taxe Mise en Charge (DH)
                    </label>
                    <input type="number" name="taxe_mise_en_charge" id="taxe_mise_en_charge" value="{{ old('taxe_mise_en_charge', $regionalBudget->taxe_mise_en_charge ?? 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="chasse_peche" class="block text-sm font-semibold text-gray-700 mb-2">
                        Chasse/Pêche (DH)
                    </label>
                    <input type="number" name="chasse_peche" id="chasse_peche" value="{{ old('chasse_peche', $regionalBudget->chasse_peche ?? 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="taxe_12_bois_importes" class="block text-sm font-semibold text-gray-700 mb-2">
                        Taxe 12% Bois Importés (DH)
                    </label>
                    <input type="number" name="taxe_12_bois_importes" id="taxe_12_bois_importes" value="{{ old('taxe_12_bois_importes', $regionalBudget->taxe_12_bois_importes ?? 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Mettre à jour</span>
                </button>
                <a href="{{ route('financial-data.index', ['tab' => 'regional-budgets']) }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection


