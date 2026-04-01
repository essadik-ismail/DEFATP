@extends('layouts.app')

@section('title', 'Permis de Colportage - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
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

        <!-- Section 1: Two cards - Volume dans Permis d'Enlever & Volume utilisé pour colportage -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-semibold flex items-center gap-2" style="color: #1F2D24;">
                        <i class="fas fa-box-open" style="color: #6B7C72;"></i>
                        Volume dans Permis d'Enlever
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Volumes enregistrés dans le Permis d'Enlever (aucun calcul).</p>
                </div>
                <div class="p-6">
                    @php
                        $detailRows = collect();
                        if (!empty($selectedPermisEnleverId ?? null) && isset($permisEnleversWithQuantities)) {
                            $permisSelected = $permisEnleversWithQuantities->firstWhere('id', (int) $selectedPermisEnleverId);
                            if ($permisSelected && isset($permisSelected->quantities)) {
                                $detailRows = collect($permisSelected->quantities);
                            }
                        }
                    @endphp

                    @if($detailRows->isNotEmpty())
                        <div class="overflow-y-auto">
                            <table class="min-w-full text-xs divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-2 py-2 text-left font-semibold text-gray-600 uppercase">Essence</th>
                                        <th class="px-2 py-2 text-left font-semibold text-gray-600 uppercase">Produit</th>
                                        <th class="px-2 py-2 text-right font-semibold text-gray-600 uppercase">Volume</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($detailRows as $row)
                                        @php
                                            $ess = $article->essences->firstWhere('id', $row['essence_id'] ?? null);
                                            $prod = isset($products) ? $products->get($row['product_id'] ?? null) : null;
                                            $qty = (float)($row['quantity'] ?? 0);
                                        @endphp
                                        <tr>
                                            <td class="px-2 py-1 text-gray-800">
                                                {{ $ess?->essence ?? '-' }}
                                            </td>
                                            <td class="px-2 py-1 text-gray-600">
                                                {{ $prod?->name ?? '-' }}
                                            </td>
                                            <td class="px-2 py-1 text-right text-gray-900">
                                                {{ number_format($qty, 2, ',', ' ') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">
                            @if(empty($selectedPermisEnleverId))
                                Sélectionnez un Permis d'Enlever pour afficher les volumes.
                            @else
                                Aucun volume enregistré pour ce Permis d'Enlever.
                            @endif
                        </p>
                    @endif
                </div>
            </div>
            @php
                $volPermis = (float)($quantityInPermisEnlever ?? 0);
                $volColportage = (float)($quantityUsedColportage ?? 0);
                $volumeFullyUsed = $volPermis > 0 && abs($volColportage - $volPermis) < 0.001;
            @endphp
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-base font-semibold flex items-center gap-2" style="color: #1F2D24;">
                        <i class="fas fa-truck-loading" style="color: #6B7C72;"></i>
                        Volume utilisé pour colportage
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-2xl font-bold {{ $volumeFullyUsed ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($quantityUsedColportage ?? 0, 2, ',', ' ') }}
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
                <a href="{{ route('articles.permis-colportage.create', array_filter([$article, 'permis_enlever_id' => $selectedPermisEnleverId])) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors text-white"
                   style="background: linear-gradient(135deg, #059669, #047857);">
                    <i class="fas fa-plus"></i>
                    <span>Créer un permis de colportage</span>
                </a>
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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Consommation</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($listPermisColportage as $pc)
                                @php
                                    $totalPermis = (float)($quantityInPermisEnlever ?? 0);
                                    $usedForThisPermis = (float)($pc->total_quantity ?? 0);
                                    $percent = $totalPermis > 0 ? round(($usedForThisPermis / $totalPermis) * 100, 1) : 0;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $pc->numero_permis ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pc->date_debut ? $pc->date_debut->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pc->date_fin ? $pc->date_fin->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pc->vehicule_immatriculation ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pc->chauffeur_nom ?? '-' }} ({{ $pc->chauffeur_cin ?? '-' }})</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $pc->destination ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        @if($totalPermis > 0)
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                                                    <div class="h-2 rounded-full {{ $percent >= 100 ? 'bg-red-500' : 'bg-green-500' }}"
                                                         style="width: {{ min($percent, 100) }}%;"></div>
                                                </div>
                                                <span class="text-xs font-medium text-gray-800 whitespace-nowrap">
                                                    {{ number_format($percent, 1, ',', ' ') }} %
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <i class="fas fa-file-alt text-4xl mb-3 opacity-50"></i>
                        <p>Aucun permis de colportage pour ce Permis d'Enlever.</p>
                        <p class="text-sm mt-1">
                            <a href="{{ route('articles.permis-colportage.create', array_filter([$article, 'permis_enlever_id' => $selectedPermisEnleverId])) }}" class="text-green-600 hover:underline font-medium">Créer un permis de colportage</a> pour en ajouter un.
                        </p>
                    </div>
                @endif
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
});
</script>
@endpush
@endsection
