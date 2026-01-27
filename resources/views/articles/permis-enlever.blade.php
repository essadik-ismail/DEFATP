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

        @php
            $showCreateOnly = request('action') === 'create';
        @endphp

        @if(!$showCreateOnly)
        <!-- Permis d'Enlever List Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold flex items-center gap-3" style="color: #1F2D24;">
                    <i class="fas fa-list" style="color: #6B7C72;"></i>
                    Liste des Permis d'Enlever
                </h2>
            </div>
            <div class="p-6">
            <!-- Create Button -->
            @if($canCreateMore)
                <div class="mb-6">
                    <a href="{{ route('articles.permis-enlever', ['article' => $article, 'action' => 'create']) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus"></i>
                        <span>Créer un nouveau Permis d'Enlever</span>
                    </a>
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
                                            <a href="{{ route('articles.permis-colportage', ['article' => $article, 'permis_enlever_id' => $permis->id]) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-white transition-colors duration-200 shadow-sm hover:shadow-md bg-purple-600 hover:bg-purple-700"
                                               title="Permis de colportage">
                                                <i class="fas fa-truck text-sm"></i>
                                            </a>
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
        @endif

        <!-- Create Form -->
        <div id="createForm" class="{{ $showCreateOnly ? '' : ($canCreateMore ? 'hidden' : 'hidden') }} mt-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form id="permisEnleverCreateForm" action="{{ route('articles.store-permis-enlever', $article) }}" method="POST" class="space-y-8">
                    @csrf
                    <x-validation-errors />

                    <!-- 1. Sélection de la Date de Paiement -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                <i class="fas fa-calendar-check text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">1. Sélection de la Date de Paiement</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div class="form-group">
                                <label for="date_paiement" class="block text-sm font-semibold text-gray-700 mb-2">Date de paiement <span class="text-red-500">*</span></label>
                                <select name="date_paiement" id="date_paiement" onchange="updateTranchesList()" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                    <option value="">Sélectionner une date de paiement</option>
                                    @foreach($tranchesByDate as $date => $tranches)
                                        <option value="{{ $date }}" {{ old('date_paiement') == $date ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} ({{ $tranches->count() }} tranche(s))
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Informations du Permis d'Enlever -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                <i class="fas fa-truck-loading text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">2. Informations du Permis d'Enlever</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="num_quittance_enlever" class="block text-sm font-semibold text-gray-700 mb-2">N° Quittance (Enlever) <span class="text-red-500">*</span></label>
                                <input type="text" name="num_quittance_enlever" id="num_quittance_enlever" value="{{ old('num_quittance_enlever') }}" placeholder="Ex: QE-2026-001" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                            </div>
                            <div class="form-group">
                                <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                            </div>
                            <div class="form-group">
                                <label for="percepteur_enlever" class="block text-sm font-semibold text-gray-700 mb-2">Percepteur (Enlever) <span class="text-red-500">*</span></label>
                                <input type="text" name="percepteur_enlever" id="percepteur_enlever" value="{{ old('percepteur_enlever') }}" placeholder="Nom du percepteur" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Essences de l'Article -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                <i class="fas fa-tree text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">3. Essences de l'Article</h3>
                        </div>
                        @if($article->essences->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-tree text-gray-400 text-4xl mb-3"></i>
                                <p class="text-gray-600">Aucune essence associée à l'article. Ajoutez des essences avant de générer un permis d'enlever.</p>
                            </div>
                        @else
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Essence</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Produit</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Quantité dans l'Article</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Quantité</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($article->essences as $index => $essence)
                                        @php
                                            $product = isset($products) && $essence->pivot->product_id ? $products->get($essence->pivot->product_id) : null;
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3">
                                                <span class="text-sm font-semibold text-gray-900">{{ $essence->essence ?? 'N/A' }}</span>
                                                <input type="hidden" name="essences[{{ $index }}][essence_id]" value="{{ $essence->id }}">
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm text-gray-700">{{ $product ? $product->name : 'N/A' }}</span>
                                                <input type="hidden" name="essences[{{ $index }}][product_id]" value="{{ $essence->pivot->product_id }}">
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm font-bold text-gray-900 base-quantity" data-base-quantity="{{ $essence->pivot->quantity }}">
                                                    {{ number_format($essence->pivot->quantity, 2, ',', ' ') }} m³
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm font-bold text-green-600 calculated-quantity" id="calculated-quantity-{{ $index }}" data-base-quantity="{{ $essence->pivot->quantity }}">
                                                    0.00 m³
                                                </span>
                                                <input type="hidden" name="essences[{{ $index }}][quantity]" id="quantity-{{ $index }}" value="0">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>

                    <!-- Tranches Preview -->
                    <div id="tranches-preview" class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6 hidden">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                <i class="fas fa-receipt text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Tranches incluses dans ce permis</h3>
                        </div>
                        <div id="tranches-list" class="space-y-2">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end gap-4 pt-2">
                        <a href="{{ route('articles.show', $article) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            <i class="fas fa-times"></i>
                            <span>Annuler</span>
                        </a>
                        <button type="submit"
                                {{ $article->essences->isEmpty() ? 'disabled' : '' }}
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 {{ $article->essences->isEmpty() ? 'opacity-60 cursor-not-allowed' : '' }}">
                            <i class="fas fa-file-download"></i>
                            <span>Générer le Permis</span>
                        </button>
                    </div>
                </form>
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
            const nombreTranche = {{ $nombreTranche ?? 1 }};
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
                        <strong>${currentTranchesCount}</strong> tranche(s) payée(s) sur <strong>${nombreTranche}</strong> tranche(s) totale(s)
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
                    
                    // Formula: (Quantité / nombre_tranche) * nombre_tranche_payée_pour_la_date_sélectionnée
                    if (nombreTranche > 0 && currentTranchesCount > 0) {
                        calculatedQuantity = (baseQuantity / nombreTranche) * currentTranchesCount;
                    }
                    
                    // Format with French number format (comma as decimal separator, space as thousands separator)
                    const formattedQuantity = calculatedQuantity.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ').replace('.', ',');
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
