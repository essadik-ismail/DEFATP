@extends('layouts.app')

@section('title', 'PV de Récolement - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
<li class="bc-item active">PV de Récolement</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-4xl">

        <x-page-header
            title="PV de Récolement"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-clipboard-check"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        @php $isSubmitted = $recolement->status !== \App\Models\Recolement::STATUS_PENDING_PV && $recolement->exists; @endphp

        @if($isSubmitted)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-2 text-green-800 text-sm font-semibold mb-2">
                <i class="fas fa-check-circle"></i> PV déjà soumis
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm text-green-800">
                <div><span class="font-medium">N° PV :</span> {{ $recolement->num_pv }}</div>
                <div><span class="font-medium">Date :</span> {{ $recolement->date_pv?->format('d/m/Y') }}</div>
                @if($recolement->num_mainlevee)
                <div><span class="font-medium">N° Mainlevée :</span> {{ $recolement->num_mainlevee }}</div>
                <div><span class="font-medium">Date mainlevée :</span> {{ $recolement->date_mainlevee?->format('d/m/Y') }}</div>
                @endif
            </div>
        </div>

        @if($recolement->status === \App\Models\Recolement::STATUS_PV_SUBMITTED)
        @can('mainlevee.issue')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-stamp text-green-600"></i> Émettre la Mainlevée
            </h3>
            <form action="{{ route('workflow.mainlevee.issue', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <x-validation-errors />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="form-group">
                        <label for="num_mainlevee" class="block text-sm font-semibold text-gray-700 mb-2">
                            N° Mainlevée <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="num_mainlevee" id="num_mainlevee" value="{{ old('num_mainlevee') }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               required maxlength="80" placeholder="Ex : ML-2026-001">
                    </div>
                    <div class="form-group">
                        <label for="date_mainlevee" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date mainlevée <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_mainlevee" id="date_mainlevee" value="{{ old('date_mainlevee', now()->format('Y-m-d')) }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               required>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label for="fichier_mainlevee" class="block text-sm font-semibold text-gray-700 mb-2">Fichier mainlevée (PDF)</label>
                        <input type="file" name="fichier_mainlevee" id="fichier_mainlevee" accept=".pdf"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                        <i class="fas fa-stamp"></i> Émettre la mainlevée
                    </button>
                </div>
            </form>
        </div>
        @endcan
        @endif

        @else
        {{-- PV submission form --}}
        <form action="{{ route('workflow.recolement.store', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <x-validation-errors />

            {{-- Section: PV de récolement --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-bold text-gray-800 mb-4 underline">PV de récolement</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Date de récolement</label>
                        <input type="date" name="date_recolement"
                               value="{{ old('date_recolement', $recolement->date_recolement?->format('Y-m-d')) }}"
                               class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <div class="form-group flex-1 min-w-[180px]">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Adjudication</label>
                        <input type="text" name="adjudication"
                               value="{{ old('adjudication', $recolement->adjudication) }}"
                               class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               maxlength="120" placeholder="N° ou référence d'adjudication">
                    </div>
                    <span class="font-bold text-gray-600 mt-5">OU</span>
                    <div class="form-group flex-1 min-w-[180px]">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">N° marché</label>
                        <input type="text" name="num_marche"
                               value="{{ old('num_marche', $recolement->num_marche) }}"
                               class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               maxlength="80" placeholder="Ex : MC-2026-042">
                    </div>
                </div>

                {{-- Commission --}}
                <div class="mb-2">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-700">Commission</span>
                        <button type="button" id="add-commission"
                                class="w-7 h-7 flex items-center justify-center bg-green-600 text-white rounded-full text-lg leading-none hover:bg-green-700">+</button>
                    </div>
                    <table class="w-full text-sm border-collapse" id="commission-table">
                        <thead>
                            <tr class="bg-[#2d6a8f] text-white">
                                <th class="px-3 py-2 text-left font-semibold">Nom et prénom</th>
                                <th class="px-3 py-2 text-left font-semibold">Fonction</th>
                                <th class="px-3 py-2 text-left font-semibold">Entité</th>
                                <th class="w-8"></th>
                            </tr>
                        </thead>
                        <tbody id="commission-body">
                            @php $commissionRows = old('commission', $recolement->commission ?? [['nom_prenom'=>'','fonction'=>'','entite'=>'']]); @endphp
                            @foreach($commissionRows as $i => $row)
                            <tr class="commission-row {{ $loop->even ? 'bg-gray-100' : 'bg-gray-50' }}">
                                <td class="px-1 py-1"><input type="text" name="commission[{{ $i }}][nom_prenom]" value="{{ $row['nom_prenom'] ?? '' }}" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="120" placeholder="Nom et prénom"></td>
                                <td class="px-1 py-1"><input type="text" name="commission[{{ $i }}][fonction]" value="{{ $row['fonction'] ?? '' }}" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="120" placeholder="Fonction"></td>
                                <td class="px-1 py-1"><input type="text" name="commission[{{ $i }}][entite]" value="{{ $row['entite'] ?? '' }}" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="120" placeholder="Entité"></td>
                                <td class="px-1 py-1 text-center"><button type="button" class="remove-row text-red-400 hover:text-red-600 text-sm" title="Supprimer">&times;</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Section: Marteau / Marque + Souches --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex flex-wrap gap-6 mb-5">
                    <div class="form-group flex-1 min-w-[180px]">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Marteau</label>
                        <input type="text" name="marteau" value="{{ old('marteau', $recolement->marteau) }}"
                               class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" maxlength="80" placeholder="Référence du marteau">
                    </div>
                    <div class="form-group flex-1 min-w-[180px]">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Marque</label>
                        <input type="text" name="marque" value="{{ old('marque', $recolement->marque) }}"
                               class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" maxlength="80" placeholder="Description de la marque">
                    </div>
                </div>

                {{-- Souches / Réserves table --}}
                <div class="overflow-x-auto mb-2">
                    <div class="flex justify-end mb-1">
                        <button type="button" id="add-souche"
                                class="w-7 h-7 flex items-center justify-center bg-green-600 text-white rounded-full text-lg leading-none hover:bg-green-700">+</button>
                    </div>
                    <table class="w-full text-sm border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-[#2d6a8f] text-white">
                                <th class="px-3 py-2 text-left font-semibold">Essence</th>
                                <th class="px-3 py-2 text-left font-semibold">Souches ou réserves avec empreinte ou marque</th>
                                <th class="px-3 py-2 text-left font-semibold">Souches ou réserves sans empreinte ou marque</th>
                                <th class="px-3 py-2 text-left font-semibold">Total</th>
                                <th class="px-3 py-2 text-left font-semibold">Nombre d'après PV de martelage ou de marquage</th>
                                <th class="w-8"></th>
                            </tr>
                        </thead>
                        <tbody id="souches-body">
                            @php $souchesRows = old('souches_reserves', $recolement->souches_reserves ?? [['essence'=>'','avec_empreinte'=>'','sans_empreinte'=>'','total'=>'','nombre_pv'=>'']]); @endphp
                            @foreach($souchesRows as $i => $row)
                            <tr class="souche-row {{ $loop->even ? 'bg-gray-100' : 'bg-gray-50' }}">
                                <td class="px-1 py-1"><input type="text" name="souches_reserves[{{ $i }}][essence]" value="{{ $row['essence'] ?? '' }}" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="120" placeholder="Ex : Cèdre"></td>
                                <td class="px-1 py-1"><input type="number" name="souches_reserves[{{ $i }}][avec_empreinte]" value="{{ $row['avec_empreinte'] ?? '' }}" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" step="any" min="0" placeholder="0"></td>
                                <td class="px-1 py-1"><input type="number" name="souches_reserves[{{ $i }}][sans_empreinte]" value="{{ $row['sans_empreinte'] ?? '' }}" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" step="any" min="0" placeholder="0"></td>
                                <td class="px-1 py-1"><input type="number" name="souches_reserves[{{ $i }}][total]" value="{{ $row['total'] ?? '' }}" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" step="any" min="0" placeholder="0"></td>
                                <td class="px-1 py-1"><input type="number" name="souches_reserves[{{ $i }}][nombre_pv]" value="{{ $row['nombre_pv'] ?? '' }}" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" step="any" min="0" placeholder="0"></td>
                                <td class="px-1 py-1 text-center"><button type="button" class="remove-row text-red-400 hover:text-red-600 text-sm" title="Supprimer">&times;</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Section: Opérations + Produits en matière --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Opérations --}}
                    <div class="space-y-3">
                        @php
                        $ops = [
                            'la_coupe'                   => ['La coupe',                     'Conforme / Non conforme'],
                            'les_limites'                => ['Les limites',                   'Conforme / Non conforme'],
                            'le_vidange'                 => ['Le vidange',                    'Effectué / Non effectué'],
                            'nettoyage_coupe'            => ['Le nettoyage de la coupe',      'Effectué / Non effectué'],
                            'le_recru'                   => ['Le recru',                      'Bon état / Mauvais état'],
                            'travaux_imposes'            => ['Les travaux imposés',           'Réalisés / Non réalisés'],
                            'fourniture_mise_en_charge'  => ['La fourniture de mise en charge','Conforme / Non conforme'],
                            'delits_constates'           => ['Délits constatés',              'Aucun / Description'],
                        ];
                        @endphp
                        @foreach($ops as $field => [$label, $placeholder])
                        <div class="flex items-center gap-3">
                            <label class="text-sm font-medium text-gray-700 w-52 shrink-0">{{ $label }}</label>
                            <input type="text" name="{{ $field }}"
                                   value="{{ old($field, $recolement->$field) }}"
                                   placeholder="{{ $placeholder }}"
                                   class="form-input flex-1 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        @endforeach
                    </div>

                    {{-- Produits en matière --}}
                    <div>
                        <p class="text-sm font-bold text-gray-800 mb-3 text-center">Produits en matière</p>
                        <div class="space-y-2">
                            @php
                            $produits = [
                                'bois_oeuvre'        => ["Bois d'oeuvre",        'm3'],
                                'bois_industrie'     => ["Bois d'industrie",     'm3'],
                                'bois_service'       => ['Bois de service',      'm3'],
                                'bois_chauffage'     => ['Bois de chauffage',    'st'],
                                'brins_cedre'        => ['Brins de cèdre',       'unité'],
                                'liege_male'         => ['Liège male',           'st'],
                                'liege_reproduction' => ['Liège de reproduction','st'],
                                'ecorce_tanin'       => ['Ecorce à Tanin',       'qx'],
                                'bois_carboniser'    => ['Bois à carboniser',    'st'],
                            ];
                            @endphp
                            @foreach($produits as $field => [$label, $unit])
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-700 w-44 shrink-0 text-right">{{ $label }}</label>
                                <input type="number" name="{{ $field }}"
                                       value="{{ old($field, $recolement->$field) }}"
                                       step="any" min="0"
                                       class="form-input flex-1 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <span class="text-xs text-gray-500 w-8">{{ $unit }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Produits abandonnés --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="text-sm font-semibold text-gray-700 w-44 shrink-0 mt-2">
                        <a class="underline cursor-pointer">Produits abandonnés</a> par l'exploitant
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-end mb-1">
                            <button type="button" id="add-produit"
                                    class="w-7 h-7 flex items-center justify-center bg-green-600 text-white rounded-full text-lg leading-none hover:bg-green-700">+</button>
                        </div>
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="bg-[#2d6a8f] text-white">
                                    <th class="px-3 py-2 text-left font-semibold">Nature</th>
                                    <th class="px-3 py-2 text-left font-semibold">Quantité</th>
                                    <th class="w-8"></th>
                                </tr>
                            </thead>
                            <tbody id="produits-body">
                                @php $produitsRows = old('produits_abandonnes', $recolement->produits_abandonnes ?? [['nature'=>'','quantite'=>'']]); @endphp
                                @foreach($produitsRows as $i => $row)
                                <tr class="produit-row {{ $loop->even ? 'bg-gray-100' : 'bg-gray-50' }}">
                                    <td class="px-1 py-1"><input type="text" name="produits_abandonnes[{{ $i }}][nature]" value="{{ $row['nature'] ?? '' }}" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="120" placeholder="Ex : Bois d'oeuvre"></td>
                                    <td class="px-1 py-1"><input type="text" name="produits_abandonnes[{{ $i }}][quantite]" value="{{ $row['quantite'] ?? '' }}" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="80" placeholder="Ex : 5 m3"></td>
                                    <td class="px-1 py-1 text-center"><button type="button" class="remove-row text-red-400 hover:text-red-600 text-sm" title="Supprimer">&times;</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Section: PV info + fichier --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="num_pv" class="block text-sm font-semibold text-gray-700 mb-2">
                            N° PV <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="num_pv" id="num_pv" value="{{ old('num_pv', $recolement->num_pv) }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               required maxlength="80" placeholder="Ex : PV-2026-001">
                    </div>
                    <div class="form-group">
                        <label for="date_pv" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date PV <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_pv" id="date_pv"
                               value="{{ old('date_pv', $recolement->date_pv?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               required>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label for="observations" class="block text-sm font-semibold text-gray-700 mb-2">Observations</label>
                        <textarea name="observations" id="observations" rows="3"
                                  class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                  placeholder="Observations éventuelles...">{{ old('observations', $recolement->observations) }}</textarea>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label for="fichier_pv" class="block text-sm font-semibold text-gray-700 mb-2">Fichier PV (PDF)</label>
                        <input type="file" name="fichier_pv" id="fichier_pv" accept=".pdf"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('articles.show', $article) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                        <i class="fas fa-save"></i> Soumettre le PV
                    </button>
                </div>
            </div>
        </form>
        @endif

    </div>
</div>

@push('scripts')
<script>
(function () {
    function reindexRows(tbody, prefix, fields) {
        tbody.querySelectorAll('tr').forEach(function (tr, i) {
            fields.forEach(function (f) {
                var inp = tr.querySelector('[name*="[' + f + ']"]');
                if (inp) inp.name = prefix + '[' + i + '][' + f + ']';
            });
        });
    }

    function bindRemove(tr, tbody, prefix, fields) {
        tr.querySelector('.remove-row').addEventListener('click', function () {
            if (tbody.querySelectorAll('tr').length > 1) {
                tr.remove();
                reindexRows(tbody, prefix, fields);
            }
        });
    }

    // Commission
    var commBody = document.getElementById('commission-body');
    commBody.querySelectorAll('tr').forEach(function (tr) {
        bindRemove(tr, commBody, 'commission', ['nom_prenom', 'fonction', 'entite']);
    });
    document.getElementById('add-commission').addEventListener('click', function () {
        var idx = commBody.querySelectorAll('tr').length;
        var bg = idx % 2 === 0 ? 'bg-gray-50' : 'bg-gray-100';
        var tr = document.createElement('tr');
        tr.className = 'commission-row ' + bg;
        tr.innerHTML = '<td class="px-1 py-1"><input type="text" name="commission[' + idx + '][nom_prenom]" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="120" placeholder="Nom et prénom"></td>'
            + '<td class="px-1 py-1"><input type="text" name="commission[' + idx + '][fonction]" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="120" placeholder="Fonction"></td>'
            + '<td class="px-1 py-1"><input type="text" name="commission[' + idx + '][entite]" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="120" placeholder="Entité"></td>'
            + '<td class="px-1 py-1 text-center"><button type="button" class="remove-row text-red-400 hover:text-red-600 text-sm" title="Supprimer">&times;</button></td>';
        commBody.appendChild(tr);
        bindRemove(tr, commBody, 'commission', ['nom_prenom', 'fonction', 'entite']);
    });

    // Souches
    var souchesBody = document.getElementById('souches-body');
    souchesBody.querySelectorAll('tr').forEach(function (tr) {
        bindRemove(tr, souchesBody, 'souches_reserves', ['essence', 'avec_empreinte', 'sans_empreinte', 'total', 'nombre_pv']);
    });
    document.getElementById('add-souche').addEventListener('click', function () {
        var idx = souchesBody.querySelectorAll('tr').length;
        var bg = idx % 2 === 0 ? 'bg-gray-50' : 'bg-gray-100';
        var tr = document.createElement('tr');
        tr.className = 'souche-row ' + bg;
        tr.innerHTML = '<td class="px-1 py-1"><input type="text" name="souches_reserves[' + idx + '][essence]" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="120" placeholder="Ex : Cèdre"></td>'
            + '<td class="px-1 py-1"><input type="number" name="souches_reserves[' + idx + '][avec_empreinte]" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" step="any" min="0" placeholder="0"></td>'
            + '<td class="px-1 py-1"><input type="number" name="souches_reserves[' + idx + '][sans_empreinte]" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" step="any" min="0" placeholder="0"></td>'
            + '<td class="px-1 py-1"><input type="number" name="souches_reserves[' + idx + '][total]" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" step="any" min="0" placeholder="0"></td>'
            + '<td class="px-1 py-1"><input type="number" name="souches_reserves[' + idx + '][nombre_pv]" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" step="any" min="0" placeholder="0"></td>'
            + '<td class="px-1 py-1 text-center"><button type="button" class="remove-row text-red-400 hover:text-red-600 text-sm" title="Supprimer">&times;</button></td>';
        souchesBody.appendChild(tr);
        bindRemove(tr, souchesBody, 'souches_reserves', ['essence', 'avec_empreinte', 'sans_empreinte', 'total', 'nombre_pv']);
    });

    // Produits abandonnés
    var produitsBody = document.getElementById('produits-body');
    produitsBody.querySelectorAll('tr').forEach(function (tr) {
        bindRemove(tr, produitsBody, 'produits_abandonnes', ['nature', 'quantite']);
    });
    document.getElementById('add-produit').addEventListener('click', function () {
        var idx = produitsBody.querySelectorAll('tr').length;
        var bg = idx % 2 === 0 ? 'bg-gray-50' : 'bg-gray-100';
        var tr = document.createElement('tr');
        tr.className = 'produit-row ' + bg;
        tr.innerHTML = '<td class="px-1 py-1"><input type="text" name="produits_abandonnes[' + idx + '][nature]" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="120" placeholder="Ex : Bois d\'oeuvre"></td>'
            + '<td class="px-1 py-1"><input type="text" name="produits_abandonnes[' + idx + '][quantite]" class="w-full px-2 py-1 border border-gray-200 rounded bg-transparent" maxlength="80" placeholder="Ex : 5 m3"></td>'
            + '<td class="px-1 py-1 text-center"><button type="button" class="remove-row text-red-400 hover:text-red-600 text-sm" title="Supprimer">&times;</button></td>';
        produitsBody.appendChild(tr);
        bindRemove(tr, produitsBody, 'produits_abandonnes', ['nature', 'quantite']);
    });
})();
</script>
@endpush
@endsection
