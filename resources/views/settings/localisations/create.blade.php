@extends('layouts.app')

@section('title', 'Nouvelle Localisation')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">
                    Nouvelle Localisation
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez une nouvelle localisation pour votre système</p>
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
            <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Formulaire de création</h2>
                <p class="text-gray-600">Remplissez les informations pour créer une nouvelle localisation</p>
            </div>
        </div>

        <form action="{{ route('settings.localisations.store') }}" method="POST" class="space-y-6" data-server-validation>
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Code -->
                <div class="form-group">
                    <label for="CODE" class="block text-sm font-semibold text-gray-700 mb-2">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="CODE" 
                        id="CODE" 
                        value="{{ old('CODE') }}"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 hover:border-gray-400"
                        placeholder="Entrez le code"
                        required
                    >
                    @error('CODE')
                        <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- DRANEF -->
                <div class="form-group">
                    <label for="DRANEF" class="block text-sm font-semibold text-gray-700 mb-2">
                        DRANEF <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="DRANEF" 
                        id="DRANEF" 
                        value="{{ old('DRANEF') }}"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 hover:border-gray-400"
                        placeholder="Entrez le DRANEF"
                        required
                    >
                    @error('DRANEF')
                        <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- ENTITE -->
                <div class="form-group">
                    <label for="ENTITE" class="block text-sm font-semibold text-gray-700 mb-2">
                        Entité <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="ENTITE" 
                        id="ENTITE" 
                        value="{{ old('ENTITE') }}"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 hover:border-gray-400"
                        placeholder="Entrez l'entité"
                        required
                    >
                    @error('ENTITE')
                        <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-xl hover:from-pink-700 hover:to-rose-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Créer la Localisation</span>
                </button>
                
                
                
                <a 
                    href="{{ route('articles.index') }}" 
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

@push('styles')
<style>
    .form-input {
        background-image: none;
    }
    
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
    }
    
    /* Prevent duplicate error messages */
    .form-group .text-red-500:not(:first-child) {
        display: none;
    }
    
</style>
@endpush
