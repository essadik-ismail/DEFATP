@extends('layouts.app')

@section('title', 'Modifier Avenant - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Contrats</a></li>
<li class="breadcrumb-item active">Modifier avenant</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Modifier l'Avenant"
        icon="fas fa-file-contract"
        :backRoute="route('contracts.index')"
        backText="Retour"
    />

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
        <div class="font-semibold mb-2">Erreurs de validation:</div>
        <ul class="list-disc pl-5">
            @php
                $uniqueErrors = array_unique($errors->all());
            @endphp
            @foreach ($uniqueErrors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Edit Form -->
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <form action="{{ route('contracts.avenants.update', $avenant) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Section 1: Informations de Base -->
            <div style="background:#F3F6F4; border-radius:0.75rem; padding:1.25rem; border:1px solid #DDE5E1; margin-bottom:1rem;">
                <div class="flex items-center gap-3 mb-6">
                    <div style="width:36px; height:36px; border-radius:0.5rem; display:flex; align-items:center; justify-content:center; background:#1A3D2B;">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 style="font-size:0.9375rem; font-weight:600; color:#1A2D22; margin:0 0 1rem;">Informations de Base</h3>
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
            <div style="background:#F0FFF4; border-radius:0.75rem; padding:1.25rem; border:1px solid #C6F6D5; margin-bottom:1rem;">
                <div class="flex items-center gap-3 mb-6">
                    <div style="width:36px; height:36px; border-radius:0.5rem; display:flex; align-items:center; justify-content:center; background:#1A3D2B;">
                        <i class="fas fa-tools text-white"></i>
                    </div>
                    <h3 style="font-size:0.9375rem; font-weight:600; color:#1A2D22; margin:0 0 1rem;">Prestations</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Gardiennage Section -->
                    <div class="form-group md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <span>Gardiennage</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Informations sur le gardiennage"></i>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="gardiennage_nbjour" class="block text-xs font-medium text-gray-600 mb-1">Nombre de Jours</label>
                                <input type="number" 
                                       step="1"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="gardiennage_nbjour" 
                                       name="gardiennage_nbjour" 
                                       value="{{ old('gardiennage_nbjour', $avenant->gardiennage_nbjour) }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="gardiennage_superficie" class="block text-xs font-medium text-gray-600 mb-1">Superficie</label>
                                <input type="number" 
                                       step="1"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="gardiennage_superficie" 
                                       name="gardiennage_superficie" 
                                       value="{{ old('gardiennage_superficie', $avenant->gardiennage_superficie) }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="gardiennage_parcelle" class="block text-xs font-medium text-gray-600 mb-1">Parcelle</label>
                                <input type="text" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="gardiennage_parcelle" 
                                       name="gardiennage_parcelle" 
                                       value="{{ old('gardiennage_parcelle', $avenant->gardiennage_parcelle) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Prévention Incendies Section -->
                    <div class="form-group md:col-span-3">
                        <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <span>Prévention contre les Incendies</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Informations sur la prévention contre les incendies"></i>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="prevention_incendies_nbjour" class="block text-xs font-medium text-gray-600 mb-1">Nombre de Jours</label>
                                <input type="number" 
                                       step="1"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="prevention_incendies_nbjour" 
                                       name="prevention_incendies_nbjour" 
                                       value="{{ old('prevention_incendies_nbjour', $avenant->prevention_incendies_nbjour) }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="prevention_incendies_superficie" class="block text-xs font-medium text-gray-600 mb-1">Superficie</label>
                                <input type="number" 
                                       step="1"
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="prevention_incendies_superficie" 
                                       name="prevention_incendies_superficie" 
                                       value="{{ old('prevention_incendies_superficie', $avenant->prevention_incendies_superficie) }}"
                                       min="0">
                            </div>
                            <div>
                                <label for="prevention_incendies_parcelle" class="block text-xs font-medium text-gray-600 mb-1">Parcelle</label>
                                <input type="text" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="prevention_incendies_parcelle" 
                                       name="prevention_incendies_parcelle" 
                                       value="{{ old('prevention_incendies_parcelle', $avenant->prevention_incendies_parcelle) }}">
                            </div>
                        </div>
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
                                    <select name="products[{{ $index }}][name]" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                                            required>
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->name }}" {{ old("products.{$index}.name", $product->name) == $prod->name ? 'selected' : '' }}>
                                                {{ $prod->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                    <select name="prestations[{{ $index }}][name]" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                                            required>
                                        <option value="">Sélectionner une prestation</option>
                                        @foreach($prestations as $p)
                                            <option value="{{ $p->name }}" {{ old("prestations.{$index}.name", $prestation->name) == $p->name ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-32">
                                    <input type="number" 
                                           name="prestations[{{ $index }}][quantity]" 
                                           placeholder="Quantité" 
                                           min="0.01" 
                                           step="0.01"
                                           value="{{ old("prestations.{$index}.quantity", $prestation->pivot->quantity ?? $prestation->quantity) }}"
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
                    <div style="width:36px; height:36px; border-radius:0.5rem; display:flex; align-items:center; justify-content:center; background:#1A3D2B;">
                        <i class="fas fa-coins text-white"></i>
                    </div>
                    <h3 style="font-size:0.9375rem; font-weight:600; color:#1A2D22; margin:0 0 1rem;">Valeurs Financières</h3>
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
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focuxs:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
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
    
    const products = @json($products ?? []);
    
    const productRow = document.createElement('div');
    productRow.className = 'product-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
    
    let productOptions = '<option value="">Sélectionner un produit</option>';
    products.forEach(product => {
        productOptions += `<option value="${product.name}">${product.name}</option>`;
    });
    
    productRow.innerHTML = `
        <div class="flex-1">
            <select name="products[${productCount}][name]" 
                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                   required>
                ${productOptions}
            </select>
        </div>
        <div class="w-32">
            <input type="number" 
                   name="products[${productCount}][quantity]" 
                   placeholder="Quantité" 
                   min="0.01" 
                   step="0.01"
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
    
    const prestations = @json($prestations ?? []);
    
    const prestationRow = document.createElement('div');
    prestationRow.className = 'prestation-row flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border border-gray-200';
    
    let prestationOptions = '<option value="">Sélectionner une prestation</option>';
    prestations.forEach(prestation => {
        prestationOptions += `<option value="${prestation.name}">${prestation.name}</option>`;
    });

    prestationRow.innerHTML = `
        <div class="flex-1">
            <select name="prestations[${prestationCount}][name]" 
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                    required>
                ${prestationOptions}
            </select>
        </div>
        <div class="w-32">
            <input type="number" 
                   name="prestations[${prestationCount}][quantity]" 
                   placeholder="Quantité" 
                   min="0.01" 
                   step="0.01"
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
