@extends('layouts.app')

@section('title', 'Créer un Permis d\'Enlever - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="bc-item"><a href="{{ route('articles.show', $article) }}">Détail #{{ $article->numero ?? $article->id }}</a></li>
<li class="bc-item active">Créer un Permis d'Enlever</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-7xl">

        <x-page-header
            title="Créer un Permis d'Enlever"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-file-contract"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form id="permisEnleverCreateForm" action="{{ route('articles.store-permis-enlever', $article) }}" method="POST" class="space-y-8">
                @csrf
                <x-validation-errors />

                <!-- 1. Informations du Permis d'Enlever -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                            <i class="fas fa-truck-loading text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">1. Informations du Permis d'Enlever</h3>
                    </div>
                    @php
                        $firstPayment = $availablePayments->first();
                        $preselectedDate = old('date', $firstPayment['date'] ?? '');
                    @endphp
                    <script>
                        var availablePayments = @json($availablePayments->keyBy('date'));
                    </script>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group md:col-span-2">
                            <label for="tranche_select" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tranche payée <span class="text-red-500">*</span>
                            </label>
                            @if($availablePayments->count() === 1)
                                <div class="px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-700 text-sm">
                                    {{ $firstPayment['tranche_label'] }} — {{ \Carbon\Carbon::parse($firstPayment['date'])->format('d/m/Y') }}
                                </div>
                            @else
                                <select id="tranche_select"
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                        onchange="onTrancheChange(this.value)" required>
                                    <option value="">-- Sélectionner une tranche --</option>
                                    @foreach($availablePayments as $ap)
                                        <option value="{{ $ap['date'] }}" {{ $preselectedDate === $ap['date'] ? 'selected' : '' }}>
                                            {{ $ap['tranche_label'] }} — {{ \Carbon\Carbon::parse($ap['date'])->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="num_quittance_display" class="block text-sm font-semibold text-gray-700 mb-2">
                                N° Quittance (Paiement)
                            </label>
                            <input type="text" id="num_quittance_display" value="" readonly
                                   title="Repris automatiquement du paiement sélectionné"
                                   class="form-input w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed text-gray-600">
                        </div>

                        <div class="form-group">
                            <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de paiement
                            </label>
                            <input type="date" name="date" id="date" value="" readonly
                                   title="Reprise automatiquement du paiement sélectionné"
                                   class="form-input w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed text-gray-600"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="percepteur_enlever" class="block text-sm font-semibold text-gray-700 mb-2">
                                Percepteur (Enlever) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="percepteur_enlever" id="percepteur_enlever"
                                   value="{{ old('percepteur_enlever') }}" placeholder="Nom du percepteur"
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- 2. Essences de l'Article -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                            <i class="fas fa-tree text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">2. Essences de l'Article</h3>
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
                                        $defaultQty = $nombreTranche > 0
                                            ? round($essence->pivot->quantity / $nombreTranche, 2)
                                            : $essence->pivot->quantity;
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
                                            <span class="text-sm font-bold text-gray-900">
                                                {{ number_format($essence->pivot->quantity, 2, ',', ' ') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number"
                                                   name="essences[{{ $index }}][quantity]"
                                                   id="quantity-{{ $index }}"
                                                   value="{{ old('essences.' . $index . '.quantity', $defaultQty) }}"
                                                   step="0.01" min="0" readonly
                                                   class="form-input w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed text-gray-600"
                                                   required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('articles.show', $article) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times"></i>
                        <span>Annuler</span>
                    </a>
                    <button type="submit"
                            {{ $article->essences->isEmpty() ? 'disabled' : '' }}
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors {{ $article->essences->isEmpty() ? 'opacity-60 cursor-not-allowed' : '' }}">
                        <i class="fas fa-file-download"></i>
                        <span>Générer le Permis</span>
                    </button>
                </div>
            </form>
        </div>

        <script>
            function onTrancheChange(date) {
                populatePaymentFields(date);
            }

            function populatePaymentFields(date) {
                var payment = availablePayments[date];
                document.getElementById('date').value = date || '';
                document.getElementById('num_quittance_display').value = payment ? (payment.num_quittace || '') : '';
            }

            document.addEventListener('DOMContentLoaded', function () {
                var keys = Object.keys(availablePayments);
                if (keys.length === 1) {
                    populatePaymentFields(keys[0]);
                } else {
                    var select = document.getElementById('tranche_select');
                    if (select && select.value) {
                        populatePaymentFields(select.value);
                    }
                }
            });
        </script>

    </div>
</div>
@endsection
