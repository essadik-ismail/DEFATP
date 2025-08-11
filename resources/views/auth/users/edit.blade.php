@extends('layouts.app')

@section('title', 'Modifier l\'Utilisateur')

@section('page-actions')
    <a href="{{ route('auth.users.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
<div class="content-card">
    <div class="card-header">
        <div class="header-content">
            <h4 class="card-title">
                <i class="fas fa-user-edit me-2"></i>
                Modifier l'Utilisateur
            </h4>
            <p class="card-subtitle">Modifiez les informations de l'utilisateur {{ $user->name }}</p>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('auth.users.update', $user) }}" method="POST" class="form-modern">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="name" class="form-label">
                        Nom complet <span class="required">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           placeholder="Ex: Jean Dupont" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="ppr" class="form-label">
                        PPR <span class="required">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('ppr') is-invalid @enderror" 
                           id="ppr" 
                           name="ppr" 
                           value="{{ old('ppr', $user->ppr) }}" 
                           placeholder="Ex: 12345678" 
                           required>
                    @error('ppr')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>
                        Le PPR doit être unique dans le système
                    </div>
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="password" class="form-label">
                        Nouveau mot de passe
                    </label>
                    <div class="password-input">
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Laisser vide pour ne pas changer">
                        <button class="password-toggle" 
                                type="button" 
                                onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="fas fa-shield-alt me-1"></i>
                        Le mot de passe doit contenir au moins 8 caractères
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        Confirmer le nouveau mot de passe
                    </label>
                    <div class="password-input">
                        <input type="password" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Confirmer le nouveau mot de passe">
                        <button class="password-toggle" 
                                type="button" 
                                onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye" id="password_confirmation-icon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Mettre à Jour
                </button>
                <a href="{{ route('auth.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content-card {
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .card-header {
        padding: 2rem;
        border-bottom: 1px solid var(--border-color);
        background: var(--card-header-bg);
    }

    .header-content {
        text-align: center;
    }

    .card-title {
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-subtitle {
        margin: 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .card-body {
        padding: 2rem;
    }

    .form-modern {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .required {
        color: var(--danger-color);
        font-weight: 600;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 0.75rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: var(--card-bg);
        color: var(--text-primary);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.1);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
    }

    .invalid-feedback {
        color: var(--danger-color);
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .form-text {
        color: var(--text-secondary);
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .password-input {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-input .form-control {
        padding-right: 3rem;
    }

    .password-toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .password-toggle:hover {
        color: var(--text-primary);
        background: var(--bg-secondary);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }



    @media (max-width: 768px) {
        .card-header, .card-body {
            padding: 1.5rem;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush 