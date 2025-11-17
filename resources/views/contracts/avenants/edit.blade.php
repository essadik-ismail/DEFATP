@extends('layouts.app')

@section('title', 'Modifier Avenant - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                <i class="fas fa-file-contract text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #6366f1, #8b5cf6); -webkit-background-clip: text; background-clip: text;">
                    Modifier Avenant
                </h1>
                <p class="text-gray-600 text-lg mt-2">Modifiez les informations de l'avenant</p>
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

    <!-- Edit Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold" style="color: #6366f1;">Formulaire de modification</h2>
                <p class="text-gray-600">Modifiez les informations de l'avenant</p>
            </div>
        </div>

        <form action="{{ route('contracts.avenants.update', $avenant) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Section 1: Informations de Base -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #6366f1;">Informations de Base</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="contact_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Contrat <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez le contrat associé à cet avenant"></i>
                        </label>
                        <select 
                            name="contact_id" 
                            id="contact_id" 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                            required
                        >
                            <option value="">Sélectionner un contrat</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}" {{ old('contact_id', $avenant->contact_id) == $contract->id ? 'selected' : '' }}>
                                    Contrat #{{ $contract->contarct }} ({{ $contract->annee }}) - {{ $contract->localisation->DRANEF ?? 'N/A' }} - {{ $contract->situationAdministrative->commune ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('contact_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="annee" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Année <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Année de l'avenant"></i>
                        </label>
                        <input type="number" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="annee" 
                               name="annee" 
                               value="{{ old('annee', $avenant->annee) }}"
                               required>
                        @error('annee')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Date <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Date de l'avenant"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="date" 
                               name="date" 
                               value="{{ old('date', $avenant->date ? \Carbon\Carbon::parse($avenant->date)->format('Y-m-d') : '') }}"
                               required>
                        @error('date')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="avenant" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Avenant <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro ou référence de l'avenant"></i>
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="avenant" 
                               name="avenant" 
                               value="{{ old('avenant', $avenant->avenant) }}"
                               required>
                        @error('avenant')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="coperative_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Coopérative</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez la coopérative"></i>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                id="coperative_id" 
                                name="coperative_id">
                            <option value="">Sélectionner une coopérative</option>
                            @foreach($coperatives as $coperative)
                                <option value="{{ $coperative->id }}" {{ old('coperative_id', $avenant->coperative_id) == $coperative->id ? 'selected' : '' }}>
                                    {{ $coperative->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('coperative_id')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="superficie" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Superficie</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Superficie en hectares"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="superficie" 
                               name="superficie" 
                               value="{{ old('superficie', $avenant->superficie) }}">
                        @error('superficie')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Prestations -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                        <i class="fas fa-tools text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #6366f1;">Prestations</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="gardiennage" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Gardiennage</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant du gardiennage"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="gardiennage" 
                               name="gardiennage" 
                               value="{{ old('gardiennage', $avenant->gardiennage) }}">
                    </div>

                    <div class="form-group">
                        <label for="prevention_incendies" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Prévention Incendies</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant de la prévention contre les incendies"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="prevention_incendies" 
                               name="prevention_incendies" 
                               value="{{ old('prevention_incendies', $avenant->prevention_incendies) }}">
                    </div>

                    <div class="form-group">
                        <label for="elagage" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Elagage</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant de l'élagage"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="elagage" 
                               name="elagage" 
                               value="{{ old('elagage', $avenant->elagage) }}">
                    </div>

                    <div class="form-group">
                        <label for="eclaircie" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Eclaircie</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant de l'éclaircie"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="eclaircie" 
                               name="eclaircie" 
                               value="{{ old('eclaircie', $avenant->eclaircie) }}">
                    </div>

                    <div class="form-group">
                        <label for="rajeunissement_romarin" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Rajeunissement Romarin</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant du rajeunissement du romarin"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="rajeunissement_romarin" 
                               name="rajeunissement_romarin" 
                               value="{{ old('rajeunissement_romarin', $avenant->rajeunissement_romarin) }}">
                    </div>
                </div>
            </div>

            <!-- Section 3: Produits -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #6366f1;">Produits</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="form-group">
                        <label for="bo_m3" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>BO (m³)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Bois d'œuvre en mètres cubes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bo_m3" 
                               name="bo_m3" 
                               value="{{ old('bo_m3', $avenant->bo_m3) }}">
                    </div>

                    <div class="form-group">
                        <label for="bi_m3" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>BI (m³)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Bois d'industrie en mètres cubes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bi_m3" 
                               name="bi_m3" 
                               value="{{ old('bi_m3', $avenant->bi_m3) }}">
                    </div>

                    <div class="form-group">
                        <label for="bf_st" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>BF (st)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Bois de feu en stères"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bf_st" 
                               name="bf_st" 
                               value="{{ old('bf_st', $avenant->bf_st) }}">
                    </div>

                    <div class="form-group">
                        <label for="tanin_t" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Tanin (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Tanin en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="tanin_t" 
                               name="tanin_t" 
                               value="{{ old('tanin_t', $avenant->tanin_t) }}">
                    </div>

                    <div class="form-group">
                        <label for="laurier_sauce" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Laurier Sauce (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Laurier sauce en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="laurier_sauce" 
                               name="laurier_sauce" 
                               value="{{ old('laurier_sauce', $avenant->laurier_sauce) }}">
                    </div>

                    <div class="form-group">
                        <label for="myrte" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Myrte (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Myrte en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="myrte" 
                               name="myrte" 
                               value="{{ old('myrte', $avenant->myrte) }}">
                    </div>

                    <div class="form-group">
                        <label for="callune" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Callune (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Callune en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="callune" 
                               name="callune" 
                               value="{{ old('callune', $avenant->callune) }}">
                    </div>

                    <div class="form-group">
                        <label for="thym" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Thym (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Thym en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="thym" 
                               name="thym" 
                               value="{{ old('thym', $avenant->thym) }}">
                    </div>

                    <div class="form-group">
                        <label for="bruyetre" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Bruyère (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Bruyère en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="bruyetre" 
                               name="bruyetre" 
                               value="{{ old('bruyetre', $avenant->bruyetre) }}">
                    </div>

                    <div class="form-group">
                        <label for="lichen" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Lichen (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Lichen en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lichen" 
                               name="lichen" 
                               value="{{ old('lichen', $avenant->lichen) }}">
                    </div>

                    <div class="form-group">
                        <label for="tanin" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Tanin (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Tanin en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="tanin" 
                               name="tanin" 
                               value="{{ old('tanin', $avenant->tanin) }}">
                    </div>

                    <div class="form-group">
                        <label for="romarin" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Romarin (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Romarin en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="romarin" 
                               name="romarin" 
                               value="{{ old('romarin', $avenant->romarin) }}">
                    </div>

                    <div class="form-group">
                        <label for="liege_male" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Liège Mâle (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Liège mâle en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liege_male" 
                               name="liege_male" 
                               value="{{ old('liege_male', $avenant->liege_male) }}">
                    </div>

                    <div class="form-group">
                        <label for="liege_de_reproduction" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Liège de Reproduction (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Liège de reproduction en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="liege_de_reproduction" 
                               name="liege_de_reproduction" 
                               value="{{ old('liege_de_reproduction', $avenant->liege_de_reproduction) }}">
                    </div>

                    <div class="form-group">
                        <label for="sauge" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Sauge (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sauge en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="sauge" 
                               name="sauge" 
                               value="{{ old('sauge', $avenant->sauge) }}">
                    </div>

                    <div class="form-group">
                        <label for="lavande" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Lavande (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Lavande en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lavande" 
                               name="lavande" 
                               value="{{ old('lavande', $avenant->lavande) }}">
                    </div>

                    <div class="form-group">
                        <label for="armoise" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Armoise (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Armoise en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="armoise" 
                               name="armoise" 
                               value="{{ old('armoise', $avenant->armoise) }}">
                    </div>

                    <div class="form-group">
                        <label for="origan" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Origan (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Origan en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="origan" 
                               name="origan" 
                               value="{{ old('origan', $avenant->origan) }}">
                    </div>

                    <div class="form-group">
                        <label for="alfa" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Alfa (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Alfa en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="alfa" 
                               name="alfa" 
                               value="{{ old('alfa', $avenant->alfa) }}">
                    </div>

                    <div class="form-group">
                        <label for="lentisque" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Lentisque (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Lentisque en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="lentisque" 
                               name="lentisque" 
                               value="{{ old('lentisque', $avenant->lentisque) }}">
                    </div>

                    <div class="form-group">
                        <label for="ciste" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Ciste (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Ciste en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="ciste" 
                               name="ciste" 
                               value="{{ old('ciste', $avenant->ciste) }}">
                    </div>

                    <div class="form-group">
                        <label for="fleur_acacia_t" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Fleur Acacia (t)</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Fleur d'acacia en tonnes"></i>
                        </label>
                        <input type="number" 
                               step="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="fleur_acacia_t" 
                               name="fleur_acacia_t" 
                               value="{{ old('fleur_acacia_t', $avenant->fleur_acacia_t) }}">
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="bg-white rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                            <i class="fas fa-box text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-bold" style="color: #6366f1;">Produits</h4>
                    </div>
                    <button type="button" 
                            onclick="addProduct()" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg transition-all duration-300 text-sm"
                            style="background: linear-gradient(to right, #6366f1, #8b5cf6);"
                            onmouseover="this.style.background='linear-gradient(to right, #4f46e5, #6366f1)'"
                            onmouseout="this.style.background='linear-gradient(to right, #6366f1, #8b5cf6)'">
                        <i class="fas fa-plus"></i>
                        Ajouter Produit
                    </button>
                </div>
                
                <div id="products-container">
                    @php
                        $productCount = 0;
                    @endphp
                    @if($avenant->products && $avenant->products->count() > 0)
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
                            @php
                                $productCount = $index + 1;
                            @endphp
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
                    @php
                        $prestationCount = 0;
                    @endphp
                    @if($avenant->prestations && $avenant->prestations->count() > 0)
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
                            @php
                                $prestationCount = $index + 1;
                            @endphp
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Section 4: Valeurs Financières -->
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #6366f1, #8b5cf6);">
                        <i class="fas fa-coins text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #6366f1;">Valeurs Financières</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="valeurs_des_produits" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Valeurs des Produits <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Valeur totale des produits"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="valeurs_des_produits" 
                               name="valeurs_des_produits" 
                               value="{{ old('valeurs_des_produits', $avenant->valeurs_des_produits) }}"
                               required>
                        @error('valeurs_des_produits')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="valeur_des_prestations" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Valeur des Prestations <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Valeur totale des prestations"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="valeur_des_prestations" 
                               name="valeur_des_prestations" 
                               value="{{ old('valeur_des_prestations', $avenant->valeur_des_prestations) }}"
                               required>
                        @error('valeur_des_prestations')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="redevances" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Redevances <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant des redevances"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="redevances" 
                               name="redevances" 
                               value="{{ old('redevances', $avenant->redevances) }}"
                               required>
                        @error('redevances')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="taxes" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Taxes <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Montant des taxes"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="taxes" 
                               name="taxes" 
                               value="{{ old('taxes', $avenant->taxes) }}"
                               required>
                        @error('taxes')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="total_avenant" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Total Avenant <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Total de l'avenant"></i>
                        </label>
                        <input type="number" 
                               step="0.01"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                               id="total_avenant" 
                               name="total_avenant" 
                               value="{{ old('total_avenant', $avenant->total_avenant) }}"
                               required>
                        @error('total_avenant')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
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

@push('scripts')
<script>
let productCount = {{ $productCount ?? 0 }};
let prestationCount = {{ $prestationCount ?? 0 }};

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
@endpush
@endsection
