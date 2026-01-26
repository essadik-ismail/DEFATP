@extends('layouts.app')

@section('title', 'Permis d\'Enlever - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
<li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">Détail #{{ $article->numero ?? $article->id }}</a></li>
<li class="breadcrumb-item active">Permis d'enlever</li>
@endsection

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        
        <!-- Page Header Component -->
        <x-page-header 
            title="Permis d'Enlever"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-file-contract"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        <!-- Success/Error Messages -->
        @if(session('success'))
            <x-alert type="success" title="Succès!" dismissible>
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="error" title="Erreur!" dismissible>
                {{ session('error') }}
            </x-alert>
        @endif

        <!-- Permis d'Enlever List Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-list"></i>
                    Liste des Permis d'Enlever
                </h2>
            </div>
            <div class="p-6">
            <!-- Create Button -->
            @if($canCreateMore)
                <div class="mb-6">
                    <x-button
                        variant="primary"
                        icon="fas fa-plus"
                        onclick="document.getElementById('createForm').classList.toggle('hidden')"
                    >
                        Créer un nouveau Permis d'Enlever
                    </x-button>
                </div>
            @endif

            <!-- Existing Permis d'Enlever Table -->
            @if($permisEnlevers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-green-50 border-b-2 border-green-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase tracking-wider">
                                    <i class="fas fa-hashtag mr-1 text-green-500"></i>
                                    #
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase tracking-wider">
                                    <i class="fas fa-file-alt mr-1 text-green-500"></i>
                                    N° Quittance
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase tracking-wider">
                                    <i class="fas fa-calendar mr-1 text-green-500"></i>
                                    Date
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase tracking-wider">
                                    <i class="fas fa-user mr-1 text-green-500"></i>
                                    Percepteur
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase tracking-wider">
                                    <i class="fas fa-cubes mr-1 text-green-500"></i>
                                    Volume
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-green-800 uppercase tracking-wider">
                                    <i class="fas fa-cog mr-1 text-green-500"></i>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-green-100">
                            @foreach($permisEnlevers as $index => $permis)
                                <tr class="hover:bg-green-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-semibold text-green-900">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-green-900">{{ $permis->num_quittance ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-blue-700">
                                            {{ $permis->date ? \Carbon\Carbon::parse($permis->date)->format('d/m/Y') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-blue-700">{{ $permis->percepteur ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-semibold text-green-600">
                                            {{ number_format($permis->volume ?? 0, 2) }} m³
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <x-table-action-button
                                                icon="fas fa-eye"
                                                color="blue"
                                                title="Voir les détails"
                                                onclick="viewPermis({{ $permis->id }})"
                                            />
                                            <x-table-action-button
                                                icon="fas fa-print"
                                                color="green"
                                                title="Imprimer"
                                                onclick="window.print()"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-empty-state
                    icon="fas fa-file-contract"
                    title="Aucun Permis d'Enlever"
                    message="Aucun permis d'enlever n'a été créé pour cet article. Cliquez sur le bouton ci-dessus pour créer le premier permis."
                    color="green"
                />
            @endif
            </div>
        </div>

        <!-- Create Form (Hidden by default) -->
        <div id="createForm" class="{{ $canCreateMore ? 'hidden' : 'hidden' }} mt-6">
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                <div class="px-6 py-4" style="background: linear-gradient(135deg, #059669, #047857);">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-file-signature"></i>
                        Créer un Permis d'Enlever
                    </h2>
                </div>
                <div class="p-6">
            <form id="permisEnleverCreateForm" action="{{ route('articles.store-permis-enlever', $article) }}" method="POST">
                @csrf

                <x-validation-errors />

                <div class="space-y-6 mb-6">
                    <!-- Date Selection Section -->
                    <x-form-section
                        title="Sélection de la Date de Paiement"
                        icon="fas fa-calendar-check"
                        color="green"
                        :columns="1"
                    >
                        <x-form-input
                            type="select"
                            name="date_paiement"
                            label="Date de paiement"
                            :required="true"
                            focusColor="green"
                            onchange="updateTranchesList()"
                        >
                            <option value="">Sélectionner une date de paiement</option>
                            @foreach($tranchesByDate as $date => $tranches)
                                <option value="{{ $date }}" {{ old('date_paiement') == $date ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} ({{ $tranches->count() }} tranche(s))
                                </option>
                            @endforeach
                        </x-form-input>
                    </x-form-section>

                    <!-- Permis Enlever Information Section -->
                    <x-form-section
                        title="Informations du Permis d'Enlever"
                        icon="fas fa-truck-loading"
                        color="blue"
                        :columns="2"
                    >
                        <x-form-input
                            name="num_quittance_enlever"
                            label="N° Quittance (Enlever)"
                            placeholder="Ex: QE-2026-001"
                            :required="true"
                            borderColor="blue"
                        />

                        <x-form-input
                            type="date"
                            name="date"
                            label="Date"
                            :value="date('Y-m-d')"
                            :required="true"
                            borderColor="blue"
                        />

                        <x-form-input
                            name="percepteur_enlever"
                            label="Percepteur (Enlever)"
                            placeholder="Nom du percepteur"
                            :required="true"
                            borderColor="blue"
                        />

                        <x-form-input
                            type="number"
                            name="volume"
                            label="Volume (m³)"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            borderColor="blue"
                        />
                    </x-form-section>

                    <!-- Essences Table Section -->
                    <x-form-section
                        title="Essences de l'Article"
                        icon="fas fa-tree"
                        color="purple"
                        :columns="1"
                    >
                        @if($article->essences->isEmpty())
                            <x-empty-state
                                icon="fas fa-tree"
                                title="Aucune essence associée"
                                message="Ajoutez des essences à l'article avant de générer un permis d'enlever."
                                color="purple"
                            />
                        @else
                        <x-data-table-custom
                            color="purple"
                            :headers="[
                                ['label' => 'Essence', 'icon' => 'fas fa-leaf'],
                                ['label' => 'Produit', 'icon' => 'fas fa-box'],
                                ['label' => 'Quantité dans l\'Article', 'icon' => 'fas fa-cubes'],
                                ['label' => 'Quantité', 'icon' => 'fas fa-calculator'],
                            ]"
                        >
                            @foreach($article->essences as $index => $essence)
                                @php
                                    $product = isset($products) && $essence->pivot->product_id ? $products->get($essence->pivot->product_id) : null;
                                @endphp
                                <tr class="border-b border-purple-100 hover:bg-purple-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-semibold text-purple-900">{{ $essence->essence ?? 'N/A' }}</span>
                                        <input type="hidden" name="essences[{{ $index }}][essence_id]" value="{{ $essence->id }}">
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-purple-700">{{ $product ? $product->name : 'N/A' }}</span>
                                        <input type="hidden" name="essences[{{ $index }}][product_id]" value="{{ $essence->pivot->product_id }}">
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-bold text-purple-600 base-quantity" 
                                              data-base-quantity="{{ $essence->pivot->quantity }}">
                                            {{ number_format($essence->pivot->quantity, 2, ',', ' ') }} m³
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-green-600 calculated-quantity" 
                                                  id="calculated-quantity-{{ $index }}"
                                                  data-base-quantity="{{ $essence->pivot->quantity }}">
                                                0.00 m³
                                            </span>
                                            <input type="hidden" 
                                                   name="essences[{{ $index }}][quantity]" 
                                                   id="quantity-{{ $index }}"
                                                   value="0">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </x-data-table-custom>
                        @endif
                    </x-form-section>
                </div>

                <!-- Tranches Preview -->
                <div id="tranches-preview" class="mb-6 hidden">
                    <x-form-section
                        title="Tranches incluses dans ce permis"
                        icon="fas fa-receipt"
                        color="green"
                        :columns="1"
                    >
                        <div id="tranches-list" class="space-y-2">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </x-form-section>
                </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-green-200">
                        <a href="{{ route('articles.show', $article) }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 border border-green-300 rounded-xl text-green-700 hover:bg-green-50 transition-all duration-300">
                            <i class="fas fa-times"></i>
                            <span>Annuler</span>
                        </a>
                        <button type="submit"
                                {{ $article->essences->isEmpty() ? 'disabled' : '' }}
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors"
                                style="background: linear-gradient(135deg, #059669, #047857); {{ $article->essences->isEmpty() ? 'opacity:0.6; cursor:not-allowed;' : '' }}">
                            <i class="fas fa-file-download"></i>
                            <span>Générer le Permis</span>
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!-- JavaScript for dynamic functionality -->
        <script>
            function viewPermis(permisId) {
                // Toggle details view for specific permis
                alert('Voir les détails du Permis d\'Enlever #' + permisId + '\n\nCette fonctionnalité sera implémentée prochainement.');
            }
        </script>
        
        <script>
            const tranchesData = @json($tranchesByDate);
            let currentTranchesCount = 0;

            function updateTranchesList() {
                const dateSelect = document.getElementById('date_paiement');
                const selectedDate = dateSelect.value;
                const previewDiv = document.getElementById('tranches-preview');
                const tranchesList = document.getElementById('tranches-list');

                if (!selectedDate || !tranchesData[selectedDate]) {
                    previewDiv.classList.add('hidden');
                    currentTranchesCount = 0;
                    updateMaxQuantities();
                    return;
                }

                const tranches = tranchesData[selectedDate];
                currentTranchesCount = tranches.length;
                let html = '';
                let totalMontant = 0;

                tranches.forEach((tranche, index) => {
                    totalMontant += parseFloat(tranche.montant || 0);
                    const trancheNumber = tranche.tranche_number || `Tranche ${index + 1}`;
                    const dateStr = tranche.date_paiement ? new Date(tranche.date_paiement).toLocaleDateString('fr-FR') : 'N/A';
                    
                    html += `
                        <div class="flex items-center justify-between p-3 bg-white border border-green-200 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #059669, #047857);">
                                    <span class="text-white text-sm font-semibold">${index + 1}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-green-900">${trancheNumber}</p>
                                    <p class="text-xs text-blue-600">Date: ${dateStr}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">${parseFloat(tranche.montant || 0).toFixed(2)} MAD</p>
                            </div>
                        </div>
                    `;
                });

                html += `
                    <div class="flex items-center justify-between p-4 bg-green-100 border-2 border-green-300 rounded-lg mt-3">
                        <p class="text-sm font-bold text-green-900">Montant Total</p>
                        <p class="text-lg font-bold text-green-700">${totalMontant.toFixed(2)} MAD</p>
                    </div>
                    <div class="mt-2 text-sm text-green-700">
                        <i class="fas fa-info-circle text-green-500 mr-1"></i>
                        <strong>${currentTranchesCount}</strong> tranche(s) payée(s)
                    </div>
                `;

                tranchesList.innerHTML = html;
                previewDiv.classList.remove('hidden');
                updateMaxQuantities();
            }

            function updateMaxQuantities() {
                const calculatedQuantityElements = document.querySelectorAll('.calculated-quantity');
                
                calculatedQuantityElements.forEach((element) => {
                    const baseQuantity = parseFloat(element.getAttribute('data-base-quantity'));
                    let calculatedQuantity = 0;
                    
                    if (currentTranchesCount > 0) {
                        calculatedQuantity = baseQuantity / currentTranchesCount;
                    }
                    
                    // Format with French number format (comma as decimal separator)
                    const formattedQuantity = calculatedQuantity.toFixed(2).replace('.', ',');
                    element.textContent = formattedQuantity + ' m³';
                    
                    // Update hidden input value
                    const index = element.id.replace('calculated-quantity-', '');
                    const hiddenInput = document.getElementById('quantity-' + index);
                    if (hiddenInput) {
                        hiddenInput.value = calculatedQuantity.toFixed(2);
                    }
                });
            }

            document.getElementById('permisEnleverCreateForm')?.addEventListener('submit', function(e) {
                if (currentTranchesCount === 0) {
                    e.preventDefault();
                    alert('Veuillez sélectionner une date de paiement.');
                    return false;
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const dateSelect = document.getElementById('date_paiement');
                if (dateSelect.value) {
                    updateTranchesList();
                }
            });
        </script>
    </div>
</div>
@endsection
