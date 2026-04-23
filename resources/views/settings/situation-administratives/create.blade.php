@extends('layouts.app')

@section('title', 'Nouvelle Situation Administrative')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Paramètres</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings.situation-administratives.index') }}">Situations administratives</a></li>
<li class="breadcrumb-item active">Nouvelle</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Nouvelle Situation Administrative"
        icon="fas fa-building"
        :backRoute="route('settings.situation-administratives.index')"
        backText="Retour"
    />

    <x-flash-messages />



    <!-- Alert Messages -->
        <x-flash-messages />

    

    <!-- Create Form -->
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <form action="{{ route('settings.situation-administratives.store') }}" method="POST" class="space-y-6" data-server-validation>
            @csrf

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
                        value="{{ old('commune') }}"
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
                        value="{{ old('province') }}"
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
                    <span class="font-semibold">Créer la Situation Administrative</span>
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
