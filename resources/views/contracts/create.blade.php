@extends('layouts.app')

@section('title', 'Nouveau Contrat - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-handshake text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                    Nouveau Contrat
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez un nouveau contrat de partenariat</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 p-6 rounded-xl mb-6 shadow-lg">
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
        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 p-6 rounded-xl mb-6 shadow-lg">
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
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold" style="color: #059669;">Formulaire de création</h2>
                <p class="text-gray-600">Remplissez les informations pour créer un nouveau contrat</p>
            </div>
        </div>

        <form action="{{ route('contracts.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Section 1: Informations de Base -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Informations de Base</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="annee" class="block text-sm font-semibold text-gray-700 mb-2">
                            Année <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="annee" 
                               name="annee" 
                               value="{{ old('annee') ?? date('Y') }}"
                               required>
                        @error('annee')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="contarct" class="block text-sm font-semibold text-gray-700 mb-2">
                            Contrat <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="contarct" 
                               name="contarct" 
                               value="{{ old('contarct') }}"
                               required>
                        @error('contarct')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Localisation (DRANEF - DPANEF - ENTITE) <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="localisation_id" 
                                name="localisation_id" 
                                required>
                            <option value="">Sélectionner une localisation</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->DRANEF }} - {{ $localisation->DPANEF }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                        @error('localisation_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="situation_administrative_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Situation Administrative (commune - province) <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="situation_administrative_id" 
                                name="situation_administrative_id" 
                                required>
                            <option value="">Sélectionner une situation</option>
                            @foreach($situationAdministratives as $situation)
                                <option value="{{ $situation->id }}" {{ old('situation_administrative_id') == $situation->id ? 'selected' : '' }}>
                                    {{ $situation->commune }} - {{ $situation->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('situation_administrative_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="foret_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Forêt <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="foret_id" 
                                name="foret_id" 
                                required>
                            <option value="">Sélectionner une forêt</option>
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ old('foret_id') == $foret->id ? 'selected' : '' }}>
                                    {{ $foret->foret }}
                                </option>
                            @endforeach
                        </select>
                        @error('foret_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="coperative_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Coopérative <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="coperative_id" 
                                name="coperative_id" 
                                required>
                            <option value="">Sélectionner une coopérative</option>
                            @foreach($coperatives as $coperative)
                                <option value="{{ $coperative->id }}" {{ old('coperative_id') == $coperative->id ? 'selected' : '' }}>
                                    {{ $coperative->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('coperative_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="especes" class="block text-sm font-semibold text-gray-700 mb-2">
                            Espèces <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="especes" 
                                name="especes[]" 
                                multiple
                                required>
                            @foreach($especes as $espece)
                                <option value="{{ $espece->id }}" {{ in_array($espece->id, old('especes', [])) ? 'selected' : '' }}>
                                    {{ $espece->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs espèces</p>
                        @error('especes')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        @error('especes.*')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Informations Complémentaires -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Informations Complémentaires</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2">Superficie <span class="text-red-500">*</span></label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="superficie" 
                               name="superficie" 
                               value="{{ old('superficie') }}"
                               required>
                        @error('superficie')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="gardiennage" class="block text-sm font-semibold text-gray-700 mb-2">Gardiennage</label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="gardiennage" 
                               name="gardiennage" 
                               value="{{ old('gardiennage') }}">
                    </div>

                    <div class="form-group">
                        <label for="prevention_contre_les_incendies" class="block text-sm font-semibold text-gray-700 mb-2">Prévention contre les Incendies</label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="prevention_contre_les_incendies" 
                               name="prevention_contre_les_incendies" 
                               value="{{ old('prevention_contre_les_incendies') }}">
                    </div>

                    <div class="form-group">
                        <label for="elagage" class="block text-sm font-semibold text-gray-700 mb-2">Elagage</label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="elagage" 
                               name="elagage" 
                               value="{{ old('elagage') }}">
                    </div>

                    <div class="form-group">
                        <label for="eclaircie" class="block text-sm font-semibold text-gray-700 mb-2">Eclaircie</label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="eclaircie" 
                               name="eclaircie" 
                               value="{{ old('eclaircie') }}">
                    </div>

                    <div class="form-group">
                        <label for="rajeunissement_romarin" class="block text-sm font-semibold text-gray-700 mb-2">Rajeunissement Romarin</label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="rajeunissement_romarin" 
                               name="rajeunissement_romarin" 
                               value="{{ old('rajeunissement_romarin') }}">
                    </div>

                    <div class="form-group">
                        <label for="autre" class="block text-sm font-semibold text-gray-700 mb-2">Autre</label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="autre" 
                               name="autre" 
                               value="{{ old('autre') }}">
                    </div>
                </div>
            </div>

            <!-- Section 3: Valeurs Financières -->
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-coins text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Valeurs Financières</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="valeurs_des_produits" class="block text-sm font-semibold text-gray-700 mb-2">Valeurs des Produits <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="valeurs_des_produits" 
                               name="valeurs_des_produits" 
                               value="{{ old('valeurs_des_produits') }}"
                               required>
                        @error('valeurs_des_produits')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="valeur_des_prestations" class="block text-sm font-semibold text-gray-700 mb-2">Valeur des Prestations <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="valeur_des_prestations" 
                               name="valeur_des_prestations" 
                               value="{{ old('valeur_des_prestations') }}"
                               required>
                        @error('valeur_des_prestations')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="redevances" class="block text-sm font-semibold text-gray-700 mb-2">Redevances <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="redevances" 
                               name="redevances" 
                               value="{{ old('redevances') }}"
                               required>
                        @error('redevances')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="taxes" class="block text-sm font-semibold text-gray-700 mb-2">Taxes <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="taxes" 
                               name="taxes" 
                               value="{{ old('taxes') }}"
                               required>
                        @error('taxes')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="total_avenant" class="block text-sm font-semibold text-gray-700 mb-2">Total Avenant <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="total_avenant" 
                               name="total_avenant" 
                               value="{{ old('total_avenant') }}"
                               required>
                        @error('total_avenant')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Produits -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Produits</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="form-group">
                        <label for="bo_m3" class="block text-sm font-semibold text-gray-700 mb-2">BO (m³)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bo_m3" 
                               name="bo_m3" 
                               value="{{ old('bo_m3') }}">
                    </div>

                    <div class="form-group">
                        <label for="bi_m3" class="block text-sm font-semibold text-gray-700 mb-2">BI (m³)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bi_m3" 
                               name="bi_m3" 
                               value="{{ old('bi_m3') }}">
                    </div>

                    <div class="form-group">
                        <label for="bf_st" class="block text-sm font-semibold text-gray-700 mb-2">BF (st)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bf_st" 
                               name="bf_st" 
                               value="{{ old('bf_st') }}">
                    </div>

                    <div class="form-group">
                        <label for="tanin_t" class="block text-sm font-semibold text-gray-700 mb-2">Tanin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="tanin_t" 
                               name="tanin_t" 
                               value="{{ old('tanin_t') }}">
                    </div>

                    <div class="form-group">
                        <label for="fleur_acacia_t" class="block text-sm font-semibold text-gray-700 mb-2">Fleur Acacia (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="fleur_acacia_t" 
                               name="fleur_acacia_t" 
                               value="{{ old('fleur_acacia_t') }}">
                    </div>

                    <div class="form-group">
                        <label for="caroube_t" class="block text-sm font-semibold text-gray-700 mb-2">Caroube (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="caroube_t" 
                               name="caroube_t" 
                               value="{{ old('caroube_t') }}">
                    </div>

                    <div class="form-group">
                        <label for="romarin_t" class="block text-sm font-semibold text-gray-700 mb-2">Romarin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="romarin_t" 
                               name="romarin_t" 
                               value="{{ old('romarin_t') }}">
                    </div>

                    <div class="form-group">
                        <label for="ps_t" class="block text-sm font-semibold text-gray-700 mb-2">PS (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="ps_t" 
                               name="ps_t" 
                               value="{{ old('ps_t') }}">
                    </div>

                    <div class="form-group">
                        <label for="liége_st" class="block text-sm font-semibold text-gray-700 mb-2">Liège (st)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liége_st" 
                               name="liége_st" 
                               value="{{ old('liége_st') }}">
                    </div>

                    <div class="form-group">
                        <label for="laurier_sauce" class="block text-sm font-semibold text-gray-700 mb-2">Laurier Sauce (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="laurier_sauce" 
                               name="laurier_sauce" 
                               value="{{ old('laurier_sauce') }}">
                    </div>

                    <div class="form-group">
                        <label for="myrte" class="block text-sm font-semibold text-gray-700 mb-2">Myrte (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="myrte" 
                               name="myrte" 
                               value="{{ old('myrte') }}">
                    </div>

                    <div class="form-group">
                        <label for="callune" class="block text-sm font-semibold text-gray-700 mb-2">Callune (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="callune" 
                               name="callune" 
                               value="{{ old('callune') }}">
                    </div>

                    <div class="form-group">
                        <label for="thym" class="block text-sm font-semibold text-gray-700 mb-2">Thym (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="thym" 
                               name="thym" 
                               value="{{ old('thym') }}">
                    </div>

                    <div class="form-group">
                        <label for="bruyetre" class="block text-sm font-semibold text-gray-700 mb-2">Bruyère (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bruyetre" 
                               name="bruyetre" 
                               value="{{ old('bruyetre') }}">
                    </div>

                    <div class="form-group">
                        <label for="lichen" class="block text-sm font-semibold text-gray-700 mb-2">Lichen (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lichen" 
                               name="lichen" 
                               value="{{ old('lichen') }}">
                    </div>

                    <div class="form-group">
                        <label for="tanin" class="block text-sm font-semibold text-gray-700 mb-2">Tanin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="tanin" 
                               name="tanin" 
                               value="{{ old('tanin') }}">
                    </div>

                    <div class="form-group">
                        <label for="romarin" class="block text-sm font-semibold text-gray-700 mb-2">Romarin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="romarin" 
                               name="romarin" 
                               value="{{ old('romarin') }}">
                    </div>

                    <div class="form-group">
                        <label for="liege_male" class="block text-sm font-semibold text-gray-700 mb-2">Liège Mâle (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liege_male" 
                               name="liege_male" 
                               value="{{ old('liege_male') }}">
                    </div>

                    <div class="form-group">
                        <label for="liege_de_reproduction" class="block text-sm font-semibold text-gray-700 mb-2">Liège de Reproduction (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liege_de_reproduction" 
                               name="liege_de_reproduction" 
                               value="{{ old('liege_de_reproduction') }}">
                    </div>

                    <div class="form-group">
                        <label for="sauge" class="block text-sm font-semibold text-gray-700 mb-2">Sauge (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="sauge" 
                               name="sauge" 
                               value="{{ old('sauge') }}">
                    </div>

                    <div class="form-group">
                        <label for="lavande" class="block text-sm font-semibold text-gray-700 mb-2">Lavande (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lavande" 
                               name="lavande" 
                               value="{{ old('lavande') }}">
                    </div>

                    <div class="form-group">
                        <label for="armoise" class="block text-sm font-semibold text-gray-700 mb-2">Armoise (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="armoise" 
                               name="armoise" 
                               value="{{ old('armoise') }}">
                    </div>

                    <div class="form-group">
                        <label for="origan" class="block text-sm font-semibold text-gray-700 mb-2">Origan (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="origan" 
                               name="origan" 
                               value="{{ old('origan') }}">
                    </div>

                    <div class="form-group">
                        <label for="alfa" class="block text-sm font-semibold text-gray-700 mb-2">Alfa (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="alfa" 
                               name="alfa" 
                               value="{{ old('alfa') }}">
                    </div>

                    <div class="form-group">
                        <label for="lentisque" class="block text-sm font-semibold text-gray-700 mb-2">Lentisque (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lentisque" 
                               name="lentisque" 
                               value="{{ old('lentisque') }}">
                    </div>

                    <div class="form-group">
                        <label for="ciste" class="block text-sm font-semibold text-gray-700 mb-2">Ciste (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="ciste" 
                               name="ciste" 
                               value="{{ old('ciste') }}">
                    </div>
                </div>
            </div>

            <!-- Section 4: Résiliation -->
            <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl p-6 border border-red-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-ban text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Résiliation</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="resiliation" class="flex items-center gap-2">
                            <input type="checkbox" 
                                   id="resiliation" 
                                   name="resiliation" 
                                   value="1"
                                   {{ old('resiliation') ? 'checked' : '' }}
                                   class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="text-sm font-semibold text-gray-700">Résilié</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="date_resiliation" class="block text-sm font-semibold text-gray-700 mb-2">Date de Résiliation</label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="date_resiliation" 
                               name="date_resiliation" 
                               value="{{ old('date_resiliation') }}">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('contracts.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                    <span>Annuler</span>
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span>Enregistrer</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
