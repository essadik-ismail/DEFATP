@extends('layouts.app')

@section('title', 'Créer une Étape')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg bg-indigo-500">
                        <i class="fas fa-tasks text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900">Créer une Étape</h1>
                        <p class="text-gray-600 text-lg mt-2">PDFC #{{ $pdfc->id }} - Phase #{{ $phase->num }}</p>
                    </div>
                </div>
                <a href="{{ route('pdfcs.show', $pdfc) }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
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

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <form action="{{ route('pdfcs.etapes.store', [$pdfc, $phase]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="num" class="block text-sm font-semibold text-gray-700 mb-2">
                        Numéro <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('num') border-red-500 @enderror" 
                           id="num" 
                           name="num" 
                           value="{{ old('num') }}"
                           min="1"
                           required>
                    @error('num')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="objet" class="block text-sm font-semibold text-gray-700 mb-2">
                        Objet <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('objet') border-red-500 @enderror" 
                           id="objet" 
                           name="objet" 
                           value="{{ old('objet') }}"
                           required>
                    @error('objet')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                    Texte Explicatif
                </label>
                <textarea 
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('content') border-red-500 @enderror" 
                    id="content" 
                    name="content" 
                    rows="5">{{ old('content') }}</textarea>
                @error('content')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="fichier_joint" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fichier Joint
                </label>
                <input type="file" 
                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('fichier_joint') border-red-500 @enderror" 
                       id="fichier_joint" 
                       name="fichier_joint"
                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                <small class="text-gray-500 text-sm mt-1">Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (max 10MB)</small>
                @error('fichier_joint')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('pdfcs.show', $pdfc) }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

