@extends('layouts.app')

@section('title', 'Nouvelle ODF - Étape 1 - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Nouvelle ODF - Étape 1/5</h1>
                <p class="text-gray-600 mt-1">Informations de base de l'ODF</p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            <div class="font-semibold mb-2">Erreurs de validation :</div>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Étape 1 - Informations de base</h2>
                    <p class="text-sm text-gray-600">Sélectionnez l'entité ODF et ajoutez un commentaire si nécessaire.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('odfs.store.step1') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="odf_entite_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        ODF Entité <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="odf_entite_id"
                        name="odf_entite_id"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                        required
                    >
                        <option value="">Sélectionner une entité ODF</option>
                        @foreach($odfEntites as $entite)
                            <option value="{{ $entite->id }}" {{ old('odf_entite_id') == $entite->id ? 'selected' : '' }}>
                                {{ $entite->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="commentaire" class="block text-sm font-semibold text-gray-700 mb-2">
                        Commentaire
                    </label>
                    <textarea
                        id="commentaire"
                        name="commentaire"
                        rows="4"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                        placeholder="Commentaires..."
                    >{{ old('commentaire') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-4">
                <a href="{{ route('odfs.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Annuler</span>
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <span>Enregistrer et continuer</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


