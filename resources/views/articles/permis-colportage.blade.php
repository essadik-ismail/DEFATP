@extends('layouts.app')

@section('title', 'Permis de Colportage - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
<li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">Détail #{{ $article->numero ?? $article->id }}</a></li>
<li class="breadcrumb-item active">Permis de colportage</li>
@endsection

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        
        <!-- Page Header Component -->
        <x-page-header 
            title="Permis de Colportage"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-truck"
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

        <!-- Main Form Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold flex items-center gap-3" style="color: #1F2D24;">
                    <i class="fas fa-file-signature" style="color: #6B7C72;"></i>
                    Générer le Permis de Colportage
                </h2>
            </div>
            <div class="p-6">
                <form action="{{ route('articles.store-permis-colportage', $article) }}" method="POST">
                    @csrf

                    <x-validation-errors />

                    <div class="space-y-6 mb-6">
                    <!-- Permis d'Enlever Selection -->
                    <x-form-section
                        title="Permis d'Enlever"
                        icon="fas fa-truck-loading"
                        color="green"
                        columns="1"
                    >
                        @if(isset($permisEnlevers) && $permisEnlevers->count() > 0)
                            <div class="form-group">
                                <label for="id_permis_enlever" class="block text-sm font-semibold text-gray-700 mb-2">Sélectionner un Permis d'Enlever <span class="text-red-500">*</span></label>
                                <select name="id_permis_enlever" id="id_permis_enlever" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                    <option value="">Choisir...</option>
                                    @foreach($permisEnlevers as $permis)
                                        <option value="{{ $permis->id }}" {{ old('id_permis_enlever', $selectedPermisEnleverId ?? null) == $permis->id ? 'selected' : '' }}>
                                            {{ $permis->num_quittance ?? ('Permis #' . $permis->id) }}
                                            — {{ $permis->date ? \Carbon\Carbon::parse($permis->date)->format('d/m/Y') : '' }}
                                            — Tranches: {{ $permis->num_tranche_paye ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <x-alert type="warning" title="Aucun Permis d'Enlever">
                                Vous devez d'abord créer un <strong>Permis d'Enlever</strong> avant de générer un Permis de Colportage.
                            </x-alert>
                        @endif
                    </x-form-section>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form-input
                            type="date"
                            name="date_debut"
                            label="Date de début"
                            :required="true"
                            :value="old('date_debut')"
                            focusColor="blue"
                        />

                        <x-form-input
                            type="date"
                            name="date_fin"
                            label="Date de fin"
                            :required="true"
                            :value="old('date_fin')"
                            focusColor="blue"
                        />
                    </div>

                    <!-- Véhicule -->
                    <x-form-input
                        type="text"
                        name="vehicule_immatriculation"
                        label="Immatriculation du véhicule"
                        :required="true"
                        :value="old('vehicule_immatriculation')"
                        focusColor="blue"
                        placeholder="Ex: A-12345-B"
                    />

                    <!-- Chauffeur Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form-input
                            type="text"
                            name="chauffeur_nom"
                            label="Nom du chauffeur"
                            :required="true"
                            :value="old('chauffeur_nom')"
                            focusColor="blue"
                            placeholder="Nom complet"
                        />

                        <x-form-input
                            type="text"
                            name="chauffeur_cin"
                            label="CIN du chauffeur"
                            :required="true"
                            :value="old('chauffeur_cin')"
                            focusColor="blue"
                            placeholder="Ex: AB123456"
                        />
                    </div>

                    <!-- Destination -->
                    <x-form-input
                        type="text"
                        name="destination"
                        label="Destination"
                        :required="true"
                        :value="old('destination')"
                        focusColor="blue"
                        placeholder="Adresse de destination"
                    />

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
                                <p class="text-gray-600">Aucune essence associée à l'article. Ajoutez des essences avant de générer un permis de colportage.</p>
                            </div>
                        @else
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Essence</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Produit</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Quantité dans Permis d'Enlever</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Volume</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="essences-table-body">
                                    @foreach($article->essences as $index => $essence)
                                        @php
                                            $product = isset($products) && $essence->pivot->product_id ? $products->get($essence->pivot->product_id) : null;
                                        @endphp
                                        <tr class="hover:bg-gray-50 essence-row" data-essence-id="{{ $essence->id }}" data-product-id="{{ $essence->pivot->product_id }}">
                                            <td class="px-4 py-3">
                                                <span class="text-sm font-semibold text-gray-900">{{ $essence->essence ?? 'N/A' }}</span>
                                                <input type="hidden" name="essences[{{ $index }}][essence_id]" value="{{ $essence->id }}">
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm text-gray-700">{{ $product ? $product->name : 'N/A' }}</span>
                                                <input type="hidden" name="essences[{{ $index }}][product_id]" value="{{ $essence->pivot->product_id }}">
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="text-sm font-bold text-gray-900 permis-enlever-quantity" id="permis-quantity-{{ $index }}">
                                                    0.00 m³
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" 
                                                       name="essences[{{ $index }}][quantity]" 
                                                       id="colportage-quantity-{{ $index }}"
                                                       step="0.01" 
                                                       min="0"
                                                       value="{{ old('essences.' . $index . '.quantity', '0') }}"
                                                       class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                       placeholder="0.00">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>

                        <!-- Article Information Summary -->
                        <x-form-section
                            title="Informations de l'Article"
                            icon="fas fa-info-circle"
                            color="purple"
                            columns="3"
                        >
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Numéro</label>
                                <p class="text-purple-900 font-semibold">{{ $article->numero }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Année</label>
                                <p class="text-purple-900 font-semibold">{{ $article->annee }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Lot</label>
                                <p class="text-purple-900 font-semibold">{{ $article->lot }}</p>
                            </div>
                            @if($article->essences->count() > 0)
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium text-purple-700 mb-1">Essences</label>
                                    <p class="text-purple-900 font-semibold">
                                        {{ $article->essences->pluck('essence')->join(', ') }}
                                    </p>
                                </div>
                            @endif
                        </x-form-section>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-green-200">
                        <a href="{{ route('articles.show', $article) }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 border border-green-300 rounded-xl text-green-700 hover:bg-green-50 transition-all duration-300">
                            <i class="fas fa-times"></i>
                            <span>Annuler</span>
                        </a>
                        <button type="submit"
                                {{ (!isset($permisEnlevers) || $permisEnlevers->count() === 0) ? 'disabled' : '' }}
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors"
                                style="background: linear-gradient(135deg, #059669, #047857); {{ (!isset($permisEnlevers) || $permisEnlevers->count() === 0) ? 'opacity:0.6; cursor:not-allowed;' : '' }}">
                            <i class="fas fa-truck"></i>
                            <span>Générer le Permis</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const permisEnleverSelect = document.getElementById('id_permis_enlever');
    
    // Permis d'Enlever data from backend - get quantities from permisenlever_product table
    // Data structure: { permis_id: { essences: [{ essence_id, product_id, quantity }] } }
    const permisEnleversData = @json($permisEnleversWithQuantities->mapWithKeys(function($permis) {
        return [$permis->id => [
            'essences' => $permis->quantities ?? []
        ]];
    }));
    
    // Debug: log the data structure (remove in production)
    console.log('Permis d\'Enlever data loaded:', permisEnleversData);
    
    function loadPermisEnleverEssences(permisId) {
        if (!permisId || !permisEnleversData[permisId]) {
            // Reset all quantities to 0
            document.querySelectorAll('.permis-enlever-quantity').forEach(function(el) {
                el.textContent = '0.00 m³';
            });
            return;
        }
        
        const permisData = permisEnleversData[permisId];
        const essencesData = permisData.essences || [];
        
        // Create a map for quick lookup: essence_id + product_id -> quantity
        // Ensure consistent key format (numbers as strings for comparison)
        const quantityMap = {};
        essencesData.forEach(function(item) {
            const essenceId = parseInt(item.essence_id) || 0;
            const productId = parseInt(item.product_id) || 0;
            const key = essenceId + '_' + productId;
            quantityMap[key] = parseFloat(item.quantity) || 0;
        });
        
        // Update the table rows
        document.querySelectorAll('.essence-row').forEach(function(row) {
            const essenceId = parseInt(row.getAttribute('data-essence-id')) || 0;
            const productId = parseInt(row.getAttribute('data-product-id')) || 0;
            const key = essenceId + '_' + productId;
            
            const quantity = quantityMap[key] || 0;
            const quantityDisplay = row.querySelector('.permis-enlever-quantity');
            
            if (quantityDisplay) {
                // Format with French number format (comma as decimal separator, space as thousands separator)
                const formattedQuantity = quantity.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ').replace('.', ',');
                quantityDisplay.textContent = formattedQuantity + ' m³';
            }
        });
    }
    
    // Load essences when permis d'enlever is selected
    if (permisEnleverSelect) {
        permisEnleverSelect.addEventListener('change', function() {
            const selectedPermisId = this.value;
            loadPermisEnleverEssences(selectedPermisId);
        });
        
        // Load on page load if a permis is pre-selected
        if (permisEnleverSelect.value) {
            loadPermisEnleverEssences(permisEnleverSelect.value);
        }
    }
});
</script>
@endpush
@endsection
