@extends('layouts.app')

@section('title', 'Creer un Permis de Colportage - DEFATP')

@section('breadcrumb')
    <li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    <li class="bc-item"><a href="{{ route('articles.show', $article) }}">Dossier #{{ $article->numero ?? $article->id }}</a></li>
    <li class="bc-item active">Permis de colportage</li>
@endsection

@section('content')
    @php
        $hasPermisEnlevers = isset($permisEnlevers) && $permisEnlevers->count() > 0;
        $hasCarnetsDisponibles = isset($carnetsDisponibles) && $carnetsDisponibles->isNotEmpty();
        $canSubmit = $hasPermisEnlevers && $hasCarnetsDisponibles;
        $currentPermisEnleverId = old('id_permis_enlever', $selectedPermisEnleverId ?? null);
        $selectedPermisEnleverRows = collect();

        if (!empty($currentPermisEnleverId) && isset($permisEnleversWithQuantities)) {
            $selectedPermis = $permisEnleversWithQuantities->firstWhere('id', (int) $currentPermisEnleverId);

            if ($selectedPermis && isset($selectedPermis->detail_rows)) {
                $selectedPermisEnleverRows = collect($selectedPermis->detail_rows);
            }
        }

        $oldColportageQuantities = collect(old('essences', []))
            ->filter(function ($row) {
                return !empty($row['essence_id']) && !empty($row['product_id']);
            })
            ->mapWithKeys(function ($row) {
                return [((int) $row['essence_id']) . '_' . ((int) $row['product_id']) => $row['quantity'] ?? '0'];
            })
            ->all();
    @endphp

    <div class="min-w-0 max-w-full overflow-x-hidden">
        <div class="container mx-auto max-w-7xl px-4">
            <x-page-header title="Creer un Permis de Colportage" :subtitle="'Article #' . ($article->numero ?? $article->id)" icon="fas fa-file-signature"
                :backRoute="route('articles.show', $article)" backText="Retour au detail" />

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="p-6">
                    <form action="{{ route('articles.store-permis-colportage', $article) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <x-validation-errors />

                        <div class="mb-6 space-y-6">
                            <x-form-section title="Permis d'Enlever" icon="fas fa-truck-loading" color="green"
                                columns="1">
                                @if ($hasPermisEnlevers)
                                    <div class="form-group">
                                        <label for="id_permis_enlever"
                                            class="mb-2 block text-sm font-semibold text-gray-700">
                                            Selectionner un Permis d'Enlever <span class="text-red-500">*</span>
                                        </label>
                                        <select name="id_permis_enlever" id="id_permis_enlever"
                                            class="form-input w-full rounded-lg border border-gray-300 px-4 py-3 bg-gray-50 cursor-not-allowed focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                            disabled required>
                                            <option value="">Choisir...</option>
                                            @foreach ($permisEnlevers as $permis)
                                                <option value="{{ $permis->id }}"
                                                    {{ $currentPermisEnleverId == $permis->id ? 'selected' : '' }}>
                                                    {{ $permis->num_quittance ?? 'Permis #' . $permis->id }}
                                                    -
                                                    {{ $permis->date ? \Carbon\Carbon::parse($permis->date)->format('d/m/Y') : '' }}
                                                    - Tranches: {{ $permis->num_tranche_paye ?? '-' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="id_permis_enlever" value="{{ $currentPermisEnleverId }}">
                                    </div>
                                @else
                                    <x-alert type="warning" title="Aucun Permis d'Enlever">
                                        Vous devez d'abord creer un <strong>Permis d'Enlever</strong> avant de generer un
                                        Permis de Colportage.
                                    </x-alert>
                                @endif

                                <div class="form-group mt-4">
                                    <label for="carnet_id" class="mb-2 block text-sm font-semibold text-gray-700">
                                        Numero de permis de colportage <span class="text-red-500">*</span>
                                    </label>
                                    <select name="carnet_id" id="carnet_id"
                                        class="form-input w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                        @if ($hasCarnetsDisponibles) required @else disabled @endif>
                                        @if ($hasCarnetsDisponibles)
                                            <option value="">Choisir un numero...</option>
                                            @foreach ($carnetsDisponibles as $carnet)
                                                <option value="{{ $carnet->id }}"
                                                    {{ old('carnet_id') == $carnet->id ? 'selected' : '' }}>
                                                    {{ $carnet->type }} - {{ $carnet->serie }} - n&deg;
                                                    {{ $carnet->num }}
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
                                        Selectionnez le numero affecte a ce permis de colportage. Il sera marque comme
                                        epuise apres enregistrement.
                                    </p>
                                </div>

                                @if (!$hasCarnetsDisponibles)
                                    <x-alert type="warning" title="Aucun numero disponible" class="mt-4">
                                        Aucun numero de permis de colportage n'est actuellement disponible. Veuillez en
                                        creer un dans les carnets avant de generer ce permis.
                                    </x-alert>
                                @endif
                            </x-form-section>

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <x-form-input type="datetime-local" name="date_debut" label="Date et heure de début"
                                    :required="true" :value="old('date_debut')" focusColor="blue" />
                                <x-form-input type="datetime-local" name="date_fin" label="Date et heure de fin"
                                    :required="true" :value="old('date_fin')" focusColor="blue" />
                            </div>

                            {{-- Vehicle select --}}
                            <div class="form-group">
                                <label for="vehicle_select" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Véhicule <span class="text-red-500">*</span>
                                </label>
                                @if (isset($vehicles) && $vehicles->isNotEmpty())
                                    <select id="vehicle_select"
                                        class="form-input w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                        required>
                                        <option value="">— Sélectionner un véhicule —</option>
                                        @foreach ($vehicles as $v)
                                            <option value="{{ $v->immatriculation }}"
                                                data-chauffeur-nom="{{ $v->chauffeur_nom }}"
                                                data-chauffeur-cin="{{ $v->chauffeur_cin }}"
                                                {{ old('vehicule_immatriculation') === $v->immatriculation ? 'selected' : '' }}>
                                                {{ $v->immatriculation }}
                                                @if ($v->marque)
                                                    — {{ $v->marque }}
                                                @endif
                                                @if ($v->chauffeur_nom)
                                                    — {{ $v->chauffeur_nom }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <p
                                        class="text-sm text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Aucun véhicule déclaré.
                                        <a href="{{ route('vehicles.create', $article) }}"
                                            class="underline font-medium">Déclarer un véhicule</a>
                                    </p>
                                @endif
                                <input type="hidden" name="vehicule_immatriculation" id="vehicule_immatriculation"
                                    value="{{ old('vehicule_immatriculation') }}" required>
                            </div>

                            {{-- Chauffeur fields — auto-filled from vehicle, editable as fallback --}}
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mt-4">
                                <div class="form-group">
                                    <label for="chauffeur_nom" class="mb-2 block text-sm font-semibold text-gray-700">
                                        Nom du chauffeur <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="chauffeur_nom" name="chauffeur_nom"
                                        value="{{ old('chauffeur_nom') }}"
                                        placeholder="Nom complet du chauffeur"
                                        class="form-input w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                        required>
                                    @error('chauffeur_nom')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="chauffeur_cin" class="mb-2 block text-sm font-semibold text-gray-700">
                                        CIN du chauffeur <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="chauffeur_cin" name="chauffeur_cin"
                                        value="{{ old('chauffeur_cin') }}"
                                        placeholder="Numéro CIN"
                                        class="form-input w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                        required>
                                    @error('chauffeur_cin')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <x-form-input type="text" name="destination" label="Destination" :required="true"
                                :value="old('destination')" focusColor="blue" placeholder="Adresse de destination" />

                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                {{-- Transport pendant la nuit --}}
                                <div class="form-group">
                                    <label class="mb-2 block text-sm font-semibold text-gray-700">Transport pendant la
                                        nuit</label>
                                    <div class="flex gap-6 mt-1">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="transport_nuit" value="1"
                                                {{ old('transport_nuit') == '1' ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Oui</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="transport_nuit" value="0"
                                                {{ old('transport_nuit', '0') == '0' ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Non</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Distance --}}
                                <x-form-input type="number" name="distance_km" label="Distance (km)" :value="old('distance_km')"
                                    focusColor="blue" placeholder="0.00" />
                            </div>

                            <div class="mt-4">
                                <label for="fichier_joint" class="mb-2 block text-sm font-semibold text-gray-700">
                                    Fichier joint (PDF ou image) <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="fichier_joint" id="fichier_joint"
                                    class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-green-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-green-700 hover:file:bg-green-100"
                                    accept="application/pdf,image/*" required>
                                @error('fichier_joint')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
                                <div class="mb-4 flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-600">
                                        <i class="fas fa-tree text-sm text-white"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Essences du Permis d'Enlever</h3>
                                </div>

                                @if ($currentPermisEnleverId && $selectedPermisEnleverRows->isEmpty())
                                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 mb-4">
                                        <p class="text-sm text-red-700">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Ce permis d'enlever ne contient aucune essence enregistrée. Veuillez recréer le
                                            permis d'enlever pour que les essences soient correctement sauvegardées.
                                        </p>
                                    </div>
                                @endif

                                <div class="overflow-x-auto">
                                    <table class="w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-700">
                                                    Essence</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-700">
                                                    Produit</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-700">
                                                    Volume permis d'enlever</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-700">
                                                    Déjà utilisé</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-700">
                                                    Restant</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-700">
                                                    Volume / Quantité</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white" id="essences-table-body">
                                            @forelse($selectedPermisEnleverRows as $index => $row)
                                                @php
                                                    $rowKey =
                                                        ((int) ($row['essence_id'] ?? 0)) .
                                                        '_' .
                                                        ((int) ($row['product_id'] ?? 0));
                                                    $parent =
                                                        (float) ($row['parent_quantity'] ??
                                                            ($row['permis_quantity'] ?? ($row['quantity'] ?? 0)));
                                                    $used = (float) ($row['used_quantity'] ?? 0);
                                                    $remaining =
                                                        (float) ($row['remaining_quantity'] ?? max(0, $parent - $used));
                                                    $usedPct = $parent > 0 ? round(($used / $parent) * 100, 1) : 0;
                                                @endphp
                                                <tr class="essence-row hover:bg-gray-50"
                                                    data-row-key="{{ $rowKey }}"
                                                    data-essence-id="{{ $row['essence_id'] ?? 0 }}"
                                                    data-product-id="{{ $row['product_id'] ?? 0 }}">
                                                    <td class="px-4 py-3">
                                                        <span
                                                            class="text-sm font-semibold text-gray-900">{{ $row['essence_name'] ?? 'N/A' }}</span>
                                                        <input type="hidden"
                                                            name="essences[{{ $index }}][essence_id]"
                                                            value="{{ $row['essence_id'] ?? 0 }}">
                                                        <input type="hidden"
                                                            name="essences[{{ $index }}][product_id]"
                                                            value="{{ $row['product_id'] ?? 0 }}">
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span
                                                            class="text-sm text-gray-600">{{ $row['product_name'] ?? 'N/A' }}</span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span
                                                            class="text-sm font-bold text-gray-900">{{ number_format($parent, 2, ',', ' ') }}</span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span
                                                            class="text-sm text-gray-700">{{ number_format($used, 2, ',', ' ') }}</span>
                                                        <span class="text-xs text-gray-400 ml-1">({{ $usedPct }}
                                                            %)</span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span
                                                            class="text-sm font-semibold {{ $remaining <= 0 ? 'text-red-600' : 'text-green-700' }}">
                                                            {{ number_format($remaining, 2, ',', ' ') }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <input type="number"
                                                            name="essences[{{ $index }}][quantity]" step="0.01"
                                                            min="0" max="{{ $remaining }}"
                                                            value="{{ $oldColportageQuantities[$rowKey] ?? ($remaining > 0 ? number_format($remaining, 2, '.', '') : '0') }}"
                                                            class="form-input w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                                            placeholder="0.00">
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6"
                                                        class="px-4 py-6 text-center text-sm text-gray-500">
                                                        {{ $currentPermisEnleverId ? "Aucune essence enregistrée pour ce permis d'enlever." : "Sélectionnez un permis d'enlever pour afficher ses essences." }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="flex justify-end gap-4 border-t border-green-200 pt-6">
                            <a href="{{ route('articles.show', $article) }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-green-300 px-6 py-3 text-green-700 transition-all hover:bg-green-50">
                                <i class="fas fa-times"></i>
                                <span>Annuler</span>
                            </a>
                            <button type="submit" @if (!$canSubmit) disabled @endif
                                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 font-medium text-white transition-opacity hover:opacity-90"
                                style="background: linear-gradient(135deg, #059669, #047857); {{ !$canSubmit ? 'opacity:0.6; cursor:not-allowed;' : '' }}">
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

                // ── Vehicle select → auto-fill immatriculation + chauffeur fields ──
                var vehicleSelect = document.getElementById('vehicle_select');
                if (vehicleSelect) {
                    function syncVehicle() {
                        var opt = vehicleSelect.options[vehicleSelect.selectedIndex];
                        document.getElementById('vehicule_immatriculation').value = opt.value;
                        var nomField = document.getElementById('chauffeur_nom');
                        var cinField = document.getElementById('chauffeur_cin');
                        // Only overwrite if currently empty or was auto-populated (not manually edited)
                        if (nomField && opt.dataset.chauffeurNom) nomField.value = opt.dataset.chauffeurNom;
                        if (cinField && opt.dataset.chauffeurCin) cinField.value = opt.dataset.chauffeurCin;
                    }
                    vehicleSelect.addEventListener('change', syncVehicle);
                    if (vehicleSelect.value) syncVehicle();
                }

                var permisEnleverSelect = document.getElementById('id_permis_enlever');
                var tableBody = document.getElementById('essences-table-body');
                var permisEnleversData = @json(
                    $permisEnleversWithQuantities->mapWithKeys(function ($permis) {
                        return [$permis->id => ['essences' => $permis->detail_rows ?? []]];
                    }));
                var oldColportageQuantities = @json($oldColportageQuantities);
                var useOldColportageValues = @json(!empty(old('essences')));

                function escapeHtml(value) {
                    return String(value ?? '').replace(/[&<>"']/g, function(character) {
                        return {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#39;'
                        } [character];
                    });
                }

                function formatQuantity(value) {
                    var number = parseFloat(value);

                    if (isNaN(number)) {
                        number = 0;
                    }

                    return number.toLocaleString('fr-FR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                function renderEmptyState(message) {
                    if (!tableBody) return;
                    tableBody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">' +
                        escapeHtml(message) + '</td></tr>';
                }

                function buildRow(item, index, initialQuantity) {
                    var essenceId = parseInt(item.essence_id) || 0;
                    var productId = parseInt(item.product_id) || 0;
                    var parent = parseFloat(
                        item.parent_quantity !== undefined ?
                        item.parent_quantity :
                        (item.permis_quantity !== undefined ? item.permis_quantity : item.quantity)
                    ) || 0;
                    var used = parseFloat(item.used_quantity) || 0;
                    var remaining = parseFloat(item.remaining_quantity !== undefined ? item.remaining_quantity : Math
                        .max(0, parent - used));
                    var usedPct = parent > 0 ? (used / parent * 100).toFixed(1) : '0.0';
                    var remColor = remaining <= 0 ? 'text-red-600' : 'text-green-700';

                    return '<tr class="essence-row hover:bg-gray-50" data-row-key="' + essenceId + '_' + productId +
                        '" data-essence-id="' + essenceId + '" data-product-id="' + productId + '">' +
                        '<td class="px-4 py-3">' +
                        '<span class="text-sm font-semibold text-gray-900">' + escapeHtml(item.essence_name || 'N/A') +
                        '</span>' +
                        '<input type="hidden" name="essences[' + index + '][essence_id]" value="' + essenceId + '">' +
                        '<input type="hidden" name="essences[' + index + '][product_id]" value="' + productId + '">' +
                        '</td>' +
                        '<td class="px-4 py-3">' +
                        '<span class="text-sm text-gray-600">' + escapeHtml(item.product_name || 'N/A') + '</span>' +
                        '</td>' +
                        '<td class="px-4 py-3">' +
                        '<span class="text-sm font-bold text-gray-900">' + formatQuantity(parent) + '</span>' +
                        '</td>' +
                        '<td class="px-4 py-3">' +
                        '<span class="text-sm text-gray-700">' + formatQuantity(used) + '</span>' +
                        '<span class="text-xs text-gray-400 ml-1">(' + usedPct + ' %)</span>' +
                        '</td>' +
                        '<td class="px-4 py-3">' +
                        '<span class="text-sm font-semibold ' + remColor + '">' + formatQuantity(remaining) +
                        '</span>' +
                        '</td>' +
                        '<td class="px-4 py-3">' +
                        '<input type="number" name="essences[' + index + '][quantity]" step="0.01" min="0" max="' +
                        remaining + '" value="' + escapeHtml(initialQuantity) +
                        '" class="form-input w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-green-500 focus:ring-2 focus:ring-green-500" placeholder="0.00">' +
                        '</td>' +
                        '</tr>';
                }

                function loadPermisEnleverEssences(permisId, useOldValues) {
                    if (!tableBody) {
                        return;
                    }

                    if (!permisId || !permisEnleversData[permisId]) {
                        renderEmptyState("Selectionnez un Permis d'Enlever pour afficher ses essences et produits.");
                        return;
                    }

                    var essencesData = (permisEnleversData[permisId].essences || []);

                    if (!essencesData.length) {
                        renderEmptyState("Aucune essence / produit enregistre pour ce Permis d'Enlever.");
                        return;
                    }

                    tableBody.innerHTML = essencesData.map(function(item, index) {
                        var rowKey = (parseInt(item.essence_id) || 0) + '_' + (parseInt(item.product_id) || 0);
                        var remaining = parseFloat(item.remaining_quantity !== undefined ? item
                            .remaining_quantity : Math.max(0, (parseFloat(item.parent_quantity || item
                                .permis_quantity || item.quantity) || 0) - (parseFloat(item
                                .used_quantity) || 0)));
                        var parentQty = parseFloat(item.parent_quantity || item.permis_quantity || item
                            .quantity) || 0;
                        var initialQuantity = useOldValues && oldColportageQuantities[rowKey] !== undefined ?
                            String(oldColportageQuantities[rowKey]) :
                            (remaining > 0 ? remaining.toFixed(2) : '0');

                        return buildRow(item, index, initialQuantity);
                    }).join('');
                }

                if (permisEnleverSelect) {
                    permisEnleverSelect.addEventListener('change', function() {
                        loadPermisEnleverEssences(this.value, false);
                    });

                    loadPermisEnleverEssences(permisEnleverSelect.value, useOldColportageValues);
                }
            });
        </script>
    @endpush
@endsection
