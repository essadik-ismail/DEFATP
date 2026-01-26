@extends('layouts.app')

@section('title', 'Permis de Colportage - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
<li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">Détail #{{ $article->numero ?? $article->id }}</a></li>
<li class="breadcrumb-item active">Permis de colportage</li>
@endsection

@section('content')
<div class="min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        
        <!-- Page Header Component -->
        <x-page-header 
            title="Permis de Colportage"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-truck"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        <!-- Success/Error Messages -->
        @if(session('success'))
            <x-alert type="success" title="Succès!" dismissible>
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="error" title="Erreur!" dismissible>
                {{ session('error') }}
            </x-alert>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-file-signature"></i>
                    Générer le Permis de Colportage
                </h2>
            </div>
            <div class="p-6">
                <form action="{{ route('articles.store-permis-colportage', $article) }}" method="POST">
                    @csrf

                    <x-validation-errors />

                    <div class="space-y-6 mb-6">
                    <!-- Permis d'Enlever Selection -->
                    <x-form-section
                        title="Permis d'Enlever"
                        icon="fas fa-truck-loading"
                        color="green"
                        columns="1"
                    >
                        @if(isset($permisEnlevers) && $permisEnlevers->count() > 0)
                            <x-form-input
                                type="select"
                                name="id_permis_enlever"
                                label="Sélectionner un Permis d'Enlever"
                                :required="true"
                                focusColor="green"
                            >
                                <option value="">Choisir...</option>
                                @foreach($permisEnlevers as $permis)
                                    <option value="{{ $permis->id }}" {{ old('id_permis_enlever') == $permis->id ? 'selected' : '' }}>
                                        {{ $permis->num_quittance ?? ('Permis #' . $permis->id) }}
                                        — {{ $permis->date ? \Carbon\Carbon::parse($permis->date)->format('d/m/Y') : '' }}
                                        — Tranches: {{ $permis->num_tranche_paye ?? '-' }}
                                    </option>
                                @endforeach
                            </x-form-input>
                        @else
                            <x-alert type="warning" title="Aucun Permis d'Enlever">
                                Vous devez d'abord créer un <strong>Permis d'Enlever</strong> avant de générer un Permis de Colportage.
                            </x-alert>
                        @endif
                    </x-form-section>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form-input
                            type="date"
                            name="date_debut"
                            label="Date de début"
                            :required="true"
                            :value="old('date_debut')"
                            focusColor="blue"
                        />

                        <x-form-input
                            type="date"
                            name="date_fin"
                            label="Date de fin"
                            :required="true"
                            :value="old('date_fin')"
                            focusColor="blue"
                        />
                    </div>

                    <!-- Véhicule -->
                    <x-form-input
                        type="text"
                        name="vehicule_immatriculation"
                        label="Immatriculation du véhicule"
                        :required="true"
                        :value="old('vehicule_immatriculation')"
                        focusColor="blue"
                        placeholder="Ex: A-12345-B"
                    />

                    <!-- Chauffeur Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form-input
                            type="text"
                            name="chauffeur_nom"
                            label="Nom du chauffeur"
                            :required="true"
                            :value="old('chauffeur_nom')"
                            focusColor="blue"
                            placeholder="Nom complet"
                        />

                        <x-form-input
                            type="text"
                            name="chauffeur_cin"
                            label="CIN du chauffeur"
                            :required="true"
                            :value="old('chauffeur_cin')"
                            focusColor="blue"
                            placeholder="Ex: AB123456"
                        />
                    </div>

                    <!-- Destination -->
                    <x-form-input
                        type="text"
                        name="destination"
                        label="Destination"
                        :required="true"
                        :value="old('destination')"
                        focusColor="blue"
                        placeholder="Adresse de destination"
                    />

                        <!-- Article Information Summary -->
                        <x-form-section
                            title="Informations de l'Article"
                            icon="fas fa-info-circle"
                            color="purple"
                            columns="3"
                        >
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Numéro</label>
                                <p class="text-purple-900 font-semibold">{{ $article->numero }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Année</label>
                                <p class="text-purple-900 font-semibold">{{ $article->annee }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-1">Lot</label>
                                <p class="text-purple-900 font-semibold">{{ $article->lot }}</p>
                            </div>
                            @if($article->essences->count() > 0)
                                <div class="col-span-3">
                                    <label class="block text-sm font-medium text-purple-700 mb-1">Essences</label>
                                    <p class="text-purple-900 font-semibold">
                                        {{ $article->essences->pluck('essence')->join(', ') }}
                                    </p>
                                </div>
                            @endif
                        </x-form-section>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-green-200">
                        <a href="{{ route('articles.show', $article) }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 border border-green-300 rounded-xl text-green-700 hover:bg-green-50 transition-all duration-300">
                            <i class="fas fa-times"></i>
                            <span>Annuler</span>
                        </a>
                        <button type="submit"
                                {{ (!isset($permisEnlevers) || $permisEnlevers->count() === 0) ? 'disabled' : '' }}
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors"
                                style="background: linear-gradient(135deg, #059669, #047857); {{ (!isset($permisEnlevers) || $permisEnlevers->count() === 0) ? 'opacity:0.6; cursor:not-allowed;' : '' }}">
                            <i class="fas fa-truck"></i>
                            <span>Générer le Permis</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
