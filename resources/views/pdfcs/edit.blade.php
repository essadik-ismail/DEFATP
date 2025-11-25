@extends('layouts.app')

@section('title', 'Modifier un PDFC')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                        <i class="fas fa-edit text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #10b981, #059669); -webkit-background-clip: text; background-clip: text;">Modifier un PDFC</h1>
                        <p class="text-gray-600 text-lg mt-2">Modifier les informations du PDFC #{{ $pdfc->id }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pdfcs.show', $pdfc) }}" class="px-6 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-eye"></i>
                        Voir
                    </a>
                    <a href="{{ route('pdfcs.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                <div>
                    <h3 class="text-red-800 font-semibold">Erreurs de validation</h3>
                    <ul class="list-disc list-inside text-red-600 mt-2">
                        @php
                            $uniqueErrors = array_unique($errors->all());
                        @endphp
                        @foreach ($uniqueErrors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #10b981, #059669); -webkit-background-clip: text; background-clip: text;">Formulaire de modification</h2>
                <p class="text-gray-600">Modifiez les informations du PDFC</p>
            </div>
        </div>

        <form action="{{ route('pdfcs.update', $pdfc) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Section 1: Informations de Base -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #10b981;">Informations de Base</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="date_de_début" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de Début <span class="text-red-500">*</span>
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400 @error('date_de_début') border-red-500 @enderror" 
                               id="date_de_début" 
                               name="date_de_début" 
                               value="{{ old('date_de_début', $pdfc->date_de_début ? $pdfc->date_de_début->format('Y-m-d') : '') }}"
                               required>
                        @error('date_de_début')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_de_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de Fin <span class="text-red-500">*</span>
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400 @error('date_de_fin') border-red-500 @enderror" 
                               id="date_de_fin" 
                               name="date_de_fin" 
                               value="{{ old('date_de_fin', $pdfc->date_de_fin ? $pdfc->date_de_fin->format('Y-m-d') : '') }}"
                               required>
                        @error('date_de_fin')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="etat_display" class="block text-sm font-semibold text-gray-700 mb-2">
                            État Actuel (automatique)
                        </label>
                        <div class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50">
                            @php
                                $stateColors = [
                                    'Non élaboré' => 'bg-gray-100 text-gray-800',
                                    'élaboré' => 'bg-blue-100 text-blue-800',
                                    'validé' => 'bg-yellow-100 text-yellow-800',
                                    'validé C.C' => 'bg-green-100 text-green-800',
                                ];
                                $colorClass = $stateColors[$pdfc->etat] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                                {{ $pdfc->etat }}
                            </span>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle"></i> L'état change automatiquement selon le cycle de vie du PDFC.
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Utilisateur
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400 @error('user_id') border-red-500 @enderror" 
                                id="user_id" 
                                name="user_id">
                            <option value="">Sélectionner un utilisateur</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $pdfc->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Localisation et Situation Administrative -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #3b82f6;">Localisation et Situation Administrative</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Localisation</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionner la localisation du PDFC"></i>
                        </label>
                        <select 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 @error('localisation_id') border-red-500 @enderror" 
                            id="localisation_id" 
                            name="localisation_id">
                            <option value="">Sélectionner une localisation</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id', $pdfc->localisation_id) == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->CODE }} - {{ $localisation->DRANEF }}
                                </option>
                            @endforeach
                        </select>
                        @error('localisation_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="situation_administrative_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Situation Administrative</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionner la situation administrative du PDFC"></i>
                        </label>
                        <select 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 @error('situation_administrative_id') border-red-500 @enderror" 
                            id="situation_administrative_id" 
                            name="situation_administrative_id">
                            <option value="">Sélectionner une situation administrative</option>
                            @foreach($situationAdministratives as $situation)
                                <option value="{{ $situation->id }}" {{ old('situation_administrative_id', $pdfc->situation_administrative_id) == $situation->id ? 'selected' : '' }}>
                                    {{ $situation->commune }}@if($situation->province) - {{ $situation->province }}@endif
                                </option>
                            @endforeach
                        </select>
                        @error('situation_administrative_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('pdfcs.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Validate date range
    document.getElementById('date_de_fin').addEventListener('change', function() {
        const startDate = new Date(document.getElementById('date_de_début').value);
        const endDate = new Date(this.value);
        
        if (endDate < startDate) {
            alert('La date de fin doit être supérieure ou égale à la date de début.');
            this.value = document.getElementById('date_de_début').value;
        }
    });
</script>
@endpush

