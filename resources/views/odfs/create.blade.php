@extends('layouts.app')

@section('title', 'Nouvelle ODF - DEFATP')

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
                    Nouvelle ODF
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez une nouvelle Organisation de la Femme</p>
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

    <form action="{{ route('odfs.store') }}" method="POST" class="space-y-8">
        @csrf

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
                    <label for="odf_entite_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>ODF Entité</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionner l'entité ODF"></i>
                    </label>
                    <select 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                        id="odf_entite_id" 
                        name="odf_entite_id">
                        <option value="">Sélectionner une entité ODF</option>
                        @foreach($odfEntites as $entite)
                            <option value="{{ $entite->id }}" {{ old('odf_entite_id') == $entite->id ? 'selected' : '' }}>
                                {{ $entite->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('odf_entite_id')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="constitution" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Constitution</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Statut de constitution de l'ODF"></i>
                    </label>
                    <select 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                        id="constitution" 
                        name="constitution">
                        <option value="">Sélectionner</option>
                        <option value="1" {{ old('constitution') == '1' ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ old('constitution') == '0' ? 'selected' : '' }}>Non</option>
                    </select>
                    @error('constitution')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Commentaire -->
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #f59e0b, #d97706);">
                    <i class="fas fa-comment text-white"></i>
                </div>
                <h3 class="text-xl font-bold" style="color: #f59e0b;">Commentaire</h3>
            </div>
            <div class="form-group">
                <label for="commentaire" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <span>Commentaire</span>
                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Commentaires additionnels sur l'ODF"></i>
                </label>
                <textarea 
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400" 
                    id="commentaire" 
                    name="commentaire" 
                    rows="4"
                    placeholder="Commentaires...">{{ old('commentaire') }}</textarea>
                @error('commentaire')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
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
                <span>Enregistrer</span>
            </button>
        </div>
    </form>
</div>
@endsection

