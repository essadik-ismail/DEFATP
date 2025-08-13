@extends('layouts.app')

@section('title', 'Créer un Article')

@section('page-actions')
    <a href="{{ route('articles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour aux Articles
    </a>
@endsection

@section('content')
<!-- Create Form Card -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-plus-circle text-primary"></i> Créer un Nouvel Article</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('articles.store') }}" method="POST" id="articleForm">
            @csrf

            
            <!-- 2. Année Section -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="annee" class="form-label">Année <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('annee') is-invalid @enderror" 
                               id="annee" name="annee" value="{{ old('annee', date('Y')) }}" min="2000" max="2100" required>
                        @error('annee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date_adjudication" class="form-label">Date d'Adjudication <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date_adjudication') is-invalid @enderror" 
                               id="date_adjudication" name="date_adjudication" value="{{ old('date_adjudication') }}" required>
                        @error('date_adjudication')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="numero" class="form-label">Numéro Article</label>
                        <input type="number" class="form-control @error('numero') is-invalid @enderror" 
                               id="numero" name="numero" value="{{ old('numero') }}" maxlength="255" placeholder="Ex: 001, 002...">
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="localisation_id" class="form-label">Localisation <span class="text-danger">*</span></label>
                        <select class="form-select @error('localisation_id') is-invalid @enderror" id="localisation_id" name="localisation_id" required>
                            <option value="">Sélectionner une localisation</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->DRANEF }} - {{ $localisation->DPANEF }} - {{ $localisation->ENTITE }}
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
                        <label for="situation_administrative_id" class="form-label">Situation Administrative <span class="text-danger">*</span></label>
                        <select class="form-select @error('situation_administrative_id') is-invalid @enderror" id="situation_administrative_id" name="situation_administrative_id" required>
                            <option value="">Sélectionner une situation administrative</option>
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
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="parcelle" class="form-label">Parcelle</label>
                        <input type="number" min="0" class="form-control @error('parcelle') is-invalid @enderror" 
                               id="parcelle" name="parcelle" value="{{ old('parcelle') }}" placeholder="Numéro de parcelle">
                        @error('parcelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="foret_id" class="form-label">Forêt <span class="text-danger">*</span></label>
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
                        <label for="essence_id" class="form-label">Essence <span class="text-danger">*</span></label>
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
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nature_de_coupe_id" class="form-label">Nature de Coupe <span class="text-danger">*</span></label>
                        <select class="form-select @error('nature_de_coupe_id') is-invalid @enderror" id="nature_de_coupe_id" name="nature_de_coupe_id" required>
                            <option value="">Sélectionner une nature de coupe</option>
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
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lot" class="form-label">Lot</label>
                        <input type="number" class="form-control @error('lot') is-invalid @enderror" 
                               id="lot" name="lot" value="{{ old('lot') }}" placeholder="Numéro de lot">
                        @error('lot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 7. Spécifications Techniques Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-cogs text-primary"></i> Spécifications Techniques</h6>
                </div>
                
                <!-- Superficie -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_superficie" name="has_superficie" value="1">
                            <label class="form-check-label" for="has_superficie">Superficie</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('superficie') is-invalid @enderror" 
                               id="superficie" name="superficie" value="{{ old('superficie') }}" style="display: none;" placeholder="ha">
                        @error('superficie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- BO (m³) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_bo_m3" name="has_bo_m3" value="1">
                            <label class="form-check-label" for="has_bo_m3">BO (m³)</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('bo_m3') is-invalid @enderror" 
                               id="bo_m3" name="bo_m3" value="{{ old('bo_m3') }}" style="display: none;">
                        @error('bo_m3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- BI (m³) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_bi_m3" name="has_bi_m3" value="1">
                            <label class="form-check-label" for="has_bi_m3">BI (m³)</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('bi_m3') is-invalid @enderror" 
                               id="bi_m3" name="bi_m3" value="{{ old('bi_m3') }}" style="display: none;">
                        @error('bi_m3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- BF (st) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_bf_st" name="has_bf_st" value="1">
                            <label class="form-check-label" for="has_bf_st">BF (st)</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('bf_st') is-invalid @enderror" 
                               id="bf_st" name="bf_st" value="{{ old('bf_st') }}" style="display: none;">
                        @error('bf_st')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tanin (t) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_tanin_t" name="has_tanin_t" value="1">
                            <label class="form-check-label" for="has_tanin_t">Tanin (t)</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('tanin_t') is-invalid @enderror" 
                               id="tanin_t" name="tanin_t" value="{{ old('tanin_t') }}" style="display: none;">
                        @error('tanin_t')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Fleur Acacia (t) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_fleur_acacia_t" name="has_fleur_acacia_t" value="1">
                            <label class="form-check-label" for="has_fleur_acacia_t">Fleur Acacia (t)</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('fleur_acacia_t') is-invalid @enderror" 
                               id="fleur_acacia_t" name="fleur_acacia_t" value="{{ old('fleur_acacia_t') }}" style="display: none;">
                        @error('fleur_acacia_t')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Caroube (t) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_caroube_t" name="has_caroube_t" value="1">
                            <label class="form-check-label" for="has_caroube_t">Caroube (t)</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('caroube_t') is-invalid @enderror" 
                               id="caroube_t" name="caroube_t" value="{{ old('caroube_t') }}" style="display: none;">
                        @error('caroube_t')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Romarin (t) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_romarin_t" name="has_romarin_t" value="1">
                            <label class="form-check-label" for="has_romarin_t">Romarin (t)</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('romarin_t') is-invalid @enderror" 
                               id="romarin_t" name="romarin_t" value="{{ old('romarin_t') }}" style="display: none;">
                        @error('romarin_t')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- PS (t) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_ps_t" name="has_ps_t" value="1">
                            <label class="form-check-label" for="has_ps_t">PS (t)</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('ps_t') is-invalid @enderror" 
                               id="ps_t" name="ps_t" value="{{ old('ps_t') }}" style="display: none;">
                        @error('ps_t')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <!-- Charbon Bois (ox) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_charbon_bois_ox" name="has_charbon_bois_ox" value="1">
                            <label class="form-check-label" for="has_charbon_bois_ox">Charbon Bois (ox)</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('charbon_bois_ox') is-invalid @enderror" 
                               id="charbon_bois_ox" name="charbon_bois_ox" value="{{ old('charbon_bois_ox') }}" style="display: none;">
                        @error('charbon_bois_ox')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 8. Suivi de l'Article Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-file-contract text-warning"></i> Suivi de l'Article</h6>
                </div>
                
                <!-- Invendu -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="invendu" class="form-label">Invendu</label>
                        <select class="form-select @error('invendu') is-invalid @enderror" id="invendu" name="invendu">
                            <option value="1" {{ old('invendu') == '1' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ old('invendu') == '0' ? 'selected' : '' }}>Non</option>
                        </select>
                        @error('invendu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Prix de Retrait -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="prix_de_retrait" class="form-label">Prix de Retrait</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('prix_de_retrait') is-invalid @enderror" 
                               id="prix_de_retrait" name="prix_de_retrait" value="{{ old('prix_de_retrait') }}" placeholder="DH">
                        @error('prix_de_retrait')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Date DR -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="date_dr" class="form-label">Date DR</label>
                        <input type="date" class="form-control @error('date_dr') is-invalid @enderror" 
                               id="date_dr" name="date_dr" value="{{ old('date_dr') }}">
                        @error('date_dr')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Exploitant -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="exploitant_id" class="form-label">Exploitant</label>
                        <select class="form-select @error('exploitant_id') is-invalid @enderror" id="exploitant_id" name="exploitant_id">
                            <option value="">Sélectionner un exploitant</option>
                            @foreach($exploitants as $exploitant)
                                <option value="{{ $exploitant->id }}" {{ old('exploitant_id') == $exploitant->id ? 'selected' : '' }}>
                                    {{ $exploitant->nom_complet ?? $exploitant->raison_sociale }}
                                </option>
                            @endforeach
                        </select>
                        @error('exploitant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Type -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                            <option value="">Sélectionner</option>
                            <option value="appel_doffre" {{ old('type') == 'appel_doffre' ? 'selected' : '' }}>Appel d'offre</option>
                            <option value="adjudication" {{ old('type') == 'adjudication' ? 'selected' : '' }}>Adjudication</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Prix de Vente -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="prix_vente" class="form-label">Prix de Vente</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('prix_vente') is-invalid @enderror" 
                               id="prix_vente" name="prix_vente" value="{{ old('prix_vente') }}" placeholder="DH">
                        @error('prix_vente')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- DC -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="dc" name="dc" value="1" {{ old('dc') ? 'checked' : '' }}>
                            <label class="form-check-label" for="dc">DC</label>
                        </div>
                    </div>
                </div>

                <!-- RC -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="rc" name="rc" value="1" {{ old('rc') ? 'checked' : '' }}>
                            <label class="form-check-label" for="rc">RC</label>
                        </div>
                    </div>
                </div>

                <!-- Date de Résiliation -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="date_de_resiliation" class="form-label">Date de Résiliation</label>
                        <input type="date" class="form-control @error('date_de_resiliation') is-invalid @enderror" 
                               id="date_de_resiliation" name="date_de_resiliation" value="{{ old('date_de_resiliation') }}">
                        @error('date_de_resiliation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Date de Déchéance -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="date_de_decheance" class="form-label">Date de Déchéance</label>
                        <input type="date" class="form-control @error('date_de_decheance') is-invalid @enderror" 
                               id="date_de_decheance" name="date_de_decheance" value="{{ old('date_de_decheance') }}">
                        @error('date_de_decheance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Validation -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="is_validated" name="is_validated" value="1" {{ old('is_validated') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_validated">Validé</label>
                        </div>
                    </div>
                </div>

                <!-- Observations -->
                <div class="col-12">
                    <div class="mb-3">
                        <label for="observations" class="form-label">Observations</label>
                        <textarea class="form-control @error('observations') is-invalid @enderror" 
                                  id="observations" name="observations" rows="3" placeholder="Observations générales...">{{ old('observations') }}</textarea>
                        @error('observations')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer l'Article
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

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .alert {
        border-radius: 10px;
        border: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle checkboxes for technical specifications
        const checkboxes = [
            'has_superficie', 'has_bo_m3', 'has_bi_m3', 'has_bf_st', 
            'has_tanin_t', 'has_fleur_acacia_t', 'has_caroube_t', 'has_romarin_t', 
            'has_ps_t', 'has_charbon_bois_ox'
        ];

        checkboxes.forEach(function(checkboxId) {
            const checkbox = document.getElementById(checkboxId);
            const inputId = checkboxId.replace('has_', '');
            const input = document.getElementById(inputId);
            
            if (checkbox && input) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        input.style.display = 'block';
                        input.required = true;
                    } else {
                        input.style.display = 'none';
                        input.required = false;
                        input.value = '';
                    }
                });
            }
        });
    });
</script>
@endsection 