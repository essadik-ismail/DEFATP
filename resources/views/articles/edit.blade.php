@extends('layouts.app')

@section('title', 'Modifier un Article')

@section('page-actions')
    <a href="{{ route('articles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour aux Articles
    </a>
@endsection

@section('content')
<!-- Edit Form Card -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-edit text-primary"></i> Modifier l'Article</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('articles.update', $article) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Basic Information Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-info-circle text-info"></i> Informations Générales</h6>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="numero" class="form-label">Numéro</label>
                        <input type="text" class="form-control @error('numero') is-invalid @enderror" 
                               id="numero" name="numero" value="{{ old('numero', $article->numero) }}" maxlength="255">
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" 
                               id="date" name="date" value="{{ old('date', $article->date) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="annee" class="form-label">Année <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('annee') is-invalid @enderror" 
                               id="annee" name="annee" value="{{ old('annee', $article->annee) }}" min="2000" max="2100" required>
                        @error('annee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="parcelle" class="form-label">Parcelle</label>
                        <input type="number" min="0" class="form-control @error('parcelle') is-invalid @enderror" 
                               id="parcelle" name="parcelle" value="{{ old('parcelle', $article->parcelle) }}">
                        @error('parcelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location & Classification Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-map-marker-alt text-success"></i> Localisation & Classification</h6>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <x-form.select-search
                            name="localisation_id"
                            label="Localisation"
                            :options="$localisations->mapWithKeys(function($localisation) { return [$localisation->id => $localisation->CODE . ' - ' . $localisation->DRANEF . ' - ' . $localisation->ENTITE]; })"
                            :selected="old('localisation_id', $article->localisation_id)"
                            placeholder="Sélectionner une localisation..."
                            searchPlaceholder="Rechercher une localisation..."
                            required="true"
                            :error="$errors->first('localisation_id')"
                        />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <x-form.select-search
                            name="foret_id"
                            label="Forêt"
                            :options="$forets->pluck('foret', 'id')"
                            :selected="old('foret_id', $article->foret_id)"
                            placeholder="Sélectionner une forêt..."
                            searchPlaceholder="Rechercher une forêt..."
                            required="true"
                            :error="$errors->first('foret_id')"
                        />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <x-form.select-search
                            name="essence_id"
                            label="Essence"
                            :options="$essences->pluck('essence', 'id')"
                            :selected="old('essence_id', $article->essence_id)"
                            placeholder="Sélectionner une essence..."
                            searchPlaceholder="Rechercher une essence..."
                            required="true"
                            :error="$errors->first('essence_id')"
                        />
                    </div>
                </div>
            </div>

            <!-- Technical Specifications Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-cogs text-warning"></i> Spécifications Techniques</h6>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="volume" class="form-label">Volume (m³) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('volume') is-invalid @enderror" 
                               id="volume" name="volume" value="{{ old('volume', $article->volume) }}" required>
                        @error('volume')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="surface" class="form-label">Surface (ha)</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('surface') is-invalid @enderror" 
                               id="surface" name="surface" value="{{ old('surface', $article->surface) }}">
                        @error('surface')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <x-form.select-search
                            name="nature_de_coupe_id"
                            label="Nature de Coupe"
                            :options="$natureDeCoupes->pluck('nature_de_coupe', 'id')"
                            :selected="old('nature_de_coupe_id', $article->nature_de_coupe_id)"
                            placeholder="Sélectionner une nature de coupe..."
                            searchPlaceholder="Rechercher une nature de coupe..."
                            required="true"
                            :error="$errors->first('nature_de_coupe_id')"
                        />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <x-form.select-search
                            name="situation_administrative_id"
                            label="Situation Administrative"
                            :options="$situationAdministratives->mapWithKeys(function($situation) { return [$situation->id => $situation->commune . ' - ' . $situation->province]; })"
                            :selected="old('situation_administrative_id', $article->situation_administrative_id)"
                            placeholder="Sélectionner une situation administrative..."
                            searchPlaceholder="Rechercher une situation administrative..."
                            required="true"
                            :error="$errors->first('situation_administrative_id')"
                        />
                    </div>
                </div>
            </div>

            <!-- Financial Details Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-dollar-sign text-success"></i> Détails Financiers</h6>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="prix_estime" class="form-label">Prix Estimé (DH)</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('prix_estime') is-invalid @enderror" 
                               id="prix_estime" name="prix_estime" value="{{ old('prix_estime', $article->prix_estime) }}">
                        @error('prix_estime')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="prix_adjudication" class="form-label">Prix d'Adjudication (DH)</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('prix_adjudication') is-invalid @enderror" 
                               id="prix_adjudication" name="prix_adjudication" value="{{ old('prix_adjudication', $article->prix_adjudication) }}">
                        @error('prix_adjudication')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">

                </div>
            </div>

            <!-- Additional Fields Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-plus text-info"></i> Champs Additionnels</h6>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <x-form.select-search
                            name="exploitant_id"
                            label="Exploitant"
                            :options="$exploitants->mapWithKeys(function($exploitant) { return [$exploitant->id => $exploitant->nom_complet ?? $exploitant->raison_sociale]; })"
                            :selected="old('exploitant_id', $article->exploitant_id)"
                            placeholder="Sélectionner un exploitant..."
                            searchPlaceholder="Rechercher un exploitant..."
                            :error="$errors->first('exploitant_id')"
                        />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <x-form.select-search
                            name="statut"
                            label="Statut"
                            :options="['disponible' => 'Disponible', 'vendu' => 'Vendu', 'en_cours' => 'En cours']"
                            :selected="old('statut', $article->statut)"
                            placeholder="Sélectionner un statut..."
                            searchPlaceholder="Rechercher un statut..."
                            :error="$errors->first('statut')"
                        />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour l'Article
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 20px;
}

.card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #495057;
}

.card-body {
    padding: 25px;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e9ecef;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}



.alert {
    border-radius: 10px;
    border: none;
}
</style>
@endsection 