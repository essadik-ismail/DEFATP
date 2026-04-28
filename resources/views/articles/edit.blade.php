@extends('layouts.app')

@section('title', 'Modifier un Article - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">Détail #{{ $article->numero ?? $article->id }}</a></li>
<li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
    <div class="min-w-0 max-w-full overflow-x-hidden">

        <x-page-header title="Modifier l'Article" :subtitle="'Modifiez les informations de l\'article #' . ($article->numero ?? $article->id)" icon="fas fa-pencil-alt">
            <x-slot name="actions">
                <x-button href="{{ route('articles.show', $article) }}" variant="secondary" icon="fas fa-arrow-left" size="sm">
                    Retour
                </x-button>
            </x-slot>
        </x-page-header>

        <x-flash-messages />

        @if ($errors->any())
            <x-alert type="error" title="Erreurs de validation" dismissible class="mb-4">
                <ul class="list-disc list-inside space-y-0.5 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <!-- Edit Form -->
        <div class="bg-white rounded-2xl border p-6"
            style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <form action="{{ route('articles.update', $article) }}" method="POST" id="articleForm" class="space-y-6"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- 1. Informations générales -->
                <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600">
                            <i class="fas fa-info-circle text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">1. Informations générales</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="numero" class="block text-xs font-semibold text-gray-600 mb-1">
                                Numéro d'article
                            </label>
                            <input type="number"
                                class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                id="numero" name="numero" value="{{ old('numero', $article->numero) }}"
                                placeholder="Numéro d'article">
                            @error('numero')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="lot" class="block text-xs font-semibold text-gray-600 mb-1">
                                Numéro du lot
                            </label>
                            <input type="number"
                                class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                id="lot" name="lot" value="{{ old('lot', $article->lot) }}"
                                placeholder="Numéro du lot">
                            @error('lot')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. Localisation du lot -->
                <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600">
                            <i class="fas fa-map-marker-alt text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">2. Localisation du lot</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label for="province_id" class="block text-xs font-semibold text-gray-600 mb-1">Province</label>
                            <select id="province_id" name="province_id"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    onchange="updateCommunes()">
                                <option value="">Sélectionner une province</option>
                                @foreach($provinces ?? [] as $province)
                                    <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="commune_ids" class="block text-xs font-semibold text-gray-600 mb-1">
                                Communes <span class="text-red-500">*</span>
                            </label>
                            <input type="text" placeholder="Rechercher..." class="form-input w-full mb-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'commune_ids')">
                            <select multiple
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="commune_ids" name="commune_ids[]">
                                @php($selectedCommunes = old('commune_ids', $article->communes ? $article->communes->pluck('id')->toArray() : []))
                                @foreach($communes ?? [] as $commune)
                                    <option value="{{ $commune->id }}"
                                            data-province-id="{{ $commune->province_id }}"
                                            {{ in_array($commune->id, $selectedCommunes) ? 'selected' : '' }}>
                                        {{ $commune->nom }}@if($commune->province) - {{ $commune->province->nom }}@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('commune_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="dranef_code" class="block text-xs font-semibold text-gray-600 mb-1">DRANEF</label>
                            <select id="dranef_code" name="dranef_code"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    onchange="updateDpanefs()">
                                <option value="">Sélectionner un DRANEF</option>
                                @foreach($dranefs ?? [] as $dranef)
                                    <option value="{{ $dranef->code }}" {{ old('dranef_code') == $dranef->code ? 'selected' : '' }}>
                                        {{ $dranef->dranef }} - {{ $dranef->Abréviation }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dranef_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="dpanef_code" class="block text-xs font-semibold text-gray-600 mb-1">DPANEF</label>
                            <select id="dpanef_code" name="dpanef_code"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    onchange="updateZdtfs()">
                                <option value="">Sélectionner un DPANEF</option>
                                @foreach($dpanefs ?? [] as $dpanef)
                                    <option value="{{ $dpanef->code }}"
                                            data-dranef-code="{{ $dpanef->dranef_code }}"
                                            {{ old('dpanef_code') == $dpanef->code ? 'selected' : '' }}>
                                        {{ $dpanef->dpanef }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dpanef_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="zdtf_code" class="block text-xs font-semibold text-gray-600 mb-1">ZDTF</label>
                            <select id="zdtf_code" name="zdtf_code"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    onchange="updateDfps()">
                                <option value="">Sélectionner un ZDTF</option>
                                @foreach($zdtfs ?? [] as $zdtf)
                                    <option value="{{ $zdtf->code }}"
                                            data-dpanef-code="{{ $zdtf->dpanef_code }}"
                                            {{ old('zdtf_code') == $zdtf->code ? 'selected' : '' }}>
                                        {{ $zdtf->zdtf }}
                                    </option>
                                @endforeach
                            </select>
                            @error('zdtf_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="dfp_code" class="block text-xs font-semibold text-gray-600 mb-1">DFP</label>
                            <select id="dfp_code" name="dfp_code"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Sélectionner un DFP</option>
                                @foreach($dfps ?? [] as $dfp)
                                    <option value="{{ $dfp->code }}"
                                            data-zdtf-code="{{ $dfp->zdtf_code }}"
                                            data-dpanef-code="{{ $dfp->dpanef_code }}"
                                            {{ old('dfp_code') == $dfp->code ? 'selected' : '' }}>
                                        {{ $dfp->dfp }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dfp_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 3. Informations forestières -->
                <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600">
                            <i class="fas fa-tree text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">3. Informations forestières</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="foret_ids" class="block text-xs font-semibold text-gray-600 mb-1">Forêt</label>
                            <input type="text" placeholder="Rechercher..." class="form-input w-full mb-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'foret_ids')">
                            <select multiple
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="foret_ids" name="foret_ids[]">
                                @php($selectedForets = old('foret_ids', $article->forets ? $article->forets->pluck('id')->toArray() : []))
                                @foreach($forets ?? [] as $foret)
                                    <option value="{{ $foret->id }}" {{ in_array($foret->id, $selectedForets) ? 'selected' : '' }}>
                                        {{ $foret->foret }}
                                    </option>
                                @endforeach
                            </select>
                            @error('foret_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="canton_id" class="block text-xs font-semibold text-gray-600 mb-1">Canton</label>
                            <select id="canton_id" name="canton_id"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    onchange="updateParcelles()">
                                <option value="">Sélectionner un canton</option>
                                @foreach($cantons ?? [] as $canton)
                                    <option value="{{ $canton->id }}" data-foret-id="{{ $canton->foret_id }}" {{ old('canton_id') == $canton->id ? 'selected' : '' }}>
                                        {{ $canton->canton }}@if($canton->foret) - {{ $canton->foret->foret }}@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('canton_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="parcelle_ids" class="block text-xs font-semibold text-gray-600 mb-1">Parcelle</label>
                            <input type="text" placeholder="Rechercher..." class="form-input w-full mb-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'parcelle_ids')">
                            <select multiple
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="parcelle_ids" name="parcelle_ids[]">
                                @php($selectedParcelles = old('parcelle_ids', $article->parcelles ? $article->parcelles->pluck('id')->toArray() : []))
                                @foreach($parcelles ?? [] as $parcelle)
                                    <option value="{{ $parcelle->id }}" data-canton-id="{{ $parcelle->canton_id }}" {{ in_array($parcelle->id, $selectedParcelles) ? 'selected' : '' }}>
                                        {{ $parcelle->parcelle }}@if($parcelle->canton) - {{ $parcelle->canton->canton }}@endif
                                    </option>
                                @endforeach
                            </select>
                            @error('parcelle_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nature_juridique" class="block text-xs font-semibold text-gray-600 mb-1">Nature juridique</label>
                            <input type="text"
                                name="nature_juridique"
                                class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                id="nature_juridique"
                                value="{{ old('nature_juridique', $article->nature_juridique) }}"
                                placeholder="Entrez la nature juridique">
                            @error('nature_juridique')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 4. Description du lot -->
                <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600">
                            <i class="fas fa-clipboard-list text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">4. Description du lot</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="nature_de_coupe_ids" class="block text-xs font-semibold text-gray-600 mb-1">Nature de coupe</label>
                            <input type="text" placeholder="Rechercher..." class="form-input w-full mb-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'nature_de_coupe_ids')">
                            <select multiple
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="nature_de_coupe_ids" name="nature_de_coupe_ids[]">
                                @php($selectedNatures = old('nature_de_coupe_ids', $article->naturesDeCoupe ? $article->naturesDeCoupe->pluck('id')->toArray() : []))
                                @foreach($natureDeCoupes ?? [] as $natureDeCoupe)
                                    <option value="{{ $natureDeCoupe->id }}" {{ in_array($natureDeCoupe->id, $selectedNatures) ? 'selected' : '' }}>
                                        {{ $natureDeCoupe->nature_de_coupe }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nature_de_coupe_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mode_exploitation_ids" class="block text-xs font-semibold text-gray-600 mb-1">Mode d'exploitation</label>
                            <input type="text" placeholder="Rechercher..." class="form-input w-full mb-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'mode_exploitation_ids')">
                            <select multiple
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="mode_exploitation_ids" name="mode_exploitation_ids[]">
                                @php($selectedModes = old('mode_exploitation_ids', $article->modeExploitations ? $article->modeExploitations->pluck('id')->toArray() : []))
                                @foreach($modeExploitations ?? [] as $modeExploitation)
                                    <option value="{{ $modeExploitation->id }}" {{ in_array($modeExploitation->id, $selectedModes) ? 'selected' : '' }}>
                                        {{ $modeExploitation->mode_exploiattion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mode_exploitation_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Limites du lot -->
                    <div class="mt-5 pt-5 border-t border-emerald-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Limites du lot</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach(['limite_nord' => 'Limite Nord', 'limite_sud' => 'Limite Sud', 'limite_est' => 'Limite Est', 'limite_ouest' => 'Limite Ouest'] as $field => $label)
                            <div>
                                <label for="{{ $field }}" class="block text-xs font-semibold text-gray-600 mb-1">{{ $label }}</label>
                                <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $article->$field) }}"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="{{ $label }}">
                                @error($field)
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Coordonnées du centre -->
                    <div class="mt-5 pt-5 border-t border-emerald-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Coordonnées du centre</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="coordonnee_x" class="block text-xs font-semibold text-gray-600 mb-1">Coordonnée X</label>
                                <input type="number" id="coordonnee_x" name="coordonnee_x" value="{{ old('coordonnee_x', $article->coordonnee_x) }}" step="any"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Coordonnée X">
                                @error('coordonnee_x')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="coordonnee_y" class="block text-xs font-semibold text-gray-600 mb-1">Coordonnée Y</label>
                                <input type="number" id="coordonnee_y" name="coordonnee_y" value="{{ old('coordonnee_y', $article->coordonnee_y) }}" step="any"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Coordonnée Y">
                                @error('coordonnee_y')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Consistance du lot -->
                <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600">
                            <i class="fas fa-cubes text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">5. Consistance du lot</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="superficie" class="block text-xs font-semibold text-gray-600 mb-1">Superficie</label>
                            <input type="number"
                                class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                id="superficie" name="superficie" value="{{ old('superficie', $article->superficie) }}"
                                min="0" step="0.01" placeholder="Superficie en hectares">
                            @error('superficie')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="bg-white rounded-xl p-5 border border-emerald-200">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-semibold text-emerald-700">Produits présumés</h4>
                                <button type="button" onclick="addProductRow()"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors">
                                    <i class="fas fa-plus"></i> Ajouter
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse text-sm">
                                    <thead>
                                        <tr class="bg-emerald-50">
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 border border-emerald-100">Essence</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 border border-emerald-100">Produits</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 border border-emerald-100">Quantité</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 border border-emerald-100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-table-body">
                                        @if($article->essences && $article->essences->count() > 0)
                                            @foreach($article->essences as $index => $essence)
                                                <tr class="product-row border-b border-gray-100">
                                                    <td class="px-3 py-2 border border-gray-100">
                                                        <select name="products[{{ $index }}][essence_id]"
                                                                class="form-input w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500"
                                                                required>
                                                            <option value="">Sélectionner une essence</option>
                                                            @foreach($essences as $ess)
                                                                <option value="{{ $ess->id }}" {{ old("products.{$index}.essence_id", $essence->id) == $ess->id ? 'selected' : '' }}>
                                                                    {{ $ess->essence }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="px-3 py-2 border border-gray-100">
                                                        <select name="products[{{ $index }}][product_id]"
                                                                class="form-input w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500"
                                                                required>
                                                            <option value="">Sélectionner un produit</option>
                                                            @foreach($products as $prod)
                                                                <option value="{{ $prod->id }}" {{ old("products.{$index}.product_id", $essence->pivot->product_id ?? '') == $prod->id ? 'selected' : '' }}>
                                                                    {{ $prod->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="px-3 py-2 border border-gray-100">
                                                        <input type="number"
                                                               name="products[{{ $index }}][quantity]"
                                                               class="form-input w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500"
                                                               min="0" step="0.01" placeholder="Quantité"
                                                               value="{{ old("products.{$index}.quantity", $essence->pivot->quantity ?? '') }}"
                                                               required>
                                                    </td>
                                                    <td class="px-3 py-2 border border-gray-100 text-center">
                                                        <button type="button" onclick="removeProductRow(this)"
                                                                class="px-2.5 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs transition-colors">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. Bois sur dépôt -->
                <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600">
                            <i class="fas fa-warehouse text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">6. Bois sur dépôt</h3>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox"
                            id="is_on_depot" name="is_on_depot" value="1"
                            {{ old('is_on_depot', $article->depots->isNotEmpty()) ? 'checked' : '' }}
                            onchange="toggleDepotSelect()"
                            class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                        <span class="text-sm font-semibold text-gray-700">Le bois est sur dépôt</span>
                    </label>
                    <div id="depot-select-container" class="mt-4" style="display: {{ old('is_on_depot', $article->depots->isNotEmpty()) ? 'block' : 'none' }};">
                        <label for="depot_ids" class="block text-xs font-semibold text-gray-600 mb-1">
                            Dépôts <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'depot_ids')">
                        <select multiple
                                class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                id="depot_ids" name="depot_ids[]">
                            @php($selectedDepots = old('depot_ids', $article->depots ? $article->depots->pluck('id')->toArray() : []))
                            @foreach($depots ?? [] as $depot)
                                <option value="{{ $depot->id }}" {{ in_array($depot->id, $selectedDepots) ? 'selected' : '' }}>
                                    {{ $depot->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('depot_ids')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 7. Charges -->
                <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600">
                            <i class="fas fa-money-bill-wave text-white text-sm"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900">7. Charges</h3>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="taxe_refection_chemins" class="block text-xs font-semibold text-gray-600 mb-1">Taxes pour la réfection du chemin (Montant)</label>
                                <input type="number"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="taxe_refection_chemins" name="taxe_refection_chemins" value="{{ old('taxe_refection_chemins', $article->taxe_refection_chemins) }}"
                                    min="0" step="0.01" placeholder="Montant">
                                @error('taxe_refection_chemins')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="date_echeance_taxe_refection_chemins" class="block text-xs font-semibold text-gray-600 mb-1">Date d'échéance – Taxes réfection chemin</label>
                                <input type="date"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="date_echeance_taxe_refection_chemins" name="date_echeance_taxe_refection_chemins" value="{{ old('date_echeance_taxe_refection_chemins', $article->date_echeance_taxe_refection_chemins ? \Carbon\Carbon::parse($article->date_echeance_taxe_refection_chemins)->format('Y-m-d') : '') }}">
                                @error('date_echeance_taxe_refection_chemins')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="service_rendu_anef" class="block text-xs font-semibold text-gray-600 mb-1">Service rendu par l'ANEF (Montant)</label>
                                <input type="number"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="service_rendu_anef" name="service_rendu_anef" value="{{ old('service_rendu_anef', $article->service_rendu_anef) }}"
                                    min="0" step="0.01" placeholder="Montant">
                                @error('service_rendu_anef')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="date_echeance_service_rendu_anef" class="block text-xs font-semibold text-gray-600 mb-1">Date d'échéance – Service rendu ANEF</label>
                                <input type="date"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="date_echeance_service_rendu_anef" name="date_echeance_service_rendu_anef" value="{{ old('date_echeance_service_rendu_anef', $article->date_echeance_service_rendu_anef ? \Carbon\Carbon::parse($article->date_echeance_service_rendu_anef)->format('Y-m-d') : '') }}">
                                @error('date_echeance_service_rendu_anef')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="bois_chauffage_destination" class="block text-xs font-semibold text-gray-600 mb-1">Bois de chauffage – Destination</label>
                                <input type="text"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="bois_chauffage_destination" name="bois_chauffage_destination" value="{{ old('bois_chauffage_destination', $article->bois_chauffage_destination) }}"
                                    placeholder="Destination">
                                @error('bois_chauffage_destination')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="bois_chauffage_volume" class="block text-xs font-semibold text-gray-600 mb-1">Bois de chauffage – Volume</label>
                                <input type="number"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="bois_chauffage_volume" name="bois_chauffage_volume" value="{{ old('bois_chauffage_volume', $article->bois_chauffage_volume) }}"
                                    min="0" step="0.01" placeholder="Volume">
                                @error('bois_chauffage_volume')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="mise_en_charge_destination" class="block text-xs font-semibold text-gray-600 mb-1">Mise en charge – Destination</label>
                                <input type="text"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="mise_en_charge_destination" name="mise_en_charge_destination" value="{{ old('mise_en_charge_destination', $article->mise_en_charge_destination) }}"
                                    placeholder="Destination">
                                @error('mise_en_charge_destination')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="mise_en_charge_volume" class="block text-xs font-semibold text-gray-600 mb-1">Mise en charge – Volume</label>
                                <input type="number"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="mise_en_charge_volume" name="mise_en_charge_volume" value="{{ old('mise_en_charge_volume', $article->mise_en_charge_volume) }}"
                                    min="0" step="0.01" placeholder="Volume">
                                @error('mise_en_charge_volume')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="date_echeance_mise_en_charge" class="block text-xs font-semibold text-gray-600 mb-1">Date d'échéance – Mise en charge</label>
                                <input type="date"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="date_echeance_mise_en_charge" name="date_echeance_mise_en_charge" value="{{ old('date_echeance_mise_en_charge', $article->date_echeance_mise_en_charge ? \Carbon\Carbon::parse($article->date_echeance_mise_en_charge)->format('Y-m-d') : '') }}">
                                @error('date_echeance_mise_en_charge')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="date_payement_service_anef" class="block text-xs font-semibold text-gray-600 mb-1">Date de paiement du service ANEF</label>
                                <input type="date"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="date_payement_service_anef" name="date_payement_service_anef" value="{{ old('date_payement_service_anef', $article->date_payement_service_anef ? \Carbon\Carbon::parse($article->date_payement_service_anef)->format('Y-m-d') : '') }}">
                                @error('date_payement_service_anef')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="date_livaison_mise_en_charge_bf" class="block text-xs font-semibold text-gray-600 mb-1">Date de livraison / mise en charge BF</label>
                                <input type="date"
                                    class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                    id="date_livaison_mise_en_charge_bf" name="date_livaison_mise_en_charge_bf" value="{{ old('date_livaison_mise_en_charge_bf', $article->date_livaison_mise_en_charge_bf ? \Carbon\Carbon::parse($article->date_livaison_mise_en_charge_bf)->format('Y-m-d') : '') }}">
                                @error('date_livaison_mise_en_charge_bf')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-200">
                    <a href="{{ route('articles.show', $article) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                        <i class="fas fa-times text-xs"></i> Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-sm transition-colors">
                        <i class="fas fa-save"></i> Mettre à jour l'Article
                    </button>
                </div>
            </form>

            <!-- 8. Plan de situation – Import Excel -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600">
                        <i class="fas fa-map text-white text-sm"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">8. Plan de situation – Import Excel</h3>
                </div>
                <p class="text-sm text-gray-500 mb-4">
                    Importez un fichier Excel contenant les colonnes <strong>mat</strong>, <strong>x</strong>, <strong>y</strong> (première ligne = en-têtes).
                </p>
                <form action="{{ route('articles.locations.import', $article) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-end gap-4">
                    @csrf
                    <div class="min-w-[240px]">
                        <label for="locations_file" class="block text-xs font-semibold text-gray-600 mb-1">Fichier Excel (.xlsx, .xls)</label>
                        <input type="file"
                            id="locations_file" name="locations_file" accept=".xlsx,.xls"
                            class="form-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            required>
                        @error('locations_file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold transition-colors">
                        <i class="fas fa-file-excel"></i> Importer
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
let productRowCount = {{ $article->essences ? $article->essences->count() : 0 }};
const essences = @json($essences ?? []);
const products = @json($products ?? []);

function addProductRow() {
    productRowCount++;
    const tbody = document.getElementById('products-table-body');
    const row = document.createElement('tr');
    row.className = 'product-row border-b border-gray-100';
    row.innerHTML = `
        <td class="px-3 py-2 border border-gray-100">
            <select name="products[${productRowCount}][essence_id]"
                    class="form-input w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500"
                    required>
                <option value="">Sélectionner une essence</option>
                ${essences.map(e => `<option value="${e.id}">${e.essence}</option>`).join('')}
            </select>
        </td>
        <td class="px-3 py-2 border border-gray-100">
            <select name="products[${productRowCount}][product_id]"
                    class="form-input w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500"
                    required>
                <option value="">Sélectionner un produit</option>
                ${products.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
            </select>
        </td>
        <td class="px-3 py-2 border border-gray-100">
            <input type="number"
                   name="products[${productRowCount}][quantity]"
                   class="form-input w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500"
                   min="0" step="0.01" placeholder="Quantité" required>
        </td>
        <td class="px-3 py-2 border border-gray-100 text-center">
            <button type="button" onclick="removeProductRow(this)"
                    class="px-2.5 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
}

function removeProductRow(button) {
    button.closest('tr').remove();
}

function updateParcelles() {
    const cantonSelect = document.getElementById('canton_id');
    const parcelleSelect = document.getElementById('parcelle_ids');
    const selectedCantonId = cantonSelect.value;
    Array.from(parcelleSelect.options).forEach(option => {
        if (option.value === '') { option.style.display = ''; return; }
        const cantonId = option.getAttribute('data-canton-id');
        if (selectedCantonId && cantonId !== selectedCantonId) {
            option.style.display = 'none'; option.selected = false;
        } else { option.style.display = ''; }
    });
}

function filterSelectOptions(inputEl, selectId) {
    const filter = inputEl.value.toLowerCase();
    const select = document.getElementById(selectId);
    if (!select) return;
    Array.from(select.options).forEach(function(opt) {
        opt.style.display = (opt.text || '').toLowerCase().indexOf(filter) !== -1 ? '' : 'none';
    });
}

function updateCommunes() {
    const provinceSelect = document.getElementById('province_id');
    const communeSelect = document.getElementById('commune_ids');
    const selectedProvinceId = provinceSelect.value;
    Array.from(communeSelect.options).forEach(option => {
        if (option.value === '') { option.style.display = ''; return; }
        const provinceId = option.getAttribute('data-province-id');
        if (selectedProvinceId && provinceId !== selectedProvinceId) {
            option.style.display = 'none'; if (option.selected) option.selected = false;
        } else { option.style.display = ''; }
    });
}

function updateDpanefs() {
    const dranefSelect = document.getElementById('dranef_code');
    const dpanefSelect = document.getElementById('dpanef_code');
    const selectedDranefCode = dranefSelect.value;
    Array.from(dpanefSelect.options).forEach(option => {
        if (option.value === '') { option.style.display = ''; return; }
        const dranefCode = option.getAttribute('data-dranef-code');
        if (selectedDranefCode && dranefCode !== selectedDranefCode) {
            option.style.display = 'none'; if (option.selected) option.selected = false;
        } else { option.style.display = ''; }
    });
    if (!selectedDranefCode) dpanefSelect.value = '';
    updateZdtfs();
}

function updateZdtfs() {
    const dpanefSelect = document.getElementById('dpanef_code');
    const zdtfSelect = document.getElementById('zdtf_code');
    const selectedDpanefCode = dpanefSelect.value;
    Array.from(zdtfSelect.options).forEach(option => {
        if (option.value === '') { option.style.display = ''; return; }
        const dpanefCode = option.getAttribute('data-dpanef-code');
        if (selectedDpanefCode && dpanefCode !== selectedDpanefCode) {
            option.style.display = 'none'; if (option.selected) option.selected = false;
        } else { option.style.display = ''; }
    });
    if (!selectedDpanefCode) zdtfSelect.value = '';
    updateDfps();
}

function updateDfps() {
    const zdtfSelect = document.getElementById('zdtf_code');
    const dpanefSelect = document.getElementById('dpanef_code');
    const dfpSelect = document.getElementById('dfp_code');
    const selectedZdtfCode = zdtfSelect.value;
    const selectedDpanefCode = dpanefSelect.value;
    Array.from(dfpSelect.options).forEach(option => {
        if (option.value === '') { option.style.display = ''; return; }
        const zdtfCode = option.getAttribute('data-zdtf-code');
        const dpanefCode = option.getAttribute('data-dpanef-code');
        let shouldShow = true;
        if (selectedZdtfCode && zdtfCode && zdtfCode !== selectedZdtfCode) shouldShow = false;
        if (selectedDpanefCode && dpanefCode && dpanefCode !== selectedDpanefCode) shouldShow = false;
        if (!shouldShow) { option.style.display = 'none'; if (option.selected) option.selected = false; }
        else { option.style.display = ''; }
    });
    if (!selectedZdtfCode && !selectedDpanefCode) dfpSelect.value = '';
}

function toggleDepotSelect() {
    const checkbox = document.getElementById('is_on_depot');
    const container = document.getElementById('depot-select-container');
    if (checkbox && container) container.style.display = checkbox.checked ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    updateParcelles();
    const provinceSelect = document.getElementById('province_id');
    if (provinceSelect && provinceSelect.value) updateCommunes();
    updateDpanefs();
    updateZdtfs();
    updateDfps();
    toggleDepotSelect();
});
</script>
@endpush
