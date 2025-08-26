@extends('layouts.app')

@section('title', 'Nouvelle Nature de Coupe - SylvaNet')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-cut me-2 text-secondary"></i>Nouvelle Nature de Coupe
                </h1>
                <a href="{{ route('settings.nature-de-coupes') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Créer une nouvelle nature de coupe</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.nature-de-coupes.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nature_de_coupe" class="form-label">Nature de Coupe *</label>
                            <input type="text" class="form-control @error('nature_de_coupe') is-invalid @enderror" 
                                   id="nature_de_coupe" name="nature_de_coupe" value="{{ old('nature_de_coupe') }}" required>
                            @error('nature_de_coupe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-save me-2"></i>Créer
                            </button>
                            <a href="{{ route('settings.nature-de-coupes') }}" class="btn btn-secondary">
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
