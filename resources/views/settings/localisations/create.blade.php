@extends('layouts.app')

@section('title', 'Nouvelle Localisation - SylvaNet')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-map-marker-alt me-2 text-info"></i>Nouvelle Localisation
                </h1>
                <a href="{{ route('settings.localisations') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Créer une nouvelle localisation</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.localisations.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="CODE" class="form-label">Code *</label>
                                    <input type="text" class="form-control @error('CODE') is-invalid @enderror" 
                                           id="CODE" name="CODE" value="{{ old('CODE') }}" required>
                                    @error('CODE')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="DRANEF" class="form-label">DRANEF *</label>
                                    <input type="text" class="form-control @error('DRANEF') is-invalid @enderror" 
                                           id="DRANEF" name="DRANEF" value="{{ old('DRANEF') }}" required>
                                    @error('DRANEF')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ENTITE" class="form-label">Entité *</label>
                                    <input type="text" class="form-control @error('ENTITE') is-invalid @enderror" 
                                           id="ENTITE" name="ENTITE" value="{{ old('ENTITE') }}" required>
                                    @error('ENTITE')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-save me-2"></i>Créer
                            </button>
                            <a href="{{ route('settings.localisations') }}" class="btn btn-secondary">
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
