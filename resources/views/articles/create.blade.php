@extends('layouts.app')

@section('title', 'Nouvel Article - DEFATP')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    <li class="breadcrumb-item active">Nouvel article</li>
@endsection

@section('content')
    <div class="min-w-0 max-w-full overflow-x-hidden">

        <x-page-header title="Nouvel Article" subtitle="Créer un nouvel article forestier pour votre système"
            icon="fas fa-file-alt">
            <x-slot name="actions">
                <x-button href="{{ route('cessions.index') }}" variant="secondary" icon="fas fa-arrow-left" size="sm">
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

        <!-- Create Form -->
        <div class="bg-white rounded-2xl border p-6"
            style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <form action="{{ route('articles.store') }}" method="POST" id="articleForm" class="space-y-8"
                enctype="multipart/form-data">
                @csrf
                @if (request('cession_id'))
                    <input type="hidden" name="cession_id" value="{{ request('cession_id') }}">
                @endif

                @if (
                    $currentUser &&
                        ($currentUser->dranef_id ||
                            $currentUser->dpanef_id ||
                            $currentUser->zdtf_id ||
                            $currentUser->dfp_id ||
                            $currentUser->province_id))
                    <!-- Affectation de l'utilisateur (lecture seule) -->
                    <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200 mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-user-tag text-emerald-600"></i>
                            <span class="text-sm font-semibold text-emerald-800 uppercase tracking-wider">Votre
                                affectation</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 text-sm">
                            @if ($currentUser->dranef)
                                <div>
                                    <span class="text-gray-500 block">DRANEF</span>
                                    <span class="font-medium text-gray-900">{{ $currentUser->dranef->dranef }}</span>
                                </div>
                            @endif
                            @if ($currentUser->dpanef)
                                <div>
                                    <span class="text-gray-500 block">DPANEF</span>
                                    <span
                                        class="font-medium text-gray-900">{{ $currentUser->dpanef->dpanef ?? $currentUser->dpanef->code }}</span>
                                </div>
                            @endif
                            @if ($currentUser->zdtf)
                                <div>
                                    <span class="text-gray-500 block">ZDTF</span>
                                    <span
                                        class="font-medium text-gray-900">{{ $currentUser->zdtf->code ?? ($currentUser->zdtf->zdtf ?? $currentUser->zdtf->sdtf) }}</span>
                                </div>
                            @endif
                            @if ($currentUser->dfp)
                                <div>
                                    <span class="text-gray-500 block">DFP</span>
                                    <span
                                        class="font-medium text-gray-900">{{ $currentUser->dfp->code ?? $currentUser->dfp->dfp }}</span>
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label for="numero" class="block text-sm font-semibold text-gray-700 mb-2">
                                Numéro d'article
                            </label>
                            <input type="number"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="numero" name="numero" value="{{ old('numero') }}" placeholder="Numéro d'article">
                            @error('numero')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="lot" class="block text-sm font-semibold text-gray-700 mb-2">
                                Numéro du lot
                            </label>
                            <input type="number"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="lot" name="lot" value="{{ old('lot') }}" placeholder="Numéro du lot">
                            @error('lot')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Champ Année supprimé --}}
                    </div>
                </x-form-section>

                {{-- 2. Localisation du lot --}}
                <x-form-section number="2" title="Localisation du lot" icon="fas fa-map-marker-alt" color="blue">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Province -->
                        <div class="form-group">
                            <label for="province_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Province
                            </label>
                            <select id="province_id" name="province_id"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                onchange="updateCommunes()">
                                <option value="">Sélectionner une province</option>
                                @foreach ($provinces ?? [] as $province)
                                    <option value="{{ $province->id }}"
                                        {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Communes (Multiple Selection) -->
                        <div class="form-group">
                            <label for="commune_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                Communes <span class="text-red-500">*</span>
                            </label>
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'commune_ids')">
                            <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="commune_ids" name="commune_ids[]">
                                @foreach ($communes ?? [] as $commune)
                                    <option value="{{ $commune->id }}" data-province-id="{{ $commune->province_id }}"
                                        {{ collect(old('commune_ids', []))->contains($commune->id) ? 'selected' : '' }}>
                                        {{ $commune->nom }}@if ($commune->province)
                                            - {{ $commune->province->nom }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('commune_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                            @error('commune_ids.*')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DRANEF -->
                        <div class="form-group">
                            <label for="dranef_code" class="block text-sm font-semibold text-gray-700 mb-2">
                                DRANEF
                            </label>
                            <select id="dranef_code" name="dranef_code"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                onchange="updateDpanefs()">
                                <option value="">Sélectionner un DRANEF</option>
                                @foreach ($dranefs ?? [] as $dranef)
                                    <option value="{{ $dranef->code }}"
                                        {{ old('dranef_code') == $dranef->code ? 'selected' : '' }}>
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
                                DPANEF
                            </label>
                            <select id="dpanef_code" name="dpanef_code"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                onchange="updateZdtfs()">
                                <option value="">Sélectionner un DPANEF</option>
                                @foreach ($dpanefs ?? [] as $dpanef)
                                    <option value="{{ $dpanef->code }}" data-dranef-code="{{ $dpanef->dranef_code }}"
                                        {{ old('dpanef_code') == $dpanef->code ? 'selected' : '' }}>
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
                                ZDTF
                            </label>
                            <select id="zdtf_code" name="zdtf_code"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                onchange="updateDfps()">
                                <option value="">Sélectionner un ZDTF</option>
                                @foreach ($zdtfs ?? [] as $zdtf)
                                    <option value="{{ $zdtf->code }}" data-dpanef-code="{{ $zdtf->dpanef_code }}"
                                        {{ old('zdtf_code') == $zdtf->code ? 'selected' : '' }}>
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
                                DFP
                            </label>
                            <select id="dfp_code" name="dfp_code"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Sélectionner un DFP</option>
                                @foreach ($dfps ?? [] as $dfp)
                                    <option value="{{ $dfp->code }}" data-zdtf-code="{{ $dfp->zdtf_code }}"
                                        data-dpanef-code="{{ $dfp->dpanef_code }}"
                                        {{ old('dfp_code') == $dfp->code ? 'selected' : '' }}>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="foret_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                Forêt
                            </label>
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'foret_ids')">
                            <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                id="foret_ids" name="foret_ids[]">
                                @foreach ($forets ?? [] as $foret)
                                    <option value="{{ $foret->id }}"
                                        {{ collect(old('foret_ids', []))->contains($foret->id) ? 'selected' : '' }}>
                                        {{ $foret->foret }}
                                    </option>
                                @endforeach
                            </select>
                            @error('foret_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="canton" class="block text-sm font-semibold text-gray-700 mb-2">
                                Canton
                            </label>
                            <input type="text"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                id="canton" name="canton" value="{{ old('canton') }}" placeholder="Canton">
                            @error('canton')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="parcelle" class="block text-sm font-semibold text-gray-700 mb-2">
                                Parcelle
                            </label>
                            <input type="text"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                id="parcelle" name="parcelle" value="{{ old('parcelle') }}" placeholder="Parcelle">
                            @error('parcelle')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nature_juridique" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nature juridique
                            </label>
                            <input type="text" name="nature_juridique"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                id="nature_juridique" value="{{ old('nature_juridique') }}"
                                placeholder="Entrez la nature juridique">
                            @error('nature_juridique')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </x-form-section>

                {{-- 4. Description du lot --}}
                <x-form-section number="4" title="Description du lot" icon="fas fa-clipboard-list" color="orange">


                    <!-- Sous-section : Limites du lot -->
                    {{-- <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-base font-semibold text-gray-800 mb-4">Limites du lot</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="limite_nord" class="block text-sm font-semibold text-gray-700 mb-2">Limite Nord</label>
                                <input type="text" id="limite_nord" name="limite_nord" value="{{ old('limite_nord') }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Limite Nord">
                                @error('limite_nord')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="limite_sud" class="block text-sm font-semibold text-gray-700 mb-2">Limite Sud</label>
                                <input type="text" id="limite_sud" name="limite_sud" value="{{ old('limite_sud') }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Limite Sud">
                                @error('limite_sud')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="limite_est" class="block text-sm font-semibold text-gray-700 mb-2">Limite Est</label>
                                <input type="text" id="limite_est" name="limite_est" value="{{ old('limite_est') }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Limite Est">
                                @error('limite_est')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="limite_ouest" class="block text-sm font-semibold text-gray-700 mb-2">Limite Ouest</label>
                                <input type="text" id="limite_ouest" name="limite_ouest" value="{{ old('limite_ouest') }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Limite Ouest">
                                @error('limite_ouest')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div> --}}

                    <!-- Sous-section : Coordonnées du centre -->
                    <div class="gap-6">
                        <h4 class="text-base font-semibold text-gray-800 mb-4">Coordonnées du centre</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="coordonnee_x"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Coordonnée X <span
                                        class="text-red-500">*</span></label>
                                <input type="number" id="coordonnee_x" name="coordonnee_x"
                                    value="{{ old('coordonnee_x') }}" step="any"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Coordonnée X" required>
                                @error('coordonnee_x')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="coordonnee_y"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Coordonnée Y <span
                                        class="text-red-500">*</span></label>
                                <input type="number" id="coordonnee_y" name="coordonnee_y"
                                    value="{{ old('coordonnee_y') }}" step="any"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Coordonnée Y" required>
                                @error('coordonnee_y')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 pt-6 border-t border-gray-200">
                        <div class="form-group">
                            <label for="nature_de_coupe_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nature de coupe
                            </label>
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'nature_de_coupe_ids')">
                            <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                id="nature_de_coupe_ids" name="nature_de_coupe_ids[]">
                                @foreach ($natureDeCoupes ?? [] as $natureDeCoupe)
                                    <option value="{{ $natureDeCoupe->id }}"
                                        {{ collect(old('nature_de_coupe_ids', []))->contains($natureDeCoupe->id) ? 'selected' : '' }}>
                                        {{ $natureDeCoupe->nature_de_coupe }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nature_de_coupe_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="mode_exploitation_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                Mode d'exploitation
                            </label>
                            <input type="text" placeholder="Rechercher..."
                                class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg"
                                onkeyup="filterSelectOptions(this, 'mode_exploitation_ids')">
                            <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                id="mode_exploitation_ids" name="mode_exploitation_ids[]">
                                @foreach ($modeExploitations ?? [] as $modeExploitation)
                                    <option value="{{ $modeExploitation->id }}"
                                        {{ collect(old('mode_exploitation_ids', []))->contains($modeExploitation->id) ? 'selected' : '' }}>
                                        {{ $modeExploitation->mode_exploiattion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mode_exploitation_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mt-5">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="is_on_depot" name="is_on_depot" value="1"
                                {{ old('is_on_depot') ? 'checked' : '' }} onchange="toggleDepotSelect()"
                                class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-semibold text-gray-700">Le bois est sur dépôt</span>
                        </label>
                    </div>
                    <div id="depot-select-container" class="form-group mt-4"
                        style="display: {{ old('is_on_depot') ? 'block' : 'none' }};">
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
                                <option value="{{ $depot->id }}"
                                    {{ collect(old('depot_ids', []))->contains($depot->id) ? 'selected' : '' }}>
                                    {{ $depot->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('depot_ids')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        @error('depot_ids.*')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </x-form-section>

                {{-- 5. Consistance du lot --}}
                <x-form-section number="5" title="Consistance du lot" icon="fas fa-cubes" color="gray">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="form-group">
                            <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2">
                                Superficie
                            </label>
                            <input type="number"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                id="superficie" name="superficie" value="{{ old('superficie') }}" min="0"
                                step="0.01" placeholder="Superficie en hectares">
                            @error('superficie')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="bg-white rounded-xl p-6 border border-indigo-200">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold" style="color: #059669;">Produits présumés</h4>
                                <x-button type="button" onclick="addProductRow()" icon="fas fa-plus" size="sm">
                                    Ajouter
                                </x-button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">
                                                Essence</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">
                                                Produits</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">
                                                Quantité</th>
                                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-table-body">
                                        <!-- Product rows will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-form-section>

                {{-- 7. Particulière --}}
                <x-form-section number="7" title="Particulière" icon="fas fa-file-alt" color="green">
                    <div class="form-group">
                        <label for="particuliere"
                            class="block text-sm font-semibold text-gray-700 mb-2">Particulière</label>
                        <textarea id="particuliere" name="particuliere" rows="4"
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Notes particulières">{{ old('particuliere') }}</textarea>
                        @error('particuliere')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </x-form-section>

                {{-- 8. Plan de situation – Import Excel --}}
                <x-form-section number="8" title="Plan de situation – Import Excel" icon="fas fa-map"
                    color="blue">
                    <p class="text-sm text-gray-600 mb-4">
                        Optionnel : importez un fichier Excel avec les colonnes <strong>mat</strong>, <strong>x</strong>,
                        <strong>y</strong> (première ligne = en-têtes). Les lignes seront enregistrées dans la table des
                        localisations (plan de situation) après création de l'article.
                    </p>
                    <div class="form-group">
                        <label for="locations_file" class="block text-sm font-semibold text-gray-700 mb-2">Fichier Excel
                            (.xlsx, .xls)</label>
                        <input type="file" id="locations_file" name="locations_file" accept=".xlsx,.xls"
                            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('locations_file')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </x-form-section>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 mt-8">
                    <x-button href="{{ route('cessions.index') }}" variant="secondary" icon="fas fa-times">
                        Annuler
                    </x-button>
                    <x-button type="submit" icon="fas fa-save">
                        Créer l'Article
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let productRowCount = 0;
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
                   min="0" 
                   step="0.01" 
                   placeholder="Quantité"
                   required>
        </td>
        <td class="px-4 py-3 border text-center">
            <button type="button" 
                    onclick="removeProductRow(this)" 
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

            Array.from(select.options).forEach(function(opt) {
                const text = (opt.text || '').toLowerCase();
                const match = text.indexOf(filter) !== -1;
                opt.style.display = match ? '' : 'none';
            });
        }

        // Filter communes based on selected province
        function updateCommunes() {
            const provinceSelect = document.getElementById('province_id');
            const communeSelect = document.getElementById('commune_ids');
            const selectedProvinceId = provinceSelect ? provinceSelect.value : '';

            if (!communeSelect) return;

            // Filter communes by province
            Array.from(communeSelect.options).forEach(option => {
                if (option.value === '') {
                    option.style.display = '';
                    return;
                }
                const provinceId = option.getAttribute('data-province-id');

                // Convert both to strings for comparison to handle type mismatches
                const selectedId = String(selectedProvinceId);
                const optionProvinceId = String(provinceId || '');

                if (selectedProvinceId && optionProvinceId !== selectedId) {
                    option.style.display = 'none';
                    // Deselect options that don't match the province
                    if (option.selected) {
                        option.selected = false;
                    }
                } else {
                    option.style.display = '';
                }
            });

            // If no province is selected, show all communes
            if (!selectedProvinceId) {
                Array.from(communeSelect.options).forEach(option => {
                    option.style.display = '';
                });
            }
        }

        function updateDpanefs() {
            const dranefSelect = document.getElementById('dranef_code');
            const dpanefSelect = document.getElementById('dpanef_code');
            const selectedDranefCode = dranefSelect.value;

            // Filter dpanefs by dranef
            Array.from(dpanefSelect.options).forEach(option => {
                if (option.value === '') {
                    option.style.display = '';
                    return;
                }
                const dranefCode = option.getAttribute('data-dranef-code');
                if (selectedDranefCode && dranefCode !== selectedDranefCode) {
                    option.style.display = 'none';
                    if (option.selected) {
                        option.selected = false;
                    }
                } else {
                    option.style.display = '';
                }
            });

            // Reset dependent selects
            if (!selectedDranefCode) {
                dpanefSelect.value = '';
            }
            updateZdtfs();
        }

        function updateZdtfs() {
            const dpanefSelect = document.getElementById('dpanef_code');
            const zdtfSelect = document.getElementById('zdtf_code');
            const selectedDpanefCode = dpanefSelect.value;

            // Filter zdtfs by dpanef
            Array.from(zdtfSelect.options).forEach(option => {
                if (option.value === '') {
                    option.style.display = '';
                    return;
                }
                const dpanefCode = option.getAttribute('data-dpanef-code');
                if (selectedDpanefCode && dpanefCode !== selectedDpanefCode) {
                    option.style.display = 'none';
                    if (option.selected) {
                        option.selected = false;
                    }
                } else {
                    option.style.display = '';
                }
            });

            // Reset dependent selects
            if (!selectedDpanefCode) {
                zdtfSelect.value = '';
            }
            updateDfps();
        }

        function updateDfps() {
            const zdtfSelect = document.getElementById('zdtf_code');
            const dpanefSelect = document.getElementById('dpanef_code');
            const dfpSelect = document.getElementById('dfp_code');
            const selectedZdtfCode = zdtfSelect.value;
            const selectedDpanefCode = dpanefSelect.value;

            // Filter dfps by zdtf and/or dpanef
            Array.from(dfpSelect.options).forEach(option => {
                if (option.value === '') {
                    option.style.display = '';
                    return;
                }
                const zdtfCode = option.getAttribute('data-zdtf-code');
                const dpanefCode = option.getAttribute('data-dpanef-code');

                let shouldShow = true;
                if (selectedZdtfCode && zdtfCode && zdtfCode !== selectedZdtfCode) {
                    shouldShow = false;
                }
                if (selectedDpanefCode && dpanefCode && dpanefCode !== selectedDpanefCode) {
                    shouldShow = false;
                }

                if (!shouldShow) {
                    option.style.display = 'none';
                    if (option.selected) {
                        option.selected = false;
                    }
                } else {
                    option.style.display = '';
                }
            });

            // Reset dfp if filters changed
            if (!selectedZdtfCode && !selectedDpanefCode) {
                dfpSelect.value = '';
            }
        }

        function toggleDepotSelect() {
            const checkbox = document.getElementById('is_on_depot');
            const container = document.getElementById('depot-select-container');
            if (checkbox && container) {
                container.style.display = checkbox.checked ? 'block' : 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize province/commune cascading
            const provinceSelect = document.getElementById('province_id');
            if (provinceSelect && provinceSelect.value) {
                updateCommunes();
            }
            updateDpanefs();
            updateZdtfs();
            updateDfps();
            // Initialize depot select visibility
            toggleDepotSelect();
        });
    </script>
@endpush
