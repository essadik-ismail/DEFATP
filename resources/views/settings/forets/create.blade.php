@extends('layouts.app')

@section('title', 'Nouvelle Forêt')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Paramètres</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings.forets.index') }}">Forêts</a></li>
<li class="breadcrumb-item active">Nouvelle</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Nouvelle Forêt"
        icon="fas fa-tree"
        :backRoute="route('settings.forets.index')"
        backText="Retour"
    />

    <!-- Create Form -->
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <form action="{{ route('settings.forets.store') }}" method="POST" class="space-y-6" data-server-validation>
            @csrf

            <!-- Forest Name -->
            <div class="form-group">
                <label for="foret" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nom de la Forêt <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="foret" 
                    id="foret" 
                    value="{{ old('foret') }}"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400"
                    placeholder="Entrez le nom de la forêt"
                    required
                >
                @error('foret')
                    <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Nature Juridique -->
            <div class="form-group">
                <label for="nature_juridique" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nature Juridique
                </label>
                <input 
                    type="text" 
                    name="nature_juridique" 
                    id="nature_juridique" 
                    value="{{ old('nature_juridique') }}"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400"
                    placeholder="Entrez la nature juridique"
                >
                @error('nature_juridique')
                    <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- DPANEF -->
            <div class="form-group">
                <label for="dpanef_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    DPANEF
                </label>
                <select 
                    name="dpanef_id" 
                    id="dpanef_id" 
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400"
                >
                    <option value="">Sélectionner un DPANEF</option>
                    @foreach($dpanefs as $dpanef)
                        <option value="{{ $dpanef->id }}" {{ old('dpanef_id') == $dpanef->id ? 'selected' : '' }}>
                            {{ $dpanef->name }}@if($dpanef->dranef) - {{ $dpanef->dranef->name }}@endif
                        </option>
                    @endforeach
                </select>
                @error('dpanef_id')
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
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Créer la Forêt</span>
                </button>
                
                
                
                <a 
                    href="{{ route('entity-data.index', ['tab' => 'forets']) }}" 
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
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }
    
    /* Prevent duplicate error messages */
    .form-group .text-red-500:not(:first-child) {
        display: none;
    }
    
</style>
@endpush
