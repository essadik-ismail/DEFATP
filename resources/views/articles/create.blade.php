@extends('layouts.app')

@section('title', 'Nouvel Article - SylvaNet')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-file-alt me-2 text-primary"></i>Nouvel Article
                </h1>
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Créer un nouvel article forestier</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('articles.store') }}" method="POST">
                        @csrf
                        
                        <!-- Informations de Base -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informations de Base
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="annee" class="form-label">Année *</label>
                                    <input type="number" class="form-control @error('annee') is-invalid @enderror" 
                                           id="annee" name="annee" value="{{ old('annee', date('Y')) }}" 
                                           min="2000" max="2100" required>
                                    @error('annee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero" class="form-label">Numéro d'Article *</label>
                                    <input type="text" class="form-control @error('numero') is-invalid @enderror" 
                                           id="numero" name="numero" value="{{ old('numero') }}" 
                                           placeholder="Ex: ART001" required>
                                    @error('numero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_adjudication" class="form-label">Date d'Adjudication *</label>
                                    <input type="date" class="form-control @error('date_adjudication') is-invalid @enderror" 
                                           id="date_adjudication" name="date_adjudication" 
                                           value="{{ old('date_adjudication') }}" required>
                                    @error('date_adjudication')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Sélectionner le type</option>
                                        <option value="adjudication" {{ old('type') == 'adjudication' ? 'selected' : '' }}>Adjudication</option>
                                        <option value="appel_doffre" {{ old('type') == 'appel_doffre' ? 'selected' : '' }}>Appel d'Offre</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Localisation et Forêt -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>Localisation et Forêt
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="foret_id" class="form-label">Forêt *</label>
                                    <select class="form-select @error('foret_id') is-invalid @enderror" id="foret_id" name="foret_id" required>
                                        <option value="">Sélectionner une forêt</option>
                                        @foreach($forets as $foret)
                                            <option value="{{ $foret->id }}" {{ old('foret_id') == $foret->id ? 'selected' : '' }}>
                                                {{ $foret->foret }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('foret_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="essence_id" class="form-label">Essence *</label>
                                    <select class="form-select @error('essence_id') is-invalid @enderror" id="essence_id" name="essence_id" required>
                                        <option value="">Sélectionner une essence</option>
                                        @foreach($essences as $essence)
                                            <option value="{{ $essence->id }}" {{ old('essence_id') == $essence->id ? 'selected' : '' }}>
                                                {{ $essence->essence }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('essence_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="localisation_id" class="form-label">Localisation *</label>
                                    <select class="form-select @error('localisation_id') is-invalid @enderror" id="localisation_id" name="localisation_id" required>
                                        <option value="">Sélectionner une localisation</option>
                                        @foreach($localisations as $localisation)
                                            <option value="{{ $localisation->id }}" {{ old('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                                {{ $localisation->CODE }} - {{ $localisation->DRANEF }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('localisation_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="situation_administrative_id" class="form-label">Situation Administrative *</label>
                                    <select class="form-select @error('situation_administrative_id') is-invalid @enderror" id="situation_administrative_id" name="situation_administrative_id" required>
                                        <option value="">Sélectionner une situation</option>
                                        @foreach($situationAdministratives as $situation)
                                            <option value="{{ $situation->id }}" {{ old('situation_administrative_id') == $situation->id ? 'selected' : '' }}>
                                                {{ $situation->commune }} - {{ $situation->province }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('situation_administrative_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Exploitant et Nature de Coupe -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user-tie me-2"></i>Exploitant et Nature de Coupe
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exploitant_id" class="form-label">Exploitant *</label>
                                    <select class="form-select @error('exploitant_id') is-invalid @enderror" id="exploitant_id" name="exploitant_id" required>
                                        <option value="">Sélectionner un exploitant</option>
                                        @foreach($exploitants as $exploitant)
                                            <option value="{{ $exploitant->id }}" {{ old('exploitant_id') == $exploitant->id ? 'selected' : '' }}>
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
                                    <label for="nature_de_coupe_id" class="form-label">Nature de Coupe *</label>
                                    <select class="form-select @error('nature_de_coupe_id') is-invalid @enderror" id="nature_de_coupe_id" name="nature_de_coupe_id" required>
                                        <option value="">Sélectionner une nature</option>
                                        @foreach($natureDeCoupes as $nature)
                                            <option value="{{ $nature->id }}" {{ old('nature_de_coupe_id') == $nature->id ? 'selected' : '' }}>
                                                {{ $nature->nature_de_coupe }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nature_de_coupe_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Volumes et Prix -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-calculator me-2"></i>Volumes et Prix
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bo_m3" class="form-label">BO (m³)</label>
                                    <input type="number" class="form-control @error('bo_m3') is-invalid @enderror" 
                                           id="bo_m3" name="bo_m3" value="{{ old('bo_m3') }}" 
                                           step="0.01" min="0">
                                    @error('bo_m3')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bi_m3" class="form-label">BI (m³)</label>
                                    <input type="number" class="form-control @error('bi_m3') is-invalid @enderror" 
                                           id="bi_m3" name="bi_m3" value="{{ old('bi_m3') }}" 
                                           step="0.01" min="0">
                                    @error('bi_m3')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prix_de_retrait" class="form-label">Prix de Retrait (DH) *</label>
                                    <input type="number" class="form-control @error('prix_de_retrait') is-invalid @enderror" 
                                           id="prix_de_retrait" name="prix_de_retrait" value="{{ old('prix_de_retrait') }}" 
                                           step="0.01" min="0" required>
                                    @error('prix_de_retrait')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prix_vente" class="form-label">Prix de Vente (DH) *</label>
                                    <input type="number" class="form-control @error('prix_vente') is-invalid @enderror" 
                                           id="prix_vente" name="prix_vente" value="{{ old('prix_vente') }}" 
                                           step="0.01" min="0" required>
                                    @error('prix_vente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bf_st" class="form-label">BF/ST</label>
                                    <input type="number" class="form-control @error('bf_st') is-invalid @enderror" 
                                           id="bf_st" name="bf_st" value="{{ old('bf_st') }}" 
                                           min="0">
                                    @error('bf_st')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="parcelle" class="form-label">Parcelle</label>
                                    <input type="number" class="form-control @error('parcelle') is-invalid @enderror" 
                                           id="parcelle" name="parcelle" value="{{ old('parcelle') }}" 
                                           min="0">
                                    @error('parcelle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Observations et Validation -->
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-clipboard me-2"></i>Observations et Validation
                        </h6>
                        <div class="mb-4">
                            <div class="mb-3">
                                <label for="observations" class="form-label">Observations</label>
                                <textarea class="form-control @error('observations') is-invalid @enderror" 
                                          id="observations" name="observations" rows="3" 
                                          placeholder="Observations supplémentaires...">{{ old('observations') }}</textarea>
                                @error('observations')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('is_validated') is-invalid @enderror" 
                                       type="checkbox" name="is_validated" id="is_validated" value="1" 
                                       {{ old('is_validated') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_validated">
                                    Article validé
                                </label>
                                @error('is_validated')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer l'Article
                            </button>
                            <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 