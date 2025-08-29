@extends('layouts.app')

@section('title', 'Créer un Utilisateur')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-plus text-primary me-2"></i>
                Créer un Utilisateur
            </h1>
            <p class="text-muted">Ajouter un nouvel utilisateur au système</p>
        </div>
        <div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- User Creation Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Informations de l'Utilisateur
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-id-card me-2"></i>Informations Personnelles
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="ppr" class="form-label">PPR <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ppr') is-invalid @enderror" 
                                           id="ppr" name="ppr" value="{{ old('ppr') }}" required>
                                    @error('ppr')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Security & Roles -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>Sécurité et Rôles
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                            <i class="fas fa-eye" id="passwordIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimum 8 caractères</small>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>

                                <div class="mb-3">
                                    <label for="roles" class="form-label">Rôles</label>
                                    <select class="form-select @error('roles') is-invalid @enderror" 
                                            id="roles" name="roles[]" multiple>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" 
                                                {{ in_array($role->name, old('roles', [])) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs rôles</small>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-image me-2"></i>Photo de Profil
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image de profil</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Formats acceptés: JPG, PNG, GIF. Taille max: 2MB</small>
                                </div>

                                <div class="mb-3">
                                    <div id="imagePreview" class="d-none">
                                        <img id="previewImg" src="" alt="Aperçu" 
                                             class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Créer l'Utilisateur
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar with Help and Information -->
        <div class="col-lg-4">
            <!-- Help Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-question-circle me-2"></i>Aide
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-info">Champs obligatoires</h6>
                        <p class="small text-muted">Les champs marqués d'un <span class="text-danger">*</span> sont obligatoires.</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-info">Mot de passe</h6>
                        <p class="small text-muted">Le mot de passe doit contenir au moins 8 caractères.</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-info">Rôles</h6>
                        <p class="small text-muted">Sélectionnez un ou plusieurs rôles pour définir les permissions de l'utilisateur.</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-info">Image de profil</h6>
                        <p class="small text-muted">L'image sera automatiquement redimensionnée et optimisée.</p>
                    </div>
                </div>
            </div>

            <!-- Available Roles Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-shield-alt me-2"></i>Rôles Disponibles
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($roles as $role)
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-success me-2">{{ $role->name }}</span>
                            <small class="text-muted">{{ $role->permissions->count() }} permissions</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

// Image preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('d-none');
    }
});

// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (password !== confirmation) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    
    if (password !== confirmation) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas');
        return false;
    }
});
</script>
@endpush

@push('styles')
<style>
.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.form-control:focus,
.form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.input-group .btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5em 0.75em;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e3e6f0;
}

.btn {
    border-radius: 0.35rem;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-1px);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
    border: none;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #545b62 0%, #3d4449 100%);
    transform: translateY(-1px);
}
</style>
@endpush
