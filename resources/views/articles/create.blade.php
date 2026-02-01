@extends('layouts.app')

@section('title', 'Nouvel Article - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
<li class="breadcrumb-item active">Nouvel article</li>
@endsection

@section('content')
    <div>
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">
                Nouvel Article
            </h1>
            <p class="text-gray-500 text-sm">Créez un nouvel article forestier pour votre système</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <div>
                        <h3 class="font-semibold text-lg">Succès!</h3>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                    <div>
                        <h3 class="font-semibold text-lg">Erreur!</h3>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            <div class="font-semibold mb-2">Erreurs de validation:</div>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Create Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('articles.store') }}" method="POST" id="articleForm" class="space-y-8" enctype="multipart/form-data">
                @csrf

                <!-- 1. Informations générales -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                            <i class="fas fa-info-circle text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">1. Informations générales</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label for="numero" class="block text-sm font-semibold text-gray-700 mb-2">
                                Numéro d'article
                            </label>
                            <input type="number" 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                id="numero" name="numero" value="{{ old('numero') }}" 
                                placeholder="Numéro d'article">
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
                                id="lot" name="lot" value="{{ old('lot') }}" 
                                placeholder="Numéro du lot">
                            @error('lot')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="annee" class="block text-sm font-semibold text-gray-700 mb-2">
                                Année
                            </label>
                            <input type="number" 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                id="annee" name="annee" value="{{ old('annee', date('Y')) }}" 
                                min="2000" max="2100" placeholder="Année">
                            @error('annee')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. Localisation du lot -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">2. Localisation du lot</h3>
                    </div>
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
                                @foreach($provinces ?? [] as $province)
                                    <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
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
                            <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'commune_ids')">
                            <select multiple
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                    id="commune_ids" name="commune_ids[]">
                                @foreach($communes ?? [] as $commune)
                                    <option value="{{ $commune->id }}" 
                                            data-province-id="{{ $commune->province_id }}"
                                            {{ collect(old('commune_ids', []))->contains($commune->id) ? 'selected' : '' }}>
                                        {{ $commune->nom }}@if($commune->province) - {{ $commune->province->nom }}@endif
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
                                @foreach($dranefs ?? [] as $dranef)
                                    <option value="{{ $dranef->code }}" {{ old('dranef_code') == $dranef->code ? 'selected' : '' }}>
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
                                @foreach($dpanefs ?? [] as $dpanef)
                                    <option value="{{ $dpanef->code }}" 
                                            data-dranef-code="{{ $dpanef->dranef_code }}"
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
                                @foreach($zdtfs ?? [] as $zdtf)
                                    <option value="{{ $zdtf->code }}" 
                                            data-dpanef-code="{{ $zdtf->dpanef_code }}"
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
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 3. Informations forestières -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                            <i class="fas fa-tree text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">3. Informations forestières</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="foret_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                Forêt
                            </label>
                            <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'foret_ids')">
                            <select multiple
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                                    id="foret_ids" name="foret_ids[]">
                                @foreach($forets ?? [] as $foret)
                                    <option value="{{ $foret->id }}" {{ collect(old('foret_ids', []))->contains($foret->id) ? 'selected' : '' }}>
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
                            <input type="text" 
                                name="nature_juridique"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" 
                                id="nature_juridique" 
                                value="{{ old('nature_juridique') }}"
                                placeholder="Entrez la nature juridique">
                            @error('nature_juridique')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 4. Description du lot -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                            <i class="fas fa-clipboard-list text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">4. Description du lot</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="nature_de_coupe_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nature de coupe
                            </label>
                            <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'nature_de_coupe_ids')">
                            <select multiple
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" 
                                    id="nature_de_coupe_ids" name="nature_de_coupe_ids[]">
                                @foreach($natureDeCoupes ?? [] as $natureDeCoupe)
                                    <option value="{{ $natureDeCoupe->id }}" {{ collect(old('nature_de_coupe_ids', []))->contains($natureDeCoupe->id) ? 'selected' : '' }}>
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
                            <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'mode_exploitation_ids')">
                            <select multiple
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" 
                                    id="mode_exploitation_ids" name="mode_exploitation_ids[]">
                                @foreach($modeExploitations ?? [] as $modeExploitation)
                                    <option value="{{ $modeExploitation->id }}" {{ collect(old('mode_exploitation_ids', []))->contains($modeExploitation->id) ? 'selected' : '' }}>
                                        {{ $modeExploitation->mode_exploiattion }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mode_exploitation_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Sous-section : Limites du lot -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-base font-semibold text-gray-800 mb-4">Limites du lot</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="limite_nord" class="block text-sm font-semibold text-gray-700 mb-2">Limite Nord <span class="text-red-500">*</span></label>
                                <input type="text" id="limite_nord" name="limite_nord" value="{{ old('limite_nord') }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Limite Nord" required>
                                @error('limite_nord')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="limite_sud" class="block text-sm font-semibold text-gray-700 mb-2">Limite Sud <span class="text-red-500">*</span></label>
                                <input type="text" id="limite_sud" name="limite_sud" value="{{ old('limite_sud') }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Limite Sud" required>
                                @error('limite_sud')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="limite_est" class="block text-sm font-semibold text-gray-700 mb-2">Limite Est <span class="text-red-500">*</span></label>
                                <input type="text" id="limite_est" name="limite_est" value="{{ old('limite_est') }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Limite Est" required>
                                @error('limite_est')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="limite_ouest" class="block text-sm font-semibold text-gray-700 mb-2">Limite Ouest <span class="text-red-500">*</span></label>
                                <input type="text" id="limite_ouest" name="limite_ouest" value="{{ old('limite_ouest') }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Limite Ouest" required>
                                @error('limite_ouest')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Sous-section : Coordonnées du centre -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-base font-semibold text-gray-800 mb-4">Coordonnées du centre</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="coordonnee_x" class="block text-sm font-semibold text-gray-700 mb-2">Coordonnée X <span class="text-red-500">*</span></label>
                                <input type="number" id="coordonnee_x" name="coordonnee_x" value="{{ old('coordonnee_x') }}"
                                    step="any"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Coordonnée X" required>
                                @error('coordonnee_x')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="coordonnee_y" class="block text-sm font-semibold text-gray-700 mb-2">Coordonnée Y <span class="text-red-500">*</span></label>
                                <input type="number" id="coordonnee_y" name="coordonnee_y" value="{{ old('coordonnee_y') }}"
                                    step="any"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Coordonnée Y" required>
                                @error('coordonnee_y')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Consistance du lot -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                            <i class="fas fa-cubes text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">5. Consistance du lot</h3>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        <div class="form-group">
                            <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2">
                                Superficie
                            </label>
                            <input type="number" 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                id="superficie" name="superficie" value="{{ old('superficie') }}" 
                                min="0" step="0.01" placeholder="Superficie en hectares">
                            @error('superficie')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="bg-white rounded-xl p-6 border border-indigo-200">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold" style="color: #059669;">Produits présumés</h4>
                                <button type="button" 
                                        onclick="addProductRow()" 
                                        class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg text-sm"
                                        style="background: linear-gradient(to right, #059669, #047857);">
                                    <i class="fas fa-plus"></i>
                                    Ajouter
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">Essence</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">Produits</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">Quantité</th>
                                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-table-body">
                                        <!-- Product rows will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. Bois sur dépôt -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                            <i class="fas fa-warehouse text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">6. Bois sur dépôt</h3>
                    </div>
                    <div class="form-group">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" 
                                id="is_on_depot" 
                                name="is_on_depot" 
                                value="1"
                                {{ old('is_on_depot') ? 'checked' : '' }}
                                onchange="toggleDepotSelect()"
                                class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-semibold text-gray-700">Le bois est sur dépôt</span>
                        </label>
                    </div>
                    <div id="depot-select-container" class="form-group mt-4" style="display: {{ old('is_on_depot') ? 'block' : 'none' }};">
                        <label for="depot_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Dépôts <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'depot_ids')">
                        <select multiple
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                id="depot_ids" name="depot_ids[]">
                            @foreach($depots ?? [] as $depot)
                                <option value="{{ $depot->id }}" {{ collect(old('depot_ids', []))->contains($depot->id) ? 'selected' : '' }}>
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
                </div>

                <!-- 7. Particulière -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                            <i class="fas fa-file-alt text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">7. Particulière</h3>
                    </div>
                    <div class="form-group">
                        <label for="particuliere" class="block text-sm font-semibold text-gray-700 mb-2">Particulière</label>
                        <textarea id="particuliere" name="particuliere" rows="4"
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Notes particulières">{{ old('particuliere') }}</textarea>
                        @error('particuliere')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- 8. Plan de situation – Import Excel (mat, x, y → table locations) -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                            <i class="fas fa-map text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">8. Plan de situation – Import Excel</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">
                        Optionnel : importez un fichier Excel avec les colonnes <strong>mat</strong>, <strong>x</strong>, <strong>y</strong> (première ligne = en-têtes). Les lignes seront enregistrées dans la table des localisations (plan de situation) après création de l'article.
                    </p>
                    <div class="form-group">
                        <label for="locations_file" class="block text-sm font-semibold text-gray-700 mb-2">Fichier Excel (.xlsx, .xls)</label>
                        <input type="file"
                            id="locations_file"
                            name="locations_file"
                            accept=".xlsx,.xls"
                            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('locations_file')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                    <a href="{{ route('articles.index') }}" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-times"></i>
                        <span>Annuler</span>
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center gap-3 px-6 py-3 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg"
                            style="background: linear-gradient(to right, #059669, #047857);">
                        <i class="fas fa-save"></i>
                        <span class="font-semibold">Créer l'Article</span>
                    </button>
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