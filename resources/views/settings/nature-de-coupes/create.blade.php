@extends('layouts.app')

@section('title', 'Nouvelle Nature de Coupe')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('settings.index') }}">Paramètres</a></li>
<li class="bc-item"><a href="{{ route('settings.nature-de-coupes.index') }}">Natures de coupe</a></li>
<li class="bc-item active">Nouvelle</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Nouvelle Nature de Coupe"
        icon="fas fa-cut"
        :backRoute="route('settings.nature-de-coupes.index')"
        backText="Retour"
    />

    <!-- Create Form -->
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <form action="{{ route('settings.nature-de-coupes.store') }}" method="POST" class="space-y-6" data-server-validation>
            @csrf

            <!-- Nature de Coupe Name -->
            <div class="form-group">
                <label for="nature_de_coupe" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nature de Coupe <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nature_de_coupe" 
                    id="nature_de_coupe" 
                    value="{{ old('nature_de_coupe') }}"
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
                    <span class="font-semibold">Créer la Nature de Coupe</span>
                </button>
                
                
                
                <a 
                    href="{{ route('entity-data.index', ['tab' => 'natures-coupe']) }}" 
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
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }
    
    /* Prevent duplicate error messages */
    .form-group .text-red-500:not(:first-child) {
        display: none;
    }
    
</style>
@endpush
