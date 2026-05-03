@extends('layouts.app')

@section('title', 'Nouvel Article - DEFATP')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    @if ($cession)
        <li class="breadcrumb-item"><a href="{{ route('cessions.show', $cession) }}">Cession #{{ $cession->id }}</a></li>
    @endif
    <li class="breadcrumb-item active">Nouvel article</li>
@endsection

@section('content')
    <div class="min-w-0 max-w-full overflow-x-hidden">

        {{-- Bug 2: removed subtitle --}}
        <x-page-header title="Nouvel Article" icon="fas fa-file-alt">
            <x-slot name="actions">
                <x-button href="{{ $cession ? route('cessions.show', $cession) : route('cessions.index') }}" variant="secondary" icon="fas fa-arrow-left" size="sm">
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
                    <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200 mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-user-tag text-emerald-600"></i>
                            <span class="text-sm font-semibold text-emerald-800 uppercase tracking-wider">Votre affectation</span>
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
                                    <span class="font-medium text-gray-900">{{ $currentUser->dpanef->dpanef ?? $currentUser->dpanef->code }}</span>
                                </div>
                            @endif
                            @if ($currentUser->zdtf)
                                <div>
                                    <span class="text-gray-500 block">ZDTF</span>
                                    <span class="font-medium text-gray-900">{{ $currentUser->zdtf->code ?? ($currentUser->zdtf->zdtf ?? $currentUser->zdtf->sdtf) }}</span>
                                </div>
                            @endif
                            @if ($currentUser->dfp)
                                <div>
                                    <span class="text-gray-500 block">DFP</span>
                                    <span class="font-medium text-gray-900">{{ $currentUser->dfp->code ?? $currentUser->dfp->dfp }}</span>
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
                    @php
                        $cessionDranefCode = $cession?->dranef?->code;
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label for="numero" class="block text-sm font-semibold text-gray-700 mb-2">Numéro d'article</label>
                            <input type="number"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="numero" name="numero" value="{{ old('numero') }}" placeholder="Numéro d'article">
                            @error('numero')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="lot" class="block text-sm font-semibold text-gray-700 mb-2">Numéro du lot</label>
                            <input type="number"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                id="lot" name="lot" value="{{ old('lot') }}" placeholder="Numéro du lot">
                            @error('lot')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Bug 3: Cession context (read-only) --}}
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
                            <label for="province_id" class="block text-sm font-semibold text-gray-700 mb-2">Province</label>
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

                        {{-- Bug 4: Communes dropdown – show only commune name (no province suffix) --}}
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
                                        {{ $commune->nom }}
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

                        @php
                            $userDranefCode  = $currentUser?->dranef?->code;
                            $userDpanefCode  = $currentUser?->dpanef?->code;
                            $userZdtfCode    = $currentUser?->zdtf?->code;
                            $userDfpCode     = $currentUser?->dfp?->code;
                            // Bug 5: DRANEF auto-filled from cession, or from user affectation
                            $effectiveDranefCode = $cessionDranefCode ?? $userDranefCode;
                            $lockDranef  = ($effectiveDranefCode) && !$currentUser?->hasRole('admin');
                            $lockDpanef  = $userDpanefCode  && !$currentUser?->hasRole('admin');
                            $lockZdtf    = $userZdtfCode    && !$currentUser?->hasRole('admin');
                            $lockDfp     = $userDfpCode     && !$currentUser?->hasRole('admin');
                        @endphp

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
                                        {{ old('dranef_code', $effectiveDranefCode) == $dranef->code ? 'selected' : '' }}>
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
                                        {{ old('dpanef_code', $userDpanefCode) == $dpanef->code ? 'selected' : '' }}>
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
                                        {{ old('zdtf_code', $userZdtfCode) == $zdtf->code ? 'selected' : '' }}>
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
                                        {{ old('dfp_code', $userDfpCode) == $dfp->code ? 'selected' : '' }}>
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

                        {{-- Bug 7: Forêts filtered by DPANEF --}}
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
                                        {{ collect(old('foret_ids', []))->contains($foret->id) ? 'selected' : '' }}>
                                        {{ $foret->foret }}
                                    </option>
                                @endforeach
                            </select>
                            @error('foret_ids')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Bug 8: Canton → dropdown --}}
                        <div class="form-group">
                            <label for="canton" class="block text-sm font-semibold text-gray-700 mb-2">Canton</label>
                            <select id="canton" name="canton"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Sélectionner un canton</option>
                                @foreach ($cantons ?? [] as $c)
                                    <option value="{{ $c->canton }}"
                                        data-foret-id="{{ $c->foret_id }}"
                                        {{ old('canton') == $c->canton ? 'selected' : '' }}>
                                        {{ $c->canton }}
                                    </option>
                                @endforeach
                            </select>
                            @error('canton')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Bug 8: Parcelle → dropdown --}}
                        <div class="form-group">
                            <label for="parcelle" class="block text-sm font-semibold text-gray-700 mb-2">Parcelle</label>
                            <select id="parcelle" name="parcelle"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Sélectionner une parcelle</option>
                                @foreach ($parcelles ?? [] as $p)
                                    <option value="{{ $p->parcelle }}"
                                        data-canton-id="{{ $p->canton_id }}"
                                        {{ old('parcelle') == $p->parcelle ? 'selected' : '' }}>
                                        {{ $p->parcelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parcelle')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Bug 6: Nature juridique → dropdown --}}
                        <div class="form-group">
                            <label for="nature_juridique" class="block text-sm font-semibold text-gray-700 mb-2">Nature juridique</label>
                            <select id="nature_juridique" name="nature_juridique"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Sélectionner</option>
                                <option value="Domaniale" {{ old('nature_juridique') == 'Domaniale' ? 'selected' : '' }}>Domaniale</option>
                                <option value="Terrain collectif" {{ old('nature_juridique') == 'Terrain collectif' ? 'selected' : '' }}>Terrain collectif</option>
                                <option value="Terrain récupéré" {{ old('nature_juridique') == 'Terrain récupéré' ? 'selected' : '' }}>Terrain récupéré</option>
                            </select>
                            @error('nature_juridique')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </x-form-section>

                {{-- 4. Description du lot --}}
                <x-form-section number="4" title="Description du lot" icon="fas fa-clipboard-list" color="orange">

                    {{-- Bug 10: Limites du lot (8 directions) --}}
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
                                    <input type="text" id="{{ $fieldName }}" name="{{ $fieldName }}" value="{{ old($fieldName) }}"
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
                                <label for="coordonnee_x" class="block text-sm font-semibold text-gray-700 mb-2">Coordonnée X <span class="text-red-500">*</span></label>
                                <input type="number" id="coordonnee_x" name="coordonnee_x"
                                    value="{{ old('coordonnee_x') }}" step="any"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    placeholder="Coordonnée X" required>
                                @error('coordonnee_x')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="coordonnee_y" class="block text-sm font-semibold text-gray-700 mb-2">Coordonnée Y <span class="text-red-500">*</span></label>
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

                    <!-- Nature de coupe & Mode d'exploitation -->
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
                            <label for="mode_exploitation_ids" class="block text-sm font-semibold text-gray-700 mb-2">Mode d'exploitation</label>
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

                    {{-- Bug 11: Dépôt shown only when "Bois empilé sur dépôt" is selected --}}
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
                                <option value="{{ $depot->id }}"
                                    {{ collect(old('depot_ids', []))->contains($depot->id) ? 'selected' : '' }}>
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
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">Essence</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">Produits</th>
                                            {{-- Bug 12: renamed column --}}
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">Volume / Quantité</th>
                                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-table-body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </x-form-section>

                {{-- 6. Charges --}}
                {{-- Bug 14: new Charges section --}}
                <x-form-section number="6" title="Charges" icon="fas fa-file-invoice-dollar" color="red">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="taxe_refection_chemins" class="block text-sm font-semibold text-gray-700 mb-2">Taxe de réfection de chemin (DH)</label>
                            <input type="number" id="taxe_refection_chemins" name="taxe_refection_chemins"
                                value="{{ old('taxe_refection_chemins') }}" step="0.01" min="0"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400"
                                placeholder="Montant">
                            @error('taxe_refection_chemins')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date_echeance_taxe_refection_chemins" class="block text-sm font-semibold text-gray-700 mb-2">Échéancier – Taxe réfection chemin</label>
                            <input type="date" id="date_echeance_taxe_refection_chemins" name="date_echeance_taxe_refection_chemins"
                                value="{{ old('date_echeance_taxe_refection_chemins') }}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400">
                            @error('date_echeance_taxe_refection_chemins')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="service_rendu_anef" class="block text-sm font-semibold text-gray-700 mb-2">Service rendu par l'ANEF (DH)</label>
                            <input type="number" id="service_rendu_anef" name="service_rendu_anef"
                                value="{{ old('service_rendu_anef') }}" step="0.01" min="0"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400"
                                placeholder="Montant">
                            @error('service_rendu_anef')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="date_echeance_service_rendu_anef" class="block text-sm font-semibold text-gray-700 mb-2">Échéancier – Service rendu ANEF</label>
                            <input type="date" id="date_echeance_service_rendu_anef" name="date_echeance_service_rendu_anef"
                                value="{{ old('date_echeance_service_rendu_anef') }}"
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
                                    value="{{ old('bois_chauffage_volume') }}" step="0.01" min="0"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400"
                                    placeholder="Volume">
                                @error('bois_chauffage_volume')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bois_chauffage_destination" class="block text-sm font-semibold text-gray-700 mb-2">Destination</label>
                                <input type="text" id="bois_chauffage_destination" name="bois_chauffage_destination"
                                    value="{{ old('bois_chauffage_destination') }}"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400"
                                    placeholder="Destination">
                                @error('bois_chauffage_destination')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="date_livraison_bois_chauffage" class="block text-sm font-semibold text-gray-700 mb-2">Date de livraison</label>
                                <input type="date" id="date_livraison_bois_chauffage" name="date_livraison_bois_chauffage"
                                    value="{{ old('date_livraison_bois_chauffage') }}"
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
                            placeholder="Notes particulières">{{ old('particuliere') }}</textarea>
                        @error('particuliere')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </x-form-section>

                {{-- 8. Plan de situation – Import Excel --}}
                <x-form-section number="8" title="Plan de situation – Import Excel" icon="fas fa-map" color="blue">
                    <p class="text-sm text-gray-600 mb-4">
                        Optionnel : importez un fichier Excel avec les colonnes <strong>mat</strong>, <strong>x</strong>,
                        <strong>y</strong> (première ligne = en-têtes).
                    </p>
                    <div class="form-group">
                        <label for="locations_file" class="block text-sm font-semibold text-gray-700 mb-2">Fichier Excel (.xlsx, .xls)</label>
                        <input type="file" id="locations_file" name="locations_file" accept=".xlsx,.xls"
                            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('locations_file')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </x-form-section>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 mt-8">
                    <x-button href="{{ $cession ? route('cessions.show', $cession) : route('cessions.index') }}" variant="secondary" icon="fas fa-times">
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
            Array.from(select.options).forEach(function(opt) {
                const text = (opt.text || '').toLowerCase();
                opt.style.display = text.indexOf(filter) !== -1 ? '' : 'none';
            });
        }

        function updateCommunes() {
            const provinceSelect = document.getElementById('province_id');
            const communeSelect = document.getElementById('commune_ids');
            const selectedProvinceId = provinceSelect ? String(provinceSelect.value) : '';

            if (!communeSelect) return;

            Array.from(communeSelect.options).forEach(option => {
                if (!selectedProvinceId) {
                    option.style.display = '';
                    return;
                }
                const provinceId = String(option.getAttribute('data-province-id') || '');
                const matches = provinceId === selectedProvinceId;
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

            // Bug 7: filter forêts by selected DPANEF's id
            const foretSelect = document.getElementById('foret_ids');
            if (foretSelect && dpanefSelect) {
                // Find the dpanef id from the selected option's data
                const selectedOption = dpanefSelect.options[dpanefSelect.selectedIndex];
                // dpanef_id is not directly available in options; we filter by name match via data attr
                // Since foret options have data-dpanef-id (integer), we need the dpanef record id.
                // We expose dpanef id per option via data-dpanef-id on the dpanef select options.
                const dpanefId = selectedOption ? selectedOption.getAttribute('data-dpanef-id') : null;

                Array.from(foretSelect.options).forEach(option => {
                    if (!dpanefId || !selectedDpanefCode) {
                        option.style.display = '';
                        return;
                    }
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

        // Bug 11: show dépôts dropdown only when "Bois empilé sur dépôt" is selected
        function toggleDepotByNatureCoupe() {
            const select = document.getElementById('nature_de_coupe_ids');
            const container = document.getElementById('depot-select-container');
            if (!select || !container) return;

            const selectedTexts = Array.from(select.selectedOptions).map(o => o.text.toLowerCase());
            const hasBoisDepot = selectedTexts.some(t => t.includes('emp') && t.includes('dép'));
            container.style.display = hasBoisDepot ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_id');
            if (provinceSelect && provinceSelect.value) updateCommunes();
            updateDpanefs();
            updateZdtfsAndForets();
            updateDfps();
            toggleDepotByNatureCoupe();
        });
    </script>
@endpush
