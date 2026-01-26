@extends('layouts.app')

@section('title', 'Modifier l\'Utilisateur')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
<li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-user-edit text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">Modifier l'Utilisateur</h1>
                        <p class="text-gray-600 text-lg mt-2">Modifier les informations de {{ $user->name }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('users.show', $user) }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-eye"></i>
                        Voir
                    </a>
                    <a href="{{ route('users.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <!-- User Edit Form -->
            <x-card 
                title="Modifier l'Utilisateur" 
                subtitle="Modifiez les informations de {{ $user->name }}"
                variant="gradient"
                color="orange"
                icon="fas fa-user"
                padding="normal"
            >
                <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div>
                            <h6 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-id-card text-orange-500"></i>
                                Informations Personnelles
                            </h6>
                                
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user text-orange-500 mr-2"></i>
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors @error('name') border-red-500 @enderror"
                                >
                                @error('name')
                                    <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="ppr" class="form-label">PPR <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ppr') is-invalid @enderror" 
                                           id="ppr" name="ppr" value="{{ old('ppr', $user->ppr) }}" required>
                                    @error('ppr')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label">Rôle Organisationnel</label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" name="role">
                                        <option value="">Sélectionner un rôle</option>
                                        <option value="dg" {{ old('role', $user->role) == 'dg' ? 'selected' : '' }}>DG (Directeur Général)</option>
                                        <option value="dc" {{ old('role', $user->role) == 'dc' ? 'selected' : '' }}>DC (Directeur Central)</option>
                                        <option value="departement" {{ old('role', $user->role) == 'departement' ? 'selected' : '' }}>Département</option>
                                        <option value="administrateur" {{ old('role', $user->role) == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                                        <option value="draned" {{ old('role', $user->role) == 'draned' ? 'selected' : '' }}>DRANED</option>
                                        <option value="dpanef" {{ old('role', $user->role) == 'dpanef' ? 'selected' : '' }}>DPANEF</option>
                                        <option value="entite" {{ old('role', $user->role) == 'entite' ? 'selected' : '' }}>Entité</option>
                                    </select>
                                    @error('role')
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
                                    <label for="password" class="form-label">Nouveau mot de passe</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" placeholder="Laisser vide pour ne pas changer">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                            <i class="fas fa-eye" id="passwordIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Laissez vide pour conserver le mot de passe actuel</small>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Confirmez le nouveau mot de passe">
                                </div>

                                <div class="mb-3">
                                    <label for="roles" class="form-label">Rôles</label>
                                    <select class="form-select @error('roles') is-invalid @enderror" 
                                            id="roles" name="roles[]" multiple>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" 
                                                {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'selected' : '' }}>
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
                                
                                <!-- Current Image -->
                                @if($user->image)
                                <div class="mb-3">
                                    <label class="form-label">Image actuelle</label>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $user->image) }}" 
                                             alt="{{ $user->name }}" 
                                             class="img-thumbnail me-3" 
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                        <div>
                                            <p class="mb-1"><strong>Image actuelle</strong></p>
                                            <small class="text-muted">{{ $user->image }}</small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Nouvelle image de profil</label>
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
                                        <i class="fas fa-save me-2"></i>Mettre à jour
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar with User Info and Help -->
        <div class="col-lg-4">
            <!-- Current User Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-user me-2"></i>Informations Actuelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-info">Statut</h6>
                        @if($user->is_deleted)
                            <span class="badge bg-danger">Inactif</span>
                        @else
                            <span class="badge bg-success">Actif</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-info">Rôles actuels</h6>
                        @forelse($user->roles as $role)
                            <span class="badge bg-success me-1 mb-1">{{ $role->name }}</span>
                        @empty
                            <span class="badge bg-light text-dark">Aucun rôle</span>
                        @endforelse
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-info">Membre depuis</h6>
                        <p class="text-muted mb-0">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    
                    @if($user->updated_at != $user->created_at)
                    <div class="mb-3">
                        <h6 class="text-info">Dernière modification</h6>
                        <p class="text-muted mb-0">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Help Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-question-circle me-2"></i>Aide
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-warning">Mot de passe</h6>
                        <p class="small text-muted">Laissez le champ mot de passe vide pour conserver l'ancien.</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-warning">Rôles</h6>
                        <p class="small text-muted">Les rôles définissent les permissions de l'utilisateur.</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-warning">Image</h6>
                        <p class="small text-muted">Choisissez une nouvelle image pour remplacer l'ancienne.</p>
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
    
    if (password && confirmation && password !== confirmation) {
        this.setCustomValidity('Les mots de passe ne correspondent pas');
    } else {
        this.setCustomValidity('');
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    
    if (password && confirmation && password !== confirmation) {
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

.btn-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border: none;
}

.btn-info:hover {
    background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
    transform: translateY(-1px);
}
</style>
@endpush
