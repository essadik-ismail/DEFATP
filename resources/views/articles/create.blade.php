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
            
            <!-- 1. Informations Générales Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-info-circle text-info"></i> Informations Générales</h6>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="numero" class="form-label">Numéro</label>
                        <input type="text" class="form-control @error('numero') is-invalid @enderror" 
                               id="numero" name="numero" value="{{ old('numero') }}" maxlength="255">
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="annee" class="form-label">Année <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('annee') is-invalid @enderror" 
                               id="annee" name="annee" value="{{ old('annee', date('Y')) }}" min="2000" max="2100" required>
                        @error('annee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date') is-invalid @enderror" 
                               id="date" name="date" value="{{ old('date') }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 2. Localisation Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-map-marker-alt text-success"></i> Localisation</h6>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="parcelle" class="form-label">Parcelle</label>
                        <input type="number" min="0" class="form-control @error('parcelle') is-invalid @enderror" 
                               id="parcelle" name="parcelle" value="{{ old('parcelle') }}">
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
            </div>

            <!-- 3. Spécifications Techniques Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="section-title"><i class="fas fa-cogs text-primary"></i> Spécifications Techniques</h6>
                </div>
                
                <!-- Lot -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_lot" name="has_lot" value="1">
                            <label class="form-check-label" for="has_lot">Lot</label>
                        </div>
                        <input type="text" class="form-control mt-2 @error('lot') is-invalid @enderror" 
                               id="lot" name="lot" value="{{ old('lot') }}" style="display: none;">
                        @error('lot')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Superficie -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_superficie" name="has_superficie" value="1">
                            <label class="form-check-label" for="has_superficie">Superficie</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('superficie') is-invalid @enderror" 
                               id="superficie" name="superficie" value="{{ old('superficie') }}" style="display: none;">
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

                <!-- Liège (st) -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="has_liege_st" name="has_liege_st" value="1">
                            <label class="form-check-label" for="has_liege_st">Liège (st)</label>
                        </div>
                        <input type="number" step="0.01" min="0" class="form-control mt-2 @error('liege_st') is-invalid @enderror" 
                               id="liege_st" name="liege_st" value="{{ old('liege_st') }}" style="display: none;">
                        @error('liege_st')
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

            <!-- Suivi de Contract Button -->
            <div class="row mb-4">
                <div class="col-12">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#contractModal">
                        <i class="fas fa-file-contract"></i> Suivi de Contract
                    </button>
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

<!-- Contract Modal -->
<div class="modal fade" id="contractModal" tabindex="-1" aria-labelledby="contractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contractModalLabel">
                    <i class="fas fa-file-contract text-warning"></i> Suivi de Contract
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="invendu" class="form-label">Invendu</label>
                            <input type="number" step="0.01" min="0" class="form-control" 
                                   id="invendu" name="invendu" value="{{ old('invendu') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exploitant_id" class="form-label">Exploitant</label>
                            <select class="form-select" id="exploitant_id" name="exploitant_id">
                                <option value="">Sélectionner un exploitant</option>
                                                            @foreach($exploitants as $exploitant)
                                <option value="{{ $exploitant->id }}" {{ old('exploitant_id') == $exploitant->id ? 'selected' : '' }}>
                                    {{ $exploitant->nom_complet }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Sélectionner</option>
                                <option value="appel_doffre" {{ old('type') == 'appel_doffre' ? 'selected' : '' }}>Appel d'offre</option>
                                <option value="adjudication" {{ old('type') == 'adjudication' ? 'selected' : '' }}>Adjudication</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="session_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="session_date" name="session_date" value="{{ old('session_date') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="session_numero" class="form-label">Numéro</label>
                            <input type="text" class="form-control" id="session_numero" name="session_numero" value="{{ old('session_numero') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nature_juridique" class="form-label">Nature Juridique</label>
                            <input type="text" class="form-control" id="nature_juridique" name="nature_juridique" value="{{ old('nature_juridique') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="adjudicatire" class="form-label">Adjudicataire</label>
                            <input type="text" class="form-control" id="adjudicatire" name="adjudicatire" value="{{ old('adjudicatire') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="dc" name="dc" value="1" {{ old('dc') ? 'checked' : '' }}>
                                <label class="form-check-label" for="dc">DC</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="rc" name="rc" value="1" {{ old('rc') ? 'checked' : '' }}>
                                <label class="form-check-label" for="rc">RC</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date_de_resiliation" class="form-label">Date de Résiliation</label>
                            <input type="date" class="form-control" id="date_de_resiliation" name="date_de_resiliation" value="{{ old('date_de_resiliation') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date_de_decheance" class="form-label">Date de Déchéance</label>
                            <input type="date" class="form-control" id="date_de_decheance" name="date_de_decheance" value="{{ old('date_de_decheance') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="observations" class="form-label">Observations</label>
                            <textarea class="form-control" id="observations" name="observations" rows="3">{{ old('observations') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="saveContractData()">Sauvegarder</button>
            </div>
        </div>
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

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    border-radius: 15px 15px 0 0;
}

.modal-body {
    padding: 25px;
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
        'has_lot', 'has_superficie', 'has_bo_m3', 'has_bi_m3', 'has_bf_st', 
        'has_tanin_t', 'has_fleur_acacia_t', 'has_caroube_t', 'has_romarin_t', 
        'has_ps_t', 'has_liege_st', 'has_charbon_bois_ox'
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

function saveContractData() {
    // Get all form data from modal
    const modalData = {
        invendu: document.getElementById('invendu').value,
        exploitant_id: document.getElementById('exploitant_id').value,
        type: document.getElementById('type').value,
        session_date: document.getElementById('session_date').value,
        session_numero: document.getElementById('session_numero').value,
        nature_juridique: document.getElementById('nature_juridique').value,
        adjudicatire: document.getElementById('adjudicatire').value,
        dc: document.getElementById('dc').checked ? 1 : 0,
        rc: document.getElementById('rc').checked ? 1 : 0,
        date_de_resiliation: document.getElementById('date_de_resiliation').value,
        date_de_decheance: document.getElementById('date_de_decheance').value,
        observations: document.getElementById('observations').value
    };

    // Store data in hidden fields or session storage
    Object.keys(modalData).forEach(function(key) {
        let hiddenField = document.getElementById('hidden_' + key);
        if (!hiddenField) {
            hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.id = 'hidden_' + key;
            hiddenField.name = key;
            document.getElementById('articleForm').appendChild(hiddenField);
        }
        hiddenField.value = modalData[key];
    });

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('contractModal'));
    modal.hide();

    // Show success message
    alert('Données du contrat sauvegardées avec succès!');
}
</script>
@endsection 