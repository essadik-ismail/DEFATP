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
                        <label for="localisation_id" class="form-label">Localisation <span class="text-danger">*</span></label>
                        <select class="form-select @error('localisation_id') is-invalid @enderror" id="localisation_id" name="localisation_id" required>
                            <option value="">Sélectionner une localisation</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id', $article->localisation_id) == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('localisation_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="foret_id" class="form-label">Forêt <span class="text-danger">*</span></label>
                        <select class="form-select @error('foret_id') is-invalid @enderror" id="foret_id" name="foret_id" required>
                            <option value="">Sélectionner une forêt</option>
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ old('foret_id', $article->foret_id) == $foret->id ? 'selected' : '' }}>
                                    {{ $foret->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('foret_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="essence_id" class="form-label">Essence <span class="text-danger">*</span></label>
                        <select class="form-select @error('essence_id') is-invalid @enderror" id="essence_id" name="essence_id" required>
                            <option value="">Sélectionner une essence</option>
                            @foreach($essences as $essence)
                                <option value="{{ $essence->id }}" {{ old('essence_id', $article->essence_id) == $essence->id ? 'selected' : '' }}>
                                    {{ $essence->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('essence_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <label for="nature_de_coupe_id" class="form-label">Nature de Coupe <span class="text-danger">*</span></label>
                        <select class="form-select @error('nature_de_coupe_id') is-invalid @enderror" id="nature_de_coupe_id" name="nature_de_coupe_id" required>
                            <option value="">Sélectionner</option>
                            @foreach($natureDeCoupes as $natureDeCoupe)
                                <option value="{{ $natureDeCoupe->id }}" {{ old('nature_de_coupe_id', $article->nature_de_coupe_id) == $natureDeCoupe->id ? 'selected' : '' }}>
                                    {{ $natureDeCoupe->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('nature_de_coupe_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="situation_administrative_id" class="form-label">Situation Administrative <span class="text-danger">*</span></label>
                        <select class="form-select @error('situation_administrative_id') is-invalid @enderror" id="situation_administrative_id" name="situation_administrative_id" required>
                            <option value="">Sélectionner</option>
                            @foreach($situationAdministratives as $situationAdministrative)
                                <option value="{{ $situationAdministrative->id }}" {{ old('situation_administrative_id', $article->situation_administrative_id) == $situationAdministrative->id ? 'selected' : '' }}>
                                    {{ $situationAdministrative->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('situation_administrative_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                    <div class="mb-3">
                        <label for="session_adjudication_id" class="form-label">Session d'Adjudication</label>
                        <select class="form-select @error('session_adjudication_id') is-invalid @enderror" id="session_adjudication_id" name="session_adjudication_id">
                            <option value="">Sélectionner</option>
                            @foreach($sessionAdjudications as $sessionAdjudication)
                                <option value="{{ $sessionAdjudication->id }}" {{ old('session_adjudication_id', $article->session_adjudication_id) == $sessionAdjudication->id ? 'selected' : '' }}>
                                    {{ $sessionAdjudication->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('session_adjudication_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Fields Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-plus text-info"></i> Champs Additionnels</h6>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="exploitant_id" class="form-label">Exploitant</label>
                        <select class="form-select @error('exploitant_id') is-invalid @enderror" id="exploitant_id" name="exploitant_id">
                            <option value="">Sélectionner un exploitant</option>
                            @foreach($exploitants as $exploitant)
                                <option value="{{ $exploitant->id }}" {{ old('exploitant_id', $article->exploitant_id) == $exploitant->id ? 'selected' : '' }}>
                                    {{ $exploitant->nom_complet }}
                                </option>
                            @endforeach
                        </select>
                        @error('exploitant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut">
                            <option value="disponible" {{ old('statut', $article->statut) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="vendu" {{ old('statut', $article->statut) == 'vendu' ? 'selected' : '' }}>Vendu</option>
                            <option value="en_cours" {{ old('statut', $article->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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