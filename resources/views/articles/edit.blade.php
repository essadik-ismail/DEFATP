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
            
            <!-- Section 1: Informations de Base -->
            <div class="section-header mb-4">
                <h6 class="text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-info-circle me-2"></i>Section 1: Informations de Base
                </h6>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="annee" class="form-label">Année *</label>
                        <input type="number" class="form-control @error('annee') is-invalid @enderror" 
                               id="annee" name="annee" value="{{ old('annee', $article->annee) }}" 
                               min="2000" max="2100" required>
                        @error('annee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="date_adjudication" class="form-label">Date d'Adjudication *</label>
                        <input type="date" class="form-control @error('date_adjudication') is-invalid @enderror" 
                               id="date_adjudication" name="date_adjudication" 
                               value="{{ old('date_adjudication', $article->date_adjudication) }}" required>
                        @error('date_adjudication')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="lot" class="form-label">Lot</label>
                        <input type="number" class="form-control @error('lot') is-invalid @enderror" 
                               id="lot" name="lot" value="{{ old('lot', $article->lot) }}" 
                               min="0" placeholder="Numéro de lot">
                        @error('lot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="numero" class="form-label">Numéro d'Article *</label>
                        <input type="text" class="form-control @error('numero') is-invalid @enderror" 
                               id="numero" name="numero" value="{{ old('numero', $article->numero) }}" 
                               placeholder="Ex: ART001" required>
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Localisation et Détails -->
            <div class="section-header mb-4">
                <h6 class="text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-map-marker-alt me-2"></i>Section 2: Localisation
                </h6>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="localisation_id" class="form-label">Localisation *</label>
                        <select class="form-select @error('localisation_id') is-invalid @enderror" id="localisation_id" name="localisation_id" required>
                            <option value="">Sélectionner une localisation</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id', $article->localisation_id) == $localisation->id ? 'selected' : '' }}>
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
                                <option value="{{ $situation->id }}" {{ old('situation_administrative_id', $article->situation_administrative_id) == $situation->id ? 'selected' : '' }}>
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

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="foret_id" class="form-label">Forêt *</label>
                        <select class="form-select @error('foret_id') is-invalid @enderror" id="foret_id" name="foret_id" required>
                            <option value="">Sélectionner une forêt</option>
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ old('foret_id', $article->foret_id) == $foret->id ? 'selected' : '' }}>
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
                        <label for="nature_juridique" class="form-label">Nature Juridique</label>
                        <input type="text" class="form-control @error('nature_juridique') is-invalid @enderror" 
                               id="nature_juridique" name="nature_juridique" value="{{ old('nature_juridique', $article->nature_juridique) }}" 
                               placeholder="Nature juridique">
                        @error('nature_juridique')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="parcelle" class="form-label">Parcelle</label>
                        <input type="number" class="form-control @error('parcelle') is-invalid @enderror" 
                               id="parcelle" name="parcelle" value="{{ old('parcelle', $article->parcelle) }}" 
                               min="0" placeholder="Numéro de parcelle">
                        @error('parcelle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="lat" class="form-label">Latitude</label>
                        <input type="text" class="form-control @error('lat') is-invalid @enderror" 
                               id="lat" name="lat" value="{{ old('lat', $article->lat) }}" 
                               placeholder="Latitude">
                        @error('lat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="log" class="form-label">Longitude</label>
                        <input type="text" class="form-control @error('log') is-invalid @enderror" 
                               id="log" name="log" value="{{ old('log', $article->log) }}" 
                               placeholder="Longitude">
                        @error('log')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Volumes et Produits -->
            <div class="section-header mb-4">
                <h6 class="text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-calculator me-2"></i>Section 3: Détails
                </h6>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="superficie" class="form-label">Superficie</label>
                        <input type="text" class="form-control @error('superficie') is-invalid @enderror" 
                               id="superficie" name="superficie" value="{{ old('superficie', $article->superficie) }}" 
                               placeholder="Superficie">
                        @error('superficie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="bo_m3" class="form-label">BO (m³)</label>
                        <input type="number" class="form-control @error('bo_m3') is-invalid @enderror" 
                               id="bo_m3" name="bo_m3" value="{{ old('bo_m3', $article->bo_m3) }}" 
                               step="0.01" min="0" placeholder="Bois d'œuvre">
                        @error('bo_m3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="bi_m3" class="form-label">BI (m³)</label>
                        <input type="number" class="form-control @error('bi_m3') is-invalid @enderror" 
                               id="bi_m3" name="bi_m3" value="{{ old('bi_m3', $article->bi_m3) }}" 
                               step="0.01" min="0" placeholder="Bois d'industrie">
                        @error('bi_m3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="bf_st" class="form-label">BF/ST</label>
                        <input type="number" class="form-control @error('bf_st') is-invalid @enderror" 
                               id="bf_st" name="bf_st" value="{{ old('bf_st', $article->bf_st) }}" 
                               min="0" placeholder="Bois de feu/Stère">
                        @error('bf_st')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="tanin_t" class="form-label">Tanin (tonnes)</label>
                        <input type="number" class="form-control @error('tanin_t') is-invalid @enderror" 
                               id="tanin_t" name="tanin_t" value="{{ old('tanin_t', $article->tanin_t) }}" 
                               min="0" placeholder="Tanin en tonnes">
                        @error('tanin_t')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="fleur_acacia_t" class="form-label">Fleur d'Acacia (tonnes)</label>
                        <input type="number" class="form-control @error('fleur_acacia_t') is-invalid @enderror" 
                               id="fleur_acacia_t" name="fleur_acacia_t" value="{{ old('fleur_acacia_t', $article->fleur_acacia_t) }}" 
                               min="0" placeholder="Fleur d'acacia en tonnes">
                        @error('fleur_acacia_t')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="caroube_t" class="form-label">Caroube (tonnes)</label>
                        <input type="number" class="form-control @error('caroube_t') is-invalid @enderror" 
                               id="caroube_t" name="caroube_t" value="{{ old('caroube_t', $article->caroube_t) }}" 
                               min="0" placeholder="Caroube en tonnes">
                        @error('caroube_t')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="romarin_t" class="form-label">Romarin (tonnes)</label>
                        <input type="number" class="form-control @error('romarin_t') is-invalid @enderror" 
                               id="romarin_t" name="romarin_t" value="{{ old('romarin_t', $article->romarin_t) }}" 
                               min="0" placeholder="Romarin en tonnes">
                        @error('romarin_t')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="ps_t" class="form-label">PS (tonnes)</label>
                        <input type="number" class="form-control @error('ps_t') is-invalid @enderror" 
                               id="ps_t" name="ps_t" value="{{ old('ps_t', $article->ps_t) }}" 
                               min="0" placeholder="PS en tonnes">
                        @error('ps_t')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="liége_st" class="form-label">Liège (stères)</label>
                        <input type="number" class="form-control @error('liége_st') is-invalid @enderror" 
                               id="liége_st" name="liége_st" value="{{ old('liége_st', $article->liége_st) }}" 
                               min="0" placeholder="Liège en stères">
                        @error('liége_st')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="charbon_bois_ox" class="form-label">Charbon de Bois (ox)</label>
                        <input type="number" class="form-control @error('charbon_bois_ox') is-invalid @enderror" 
                               id="charbon_bois_ox" name="charbon_bois_ox" value="{{ old('charbon_bois_ox', $article->charbon_bois_ox) }}" 
                               min="0" placeholder="Charbon de bois en ox">
                        @error('charbon_bois_ox')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Informations Supplémentaires -->
            <div class="section-header mb-4">
                <h6 class="text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-plus-circle me-2"></i>Section 4: Suivi d'article
                </h6>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Sélectionner le type</option>
                            <option value="adjudication" {{ old('type', $article->type) == 'adjudication' ? 'selected' : '' }}>Adjudication</option>
                            <option value="appel_doffre" {{ old('type', $article->type) == 'appel_doffre' ? 'selected' : '' }}>Appel d'Offre</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="numero_adjudication" class="form-label">Numéro d'Adjudication</label>
                        <input type="text" class="form-control @error('numero_adjudication') is-invalid @enderror" 
                               id="numero_adjudication" name="numero_adjudication" value="{{ old('numero_adjudication', $article->numero_adjudication) }}" 
                               placeholder="Numéro d'adjudication">
                        @error('numero_adjudication')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="exploitant_id" class="form-label">Exploitant</label>
                        <select class="form-select @error('exploitant_id') is-invalid @enderror" id="exploitant_id" name="exploitant_id">
                            <option value="">Sélectionner un exploitant</option>
                            @foreach($exploitants as $exploitant)
                                <option value="{{ $exploitant->id }}" {{ old('exploitant_id', $article->exploitant_id) == $exploitant->id ? 'selected' : '' }}>
                                    {{ $exploitant->nom_complet ?? ($exploitant->nom . ' ' . $exploitant->prenom) }}
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
                        <label for="fourniture_mise_charge" class="form-label">Fourniture Mise en Charge (DH)</label>
                        <input type="number" class="form-control @error('fourniture_mise_charge') is-invalid @enderror" 
                               id="fourniture_mise_charge" name="fourniture_mise_charge" value="{{ old('fourniture_mise_charge', $article->fourniture_mise_charge) }}" 
                               step="0.01" min="0" placeholder="Fourniture mise en charge">
                        @error('fourniture_mise_charge')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="prix_de_retrait" class="form-label">Prix de Retrait (DH) *</label>
                        <input type="number" class="form-control @error('prix_de_retrait') is-invalid @enderror" 
                               id="prix_de_retrait" name="prix_de_retrait" value="{{ old('prix_de_retrait', $article->prix_de_retrait) }}" 
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
                               id="prix_vente" name="prix_vente" value="{{ old('prix_vente', $article->prix_vente) }}" 
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
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('dc') is-invalid @enderror" 
                                           type="checkbox" name="dc" id="dc" value="1" 
                                           {{ old('dc', $article->dc) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dc">
                                        DC
                                    </label>
                                    @error('dc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('rc') is-invalid @enderror" 
                                           type="checkbox" name="rc" id="rc" value="1" 
                                           {{ old('rc', $article->rc) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rc">
                                        RC
                                    </label>
                                    @error('rc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input @error('invendu') is-invalid @enderror" 
                                            type="checkbox" name="invendu" id="invendu" value="1" 
                                            {{ old('invendu', $article->invendu) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="invendu">
                                            Invendu
                                        </label>
                                        @error('invendu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date_de_resiliation" class="form-label">Date de Résiliation</label>
                        <input type="date" class="form-control @error('date_de_resiliation') is-invalid @enderror" 
                               id="date_de_resiliation" name="date_de_resiliation" 
                               value="{{ old('date_de_resiliation', $article->date_de_resiliation) }}">
                        @error('date_de_resiliation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date_de_decheance" class="form-label">Date de Déchéance</label>
                        <input type="date" class="form-control @error('date_de_decheance') is-invalid @enderror" 
                               id="date_de_decheance" name="date_de_decheance" 
                               value="{{ old('date_de_decheance', $article->date_de_decheance) }}">
                        @error('date_de_decheance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Observations et Validation -->
            <div class="section-header mb-4">
                <h6 class="text-primary mb-3 border-bottom pb-2">
                    <i class="fas fa-clipboard me-2"></i>Observations et Validation
                </h6>
            </div>
            <div class="mb-4">
                <div class="mb-3">
                    <label for="observations" class="form-label">Observations</label>
                    <textarea class="form-control @error('observations') is-invalid @enderror" 
                              id="observations" name="observations" rows="3" 
                              placeholder="Observations supplémentaires...">{{ old('observations', $article->observations) }}</textarea>
                    @error('observations')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input @error('is_validated') is-invalid @enderror" 
                           type="checkbox" name="is_validated" id="is_validated" value="1" 
                           {{ old('is_validated', $article->is_validated) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_validated">
                        Article validé
                    </label>
                    @error('is_validated')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Mettre à jour l'Article
                </button>
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.section-header h6 {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-header h6 i {
    color: #007bff;
}

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
</style>
@endsection 