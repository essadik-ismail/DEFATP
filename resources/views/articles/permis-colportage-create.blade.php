@extends('layouts.app')

@section('title', 'Creer un Permis de Colportage - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">Detail #{{ $article->numero ?? $article->id }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('articles.permis-colportage', $article) }}">Permis de colportage</a></li>
<li class="breadcrumb-item active">Creer</li>
@endsection

@section('content')
@php
    $hasPermisEnlevers = isset($permisEnlevers) && $permisEnlevers->count() > 0;
    $hasCarnetsDisponibles = isset($carnetsDisponibles) && $carnetsDisponibles->isNotEmpty();
    $canSubmit = $hasPermisEnlevers && $hasCarnetsDisponibles;
@endphp

<div class="min-h-screen py-8">
    <div class="container mx-auto max-w-7xl px-4">
        <x-page-header
            title="Creer un Permis de Colportage"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-file-signature"
            :backRoute="route('articles.permis-colportage', array_filter([$article, 'permis_enlever_id' => $selectedPermisEnleverId]))"
            backText="Retour a la liste"
        />

        @if(session('success'))
            <x-alert type="success" title="Succes!" dismissible>
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="error" title="Erreur!" dismissible>
                {{ session('error') }}
            </x-alert>
        @endif

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="p-6">
                <form action="{{ route('articles.store-permis-colportage', $article) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <x-validation-errors />

                    <div class="mb-6 space-y-6">
                        <x-form-section title="Permis d'Enlever" icon="fas fa-truck-loading" color="green" columns="1">
                            @if($hasPermisEnlevers)
                                <div class="form-group">
                                    <label for="id_permis_enlever" class="mb-2 block text-sm font-semibold text-gray-700">
                                        Selectionner un Permis d'Enlever <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        name="id_permis_enlever"
                                        id="id_permis_enlever"
                                        class="form-input w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                        required
                                    >
                                        <option value="">Choisir...</option>
                                        @foreach($permisEnlevers as $permis)
                                            <option value="{{ $permis->id }}" {{ old('id_permis_enlever', $selectedPermisEnleverId ?? null) == $permis->id ? 'selected' : '' }}>
                                                {{ $permis->num_quittance ?? ('Permis #' . $permis->id) }}
                                                - {{ $permis->date ? \Carbon\Carbon::parse($permis->date)->format('d/m/Y') : '' }}
                                                - Tranches: {{ $permis->num_tranche_paye ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <x-alert type="warning" title="Aucun Permis d'Enlever">
                                    Vous devez d'abord creer un <strong>Permis d'Enlever</strong> avant de generer un Permis de Colportage.
                                </x-alert>
                            @endif

                            <div class="form-group mt-4">
                                <label for="carnet_id" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Numero de permis de colportage <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="carnet_id"
                                    id="carnet_id"
                                    class="form-input w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                    @if($hasCarnetsDisponibles) required @else disabled @endif
                                >
                                    @if($hasCarnetsDisponibles)
                                        <option value="">Choisir un numero...</option>
                                        @foreach($carnetsDisponibles as $carnet)
                                            <option value="{{ $carnet->id }}" {{ old('carnet_id') == $carnet->id ? 'selected' : '' }}>
                                                {{ $carnet->serie }} - n&deg; {{ $carnet->num }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="">Aucun numero disponible</option>
                                    @endif
                                </select>
                                @error('carnet_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    Selectionnez le numero affecte a ce permis de colportage. Il sera marque comme epuise apres enregistrement.
                                </p>
                            </div>

                            @if(!$hasCarnetsDisponibles)
                                <x-alert type="warning" title="Aucun numero disponible" class="mt-4">
                                    Aucun numero de permis de colportage n'est actuellement disponible. Veuillez en creer un dans les carnets avant de generer ce permis.
                                </x-alert>
                            @endif
                        </x-form-section>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <x-form-input type="date" name="date_debut" label="Date de debut" :required="true" :value="old('date_debut')" focusColor="blue" />
                            <x-form-input type="date" name="date_fin" label="Date de fin" :required="true" :value="old('date_fin')" focusColor="blue" />
                        </div>

                        <x-form-input type="text" name="vehicule_immatriculation" label="Immatriculation du vehicule" :required="true" :value="old('vehicule_immatriculation')" focusColor="blue" placeholder="Ex: A-12345-B" />

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <x-form-input type="text" name="chauffeur_nom" label="Nom du chauffeur" :required="true" :value="old('chauffeur_nom')" focusColor="blue" placeholder="Nom complet" />
                            <x-form-input type="text" name="chauffeur_cin" label="CIN du chauffeur" :required="true" :value="old('chauffeur_cin')" focusColor="blue" placeholder="Ex: AB123456" />
                        </div>

                        <x-form-input type="text" name="destination" label="Destination" :required="true" :value="old('destination')" focusColor="blue" placeholder="Adresse de destination" />

                        <div class="mt-4">
                            <label for="fichier_joint" class="mb-2 block text-sm font-semibold text-gray-700">
                                Fichier joint (PDF ou image)
                            </label>
                            <input
                                type="file"
                                name="fichier_joint"
                                id="fichier_joint"
                                class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-green-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-green-700 hover:file:bg-green-100"
                                accept="application/pdf,image/*"
                            >
                            <p class="mt-1 text-xs text-gray-500">
                                Optionnel. Vous pouvez joindre une copie scannee du permis ou tout document justificatif.
                            </p>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-600">
                                    <i class="fas fa-tree text-sm text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Essences de l'Article</h3>
                            </div>

                            @if($article->essences->isEmpty())
                                <div class="py-8 text-center text-gray-600">
                                    <i class="fas fa-tree mb-3 text-4xl text-gray-400"></i>
                                    <p>Aucune essence associee a l'article.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-700">Essence</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-700">Produit</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-700">Volume dans Permis d'Enlever</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-700">Volume</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white" id="essences-table-body">
                                            @foreach($article->essences as $index => $essence)
                                                @php
                                                    $product = isset($products) && $essence->pivot->product_id ? $products->get($essence->pivot->product_id) : null;
                                                @endphp
                                                <tr class="essence-row hover:bg-gray-50" data-essence-id="{{ $essence->id }}" data-product-id="{{ $essence->pivot->product_id }}">
                                                    <td class="px-4 py-3">
                                                        <span class="text-sm font-semibold text-gray-900">{{ $essence->essence ?? 'N/A' }}</span>
                                                        <input type="hidden" name="essences[{{ $index }}][essence_id]" value="{{ $essence->id }}">
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="text-sm text-gray-700">{{ $product ? $product->name : 'N/A' }}</span>
                                                        <input type="hidden" name="essences[{{ $index }}][product_id]" value="{{ $essence->pivot->product_id }}">
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="permis-enlever-quantity text-sm font-bold text-gray-900" id="permis-quantity-{{ $index }}">0.00</span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <input
                                                            type="number"
                                                            name="essences[{{ $index }}][quantity]"
                                                            id="colportage-quantity-{{ $index }}"
                                                            step="0.01"
                                                            min="0"
                                                            value="{{ old('essences.' . $index . '.quantity', '0') }}"
                                                            class="form-input w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                                            placeholder="0.00"
                                                        >
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
                                <label class="mb-1 block text-sm font-medium text-purple-700">Numero</label>
                                <p class="font-semibold text-purple-900">{{ $article->numero }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-purple-700">Lot</label>
                                <p class="font-semibold text-purple-900">{{ $article->lot }}</p>
                            </div>
                        </x-form-section>
                    </div>

                    <div class="flex justify-end gap-4 border-t border-green-200 pt-6">
                        <a href="{{ route('articles.permis-colportage', array_filter([$article, 'permis_enlever_id' => $selectedPermisEnleverId])) }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-green-300 px-6 py-3 text-green-700 transition-all hover:bg-green-50">
                            <i class="fas fa-times"></i>
                            <span>Annuler</span>
                        </a>
                        <button
                            type="submit"
                            @if(!$canSubmit) disabled @endif
                            class="inline-flex items-center gap-2 rounded-lg px-4 py-2 font-medium text-white transition-opacity hover:opacity-90"
                            style="background: linear-gradient(135deg, #059669, #047857); {{ !$canSubmit ? 'opacity:0.6; cursor:not-allowed;' : '' }}"
                        >
                            <i class="fas fa-truck"></i>
                            <span>Generer le Permis</span>
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
    var permisEnleverSelect = document.getElementById('id_permis_enlever');
    var permisEnleversData = @json($permisEnleversWithQuantities->mapWithKeys(function($permis) {
        return [$permis->id => ['essences' => $permis->quantities ?? []]];
    }));

    function loadPermisEnleverEssences(permisId) {
        if (!permisId || !permisEnleversData[permisId]) {
            document.querySelectorAll('.permis-enlever-quantity').forEach(function(el) {
                el.textContent = '0.00';
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
                quantityDisplay.textContent = quantity.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ').replace('.', ',');
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
