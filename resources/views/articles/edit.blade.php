@extends('layouts.app')

@section('title', 'Modifier Article #' . ($article->numero ?? $article->id) . ' - DEFATP')

@section('breadcrumb')
    <li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    @if ($article->cession)
        <li class="bc-item"><a href="{{ route('cessions.show', $article->cession) }}">Cession #{{ $article->cession->id }}</a></li>
    @endif
    <li class="bc-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
    <li class="bc-item active">Modifier</li>
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

                @if (
                    $currentUser &&
                        ($currentUser->dranef_id ||
                            $currentUser->dpanef_id ||
                            $currentUser->zdtf_id ||
                            $currentUser->dfp_id ||
                            $currentUser->province_id))
                    <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200 mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-user-tag text-emerald-600"></i>
                            <span class="text-sm font-semibold text-emerald-800 uppercase tracking-wider">Votre affectation</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 text-sm">
                            @if ($currentUser->dranef)
                                <div>
                                    <span class="text-gray-500 block">DRANEF</span>
                                    <span class="font-medium text-gray-900">{{ $currentUser->dranef->dranef }} - {{ $currentUser->dranef->Abréviation }}</span>
                                </div>
                            @endif
                            @if ($currentUser->dpanef)
                                <div>
                                    <span class="text-gray-500 block">DPANEF</span>
                                    <span class="font-medium text-gray-900">{{ $currentUser->dpanef->dpanef }}</span>
                                </div>
                            @endif
                            @if ($currentUser->zdtf)
                                <div>
                                    <span class="text-gray-500 block">ZDTF</span>
                                    <span class="font-medium text-gray-900">{{ $currentUser->zdtf->zdtf }}</span>
                                </div>
                            @endif
                            @if ($currentUser->dfp)
                                <div>
                                    <span class="text-gray-500 block">DFP</span>
                                    <span class="font-medium text-gray-900">{{ $currentUser->dfp->dfp }}</span>
                                </div>
                            @endif
                            @if ($currentUser->province)
                                <div>
                                    <span class="text-gray-500 block">Province</span>
                                    <span class="font-medium text-gray-900">{{ $currentUser->province->nom }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

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
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Type de cession</label>
                                <input type="text" readonly
                                    class="form-input w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                                    value="{{ $cession->mode_cession === 'appel_offre' ? "Appel d'offre" : 'Adjudication' }}">
                            </div>
                            @if ($cession->mode_cession === 'adjudication')
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'adjudication</label>
                                    <input type="text" readonly
                                        class="form-input w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                                        value="{{ $cession->DateAdj ? $cession->DateAdj->format('d/m/Y') : '-' }}">
                                </div>
                            @else
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Numéro AO</label>
                                    <input type="text" readonly
                                        class="form-input w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                                        value="{{ $cession->numAO ?? '-' }}">
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'attribution (AO)</label>
                                    <input type="text" readonly
                                        class="form-input w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                                        value="{{ $cession->dateAO ? $cession->dateAO->format('d/m/Y') : '-' }}">
                                </div>
                            @endif
                        </div>
                    @endif
                </x-form-section>

                {{-- 2. Localisation du lot --}}
                <x-form-section number="2" title="Localisation du lot" icon="fas fa-map-marker-alt" color="blue">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        @php
                            $cessionDranefCode = $cession?->dranef?->code;
                            $userDranefCode  = $currentUser?->dranef?->code;
                            $userDpanefCode  = $currentUser?->dpanef?->code;
                            $userZdtfCode    = $currentUser?->zdtf?->code;
                            $userDfpCode     = $currentUser?->dfp?->code;
                            $userProvinceId  = $currentUser?->province_id;
                            $userCommuneId   = $currentUser?->commune_id;
                            $effectiveDranefCode = $cessionDranefCode ?? $userDranefCode;
                            $isAdmin      = $currentUser?->hasRole('admin');
                            $lockDranef   = (bool) $effectiveDranefCode;
                            $lockDpanef   = (bool) $userDpanefCode;
                            $lockZdtf     = (bool) $userZdtfCode;
                            $lockDfp      = (bool) $userDfpCode;
                            $lockProvince = (bool) $userProvinceId;
                            $lockCommune  = $userCommuneId   && !$isAdmin;
                            $articleProvinceId = $article->provinces->first()?->id;
                        @endphp

                        <!-- Province -->
                        <div class="form-group">
                            <label for="province_ids" class="block text-sm font-semibold text-gray-700 mb-2">Province</label>
                            @php $selectedProvinces = collect(old('province_ids', $lockProvince ? [$userProvinceId] : $article->provinces->pluck('id')->toArray())); @endphp
                            @if($lockProvince)
                                <input type="hidden" name="province_ids[]" value="{{ old('province_ids.0', $userProvinceId) }}">
                            @endif
                            @if(!$lockProvince)
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'province_ids')">
                            @endif
                            <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 {{ $lockProvince ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                id="province_ids" {{ $lockProvince ? 'name="_province_ids_readonly" disabled' : 'name="province_ids[]"' }}
                                onchange="{{ $lockProvince ? '' : 'renderTags(\'province_ids\',\'province_tags\'); updateCommunes();' }}">
                                @foreach ($provinces ?? [] as $province)
                                    <option value="{{ $province->id }}"
                                        {{ $selectedProvinces->contains((string) $province->id) ? 'selected' : '' }}>
                                        {{ $province->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="province_tags" class="flex flex-wrap gap-1.5 mt-2"></div>
                            @error('province_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Communes -->
                        <div class="form-group">
                            <label for="commune_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                Communes <span class="text-red-500">*</span>
                            </label>
                            @php $selectedCommunes = collect(old('commune_ids', $lockCommune ? [$userCommuneId] : $article->communes->pluck('id')->toArray())); @endphp
                            @if($lockCommune)
                                <input type="hidden" name="commune_ids[]" value="{{ old('commune_ids.0', $userCommuneId) }}">
                            @endif
                            @if(!$lockCommune)
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'commune_ids')">
                            @endif
                            <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 {{ $lockCommune ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                id="commune_ids" {{ $lockCommune ? 'name="_commune_ids_readonly" disabled' : 'name="commune_ids[]"' }}
                                onchange="renderSelectTags('commune_ids','commune_tags');">
                                @foreach ($communes ?? [] as $commune)
                                    <option value="{{ $commune->id }}" data-province-id="{{ $commune->province_id }}"
                                        {{ $selectedCommunes->contains((string) $commune->id) ? 'selected' : '' }}>
                                        {{ $commune->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="commune_tags" class="flex flex-wrap gap-1.5 mt-2"></div>
                            @error('commune_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DRANEF -->
                        <div class="form-group">
                            <label for="dranef_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                DRANEF <span class="text-red-500">*</span>
                            </label>
                            @if($lockDranef)
                                <input type="hidden" name="dranef_code" value="{{ old('dranef_code', $effectiveDranefCode) }}">
                            @endif
                            <select id="dranef_code" {{ $lockDranef ? 'name="_dranef_code_readonly" disabled' : 'name="dranef_code"' }}
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 {{ $lockDranef ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                onchange="{{ $lockDranef ? '' : 'updateDpanefs()' }}">
                                <option value="">Sélectionner un DRANEF</option>
                                @foreach ($dranefs ?? [] as $dranef)
                                    <option value="{{ $dranef->code }}"
                                        {{ old('dranef_code', $lockDranef ? $effectiveDranefCode : $article->dranef_code) == $dranef->code ? 'selected' : '' }}>
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
                            <label for="dpanef_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                DPANEF <span class="text-red-500">*</span>
                            </label>
                            @if($lockDpanef)
                                <input type="hidden" name="dpanef_code" value="{{ old('dpanef_code', $userDpanefCode) }}">
                            @endif
                            <select id="dpanef_code" {{ $lockDpanef ? 'name="_dpanef_code_readonly" disabled' : 'name="dpanef_code"' }}
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 {{ $lockDpanef ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                onchange="{{ $lockDpanef ? '' : 'updateZdtfsAndForets()' }}">
                                <option value="">Sélectionner un DPANEF</option>
                                @foreach ($dpanefs ?? [] as $dpanef)
                                    <option value="{{ $dpanef->code }}"
                                        data-dranef-code="{{ $dpanef->dranef_code }}"
                                        data-dpanef-id="{{ $dpanef->id }}"
                                        {{ old('dpanef_code', $lockDpanef ? $userDpanefCode : $article->dpanef_code) == $dpanef->code ? 'selected' : '' }}>
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
                            <label for="zdtf_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                ZDTF <span class="text-red-500">*</span>
                            </label>
                            @if($lockZdtf)
                                <input type="hidden" name="zdtf_code" value="{{ old('zdtf_code', $userZdtfCode) }}">
                            @endif
                            <select id="zdtf_code" {{ $lockZdtf ? 'name="_zdtf_code_readonly" disabled' : 'name="zdtf_code"' }}
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 {{ $lockZdtf ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                onchange="{{ $lockZdtf ? '' : 'updateDfps()' }}">
                                <option value="">Sélectionner un ZDTF</option>
                                @foreach ($zdtfs ?? [] as $zdtf)
                                    <option value="{{ $zdtf->code }}" data-dpanef-code="{{ $zdtf->dpanef_code }}"
                                        {{ old('zdtf_code', $lockZdtf ? $userZdtfCode : $article->zdtf_code) == $zdtf->code ? 'selected' : '' }}>
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
                            <label for="dfp_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                DFP <span class="text-red-500">*</span>
                            </label>
                            @if($lockDfp)
                                <input type="hidden" name="dfp_code" value="{{ old('dfp_code', $userDfpCode) }}">
                            @endif
                            <select id="dfp_code" {{ $lockDfp ? 'name="_dfp_code_readonly" disabled' : 'name="dfp_code"' }}
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 {{ $lockDfp ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                <option value="">Sélectionner un DFP</option>
                                @foreach ($dfps ?? [] as $dfp)
                                    <option value="{{ $dfp->code }}" data-zdtf-code="{{ $dfp->zdtf_code }}"
                                        data-dpanef-code="{{ $dfp->dpanef_code }}"
                                        {{ old('dfp_code', $lockDfp ? $userDfpCode : $article->dfp_code) == $dfp->code ? 'selected' : '' }}>
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
                                id="foret_ids" name="foret_ids[]"
                                onchange="renderTags('foret_ids','foret_tags');">
                                @foreach ($forets ?? [] as $foret)
                                    <option value="{{ $foret->id }}"
                                        data-dpanef-id="{{ $foret->dpanef_id }}"
                                        {{ in_array($foret->id, $selectedForets) ? 'selected' : '' }}>
                                        {{ $foret->foret }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="foret_tags" class="flex flex-wrap gap-1.5 mt-2"></div>
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
                                onchange="toggleDepotByNatureCoupe(); renderTags('nature_de_coupe_ids','nature_coupe_tags');">
                                @foreach ($natureDeCoupes ?? [] as $ndc)
                                    <option value="{{ $ndc->id }}" {{ in_array($ndc->id, $selectedNatures) ? 'selected' : '' }}>
                                        {{ $ndc->nature_de_coupe }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="nature_coupe_tags" class="flex flex-wrap gap-1.5 mt-2"></div>
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
                                id="mode_exploitation_ids" name="mode_exploitation_ids[]"
                                onchange="renderTags('mode_exploitation_ids','mode_exploitation_tags');">
                                @foreach ($modeExploitations ?? [] as $me)
                                    <option value="{{ $me->id }}" {{ in_array($me->id, $selectedModes) ? 'selected' : '' }}>
                                        {{ $me->mode_exploiattion }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="mode_exploitation_tags" class="flex flex-wrap gap-1.5 mt-2"></div>
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
                            id="depot_ids" name="depot_ids[]"
                            onchange="renderTags('depot_ids','depot_tags');">
                            @foreach ($depots ?? [] as $depot)
                                <option value="{{ $depot->id }}" {{ in_array($depot->id, $selectedDepots) ? 'selected' : '' }}>
                                    {{ $depot->nom }}
                                </option>
                            @endforeach
                        </select>
                        <div id="depot_tags" class="flex flex-wrap gap-1.5 mt-2"></div>
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
                                        @php
                                            $essenceGroups = $article->essences->groupBy('id');
                                            $essenceGroupIdx = 0;
                                        @endphp
                                        @foreach ($essenceGroups as $essenceId => $essenceRows)
                                            @php $essenceGroupIdx++; @endphp
                                            <tr class="essence-group-row" data-essence-idx="{{ $essenceGroupIdx }}">
                                                <td colspan="4" class="px-3 py-3 border-b">
                                                    <div class="border border-indigo-200 rounded-xl bg-indigo-50 p-4">
                                                        <div class="flex items-center gap-3 mb-3">
                                                            <div class="flex-1">
                                                                <label class="block text-xs font-semibold text-gray-600 mb-1">Essence</label>
                                                                <select name="products[{{ $essenceGroupIdx }}][essence_id]"
                                                                    class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                                                                    required>
                                                                    <option value="">Sélectionner une essence</option>
                                                                    @foreach ($essences as $ess)
                                                                        <option value="{{ $ess->id }}"
                                                                            {{ $essenceId == $ess->id ? 'selected' : '' }}>
                                                                            {{ $ess->essence }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="pt-5">
                                                                <button type="button" onclick="removeEssenceRow(this)"
                                                                    class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="ml-2">
                                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Produits &amp; Volumes</label>
                                                            <table class="w-full text-sm">
                                                                <thead>
                                                                    <tr class="bg-white">
                                                                        <th class="px-3 py-2 text-left font-medium text-gray-600 border-b">Produit</th>
                                                                        <th class="px-3 py-2 text-left font-medium text-gray-600 border-b">Volume / Quantité</th>
                                                                        <th class="w-10 border-b"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="essence-items-body" data-essence-idx="{{ $essenceGroupIdx }}">
                                                                    @foreach ($essenceRows as $itemIdx => $essRow)
                                                                        <tr class="product-item-row">
                                                                            <td class="px-3 py-2 border-b border-gray-100">
                                                                                <select name="products[{{ $essenceGroupIdx }}][items][{{ $itemIdx + 1 }}][product_id]"
                                                                                    class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                                                    required>
                                                                                    <option value="">Sélectionner un produit</option>
                                                                                    @foreach ($products as $prod)
                                                                                        <option value="{{ $prod->id }}"
                                                                                            {{ ($essRow->pivot->product_id ?? '') == $prod->id ? 'selected' : '' }}>
                                                                                            {{ $prod->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td class="px-3 py-2 border-b border-gray-100">
                                                                                <input type="number"
                                                                                    name="products[{{ $essenceGroupIdx }}][items][{{ $itemIdx + 1 }}][quantity]"
                                                                                    class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                                                    min="0" step="0.01" placeholder="Volume / Quantité"
                                                                                    value="{{ $essRow->pivot->quantity ?? '' }}"
                                                                                    required>
                                                                            </td>
                                                                            <td class="px-3 py-2 border-b border-gray-100 text-center w-10">
                                                                                <button type="button" onclick="removeProductItemRow(this)"
                                                                                    class="px-2 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors text-xs">
                                                                                    <i class="fas fa-times"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                            <button type="button" onclick="addProductItemRow(this, {{ $essenceGroupIdx }})"
                                                                class="mt-2 px-3 py-1 bg-white border border-indigo-300 text-indigo-700 rounded-lg hover:bg-indigo-100 text-xs transition-colors">
                                                                <i class="fas fa-plus mr-1"></i>Ajouter un produit
                                                            </button>
                                                        </div>
                                                    </div>
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
        let essenceRowCount = {{ $article->essences->groupBy('id')->count() }};
        const essences = @json($essences ?? []);
        const products = @json($products ?? []);

        function buildProductItemHtml(essenceIdx, itemIdx) {
            return `
                <tr class="product-item-row">
                    <td class="px-3 py-2 border-b border-gray-100">
                        <select name="products[${essenceIdx}][items][${itemIdx}][product_id]"
                                class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                required>
                            <option value="">Sélectionner un produit</option>
                            ${products.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                        </select>
                    </td>
                    <td class="px-3 py-2 border-b border-gray-100">
                        <input type="number"
                               name="products[${essenceIdx}][items][${itemIdx}][quantity]"
                               class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               min="0" step="0.01" placeholder="Volume / Quantité" required>
                    </td>
                    <td class="px-3 py-2 border-b border-gray-100 text-center w-10">
                        <button type="button" onclick="removeProductItemRow(this)"
                                class="px-2 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition-colors text-xs">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>`;
        }

        function addProductRow() {
            essenceRowCount++;
            const idx = essenceRowCount;
            const tbody = document.getElementById('products-table-body');
            const row = document.createElement('tr');
            row.className = 'essence-group-row';
            row.dataset.essenceIdx = idx;
            row.innerHTML = `
                <td colspan="4" class="px-3 py-3 border-b">
                    <div class="border border-indigo-200 rounded-xl bg-indigo-50 p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Essence</label>
                                <select name="products[${idx}][essence_id]"
                                        class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                                        required>
                                    <option value="">Sélectionner une essence</option>
                                    ${essences.map(e => `<option value="${e.id}">${e.essence}</option>`).join('')}
                                </select>
                            </div>
                            <div class="pt-5">
                                <button type="button" onclick="removeEssenceRow(this)"
                                        class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="ml-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Produits &amp; Volumes</label>
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-white">
                                        <th class="px-3 py-2 text-left font-medium text-gray-600 border-b">Produit</th>
                                        <th class="px-3 py-2 text-left font-medium text-gray-600 border-b">Volume / Quantité</th>
                                        <th class="w-10 border-b"></th>
                                    </tr>
                                </thead>
                                <tbody class="essence-items-body" data-essence-idx="${idx}">
                                    ${buildProductItemHtml(idx, 1)}
                                </tbody>
                            </table>
                            <button type="button" onclick="addProductItemRow(this, ${idx})"
                                    class="mt-2 px-3 py-1 bg-white border border-indigo-300 text-indigo-700 rounded-lg hover:bg-indigo-100 text-xs transition-colors">
                                <i class="fas fa-plus mr-1"></i>Ajouter un produit
                            </button>
                        </div>
                    </div>
                </td>`;
            tbody.appendChild(row);
        }

        function removeEssenceRow(button) {
            button.closest('tr.essence-group-row').remove();
        }

        function addProductItemRow(button, essenceIdx) {
            const tbody = button.closest('div').querySelector('tbody.essence-items-body');
            const existingCount = tbody.querySelectorAll('tr.product-item-row').length;
            const itemIdx = existingCount + 1;
            tbody.insertAdjacentHTML('beforeend', buildProductItemHtml(essenceIdx, itemIdx));
        }

        function removeProductItemRow(button) {
            const row = button.closest('tr.product-item-row');
            const tbody = row.closest('tbody.essence-items-body');
            if (tbody.querySelectorAll('tr.product-item-row').length > 1) {
                row.remove();
            }
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
                ? Array.from(provinceSelect.selectedOptions).map(o => o.value).filter(Boolean)
                : [];

            Array.from(communeSelect.options).forEach(option => {
                if (!selectedProvinceIds.length) {
                    option.style.display = '';
                    return;
                }
                const provinceId = String(option.getAttribute('data-province-id') || '');
                const matches = selectedProvinceIds.includes(provinceId);
                option.style.display = matches ? '' : 'none';
                if (!matches && option.selected) option.selected = false;
            });
            renderTags('commune_ids', 'commune_tags');
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

        function renderTags(selectId, containerId) {
            const select = document.getElementById(selectId);
            const container = document.getElementById(containerId);
            if (!select || !container) return;
            const selected = Array.from(select.selectedOptions).map(o => o.text.trim()).filter(Boolean);
            if (!selected.length) { container.innerHTML = ''; return; }
            container.innerHTML = selected.map(function(t) {
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">' + t + '</span>';
            }).join('');
        }

        // renderSelectTags is an alias used by commune_ids onchange
        function renderSelectTags(selectId, containerId) { renderTags(selectId, containerId); }

        // Show dépôts only when "Bois empilé sur dépôt" nature de coupe is selected
        function toggleDepotByNatureCoupe() {
            const select = document.getElementById('nature_de_coupe_ids');
            const container = document.getElementById('depot-select-container');
            if (!select || !container) return;
            const selectedTexts = Array.from(select.selectedOptions).map(o => o.text.toLowerCase());
            const hasBoisDepot = selectedTexts.some(t => t.includes('empil'));
            container.style.display = hasBoisDepot ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // On init: only show/hide options based on parent selection, never deselect pre-filled values
            function softFilter(selectEl, matchFn) {
                if (!selectEl) return;
                Array.from(selectEl.options).forEach(function(opt) {
                    if (opt.value === '') { opt.style.display = ''; return; }
                    opt.style.display = matchFn(opt) ? '' : 'none';
                });
            }
            var dranefSel = document.getElementById('dranef_code');
            var dpanefSel = document.getElementById('dpanef_code');
            var zdtfSel   = document.getElementById('zdtf_code');
            var dfpSel    = document.getElementById('dfp_code');

            if (dranefSel && dpanefSel) {
                var dranefCode = dranefSel.value;
                softFilter(dpanefSel, function(o) { return !dranefCode || o.getAttribute('data-dranef-code') === dranefCode; });
            }
            if (dpanefSel && zdtfSel) {
                var dpanefCode = dpanefSel.value;
                softFilter(zdtfSel, function(o) { return !dpanefCode || o.getAttribute('data-dpanef-code') === dpanefCode; });
            }
            if (zdtfSel && dfpSel) {
                var zdtfCode2 = zdtfSel.value;
                var dpanefCode2 = dpanefSel ? dpanefSel.value : '';
                softFilter(dfpSel, function(o) {
                    var zc = o.getAttribute('data-zdtf-code');
                    var dc = o.getAttribute('data-dpanef-code');
                    if (zdtfCode2 && zc && zc !== zdtfCode2) return false;
                    if (dpanefCode2 && dc && dc !== dpanefCode2) return false;
                    return true;
                });
            }
            updateCommunes();
            toggleDepotByNatureCoupe();
            renderTags('province_ids', 'province_tags');
            renderTags('commune_ids', 'commune_tags');
            renderTags('foret_ids', 'foret_tags');
            renderTags('nature_de_coupe_ids', 'nature_coupe_tags');
            renderTags('mode_exploitation_ids', 'mode_exploitation_tags');
            renderTags('depot_ids', 'depot_tags');
        });
    </script>
@endpush
