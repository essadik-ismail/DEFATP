@extends('layouts.app')

@section('title', 'Nouvelle Vocation - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('contracts.index') }}">Contrats</a></li>
<li class="breadcrumb-item active">Nouvelle vocation</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Nouvelle Vocation"
        icon="fas fa-briefcase"
        :backRoute="route('contracts.index')"
        backText="Retour"
    />

    <!-- Create Form -->
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <form action="{{ route('contracts.vocations.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Vocation Name -->
            <div class="form-group">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nom de la Vocation <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                    placeholder="Entrez le nom de la vocation"
                    required
                >
                @error('name')
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
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Créer la Vocation</span>
                </button>
                
                <a 
                    href="{{ route('entity-data.index', ['tab' => 'vocations']) }}" 
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

