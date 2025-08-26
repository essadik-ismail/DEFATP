@extends('layouts.app')

@section('title', 'Nouvelle Situation Administrative - SylvaNet')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-building me-2 text-warning"></i>Nouvelle Situation Administrative
                </h1>
                <a href="{{ route('settings.situation-administratives') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Créer une nouvelle situation administrative</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.situation-administratives.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="commune" class="form-label">Commune *</label>
                                    <input type="text" class="form-control @error('commune') is-invalid @enderror" 
                                           id="commune" name="commune" value="{{ old('commune') }}" required>
                                    @error('commune')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="province" class="form-label">Province *</label>
                                    <input type="text" class="form-control @error('province') is-invalid @enderror" 
                                           id="province" name="province" value="{{ old('province') }}" required>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Créer
                            </button>
                            <a href="{{ route('settings.situation-administratives') }}" class="btn btn-secondary">
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
