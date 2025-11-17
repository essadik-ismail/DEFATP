@extends('layouts.app')

@section('title', 'Nouvel Avenant - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-file-contract text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    @if(isset($avenant)) Modifier Avenant @else Nouvel Avenant @endif
                </h1>
                <p class="text-gray-600 text-lg mt-2">@if(isset($avenant)) Modifiez les informations de l'avenant @else Créez un nouvel avenant de contrat @endif</p>
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
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">@if(isset($avenant)) Formulaire de modification @else Formulaire de création @endif</h2>
                <p class="text-gray-600">@if(isset($avenant)) Modifiez les informations de l'avenant @else Remplissez les informations pour créer un nouvel avenant @endif</p>
            </div>
        </div>

        <form action="{{ isset($avenant) ? route('contracts.avenants.update', $avenant) : route('contracts.avenants.store') }}" method="POST" class="space-y-8">
            @csrf
            @if(isset($avenant))
                @method('PUT')
            @endif
            
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
                        <label for="contact_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Contrat <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="contact_id" 
                            id="contact_id" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                            required
                        >
                            <option value="">Sélectionner un contrat</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}" {{ old('contact_id', isset($avenant) ? $avenant->contact_id : (isset($selectedContract) && $selectedContract && $selectedContract->id == $contract->id ? $contract->id : null)) == $contract->id ? 'selected' : '' }}>
                                    Contrat #{{ $contract->contarct }} ({{ $contract->annee }}) - {{ $contract->localisation->DRANEF ?? 'N/A' }} - {{ $contract->situationAdministrative->commune ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('contact_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="annee" class="block text-sm font-semibold text-gray-700 mb-2">
                            Année <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="annee" 
                               name="annee" 
                               value="{{ old('annee', isset($avenant) ? $avenant->annee : date('Y')) }}"
                               required>
                        @error('annee')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date <span class="text-red-500">*</span>
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="date" 
                               name="date" 
                               value="{{ old('date', isset($avenant) && $avenant->date ? \Carbon\Carbon::parse($avenant->date)->format('Y-m-d') : '') }}"
                               placeholder="jj/mm/aaaa"
                               required>
                        @error('date')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="avenant" class="block text-sm font-semibold text-gray-700 mb-2">
                            Avenant <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="avenant" 
                               name="avenant" 
                               value="{{ old('avenant', isset($avenant) ? $avenant->avenant : '') }}"
                               required>
                        @error('avenant')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="coperative_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Coopérative
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="coperative_id" 
                                name="coperative_id">
                            <option value="">Sélectionner une coopérative</option>
                            @foreach($coperatives as $coperative)
                                <option value="{{ $coperative->id }}" {{ old('coperative_id', isset($avenant) ? $avenant->coperative_id : null) == $coperative->id ? 'selected' : '' }}>
                                    {{ $coperative->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('coperative_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Informations Financières -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-coins text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #059669;">Informations Financières</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2">Superficie</label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="superficie" 
                               name="superficie" 
                               value="{{ old('superficie', isset($avenant) ? $avenant->superficie : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="gardiennage" class="block text-sm font-semibold text-gray-700 mb-2">Gardiennage</label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="gardiennage" 
                               name="gardiennage" 
                               value="{{ old('gardiennage', isset($avenant) ? $avenant->gardiennage : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="prevention_incendies" class="block text-sm font-semibold text-gray-700 mb-2">Prévention Incendies</label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="prevention_incendies" 
                               name="prevention_incendies" 
                               value="{{ old('prevention_incendies', isset($avenant) ? $avenant->prevention_incendies : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="elagage" class="block text-sm font-semibold text-gray-700 mb-2">Elagage</label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="elagage" 
                               name="elagage" 
                               value="{{ old('elagage', isset($avenant) ? $avenant->elagage : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="eclaircie" class="block text-sm font-semibold text-gray-700 mb-2">Eclaircie</label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="eclaircie" 
                               name="eclaircie" 
                               value="{{ old('eclaircie', isset($avenant) ? $avenant->eclaircie : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="rajeunissement_romarin" class="block text-sm font-semibold text-gray-700 mb-2">Rajeunissement Romarin</label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="rajeunissement_romarin" 
                               name="rajeunissement_romarin" 
                               value="{{ old('rajeunissement_romarin', isset($avenant) ? $avenant->rajeunissement_romarin : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="valeurs_des_produits" class="block text-sm font-semibold text-gray-700 mb-2">Valeurs des Produits <span class="text-red-500">*</span></label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="valeurs_des_produits" 
                               name="valeurs_des_produits" 
                               value="{{ old('valeurs_des_produits', isset($avenant) ? $avenant->valeurs_des_produits : '') }}"
                               required>
                        @error('valeurs_des_produits')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="valeur_des_prestations" class="block text-sm font-semibold text-gray-700 mb-2">Valeur des Prestations <span class="text-red-500">*</span></label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="valeur_des_prestations" 
                               name="valeur_des_prestations" 
                               value="{{ old('valeur_des_prestations', isset($avenant) ? $avenant->valeur_des_prestations : '') }}"
                               required>
                        @error('valeur_des_prestations')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="redevances" class="block text-sm font-semibold text-gray-700 mb-2">Redevances <span class="text-red-500">*</span></label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="redevances" 
                               name="redevances" 
                               value="{{ old('redevances', isset($avenant) ? $avenant->redevances : '') }}"
                               required>
                        @error('redevances')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="taxes" class="block text-sm font-semibold text-gray-700 mb-2">Taxes <span class="text-red-500">*</span></label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="taxes" 
                               name="taxes" 
                               value="{{ old('taxes', isset($avenant) ? $avenant->taxes : '') }}"
                               required>
                        @error('taxes')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="total_avenant" class="block text-sm font-semibold text-gray-700 mb-2">Total Avenant <span class="text-red-500">*</span></label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="total_avenant" 
                               name="total_avenant" 
                               value="{{ old('total_avenant', isset($avenant) ? $avenant->total_avenant : '') }}"
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
                               value="{{ old('bo_m3', isset($avenant) ? $avenant->bo_m3 : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="bi_m3" class="block text-sm font-semibold text-gray-700 mb-2">BI (m³)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bi_m3" 
                               name="bi_m3" 
                               value="{{ old('bi_m3', isset($avenant) ? $avenant->bi_m3 : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="bf_st" class="block text-sm font-semibold text-gray-700 mb-2">BF (st)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bf_st" 
                               name="bf_st" 
                               value="{{ old('bf_st', isset($avenant) ? $avenant->bf_st : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="tanin_t" class="block text-sm font-semibold text-gray-700 mb-2">Tanin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="tanin_t" 
                               name="tanin_t" 
                               value="{{ old('tanin_t', isset($avenant) ? $avenant->tanin_t : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="laurier_sauce" class="block text-sm font-semibold text-gray-700 mb-2">Laurier Sauce (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="laurier_sauce" 
                               name="laurier_sauce" 
                               value="{{ old('laurier_sauce', isset($avenant) ? $avenant->laurier_sauce : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="myrte" class="block text-sm font-semibold text-gray-700 mb-2">Myrte (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="myrte" 
                               name="myrte" 
                               value="{{ old('myrte', isset($avenant) ? $avenant->myrte : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="callune" class="block text-sm font-semibold text-gray-700 mb-2">Callune (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="callune" 
                               name="callune" 
                               value="{{ old('callune', isset($avenant) ? $avenant->callune : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="thym" class="block text-sm font-semibold text-gray-700 mb-2">Thym (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="thym" 
                               name="thym" 
                               value="{{ old('thym', isset($avenant) ? $avenant->thym : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="bruyetre" class="block text-sm font-semibold text-gray-700 mb-2">Bruyère (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bruyetre" 
                               name="bruyetre" 
                               value="{{ old('bruyetre', isset($avenant) ? $avenant->bruyetre : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="lichen" class="block text-sm font-semibold text-gray-700 mb-2">Lichen (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lichen" 
                               name="lichen" 
                               value="{{ old('lichen', isset($avenant) ? $avenant->lichen : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="tanin" class="block text-sm font-semibold text-gray-700 mb-2">Tanin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="tanin" 
                               name="tanin" 
                               value="{{ old('tanin', isset($avenant) ? $avenant->tanin : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="romarin" class="block text-sm font-semibold text-gray-700 mb-2">Romarin (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="romarin" 
                               name="romarin" 
                               value="{{ old('romarin', isset($avenant) ? $avenant->romarin : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="liege_male" class="block text-sm font-semibold text-gray-700 mb-2">Liège Mâle (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liege_male" 
                               name="liege_male" 
                               value="{{ old('liege_male', isset($avenant) ? $avenant->liege_male : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="liege_de_reproduction" class="block text-sm font-semibold text-gray-700 mb-2">Liège de Reproduction (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liege_de_reproduction" 
                               name="liege_de_reproduction" 
                               value="{{ old('liege_de_reproduction', isset($avenant) ? $avenant->liege_de_reproduction : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="sauge" class="block text-sm font-semibold text-gray-700 mb-2">Sauge (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="sauge" 
                               name="sauge" 
                               value="{{ old('sauge', isset($avenant) ? $avenant->sauge : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="lavande" class="block text-sm font-semibold text-gray-700 mb-2">Lavande (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lavande" 
                               name="lavande" 
                               value="{{ old('lavande', isset($avenant) ? $avenant->lavande : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="armoise" class="block text-sm font-semibold text-gray-700 mb-2">Armoise (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="armoise" 
                               name="armoise" 
                               value="{{ old('armoise', isset($avenant) ? $avenant->armoise : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="origan" class="block text-sm font-semibold text-gray-700 mb-2">Origan (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="origan" 
                               name="origan" 
                               value="{{ old('origan', isset($avenant) ? $avenant->origan : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="alfa" class="block text-sm font-semibold text-gray-700 mb-2">Alfa (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="alfa" 
                               name="alfa" 
                               value="{{ old('alfa', isset($avenant) ? $avenant->alfa : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="lentisque" class="block text-sm font-semibold text-gray-700 mb-2">Lentisque (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lentisque" 
                               name="lentisque" 
                               value="{{ old('lentisque', isset($avenant) ? $avenant->lentisque : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="ciste" class="block text-sm font-semibold text-gray-700 mb-2">Ciste (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="ciste" 
                               name="ciste" 
                               value="{{ old('ciste', isset($avenant) ? $avenant->ciste : '') }}">
                    </div>

                    <div class="form-group">
                        <label for="fleur_acacia_t" class="block text-sm font-semibold text-gray-700 mb-2">Fleur Acacia (t)</label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="fleur_acacia_t" 
                               name="fleur_acacia_t" 
                               value="{{ old('fleur_acacia_t', isset($avenant) ? $avenant->fleur_acacia_t : '') }}">
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="bg-white rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                            <i class="fas fa-box text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-bold" style="color: #059669;">Produits</h4>
                    </div>
                    <button type="button" 
                            onclick="addProduct()" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg transition-all duration-300 text-sm"
                            style="background: linear-gradient(to right, #059669, #047857);"
                            onmouseover="this.style.background='linear-gradient(to right, #047857, #065f46)'"
                            onmouseout="this.style.background='linear-gradient(to right, #059669, #047857)'">
                        <i class="fas fa-plus"></i>
                        Ajouter Produit
                    </button>
                </div>
                
                <div id="products-container">
                    @if(isset($avenant) && $avenant->products && $avenant->products->count() > 0)
                        @foreach($avenant->products as $index => $product)
                            <div class="product-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-1">
                                    <input type="text" 
                                           name="products[{{ $index }}][name]" 
                                           placeholder="Nom du produit" 
                                           value="{{ old("products.{$index}.name", $product->name) }}"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                                           required>
                                </div>
                                <div class="w-32">
                                    <input type="number" 
                                           name="products[{{ $index }}][quantity]" 
                                           placeholder="Quantité" 
                                           min="1" 
                                           value="{{ old("products.{$index}.quantity", $product->quantity) }}"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                                           required>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            onclick="removeProduct(this)" 
                                            class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Prestations Section -->
            <div class="bg-white rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                            <i class="fas fa-tasks text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-bold" style="color: #3b82f6;">Prestations</h4>
                    </div>
                    <button type="button" 
                            onclick="addPrestation()" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg transition-all duration-300 text-sm"
                            style="background: linear-gradient(to right, #3b82f6, #2563eb);"
                            onmouseover="this.style.background='linear-gradient(to right, #2563eb, #1d4ed8)'"
                            onmouseout="this.style.background='linear-gradient(to right, #3b82f6, #2563eb)'">
                        <i class="fas fa-plus"></i>
                        Ajouter Prestation
                    </button>
                </div>
                
                <div id="prestations-container">
                    @if(isset($avenant) && $avenant->prestations && $avenant->prestations->count() > 0)
                        @foreach($avenant->prestations as $index => $prestation)
                            <div class="prestation-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex-1">
                                    <input type="text" 
                                           name="prestations[{{ $index }}][name]" 
                                           placeholder="Nom de la prestation" 
                                           value="{{ old("prestations.{$index}.name", $prestation->name) }}"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                                           required>
                                </div>
                                <div class="w-32">
                                    <input type="number" 
                                           name="prestations[{{ $index }}][quantity]" 
                                           placeholder="Quantité" 
                                           min="1" 
                                           value="{{ old("prestations.{$index}.quantity", $prestation->quantity) }}"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                                           required>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            onclick="removePrestation(this)" 
                                            class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('contracts.index', ['tab' => 'avenants']) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-times"></i>
                    <span>Annuler</span>
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span>Enregistrer</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let productCount = {{ isset($avenant) && $avenant->products ? $avenant->products->count() : 0 }};
let prestationCount = {{ isset($avenant) && $avenant->prestations ? $avenant->prestations->count() : 0 }};

// Add new product row
function addProduct() {
    productCount++;
    const container = document.getElementById('products-container');
    
    const productRow = document.createElement('div');
    productRow.className = 'product-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
    productRow.innerHTML = `
        <div class="flex-1">
            <input type="text" 
                   name="products[${productCount}][name]" 
                   placeholder="Nom du produit" 
                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                   required>
        </div>
        <div class="w-32">
            <input type="number" 
                   name="products[${productCount}][quantity]" 
                   placeholder="Quantité" 
                   min="1" 
                   value="1"
                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                   required>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" 
                    onclick="removeProduct(this)" 
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    
    container.appendChild(productRow);
}

// Remove product row
function removeProduct(button) {
    const productRow = button.closest('.product-row');
    productRow.remove();
}

// Add new prestation row
function addPrestation() {
    prestationCount++;
    const container = document.getElementById('prestations-container');
    
    const prestationRow = document.createElement('div');
    prestationRow.className = 'prestation-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
    prestationRow.innerHTML = `
        <div class="flex-1">
            <input type="text" 
                   name="prestations[${prestationCount}][name]" 
                   placeholder="Nom de la prestation" 
                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                   required>
        </div>
        <div class="w-32">
            <input type="number" 
                   name="prestations[${prestationCount}][quantity]" 
                   placeholder="Quantité" 
                   min="1" 
                   value="1"
                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                   required>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" 
                    onclick="removePrestation(this)" 
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    
    container.appendChild(prestationRow);
}

// Remove prestation row
function removePrestation(button) {
    const prestationRow = button.closest('.prestation-row');
    prestationRow.remove();
}

document.addEventListener('DOMContentLoaded', function() {
    const contactSelect = document.getElementById('contact_id');
    const anneeInput = document.getElementById('annee');
    
    if (contactSelect && anneeInput) {
        contactSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                // Extract year from the contract option text (format: "Contrat #X (YYYY)")
                const optionText = selectedOption.text;
                const yearMatch = optionText.match(/\((\d{4})\)/);
                if (yearMatch) {
                    anneeInput.value = yearMatch[1];
                }
            }
        });
    }
});
</script>
@endsection
