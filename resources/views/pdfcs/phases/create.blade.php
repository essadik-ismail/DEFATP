@extends('layouts.app')

@section('title', 'Créer une Phase')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg bg-purple-500">
                        <i class="fas fa-list-ol text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900">Créer une Phase</h1>
                        <p class="text-gray-600 text-lg mt-2">PDFC #{{ $pdfc->id }}</p>
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
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <form action="{{ route('pdfcs.phases.store', $pdfc) }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="num" class="block text-sm font-semibold text-gray-700 mb-2">
                        Numéro <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('num') border-red-500 @enderror" 
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
                    <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nom
                    </label>
                    <input type="text" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('nom') border-red-500 @enderror" 
                           id="nom" 
                           name="nom" 
                           value="{{ old('nom') }}">
                    @error('nom')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Date
                        <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa"></i>
                    </label>
                    <input type="date" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('date') border-red-500 @enderror" 
                           id="date" 
                           name="date" 
                           value="{{ old('date') }}">
                    @error('date')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_de_début" class="block text-sm font-semibold text-gray-700 mb-2">
                        Date de Début
                        <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa"></i>
                    </label>
                    <input type="date" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('date_de_début') border-red-500 @enderror" 
                           id="date_de_début" 
                           name="date_de_début" 
                           value="{{ old('date_de_début') }}">
                    @error('date_de_début')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date_de_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                        Date de Fin
                        <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa"></i>
                    </label>
                    <input type="date" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('date_de_fin') border-red-500 @enderror" 
                           id="date_de_fin" 
                           name="date_de_fin" 
                           value="{{ old('date_de_fin') }}">
                    @error('date_de_fin')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="échéance" class="block text-sm font-semibold text-gray-700 mb-2">
                        Échéance
                        <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa"></i>
                    </label>
                    <input type="date" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('échéance') border-red-500 @enderror" 
                           id="échéance" 
                           name="échéance" 
                           value="{{ old('échéance') }}">
                    @error('échéance')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('pdfcs.show', $pdfc) }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 bg-purple-500 text-white rounded-xl hover:bg-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

