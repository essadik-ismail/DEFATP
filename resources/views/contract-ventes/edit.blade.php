@extends('layouts.app')

@section('title', 'Modifier un Contrat de Vente - DEFATP')

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-700 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-file-contract text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                                Modifier un Contrat de Vente
                            </h1>
                            <p class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-file-alt text-green-500"></i>
                                Article #{{ $article->numero ?? $article->id }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('contract-ventes.update', [$article, $contractVente]) }}" method="POST" id="contractVenteForm" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Informations de l'article et de l'adjudication -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl shadow-2xl p-8 border border-green-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-info-circle text-green-600"></i>
                    1. Informations de l'article et de l'adjudication
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="article_number" class="block text-sm font-semibold text-gray-700 mb-2">
                            Numéro d'Article
                        </label>
                        <input type="text" 
                            id="article_number" 
                            value="{{ $article->numero ?? $article->id }}" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" 
                            readonly>
                    </div>

                    <div class="form-group">
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <select id="type" 
                            name="type" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>
                            <option value="">Sélectionner un type</option>
                            <option value="adjudication" {{ old('type', $contractVente->type ?? '') == 'adjudication' ? 'selected' : '' }}>Adjudication</option>
                            <option value="appel_doffre" {{ old('type', $contractVente->type ?? '') == 'appel_doffre' ? 'selected' : '' }}>Appel d'offre</option>
                            <option value="marche_negocie" {{ old('type', $contractVente->type ?? '') == 'marche_negocie' ? 'selected' : '' }}>Marché négocié</option>
                        </select>
                        @error('type')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_adjudication" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date d'Adjudication <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                            id="date_adjudication" 
                            name="date_adjudication" 
                            value="{{ old('date_adjudication', $contractVente->date_adjudication ?? '') }}" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>
                        @error('date_adjudication')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group" id="numeraAO_group" style="display: none;">
                        <label for="numeraAO" class="block text-sm font-semibold text-gray-700 mb-2">
                            Numéro Appel d'Offre
                        </label>
                        <input type="text" 
                            id="numeraAO" 
                            name="numeraAO" 
                            value="{{ old('numeraAO', $contractVente->numeraAO ?? '') }}" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Numéro d'appel d'offre">
                        @error('numeraAO')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Informations sur l'exploitant -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl shadow-2xl p-8 border border-green-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-user-tie text-green-600"></i>
                    2. Informations sur l'exploitant
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="exploitant_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <select id="exploitant_id" 
                            name="exploitant_id" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>
                            <option value="">Sélectionner un exploitant</option>
                            @foreach($exploitants as $exploitant)
                                <option value="{{ $exploitant->id }}" 
                                    data-cin="{{ $exploitant->n_cin }}"
                                    data-numero="{{ $exploitant->numero }}"
                                    data-adresse="{{ $exploitant->adresse }}"
                                    data-categorie="{{ $exploitant->categorie }}"
                                    {{ old('exploitant_id', $contractVente->exploitant_id ?? '') == $exploitant->id ? 'selected' : '' }}>
                                    {{ $exploitant->nom_complet }}
                                </option>
                            @endforeach
                        </select>
                        @error('exploitant_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="exploitant_cin" class="block text-sm font-semibold text-gray-700 mb-2">
                            CIN
                        </label>
                        <input type="text" 
                            id="exploitant_cin" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" 
                            readonly>
                    </div>

                    <div class="form-group">
                        <label for="exploitant_numero" class="block text-sm font-semibold text-gray-700 mb-2">
                            Numéro de Patente
                        </label>
                        <input type="text" 
                            id="exploitant_numero" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" 
                            readonly>
                    </div>

                    <div class="form-group">
                        <label for="exploitant_adresse" class="block text-sm font-semibold text-gray-700 mb-2">
                            Adresse
                        </label>
                        <input type="text" 
                            id="exploitant_adresse" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" 
                            readonly>
                    </div>

                    <div class="form-group">
                        <label for="exploitant_categorie" class="block text-sm font-semibold text-gray-700 mb-2">
                            Catégorie
                        </label>
                        <input type="text" 
                            id="exploitant_categorie" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100" 
                            readonly>
                    </div>
                </div>
            </div>

            <!-- Section 3: Détails de la vente -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl shadow-2xl p-8 border border-green-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-money-bill-wave text-green-600"></i>
                    3. Détails de la vente
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="prix_de_retrait" class="block text-sm font-semibold text-gray-700 mb-2">
                            Prix de Retrait
                        </label>
                        <input type="number" 
                            id="prix_de_retrait" 
                            name="prix_de_retrait" 
                            step="0.01" 
                            min="0"
                            value="{{ old('prix_de_retrait', $contractVente->prix_de_retrait ?? '') }}" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="0.00">
                        @error('prix_de_retrait')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="prix_vente" class="block text-sm font-semibold text-gray-700 mb-2">
                            Prix de Vente <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                            id="prix_vente" 
                            name="prix_vente" 
                            step="0.01" 
                            min="0"
                            value="{{ old('prix_vente', $contractVente->prix_vente ?? '') }}" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="0.00"
                            required>
                        @error('prix_vente')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nombre_tranche" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nombre de Tranches <span class="text-red-500">*</span>
                        </label>
                        <select id="nombre_tranche" 
                            name="nombre_tranche" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>
                            <option value="1" {{ old('nombre_tranche', $contractVente->nombre_tranche ?? 1) == 1 ? 'selected' : '' }}>1</option>
                            <option value="2" {{ old('nombre_tranche', $contractVente->nombre_tranche ?? 1) == 2 ? 'selected' : '' }}>2</option>
                            <option value="4" {{ old('nombre_tranche', $contractVente->nombre_tranche ?? 1) == 4 ? 'selected' : '' }}>4</option>
                        </select>
                        @error('nombre_tranche')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="duree_decheache" class="block text-sm font-semibold text-gray-700 mb-2">
                            Durée d'échéance
                        </label>
                        <input type="text" 
                            id="duree_decheache" 
                            name="duree_decheache" 
                            value="{{ old('duree_decheache', $contractVente->duree_decheache ?? '') }}" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Ex: 12 mois, 1 an, etc.">
                        @error('duree_decheache')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Récapitulatif des charges -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl shadow-2xl p-8 border border-green-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-calculator text-green-600"></i>
                    4. Récapitulatif des charges (calcul automatique)
                </h2>
                
                <div class="space-y-4">
                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Cautionnement définitif (10%)</label>
                                <input type="number" 
                                    id="charge_cautionnement" 
                                    name="charges[0][montant]" 
                                    step="0.01" 
                                    value="{{ old('charges.0.montant', $charges->firstWhere('nom', 'Cautionnement définitif')->montant ?? '') }}"
                                    readonly
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100">
                                <input type="hidden" name="charges[0][nom]" value="Cautionnement définitif">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance</label>
                                <input type="date" 
                                    name="charges[0][date_echeance]" 
                                    value="{{ old('charges.0.date_echeance', $charges->firstWhere('nom', 'Cautionnement définitif')->date_echeance->format('Y-m-d') ?? '') }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Taxe FNF (20%)</label>
                                <input type="number" 
                                    id="charge_taxe_fnf" 
                                    name="charges[1][montant]" 
                                    step="0.01" 
                                    readonly
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100">
                                <input type="hidden" name="charges[1][nom]" value="Taxe FNF">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance</label>
                                <input type="date" 
                                    name="charges[1][date_echeance]" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Frais d'adjudication (1.6%)</label>
                                <input type="number" 
                                    id="charge_frais_adjudication" 
                                    name="charges[2][montant]" 
                                    step="0.01" 
                                    readonly
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100">
                                <input type="hidden" name="charges[2][nom]" value="Frais d'adjudication">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance</label>
                                <input type="date" 
                                    name="charges[2][date_echeance]" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Taxe provinciale (10%)</label>
                                <input type="number" 
                                    id="charge_taxe_provinciale" 
                                    name="charges[3][montant]" 
                                    step="0.01" 
                                    readonly
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100">
                                <input type="hidden" name="charges[3][nom]" value="Taxe provinciale">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance</label>
                                <input type="date" 
                                    name="charges[3][date_echeance]" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Taxe pour la réfection des chemins forestiers</label>
                                <input type="number" 
                                    id="charge_taxe_refection" 
                                    name="charges[4][montant]" 
                                    step="0.01" 
                                    value="{{ old('charges.4.montant', $article->taxe_refection_chemins ?? 0) }}" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100"
                                    readonly>
                                <input type="hidden" name="charges[4][nom]" value="Taxe pour la réfection des chemins forestiers">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance</label>
                                <input type="date" 
                                    name="charges[4][date_echeance]" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Service rendu par l'ANEF</label>
                                <input type="number" 
                                    id="charge_service_anef" 
                                    name="charges[5][montant]" 
                                    step="0.01" 
                                    value="{{ old('charges.5.montant', $article->service_rendu_anef ?? 0) }}" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100"
                                    readonly>
                                <input type="hidden" name="charges[5][nom]" value="Service rendu par l'ANEF">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance</label>
                                <input type="date" 
                                    name="charges[5][date_echeance]" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Tranches de paiement -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl shadow-2xl p-8 border border-green-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-calendar-alt text-green-600"></i>
                    5. Tranches de paiement
                </h2>
                
                <div id="tranches_container" class="space-y-4">
                    <!-- Tranches will be dynamically generated here -->
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('articles.show', $article) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                    <span>Annuler</span>
                </a>
                <button type="submit" 
                    class="inline-flex items-center gap-3 px-6 py-3 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg" 
                    style="background: linear-gradient(to right, #059669, #047857);">
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Mettre à jour le Contrat</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const numeraAOGroup = document.getElementById('numeraAO_group');
    const exploitantSelect = document.getElementById('exploitant_id');
    const prixVenteInput = document.getElementById('prix_vente');
    const nombreTrancheInput = document.getElementById('nombre_tranche');
    const tranchesContainer = document.getElementById('tranches_container');

    // Show/hide numeraAO field based on type
    typeSelect.addEventListener('change', function() {
        if (this.value === 'appel_doffre') {
            numeraAOGroup.style.display = 'block';
            document.getElementById('numeraAO').required = true;
        } else {
            numeraAOGroup.style.display = 'none';
            document.getElementById('numeraAO').required = false;
        }
    });

    // Auto-fill exploitant details
    exploitantSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            document.getElementById('exploitant_cin').value = selectedOption.dataset.cin || '';
            document.getElementById('exploitant_numero').value = selectedOption.dataset.numero || '';
            document.getElementById('exploitant_adresse').value = selectedOption.dataset.adresse || '';
            document.getElementById('exploitant_categorie').value = selectedOption.dataset.categorie || '';
        } else {
            document.getElementById('exploitant_cin').value = '';
            document.getElementById('exploitant_numero').value = '';
            document.getElementById('exploitant_adresse').value = '';
            document.getElementById('exploitant_categorie').value = '';
        }
    });

    // Trigger exploitant change on page load if already selected
    if (exploitantSelect.value) {
        exploitantSelect.dispatchEvent(new Event('change'));
    }

    // Calculate charges
    function calculateCharges() {
        const prixVente = parseFloat(prixVenteInput.value) || 0;
        
        document.getElementById('charge_cautionnement').value = (prixVente * 0.10).toFixed(2);
        document.getElementById('charge_taxe_fnf').value = (prixVente * 0.20).toFixed(2);
        document.getElementById('charge_frais_adjudication').value = (prixVente * 0.016).toFixed(2);
        document.getElementById('charge_taxe_provinciale').value = (prixVente * 0.10).toFixed(2);
    }

    prixVenteInput.addEventListener('input', calculateCharges);

    // Generate tranches
    function generateTranches() {
        const nombreTranche = parseInt(nombreTrancheInput.value) || 1;
        const prixVente = parseFloat(prixVenteInput.value) || 0;
        const montantParTranche = prixVente / nombreTranche;

        tranchesContainer.innerHTML = '';
        
        @if(isset($tranches) && $tranches->count() > 0)
        const existingTranches = @json($tranches->map(function($t) {
            return [
                'montant' => $t->montant,
                'date_echeance' => $t->date_echeance ? $t->date_echeance->format('Y-m-d') : ''
            ];
        }));
        @else
        const existingTranches = [];
        @endif
        
        for (let i = 0; i < nombreTranche; i++) {
            const existingTranche = existingTranches[i] || {};
            const trancheDiv = document.createElement('div');
            trancheDiv.className = 'bg-white rounded-xl p-4 border border-gray-200';
            trancheDiv.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tranche ${i + 1}</label>
                        <input type="number" 
                            name="tranches[${i}][montant]" 
                            value="${existingTranche.montant || montantParTranche.toFixed(2)}"
                            step="0.01" 
                            readonly
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'échéance</label>
                        <input type="date" 
                            name="tranches[${i}][date_echeance]" 
                            value="${existingTranche.date_echeance || ''}"
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>
                    </div>
                </div>
            `;
            tranchesContainer.appendChild(trancheDiv);
        }
    }

    nombreTrancheInput.addEventListener('change', generateTranches);
    prixVenteInput.addEventListener('input', generateTranches);

    // Initialize on page load
    if (typeSelect.value === 'appel_doffre') {
        numeraAOGroup.style.display = 'block';
    }
    calculateCharges();
    generateTranches();
});
</script>
@endpush
@endsection

