@extends('layouts.app')

@section('title', 'Nouvelle Coopérative - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-users-cog text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
                    Nouvelle Coopérative
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez une nouvelle coopérative pour votre système</p>
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

    <!-- Create Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Formulaire de création</h2>
                <p class="text-gray-600">Remplissez les informations pour créer une nouvelle coopérative</p>
            </div>
        </div>

        <form action="{{ route('contracts.coperatives.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nom -->
            <div class="form-group">
                <label for="nom" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <span>Nom de la Coopérative <span class="text-red-500">*</span></span>
                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nom de la coopérative"></i>
                </label>
                <input 
                    type="text" 
                    name="nom" 
                    id="nom" 
                    value="{{ old('nom') }}"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                    placeholder="Entrez le nom de la coopérative"
                    required
                >
                @error('nom')
                    <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Vocation -->
            <div class="form-group">
                <label for="vocation_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <span>Vocation</span>
                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez la vocation de la coopérative"></i>
                </label>
                <select 
                    name="vocation_id" 
                    id="vocation_id" 
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                >
                    <option value="">Sélectionner une vocation</option>
                    @foreach($vocations as $vocation)
                        <option value="{{ $vocation->id }}" {{ old('vocation_id') == $vocation->id ? 'selected' : '' }}>
                            {{ $vocation->name }}
                        </option>
                    @endforeach
                </select>
                @error('vocation_id')
                    <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Nombre de Membres -->
            <div class="form-group">
                <label for="nombre_membres" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <span>Nombre de Membres</span>
                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nombre de membres de la coopérative"></i>
                </label>
                <input 
                    type="number" 
                    name="nombre_membres" 
                    id="nombre_membres" 
                    value="{{ old('nombre_membres', 0) }}"
                    min="0"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                    placeholder="Entrez le nombre de membres"
                >
                @error('nombre_membres')
                    <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Nombre de Coopératives -->
            <div class="form-group">
                <label for="nombre_coperatives" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <span>Nombre de Coopératives</span>
                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nombre de coopératives associées"></i>
                </label>
                <input 
                    type="number" 
                    name="nombre_coperatives" 
                    id="nombre_coperatives" 
                    value="{{ old('nombre_coperatives', 0) }}"
                    min="0"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400"
                    placeholder="Entrez le nombre de coopératives"
                >
                @error('nombre_coperatives')
                    <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Créer la Coopérative</span>
                </button>
                
                <a 
                    href="{{ route('contracts.index', ['tab' => 'coperatives']) }}" 
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

