@extends('layouts.app')

@section('title', 'Modifier la Nature de Coupe')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-cut text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                    Modifier la Nature de Coupe
                </h1>
                <p class="text-gray-600 text-lg mt-2">Modifiez les informations de la nature de coupe "{{ $natureDeCoupe->nature_de_coupe }}"</p>
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

    <!-- Edit Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Formulaire de modification</h2>
                <p class="text-gray-600">Modifiez les informations de la nature de coupe</p>
            </div>
        </div>

        <form action="{{ route('settings.nature-de-coupes.update', $natureDeCoupe) }}" method="POST" class="space-y-6" data-server-validation>
            @csrf
            @method('PUT')

            <!-- Nature de Coupe Name -->
            <div class="form-group">
                <label for="nature_de_coupe" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nature de Coupe <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nature_de_coupe" 
                    id="nature_de_coupe" 
                    value="{{ old('nature_de_coupe', $natureDeCoupe->nature_de_coupe) }}"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400"
                    placeholder="Entrez la nature de coupe"
                    required
                >
                @error('nature_de_coupe')
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
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Mettre à jour</span>
                </button>
                
                <a 
                    href="{{ route('settings.nature-de-coupes') }}" 
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Nature de Coupe Information -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-8 mt-8 border border-blue-200 shadow-xl">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-info-circle text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-blue-900">Informations de la Nature de Coupe</h3>
                <p class="text-blue-700">Détails et statistiques</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Créée le</h4>
                        <p class="text-gray-600 text-sm">{{ $natureDeCoupe->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Modifiée le</h4>
                        <p class="text-gray-600 text-sm">{{ $natureDeCoupe->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-database text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">ID</h4>
                        <p class="text-gray-600 text-sm">#{{ $natureDeCoupe->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-input {
        background-image: none;
    }
    
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }
    
    /* Prevent duplicate error messages */
    .form-group .text-red-500:not(:first-child) {
        display: none;
    }
    
    /* Dark mode support */
    .dark-mode .form-input {
        background-color: var(--dark-bg-secondary);
        border-color: var(--dark-border);
        color: var(--dark-text-primary);
    }
    
    .dark-mode .form-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2);
    }
</style>
@endpush
