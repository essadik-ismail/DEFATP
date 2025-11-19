@extends('layouts.app')

@section('title', 'Modifier ODF - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                    Modifier ODF
                </h1>
                <p class="text-gray-600 text-lg mt-2">Modifiez les informations de l'Organisation développement forestier (ODF)</p>
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
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium">Veuillez corriger les erreurs suivantes :</h3>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('odfs.update', $odf) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Section 1: Informations de Base -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6 border border-purple-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <h3 class="text-xl font-bold" style="color: #8b5cf6;">Informations de Base</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="présidente" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Présidente</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nom de la présidente de l'ODF"></i>
                    </label>
                    <input type="text" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                           id="présidente" 
                           name="présidente" 
                           value="{{ old('présidente', $odf->présidente) }}"
                           placeholder="Nom de la présidente">
                    @error('présidente')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="vice_présidente" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Vice-Présidente</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nom de la vice-présidente de l'ODF"></i>
                    </label>
                    <input type="text" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                           id="vice_présidente" 
                           name="vice_présidente" 
                           value="{{ old('vice_présidente', $odf->vice_présidente) }}"
                           placeholder="Nom de la vice-présidente">
                    @error('vice_présidente')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group md:col-span-2">
                    <label for="trésorière" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Trésorière</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nom de la trésorière de l'ODF"></i>
                    </label>
                    <input type="text" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                           id="trésorière" 
                           name="trésorière" 
                           value="{{ old('trésorière', $odf->trésorière) }}"
                           placeholder="Nom de la trésorière">
                    @error('trésorière')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 3: Localisation et Situation Administrative -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                    <i class="fas fa-map-marker-alt text-white"></i>
                </div>
                <h3 class="text-xl font-bold" style="color: #10b981;">Localisation et Situation Administrative</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Localisation</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionner la localisation de l'ODF"></i>
                    </label>
                    <select 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                        id="localisation_id" 
                        name="localisation_id">
                        <option value="">Sélectionner une localisation</option>
                        @foreach($localisations as $localisation)
                            <option value="{{ $localisation->id }}" {{ old('localisation_id', $odf->localisation_id) == $localisation->id ? 'selected' : '' }}>
                                {{ $localisation->DRANEF }} - {{ $localisation->DPANEF }} - {{ $localisation->ENTITE }}
                            </option>
                        @endforeach
                    </select>
                    @error('localisation_id')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="situation_administrative_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Situation Administrative</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionner la situation administrative de l'ODF"></i>
                    </label>
                    <select 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                        id="situation_administrative_id" 
                        name="situation_administrative_id">
                        <option value="">Sélectionner une situation administrative</option>
                        @foreach($situationAdministratives as $situation)
                            <option value="{{ $situation->id }}" {{ old('situation_administrative_id', $odf->situation_administrative_id) == $situation->id ? 'selected' : '' }}>
                                {{ $situation->commune }}@if($situation->province) - {{ $situation->province }}@endif
                            </option>
                        @endforeach
                    </select>
                    @error('situation_administrative_id')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Détails -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
                <h3 class="text-xl font-bold" style="color: #3b82f6;">Détails</h3>
            </div>
            <div class="space-y-6">
                <div class="form-group">
                    <label for="reçu_du_dépôt" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Reçu du Dépôt</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Informations sur le reçu du dépôt"></i>
                    </label>
                    <textarea 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                        id="reçu_du_dépôt" 
                        name="reçu_du_dépôt" 
                        rows="4"
                        placeholder="Détails du reçu du dépôt...">{{ old('reçu_du_dépôt', $odf->reçu_du_dépôt) }}</textarea>
                    @error('reçu_du_dépôt')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="constitution" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Constitution</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Informations sur la constitution de l'ODF"></i>
                    </label>
                    <textarea 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                        id="constitution" 
                        name="constitution" 
                        rows="4"
                        placeholder="Détails sur la constitution...">{{ old('constitution', $odf->constitution) }}</textarea>
                    @error('constitution')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <a href="{{ route('odfs.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                <i class="fas fa-arrow-left"></i>
                <span>Annuler</span>
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-save"></i>
                <span>Enregistrer les modifications</span>
            </button>
        </div>
    </form>
</div>
@endsection

