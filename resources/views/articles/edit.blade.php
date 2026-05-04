@extends('layouts.app')

@section('title', 'Modifier Article #' . ($article->numero ?? $article->id) . ' - DEFATP')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    @if ($article->cession)
        <li class="breadcrumb-item"><a href="{{ route('cessions.show', $article->cession) }}">Cession #{{ $article->cession->id }}</a></li>
    @endif
    <li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
    <li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
    <div class="min-w-0 max-w-full overflow-x-hidden">

        <x-page-header title="Modifier l'Article #{{ $article->numero ?? $article->id }}" icon="fas fa-pencil-alt">
            <x-slot name="actions">
                <x-button href="{{ route('articles.show', $article) }}" variant="secondary" icon="fas fa-arrow-left" size="sm">
                    Retour
                </x-button>
            </x-slot>
        </x-page-header>

        @if ($errors->any())
            <x-alert type="error" title="Erreurs de validation" dismissible class="mb-4">
                <ul class="list-disc list-inside space-y-0.5 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <div class="bg-white rounded-2xl border p-6"
            style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <form action="{{ route('articles.update', $article) }}" method="POST" id="articleForm" class="space-y-8"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- 1. Informations générales --}}
                <x-form-section number="1" title="Informations générales" icon="fas fa-info-circle" color="green">
                    @php $cession = $article->cession; @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label for="numero" class="block text-sm font-semibold text-gray-700 mb-2">Numéro d'article</label>
                            <input type="number"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="numero" name="numero" value="{{ old('numero', $article->numero) }}" placeholder="Numéro d'article">
                            @error('numero')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="lot" class="block text-sm font-semibold text-gray-700 mb-2">Numéro du lot</label>
                            <input type="number"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="lot" name="lot" value="{{ old('lot', $article->lot) }}" placeholder="Numéro du lot">
                            @error('lot')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Cession context (read-only) --}}
                    @if ($cession)
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Num AO</label>
                                <input type="text" readonly
                                    class="form-input w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                                    value="{{ $cession->numAO ?? '-' }}">
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date AO</label>
                                <input type="text" readonly
                                    class="form-input w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                                    value="{{ $cession->dateAO ? $cession->dateAO->format('d/m/Y') : '-' }}">
                            </div>
                            <div class="form-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Type de cession</label>
                                <input type="text" readonly
                                    class="form-input w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                                    value="{{ $cession->mode_cession === 'appel_offre' ? "Appel d'offre" : 'Adjudication' }}">
                            </div>
                        </div>
                    @endif
                </x-form-section>

                {{-- 2. Localisation du lot --}}
                <x-form-section number="2" title="Localisation du lot" icon="fas fa-map-marker-alt" color="blue">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Province -->
                        <div class="form-group">
                            <label for="province_ids" class="block text-sm font-semibold text-gray-700 mb-2">Province</label>
                            @php $selectedProvinceIds = old('province_ids', $article->provinces->pluck('id')->toArray()); @endphp
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'province_ids')">
                            <select multiple id="province_ids" name="province_ids[]"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                onchange="updateCommunes()">
                                @foreach ($provinces ?? [] as $province)
                                    <option value="{{ $province->id }}" {{ in_array($province->id, $selectedProvinceIds) ? 'selected' : '' }}>
                                        {{ $province->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Communes -->
                        <div class="form-group">
                            <label for="commune_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                Communes <span class="text-red-500">*</span>
                            </label>
                            @php $selectedCommunes = old('commune_ids', $article->communes->pluck('id')->toArray()); @endphp
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'commune_ids')">
                            <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="commune_ids" name="commune_ids[]">
                                @foreach ($communes ?? [] as $commune)
                                    <option value="{{ $commune->id }}" data-province-id="{{ $commune->province_id }}"
                                        {{ in_array($commune->id, $selectedCommunes) ? 'selected' : '' }}>
                                        {{ $commune->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('commune_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DRANEF -->
                        <div class="form-group">
                            <label for="dranef_code" class="block text-sm font-semibold text-gray-700 mb-2">DRANEF</label>
                            <select id="dranef_code" name="dranef_code"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                onchange="updateDpanefs()">
                                <option value="">Sélectionner un DRANEF</option>
                                @foreach ($dranefs ?? [] as $dranef)
                                    <option value="{{ $dranef->code }}"
                                        {{ old('dranef_code', $article->dranef_code) == $dranef->code ? 'selected' : '' }}>
                                        {{ $dranef->dranef }} - {{ $dranef->Abréviation }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dranef_code')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DPANEF -->
                        <div class="form-group">
                            <label for="dpanef_code" class="block text-sm font-semibold text-gray-700 mb-2">DPANEF</label>
                            <select id="dpanef_code" name="dpanef_code"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                onchange="updateZdtfsAndForets()">
                                <option value="">Sélectionner un DPANEF</option>
                                @foreach ($dpanefs ?? [] as $dpanef)
                                    <option value="{{ $dpanef->code }}"
                                        data-dranef-code="{{ $dpanef->dranef_code }}"
                                        data-dpanef-id="{{ $dpanef->id }}"
                                        {{ old('dpanef_code', $article->dpanef_code) == $dpanef->code ? 'selected' : '' }}>
                                        {{ $dpanef->dpanef }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dpanef_code')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ZDTF -->
                        <div class="form-group">
                            <label for="zdtf_code" class="block text-sm font-semibold text-gray-700 mb-2">ZDTF</label>
                            <select id="zdtf_code" name="zdtf_code"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                onchange="updateDfps()">
                                <option value="">Sélectionner un ZDTF</option>
                                @foreach ($zdtfs ?? [] as $zdtf)
                                    <option value="{{ $zdtf->code }}" data-dpanef-code="{{ $zdtf->dpanef_code }}"
                                        {{ old('zdtf_code', $article->zdtf_code) == $zdtf->code ? 'selected' : '' }}>
                                        {{ $zdtf->zdtf }}
                                    </option>
                                @endforeach
                            </select>
                            @error('zdtf_code')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DFP -->
                        <div class="form-group">
                            <label for="dfp_code" class="block text-sm font-semibold text-gray-700 mb-2">DFP</label>
                            <select id="dfp_code" name="dfp_code"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Sélectionner un DFP</option>
                                @foreach ($dfps ?? [] as $dfp)
                                    <option value="{{ $dfp->code }}" data-zdtf-code="{{ $dfp->zdtf_code }}"
                                        data-dpanef-code="{{ $dfp->dpanef_code }}"
                                        {{ old('dfp_code', $article->dfp_code) == $dfp->code ? 'selected' : '' }}>
                                        {{ $dfp->dfp }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dfp_code')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </x-form-section>

                {{-- 3. Informations forestières --}}
                <x-form-section number="3" title="Informations forestières" icon="fas fa-tree" color="purple">
                    @php
                        $selectedForets   = old('foret_ids',  $article->forets->pluck('id')->toArray());
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Forêts filtered by DPANEF -->
                        <div class="form-group">
                            <label for="foret_ids" class="block text-sm font-semibold text-gray-700 mb-2">Forêt</label>
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'foret_ids')">
                            <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                id="foret_ids" name="foret_ids[]">
                                @foreach ($forets ?? [] as $foret)
                                    <option value="{{ $foret->id }}"
                                        data-dpanef-id="{{ $foret->dpanef_id }}"
                                        {{ in_array($foret->id, $selectedForets) ? 'selected' : '' }}>
                                        {{ $foret->foret }}
                                    </option>
                                @endforeach
                            </select>
                            @error('foret_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Canton → dropdown -->
                        <div class="form-group">
                            <label for="canton" class="block text-sm font-semibold text-gray-700 mb-2">Canton</label>
                            <select id="canton" name="canton"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Sélectionner un canton</option>
                                @foreach ($cantons ?? [] as $c)
                                    <option value="{{ $c->canton }}" data-foret-id="{{ $c->foret_id }}"
                                        {{ old('canton', $article->canton) == $c->canton ? 'selected' : '' }}>
                                        {{ $c->canton }}
                                    </option>
                                @endforeach
                            </select>
                            @error('canton')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Parcelle → dropdown -->
                        <div class="form-group">
                            <label for="parcelle" class="block text-sm font-semibold text-gray-700 mb-2">Parcelle</label>
                            <select id="parcelle" name="parcelle"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Sélectionner une parcelle</option>
                                @foreach ($parcelles ?? [] as $p)
                                    <option value="{{ $p->parcelle }}" data-canton-id="{{ $p->canton_id }}"
                                        {{ old('parcelle', $article->parcelle) == $p->parcelle ? 'selected' : '' }}>
                                        {{ $p->parcelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parcelle')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nature juridique → dropdown -->
                        <div class="form-group">
                            <label for="nature_juridique" class="block text-sm font-semibold text-gray-700 mb-2">Nature juridique</label>
                            <select id="nature_juridique" name="nature_juridique"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Sélectionner</option>
                                @foreach (['Domaniale', 'Terrain collectif', 'Terrain récupéré'] as $nj)
                                    <option value="{{ $nj }}" {{ old('nature_juridique', $article->nature_juridique) == $nj ? 'selected' : '' }}>{{ $nj }}</option>
                                @endforeach
                            </select>
                            @error('nature_juridique')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </x-form-section>

                {{-- 4. Description du lot --}}
                <x-form-section number="4" title="Description du lot" icon="fas fa-clipboard-list" color="orange">

                    <!-- Limites du lot (8 directions) -->
                    <div class="mb-6">
                        <h4 class="text-base font-semibold text-gray-800 mb-4">Limites du lot</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ([
                                'limite_nord'  => 'Limite Nord',
                                'limite_sud'   => 'Limite Sud',
                                'limite_est'   => 'Limite Est',
                                'limite_ouest' => 'Limite Ouest',
                                'limite_ne'    => 'Limite Nord-Est',
                                'limite_no'    => 'Limite Nord-Ouest',
                                'limite_se'    => 'Limite Sud-Est',
                                'limite_so'    => 'Limite Sud-Ouest',
                            ] as $fieldName => $fieldLabel)
                                <div class="form-group">
                                    <label for="{{ $fieldName }}" class="block text-sm font-semibold text-gray-700 mb-2">{{ $fieldLabel }}</label>
                                    <input type="text" id="{{ $fieldName }}" name="{{ $fieldName }}"
                                        value="{{ old($fieldName, $article->$fieldName) }}"
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                        placeholder="{{ $fieldLabel }}">
                                    @error($fieldName)
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Coordonnées du centre -->
                    <div class="mb-6 pt-6 border-t border-gray-200">
                        <h4 class="text-base font-semibold text-gray-800 mb-4">Coordonnées du centre</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="coordonnee_x" class="block text-sm font-semibold text-gray-700 mb-2">Coordonnée X</label>
                                <input type="number" id="coordonnee_x" name="coordonnee_x"
                                    value="{{ old('coordonnee_x', $article->coordonnee_x) }}" step="any"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Coordonnée X">
                                @error('coordonnee_x')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="coordonnee_y" class="block text-sm font-semibold text-gray-700 mb-2">Coordonnée Y</label>
                                <input type="number" id="coordonnee_y" name="coordonnee_y"
                                    value="{{ old('coordonnee_y', $article->coordonnee_y) }}" step="any"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Coordonnée Y">
                                @error('coordonnee_y')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nature de coupe & Mode d'exploitation -->
                    @php
                        $selectedNatures = old('nature_de_coupe_ids', $article->natureDeCoupes->pluck('id')->toArray());
                        $selectedModes   = old('mode_exploitation_ids', $article->modeExploitations->pluck('id')->toArray());
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                        <div class="form-group">
                            <label for="nature_de_coupe_ids" class="block text-sm font-semibold text-gray-700 mb-2">Nature de coupe</label>
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'nature_de_coupe_ids')">
                            <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                id="nature_de_coupe_ids" name="nature_de_coupe_ids[]"
                                onchange="toggleDepotByNatureCoupe()">
                                @foreach ($natureDeCoupes ?? [] as $ndc)
                                    <option value="{{ $ndc->id }}" {{ in_array($ndc->id, $selectedNatures) ? 'selected' : '' }}>
                                        {{ $ndc->nature_de_coupe }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nature_de_coupe_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="mode_exploitation_ids" class="block text-sm font-semibold text-gray-700 mb-2">Mode d'exploitation</label>
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'mode_exploitation_ids')">
                            <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                id="mode_exploitation_ids" name="mode_exploitation_ids[]">
                                @foreach ($modeExploitations ?? [] as $me)
                                    <option value="{{ $me->id }}" {{ in_array($me->id, $selectedModes) ? 'selected' : '' }}>
                                        {{ $me->mode_exploiattion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mode_exploitation_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Dépôts: shown only when "Bois empilé sur dépôt" nature is selected -->
                    @php $selectedDepots = old('depot_ids', $article->depots->pluck('id')->toArray()); @endphp
                    <div id="depot-select-container" class="form-group mt-4" style="display: none;">
                        <label for="depot_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Dépôts <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..."
                            class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                            onkeyup="filterSelectOptions(this, 'depot_ids')">
                        <select multiple
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            id="depot_ids" name="depot_ids[]">
                            @foreach ($depots ?? [] as $depot)
                                <option value="{{ $depot->id }}" {{ in_array($depot->id, $selectedDepots) ? 'selected' : '' }}>
                                    {{ $depot->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('depot_ids')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </x-form-section>

                {{-- 5. Consistance du lot --}}
                <x-form-section number="5" title="Consistance du lot" icon="fas fa-cubes" color="gray">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="form-group">
                            <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2">Superficie</label>
                            <input type="number"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                id="superficie" name="superficie" value="{{ old('superficie', $article->superficie) }}"
                                min="0" step="0.01" placeholder="Superficie en hectares">
                            @error('superficie')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Produits présumés -->
                        <div class="bg-white rounded-xl p-6 border border-indigo-200">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold" style="color: #059669;">Produits présumés</h4>
                                <x-button type="button" onclick="addProductRow()" icon="fas fa-plus" size="sm">Ajouter</x-button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">Essence</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">Produits</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">Volume / Quantité</th>
                                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-table-body">
                                        @foreach ($article->essences as $idx => $essence)
                                            <tr class="product-row border-b">
                                                <td class="px-4 py-3 border">
                                                    <select name="products[{{ $idx }}][essence_id]"
                                                        class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                        required>
                                                        <option value="">Sélectionner une essence</option>
                                                        @foreach ($essences as $ess)
                                                            <option value="{{ $ess->id }}"
                                                                {{ old("products.{$idx}.essence_id", $essence->id) == $ess->id ? 'selected' : '' }}>
                                                                {{ $ess->essence }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="px-4 py-3 border">
                                                    <select name="products[{{ $idx }}][product_id]"
                                                        class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                        required>
                                                        <option value="">Sélectionner un produit</option>
                                                        @foreach ($products as $prod)
                                                            <option value="{{ $prod->id }}"
                                                                {{ old("products.{$idx}.product_id", $essence->pivot->product_id ?? '') == $prod->id ? 'selected' : '' }}>
                                                                {{ $prod->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="px-4 py-3 border">
                                                    <input type="number"
                                                        name="products[{{ $idx }}][quantity]"
                                                        class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                        min="0" step="0.01" placeholder="Volume / Quantité"
                                                        value="{{ old("products.{$idx}.quantity", $essence->pivot->quantity ?? '') }}"
                                                        required>
                                                </td>
                                                <td class="px-4 py-3 border text-center">
                                                    <button type="button" onclick="removeProductRow(this)"
                                                        class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-form-section>

                {{-- 6. Charges --}}
                <x-form-section number="6" title="Charges" icon="fas fa-file-invoice-dollar" color="red">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="taxe_refection_chemins" class="block text-sm font-semibold text-gray-700 mb-2">Taxe de réfection de chemin (DH)</label>
                            <input type="number" id="taxe_refection_chemins" name="taxe_refection_chemins"
                                value="{{ old('taxe_refection_chemins', $article->taxe_refection_chemins) }}" step="0.01" min="0"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400"
                                placeholder="Montant">
                            @error('taxe_refection_chemins')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date_echeance_taxe_refection_chemins" class="block text-sm font-semibold text-gray-700 mb-2">Échéancier – Taxe réfection chemin</label>
                            <input type="date" id="date_echeance_taxe_refection_chemins" name="date_echeance_taxe_refection_chemins"
                                value="{{ old('date_echeance_taxe_refection_chemins', $article->date_echeance_taxe_refection_chemins?->format('Y-m-d')) }}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400">
                            @error('date_echeance_taxe_refection_chemins')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="service_rendu_anef" class="block text-sm font-semibold text-gray-700 mb-2">Service rendu par l'ANEF (DH)</label>
                            <input type="number" id="service_rendu_anef" name="service_rendu_anef"
                                value="{{ old('service_rendu_anef', $article->service_rendu_anef) }}" step="0.01" min="0"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400"
                                placeholder="Montant">
                            @error('service_rendu_anef')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date_echeance_service_rendu_anef" class="block text-sm font-semibold text-gray-700 mb-2">Échéancier – Service rendu ANEF</label>
                            <input type="date" id="date_echeance_service_rendu_anef" name="date_echeance_service_rendu_anef"
                                value="{{ old('date_echeance_service_rendu_anef', $article->date_echeance_service_rendu_anef?->format('Y-m-d')) }}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400">
                            @error('date_echeance_service_rendu_anef')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-base font-semibold text-gray-800 mb-4">Volume bois de chauffage</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group">
                                <label for="bois_chauffage_volume" class="block text-sm font-semibold text-gray-700 mb-2">Volume (m³)</label>
                                <input type="number" id="bois_chauffage_volume" name="bois_chauffage_volume"
                                    value="{{ old('bois_chauffage_volume', $article->bois_chauffage_volume) }}" step="0.01" min="0"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400"
                                    placeholder="Volume">
                                @error('bois_chauffage_volume')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bois_chauffage_destination" class="block text-sm font-semibold text-gray-700 mb-2">Destination</label>
                                <input type="text" id="bois_chauffage_destination" name="bois_chauffage_destination"
                                    value="{{ old('bois_chauffage_destination', $article->bois_chauffage_destination) }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400"
                                    placeholder="Destination">
                                @error('bois_chauffage_destination')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="date_livraison_bois_chauffage" class="block text-sm font-semibold text-gray-700 mb-2">Date de livraison</label>
                                <input type="date" id="date_livraison_bois_chauffage" name="date_livraison_bois_chauffage"
                                    value="{{ old('date_livraison_bois_chauffage', $article->date_livraison_bois_chauffage?->format('Y-m-d')) }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400">
                                @error('date_livraison_bois_chauffage')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </x-form-section>

                {{-- 7. Particulière --}}
                <x-form-section number="7" title="Particulière" icon="fas fa-file-alt" color="green">
                    <div class="form-group">
                        <label for="particuliere" class="block text-sm font-semibold text-gray-700 mb-2">Particulière</label>
                        <textarea id="particuliere" name="particuliere" rows="4"
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Notes particulières">{{ old('particuliere', $article->particuliere) }}</textarea>
                        @error('particuliere')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </x-form-section>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 mt-8">
                    <x-button href="{{ route('articles.show', $article) }}" variant="secondary" icon="fas fa-times">
                        Annuler
                    </x-button>
                    <x-button type="submit" icon="fas fa-save">
                        Mettre à jour l'Article
                    </x-button>
                </div>
            </form>

            {{-- Plan de situation (separate form, outside PUT form) --}}
            <x-form-section number="8" title="Plan de situation – Import Excel" icon="fas fa-map" color="blue" class="mt-8 pt-8 border-t border-gray-100">
                <p class="text-sm text-gray-600 mb-4">
                    Importez un fichier Excel avec les colonnes <strong>mat</strong>, <strong>x</strong>, <strong>y</strong> (première ligne = en-têtes).
                </p>
                <form action="{{ route('articles.locations.import', $article) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-end gap-4">
                    @csrf
                    <div class="min-w-[240px]">
                        <label for="locations_file" class="block text-sm font-semibold text-gray-700 mb-2">Fichier Excel (.xlsx, .xls)</label>
                        <input type="file" id="locations_file" name="locations_file" accept=".xlsx,.xls"
                            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>
                        @error('locations_file')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <x-button type="submit" icon="fas fa-file-excel">Importer</x-button>
                </form>
            </x-form-section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let productRowCount = {{ $article->essences->count() }};
        const essences = @json($essences ?? []);
        const products = @json($products ?? []);

        function addProductRow() {
            productRowCount++;
            const tbody = document.getElementById('products-table-body');
            const row = document.createElement('tr');
            row.className = 'product-row border-b';
            row.innerHTML = `
        <td class="px-4 py-3 border">
            <select name="products[${productRowCount}][essence_id]"
                    class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                <option value="">Sélectionner une essence</option>
                ${essences.map(e => `<option value="${e.id}">${e.essence}</option>`).join('')}
            </select>
        </td>
        <td class="px-4 py-3 border">
            <select name="products[${productRowCount}][product_id]"
                    class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                <option value="">Sélectionner un produit</option>
                ${products.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
            </select>
        </td>
        <td class="px-4 py-3 border">
            <input type="number"
                   name="products[${productRowCount}][quantity]"
                   class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   min="0" step="0.01" placeholder="Volume / Quantité" required>
        </td>
        <td class="px-4 py-3 border text-center">
            <button type="button" onclick="removeProductRow(this)"
                    class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
            tbody.appendChild(row);
        }

        function removeProductRow(button) {
            button.closest('tr').remove();
        }

        function filterSelectOptions(inputEl, selectId) {
            const filter = inputEl.value.toLowerCase();
            const select = document.getElementById(selectId);
            if (!select) return;
            Array.from(select.options).forEach(opt => {
                opt.style.display = (opt.text || '').toLowerCase().indexOf(filter) !== -1 ? '' : 'none';
            });
        }

        function updateCommunes() {
            const provinceSelect = document.getElementById('province_ids');
            const communeSelect = document.getElementById('commune_ids');
            if (!communeSelect) return;
            const selectedProvinceIds = provinceSelect
                ? Array.from(provinceSelect.selectedOptions).map(o => String(o.value)).filter(v => v)
                : [];
            Array.from(communeSelect.options).forEach(option => {
                if (!selectedProvinceIds.length) { option.style.display = ''; return; }
                const provinceId = String(option.getAttribute('data-province-id') || '');
                const matches = selectedProvinceIds.includes(provinceId);
                option.style.display = matches ? '' : 'none';
                if (!matches && option.selected) option.selected = false;
            });
        }

        function updateDpanefs() {
            const dranefSelect = document.getElementById('dranef_code');
            const dpanefSelect = document.getElementById('dpanef_code');
            const selectedDranefCode = dranefSelect ? dranefSelect.value : '';
            Array.from(dpanefSelect.options).forEach(option => {
                if (option.value === '') { option.style.display = ''; return; }
                const dranefCode = option.getAttribute('data-dranef-code');
                const matches = !selectedDranefCode || dranefCode === selectedDranefCode;
                option.style.display = matches ? '' : 'none';
                if (!matches && option.selected) option.selected = false;
            });
            updateZdtfsAndForets();
        }

        function updateZdtfsAndForets() {
            const dpanefSelect = document.getElementById('dpanef_code');
            const zdtfSelect = document.getElementById('zdtf_code');
            const selectedDpanefCode = dpanefSelect ? dpanefSelect.value : '';
            Array.from(zdtfSelect.options).forEach(option => {
                if (option.value === '') { option.style.display = ''; return; }
                const dpanefCode = option.getAttribute('data-dpanef-code');
                const matches = !selectedDpanefCode || dpanefCode === selectedDpanefCode;
                option.style.display = matches ? '' : 'none';
                if (!matches && option.selected) option.selected = false;
            });

            // Filter forêts by DPANEF id
            const foretSelect = document.getElementById('foret_ids');
            if (foretSelect && dpanefSelect) {
                const selectedOption = dpanefSelect.options[dpanefSelect.selectedIndex];
                const dpanefId = selectedOption ? selectedOption.getAttribute('data-dpanef-id') : null;
                Array.from(foretSelect.options).forEach(option => {
                    if (!dpanefId || !selectedDpanefCode) { option.style.display = ''; return; }
                    const foretDpanefId = option.getAttribute('data-dpanef-id');
                    const matches = foretDpanefId && foretDpanefId === dpanefId;
                    option.style.display = matches ? '' : 'none';
                    if (!matches && option.selected) option.selected = false;
                });
            }
            updateDfps();
        }

        function updateDfps() {
            const zdtfSelect = document.getElementById('zdtf_code');
            const dpanefSelect = document.getElementById('dpanef_code');
            const dfpSelect = document.getElementById('dfp_code');
            if (!dfpSelect) return;
            const selectedZdtfCode = zdtfSelect ? zdtfSelect.value : '';
            const selectedDpanefCode = dpanefSelect ? dpanefSelect.value : '';
            Array.from(dfpSelect.options).forEach(option => {
                if (option.value === '') { option.style.display = ''; return; }
                const zdtfCode = option.getAttribute('data-zdtf-code');
                const dpanefCode = option.getAttribute('data-dpanef-code');
                let shouldShow = true;
                if (selectedZdtfCode && zdtfCode && zdtfCode !== selectedZdtfCode) shouldShow = false;
                if (selectedDpanefCode && dpanefCode && dpanefCode !== selectedDpanefCode) shouldShow = false;
                option.style.display = shouldShow ? '' : 'none';
                if (!shouldShow && option.selected) option.selected = false;
            });
        }

        // Show dépôts only when "Bois empilé sur dépôt" nature de coupe is selected
        function toggleDepotByNatureCoupe() {
            const select = document.getElementById('nature_de_coupe_ids');
            const container = document.getElementById('depot-select-container');
            if (!select || !container) return;
            const selectedTexts = Array.from(select.selectedOptions).map(o => o.text.toLowerCase());
            const hasBoisDepot = selectedTexts.some(t => t.includes('emp') && t.includes('dép'));
            container.style.display = hasBoisDepot ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateCommunes();
            updateDpanefs();
            updateZdtfsAndForets();
            updateDfps();
            toggleDepotByNatureCoupe();
        });
    </script>
@endpush
