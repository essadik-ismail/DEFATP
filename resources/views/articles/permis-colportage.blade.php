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

        <x-page-header
            title="Permis de Colportage"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-truck"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

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

        <!-- Permis d'Enlever selector (reload page to show section 1 & 2 for selected permis) -->
        @if(isset($permisEnlevers) && $permisEnlevers->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <label for="permis_enlever_filter" class="block text-sm font-semibold text-gray-700 mb-2">Permis d'Enlever</label>
                <select id="permis_enlever_filter" class="form-input w-full max-w-md px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Choisir un Permis d'Enlever...</option>
                    @foreach($permisEnlevers as $permis)
                        <option value="{{ $permis->id }}" {{ (string)($selectedPermisEnleverId ?? '') === (string)$permis->id ? 'selected' : '' }}>
                            {{ $permis->num_quittance ?? ('Permis #' . $permis->id) }}
                            — {{ $permis->date ? \Carbon\Carbon::parse($permis->date)->format('d/m/Y') : '' }}
                            — Tranches: {{ $permis->num_tranche_paye ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Section 1: Two cards - Quantité dans Permis d'Enlever & Quantité utilisée pour colportage -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-semibold flex items-center gap-2" style="color: #1F2D24;">
                        <i class="fas fa-box-open" style="color: #6B7C72;"></i>
                        Quantité dans Permis d'Enlever
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($quantityInPermisEnlever ?? 0, 2, ',', ' ') }} m³
                    </p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-semibold flex items-center gap-2" style="color: #1F2D24;">
                        <i class="fas fa-truck-loading" style="color: #6B7C72;"></i>
                        Quantité utilisée pour colportage
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($quantityUsedColportage ?? 0, 2, ',', ' ') }} m³
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 2: Liste des permis de colportage -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between flex-wrap gap-2">
                <h2 class="text-lg font-semibold flex items-center gap-3" style="color: #1F2D24;">
                    <i class="fas fa-list" style="color: #6B7C72;"></i>
                    Liste des permis de colportage
                </h2>
            </div>
            <div class="overflow-x-auto">
                @if(isset($listPermisColportage) && $listPermisColportage->count() > 0)
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">N° Permis</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date début</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date fin</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Véhicule</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Chauffeur</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Destination</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($listPermisColportage as $pc)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $pc->numero_permis ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pc->date_debut ? $pc->date_debut->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pc->date_fin ? $pc->date_fin->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pc->vehicule_immatriculation ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pc->chauffeur_nom ?? '-' }} ({{ $pc->chauffeur_cin ?? '-' }})</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pc->destination ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-file-alt text-4xl mb-3 opacity-50"></i>
                        <p>Aucun permis de colportage pour ce Permis d'Enlever.</p>
                        <p class="text-sm mt-1">Cliquez sur « Créer permis de colportage » pour en ajouter un.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Section 3: Create button + form (hidden by default) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between flex-wrap gap-2">
                <h2 class="text-lg font-semibold flex items-center gap-3" style="color: #1F2D24;">
                    <i class="fas fa-file-signature" style="color: #6B7C72;"></i>
                    Générer un Permis de Colportage
                </h2>
                <button type="button" id="toggle-colportage-form" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors text-white" style="background: linear-gradient(135deg, #059669, #047857);">
                    <i class="fas fa-plus" id="toggle-colportage-form-icon"></i>
                    <span id="toggle-colportage-form-text">Créer permis de colportage</span>
                </button>
            </div>
            <div id="colportage-form-container" class="hidden">
                <div class="p-6">
                    <form action="{{ route('articles.store-permis-colportage', $article) }}" method="POST">
                        @csrf
                        <x-validation-errors />

                        <div class="space-y-6 mb-6">
                            <x-form-section title="Permis d'Enlever" icon="fas fa-truck-loading" color="green" columns="1">
                                @if(isset($permisEnlevers) && $permisEnlevers->count() > 0)
                                    <div class="form-group">
                                        <label for="id_permis_enlever" class="block text-sm font-semibold text-gray-700 mb-2">Sélectionner un Permis d'Enlever <span class="text-red-500">*</span></label>
                                        <select name="id_permis_enlever" id="id_permis_enlever" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-form-input type="date" name="date_debut" label="Date de début" :required="true" :value="old('date_debut')" focusColor="blue" />
                                <x-form-input type="date" name="date_fin" label="Date de fin" :required="true" :value="old('date_fin')" focusColor="blue" />
                            </div>

                            <x-form-input type="text" name="vehicule_immatriculation" label="Immatriculation du véhicule" :required="true" :value="old('vehicule_immatriculation')" focusColor="blue" placeholder="Ex: A-12345-B" />

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-form-input type="text" name="chauffeur_nom" label="Nom du chauffeur" :required="true" :value="old('chauffeur_nom')" focusColor="blue" placeholder="Nom complet" />
                                <x-form-input type="text" name="chauffeur_cin" label="CIN du chauffeur" :required="true" :value="old('chauffeur_cin')" focusColor="blue" placeholder="Ex: AB123456" />
                            </div>

                            <x-form-input type="text" name="destination" label="Destination" :required="true" :value="old('destination')" focusColor="blue" placeholder="Adresse de destination" />

                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                        <i class="fas fa-tree text-white text-sm"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Essences de l'Article</h3>
                                </div>
                                @if($article->essences->isEmpty())
                                    <div class="text-center py-8 text-gray-600">
                                        <i class="fas fa-tree text-gray-400 text-4xl mb-3"></i>
                                        <p>Aucune essence associée à l'article.</p>
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
                                                            <span class="text-sm font-bold text-gray-900 permis-enlever-quantity" id="permis-quantity-{{ $index }}">0.00 m³</span>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <input type="number" name="essences[{{ $index }}][quantity]" id="colportage-quantity-{{ $index }}" step="0.01" min="0" value="{{ old('essences.' . $index . '.quantity', '0') }}"
                                                                   class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="0.00">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <x-form-section title="Informations de l'Article" icon="fas fa-info-circle" color="purple" columns="3">
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
                            </x-form-section>
                        </div>

                        <div class="flex justify-end gap-4 pt-6 border-t border-green-200">
                            <button type="button" id="cancel-colportage-form" class="inline-flex items-center gap-2 px-6 py-3 border border-green-300 rounded-xl text-green-700 hover:bg-green-50 transition-all">
                                <i class="fas fa-times"></i>
                                <span>Annuler</span>
                            </button>
                            <button type="submit" {{ (!isset($permisEnlevers) || $permisEnlevers->count() === 0) ? 'disabled' : '' }}
                                    class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg font-medium hover:opacity-90 transition-opacity"
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
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Permis d'Enlever filter: reload page with selected id
    var filterEl = document.getElementById('permis_enlever_filter');
    if (filterEl) {
        filterEl.addEventListener('change', function() {
            var id = this.value;
            var url = new URL(window.location.href);
            if (id) {
                url.searchParams.set('permis_enlever_id', id);
            } else {
                url.searchParams.delete('permis_enlever_id');
            }
            window.location.href = url.toString();
        });
    }

    // Toggle create form
    var formContainer = document.getElementById('colportage-form-container');
    var toggleBtn = document.getElementById('toggle-colportage-form');
    var toggleText = document.getElementById('toggle-colportage-form-text');
    var toggleIcon = document.getElementById('toggle-colportage-form-icon');
    var cancelBtn = document.getElementById('cancel-colportage-form');

    function setFormVisible(visible) {
        if (visible) {
            formContainer.classList.remove('hidden');
            toggleText.textContent = 'Masquer le formulaire';
            toggleIcon.className = 'fas fa-chevron-up';
        } else {
            formContainer.classList.add('hidden');
            toggleText.textContent = 'Créer permis de colportage';
            toggleIcon.className = 'fas fa-plus';
        }
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            setFormVisible(formContainer.classList.contains('hidden'));
        });
    }
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            setFormVisible(false);
        });
    }

    // Show form if validation errors (old input)
    @if($errors->any())
        setFormVisible(true);
    @endif

    // Permis d'Enlever quantities in form
    var permisEnleverSelect = document.getElementById('id_permis_enlever');
    var permisEnleversData = @json($permisEnleversWithQuantities->mapWithKeys(function($permis) {
        return [$permis->id => ['essences' => $permis->quantities ?? []]];
    }));

    function loadPermisEnleverEssences(permisId) {
        if (!permisId || !permisEnleversData[permisId]) {
            document.querySelectorAll('.permis-enlever-quantity').forEach(function(el) {
                el.textContent = '0.00 m³';
            });
            return;
        }
        var essencesData = (permisEnleversData[permisId].essences || []);
        var quantityMap = {};
        essencesData.forEach(function(item) {
            var key = (parseInt(item.essence_id) || 0) + '_' + (parseInt(item.product_id) || 0);
            quantityMap[key] = parseFloat(item.quantity) || 0;
        });
        document.querySelectorAll('.essence-row').forEach(function(row) {
            var essenceId = parseInt(row.getAttribute('data-essence-id')) || 0;
            var productId = parseInt(row.getAttribute('data-product-id')) || 0;
            var key = essenceId + '_' + productId;
            var quantity = quantityMap[key] || 0;
            var quantityDisplay = row.querySelector('.permis-enlever-quantity');
            if (quantityDisplay) {
                quantityDisplay.textContent = quantity.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ').replace('.', ',') + ' m³';
            }
        });
    }

    if (permisEnleverSelect) {
        permisEnleverSelect.addEventListener('change', function() {
            loadPermisEnleverEssences(this.value);
        });
        if (permisEnleverSelect.value) {
            loadPermisEnleverEssences(permisEnleverSelect.value);
        }
    }
});
</script>
@endpush
@endsection
