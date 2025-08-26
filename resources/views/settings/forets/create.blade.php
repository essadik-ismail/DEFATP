@extends('layouts.app')

@section('title', 'Nouvelle Forêt - SylvaNet')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-tree me-2 text-primary"></i>Nouvelle Forêt
                </h1>
                <a href="{{ route('settings.forets') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Créer une nouvelle forêt</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.forets.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="foret" class="form-label">Nom de la Forêt *</label>
                            <input type="text" class="form-control @error('foret') is-invalid @enderror" 
                                   id="foret" name="foret" value="{{ old('foret') }}" required>
                            @error('foret')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer
                            </button>
                            <a href="{{ route('settings.forets') }}" class="btn btn-secondary">
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
