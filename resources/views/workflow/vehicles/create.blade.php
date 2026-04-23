@extends('layouts.app')

@section('title', 'Déclarer un véhicule - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('vehicles.index', $article) }}">Véhicules</a></li>
<li class="breadcrumb-item active">Nouvelle déclaration</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-3xl">

        <x-page-header
            title="Déclarer un véhicule"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-truck"
            :backRoute="route('vehicles.index', $article)"
            backText="Retour"
        />

        @if(session('error'))
            <x-alert type="error" title="Erreur!" dismissible>{{ session('error') }}</x-alert>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('vehicles.store', $article) }}" method="POST" class="space-y-6">
                @csrf

                <x-validation-errors />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Immatriculation -->
                    <div class="form-group">
                        <label for="immatriculation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Immatriculation <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="immatriculation" id="immatriculation"
                               value="{{ old('immatriculation') }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               required maxlength="80">
                    </div>

                    <!-- Marque -->
                    <div class="form-group">
                        <label for="marque" class="block text-sm font-semibold text-gray-700 mb-2">Marque</label>
                        <input type="text" name="marque" id="marque"
                               value="{{ old('marque') }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               maxlength="100">
                    </div>

                    <!-- Capacité -->
                    <div class="form-group">
                        <label for="capacite" class="block text-sm font-semibold text-gray-700 mb-2">Capacité</label>
                        <input type="number" name="capacite" id="capacite"
                               value="{{ old('capacite') }}" step="0.01" min="0"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Unité de capacité -->
                    <div class="form-group">
                        <label for="capacite_unite" class="block text-sm font-semibold text-gray-700 mb-2">
                            Unité <span class="text-red-500">*</span>
                        </label>
                        <select name="capacite_unite" id="capacite_unite"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                required>
                            @foreach(['m3' => 'm³', 'stere' => 'Stère', 'sacs' => 'Sacs', 'tonnes' => 'Tonnes', 'autre' => 'Autre'] as $val => $label)
                                <option value="{{ $val }}" {{ old('capacite_unite') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nom du chauffeur -->
                    <div class="form-group">
                        <label for="chauffeur_nom" class="block text-sm font-semibold text-gray-700 mb-2">Nom du chauffeur</label>
                        <input type="text" name="chauffeur_nom" id="chauffeur_nom"
                               value="{{ old('chauffeur_nom') }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               maxlength="150">
                    </div>

                    <!-- CIN du chauffeur -->
                    <div class="form-group">
                        <label for="chauffeur_cin" class="block text-sm font-semibold text-gray-700 mb-2">CIN du chauffeur</label>
                        <input type="text" name="chauffeur_cin" id="chauffeur_cin"
                               value="{{ old('chauffeur_cin') }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               maxlength="50">
                    </div>

                    <!-- Date de déclaration -->
                    <div class="form-group md:col-span-2">
                        <label for="date_declaration" class="block text-sm font-semibold text-gray-700 mb-2">Date de déclaration</label>
                        <input type="date" name="date_declaration" id="date_declaration"
                               value="{{ old('date_declaration', now()->format('Y-m-d')) }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('vehicles.index', $article) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
