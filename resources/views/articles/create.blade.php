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
                    <form action="{{ route('articles.store') }}" method="POST" id="articleForm" novalidate>
                        @csrf
                        
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
                                           id="annee" name="annee" value="{{ old('annee', date('Y')) }}" 
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
                                           value="{{ old('date_adjudication') }}" required>
                                    @error('date_adjudication')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="lot" class="form-label">Lot</label>
                                    <input type="number" class="form-control @error('lot') is-invalid @enderror" 
                                           id="lot" name="lot" value="{{ old('lot') }}" 
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
                                           id="numero" name="numero" value="{{ old('numero') }}" 
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
                                            <option value="{{ $localisation->id }}" {{ old('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                                {{ $localisation->CODE }} - {{ $localisation->DRANEF }} - {{ $localisation->ENTITE }}
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
                                    <label for="nature_juridique" class="form-label">Nature Juridique</label>
                                    <input type="text" class="form-control @error('nature_juridique') is-invalid @enderror" 
                                           id="nature_juridique" name="nature_juridique" value="{{ old('nature_juridique') }}" 
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
                                           id="parcelle" name="parcelle" value="{{ old('parcelle') }}" 
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
                                           id="lat" name="lat" value="{{ old('lat') }}" 
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
                                           id="log" name="log" value="{{ old('log') }}" 
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

                        <div class="row mb-4">

                         
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="superficie" class="form-label">Superficie</label>
                                    <input type="text" class="form-control @error('superficie') is-invalid @enderror" 
                                           id="superficie" name="superficie" value="{{ old('superficie') }}" 
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
                                           id="bo_m3" name="bo_m3" value="{{ old('bo_m3') }}" 
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
                                           id="bi_m3" name="bi_m3" value="{{ old('bi_m3') }}" 
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
                                           id="bf_st" name="bf_st" value="{{ old('bf_st') }}" 
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
                                           id="tanin_t" name="tanin_t" value="{{ old('tanin_t') }}" 
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
                                           id="fleur_acacia_t" name="fleur_acacia_t" value="{{ old('fleur_acacia_t') }}" 
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
                                           id="caroube_t" name="caroube_t" value="{{ old('caroube_t') }}" 
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
                                           id="romarin_t" name="romarin_t" value="{{ old('romarin_t') }}" 
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
                                           id="ps_t" name="ps_t" value="{{ old('ps_t') }}" 
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
                                           id="liége_st" name="liége_st" value="{{ old('liége_st') }}" 
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
                                           id="charbon_bois_ox" name="charbon_bois_ox" value="{{ old('charbon_bois_ox') }}" 
                                           min="0" placeholder="Charbon de bois en ox">
                                    @error('charbon_bois_ox')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="mb-3">
                                <label for="charges_du_lot" class="form-label">charges du lot</label>
                                <textarea class="form-control @error('charges_du_lot') is-invalid @enderror" 
                                          id="charges_du_lot" name="charges_du_lot" rows="3" 
                                          placeholder="charges_du_lot supplémentaires...">{{ old('charges_du_lot') }}</textarea>
                                @error('charges_du_lot')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-2"></i><span class="btn-text">Créer l'Article</span>
                                </button>
                                <!-- <button type="submit" class="btn btn-success" id="submitAndNextBtn" name="action" value="create_and_next">
                                    <i class="fas fa-plus me-2"></i><span class="btn-text">Créer et Ajouter un Autre</span>
                                </button> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('articleForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn ? submitBtn.querySelector('.btn-text') : null;
    
    // Traditional form submission (no AJAX)
    console.log('Form element:', form);
    console.log('Submit button:', submitBtn);
    console.log('Button text element:', btnText);
    
    // Simple field validation
    function validateField(field) {
        const value = field.value.trim();
        
        // Remove existing validation classes
        field.classList.remove('is-valid', 'is-invalid');
        
        if (field.hasAttribute('required') && value === '') {
            field.classList.add('is-invalid');
            return false;
        }
        
        if (value !== '') {
            field.classList.add('is-valid');
        }
        
        return true;
    }
    
    // Real-time validation
    form.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });
        
        field.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
    
    // Simple form validation only
    form.addEventListener('submit', function(e) {
        // Basic validation
        const fields = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        fields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
            return false;
        }
        
        // Allow normal form submission
        return true;
    });
    
    // Simple date and number input handling
    const dateInputs = form.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateField(this);
        });
    });
    
    const numberInputs = form.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('input', function() {
            validateField(this);
        });
    });
});
</script>

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
</style>
@endsection 