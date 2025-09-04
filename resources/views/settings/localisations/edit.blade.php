@extends('layouts.app')

@section('title', 'Modifier la localisation')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Modifier la localisation</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.localisations.update', $localisation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="CODE">Code *</label>
                                    <input type="text" class="form-control @error('CODE') is-invalid @enderror" 
                                           id="CODE" name="CODE" value="{{ old('CODE', $localisation->CODE) }}" required>
                                    @error('CODE')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="DRANEF">DRANEF *</label>
                                    <input type="text" class="form-control @error('DRANEF') is-invalid @enderror" 
                                           id="DRANEF" name="DRANEF" value="{{ old('DRANEF', $localisation->DRANEF) }}" required>
                                    @error('DRANEF')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="DPANEF">DPANEF *</label>
                                    <input type="text" class="form-control @error('DPANEF') is-invalid @enderror" 
                                           id="DPANEF" name="DPANEF" value="{{ old('DPANEF', $localisation->DPANEF) }}" required>
                                    @error('DPANEF')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ENTITE">Entité</label>
                                    <input type="text" class="form-control @error('ENTITE') is-invalid @enderror" 
                                           id="ENTITE" name="ENTITE" value="{{ old('ENTITE', $localisation->ENTITE) }}">
                                    @error('ENTITE')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                <a href="{{ route('settings.localisations') }}" class="btn btn-secondary">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
