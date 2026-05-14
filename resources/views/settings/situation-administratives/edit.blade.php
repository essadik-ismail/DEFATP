@extends('layouts.app')

@section('title', 'Modifier la Situation Administrative')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('settings.index') }}">Paramètres</a></li>
<li class="bc-item"><a href="{{ route('settings.situation-administratives.index') }}">Situations administratives</a></li>
<li class="bc-item active">Modifier</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Modifier la Situation Administrative"
        icon="fas fa-building"
        :backRoute="route('settings.situation-administratives.index')"
        backText="Retour"
    />

    <!-- Edit Form -->
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <form action="{{ route('settings.situation-administratives.update', $situationAdministrative) }}" method="POST" class="space-y-6" data-server-validation>
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Commune -->
                <div class="form-group">
                    <label for="commune" class="block text-sm font-semibold text-gray-700 mb-2">
                        Commune <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="commune" 
                        id="commune" 
                        value="{{ old('commune', $situationAdministrative->commune) }}"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400"
                        placeholder="Entrez le nom de la commune"
                        required
                    >
                    @error('commune')
                        <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Province -->
                <div class="form-group">
                    <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">
                        Province <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="province" 
                        id="province" 
                        value="{{ old('province', $situationAdministrative->province) }}"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 hover:border-gray-400"
                        placeholder="Entrez le nom de la province"
                        required
                    >
                    @error('province')
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
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Mettre à jour</span>
                </button>
                
                <a 
                    href="{{ route('entity-data.index', ['tab' => 'situations']) }}" 
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Situation Administrative Information -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-8 mt-8 border border-blue-200 shadow-xl">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-blue-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Créée le</h4>
                        <p class="text-gray-600 text-sm">{{ $situationAdministrative->created_at->format('d/m/Y H:i') }}</p>
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
                        <p class="text-gray-600 text-sm">{{ $situationAdministrative->updated_at->format('d/m/Y H:i') }}</p>
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
                        <p class="text-gray-600 text-sm">#{{ $situationAdministrative->id }}</p>
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
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    /* Prevent duplicate error messages */
    .form-group .text-red-500:not(:first-child) {
        display: none;
    }
    
</style>
@endpush
