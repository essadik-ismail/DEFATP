@extends('layouts.app')

@section('title', 'Créer un Utilisateur')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
<li class="bc-item active">Nouveau</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    <x-page-header
        title="Créer un utilisateur"
        subtitle="Ajouter un nouvel utilisateur au système"
        icon="fas fa-user-plus"
        :backRoute="route('users.index')"
        backText="Retour"
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- User Creation Form -->
            <x-card 
                title="Informations de l'Utilisateur" 
                subtitle="Remplissez les informations pour créer un nouvel utilisateur"
                variant="gradient"
                color="green"
                icon="fas fa-user"
                padding="normal"
            >
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="userCreateForm" novalidate>
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div>
                            <h6 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-id-card text-green-500"></i>
                                Informations Personnelles
                            </h6>
                                
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user text-green-500 mr-2"></i>
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    placeholder="Ex: Jean Dupont" 
                                    required 
                                    autocomplete="name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('name') border-red-500 @enderror"
                                >
                                @error('name')
                                    <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>Nom complet de l'utilisateur
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-green-500 mr-2"></i>
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    placeholder="Ex: jean.dupont@example.com" 
                                    required 
                                    autocomplete="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('email') border-red-500 @enderror"
                                >
                                @error('email')
                                    <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>Adresse email valide requise
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="ppr" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-id-card text-green-500 mr-2"></i>
                                    PPR <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="ppr" 
                                    name="ppr" 
                                    value="{{ old('ppr') }}" 
                                    placeholder="Ex: 12345678" 
                                    required 
                                    pattern="[0-9]{8}" 
                                    title="Le PPR doit contenir exactement 8 chiffres"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('ppr') border-red-500 @enderror"
                                >
                                @error('ppr')
                                    <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>Numéro de personnel (8 chiffres)
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-tag text-green-500 mr-2"></i>
                                    Rôle Organisationnel
                                </label>
                                <select 
                                    id="role" 
                                    name="role" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('role') border-red-500 @enderror"
                                >
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="dg" {{ old('role') == 'dg' ? 'selected' : '' }}>DG (Directeur Général)</option>
                                    <option value="dc" {{ old('role') == 'dc' ? 'selected' : '' }}>DC (Directeur Central)</option>
                                    <option value="departement" {{ old('role') == 'departement' ? 'selected' : '' }}>Département</option>
                                    <option value="administrateur" {{ old('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="draned" {{ old('role') == 'draned' ? 'selected' : '' }}>DRANED</option>
                                    <option value="dpanef" {{ old('role') == 'dpanef' ? 'selected' : '' }}>DPANEF</option>
                                    <option value="entite" {{ old('role') == 'entite' ? 'selected' : '' }}>Entité</option>
                                </select>
                                @error('role')
                                    <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>Rôle organisationnel de l'utilisateur
                                </div>
                            </div>
                        </div>

                        <!-- Security & Roles -->
                        <div>
                            <h6 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-shield-alt text-green-500"></i>
                                Sécurité et Rôles
                            </h6>
                                
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-green-500 mr-2"></i>
                                    Mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    minlength="8"
                                    placeholder="Minimum 8 caractères"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('password') border-red-500 @enderror"
                                >
                                @error('password')
                                    <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-shield-alt"></i>Minimum 8 caractères avec lettres et chiffres
                                </div>
                                <div class="password-strength mt-2" id="passwordStrength" style="display: none;">
                                    <div class="w-full bg-gray-200 rounded-full h-1">
                                        <div class="bg-green-500 h-1 rounded-full transition-all duration-300" id="strengthBar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-gray-500 mt-1 block" id="strengthText">Force du mot de passe</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-green-500 mr-2"></i>
                                    Confirmer le mot de passe <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    required
                                    placeholder="Répétez le mot de passe"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                >
                                <div class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>Doit correspondre au mot de passe
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="roles" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-tag text-green-500 mr-2"></i>
                                    Rôles
                                </label>
                                <select 
                                    id="roles" 
                                    name="roles[]" 
                                    multiple
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('roles') border-red-500 @enderror"
                                >
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" 
                                            {{ in_array($role->name, old('roles', [])) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </div>
                                @enderror
                                <div class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs rôles
                                </div>
                            </div>
                            </div>
                        </div>

                    <!-- Profile Image -->
                    <div class="mt-8">
                        <h6 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-image text-green-500"></i>
                            Photo de Profil
                        </h6>
                        
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-upload text-green-500 mr-2"></i>
                                Image de profil
                            </label>
                            <input 
                                type="file" 
                                id="image" 
                                name="image" 
                                accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('image') border-red-500 @enderror"
                            >
                            @error('image')
                                <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                </div>
                            @enderror
                            <div class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>Formats acceptés: JPG, PNG, GIF. Taille max: 2MB
                            </div>
                        </div>

                        <div class="mb-4">
                            <div id="imagePreview" class="hidden">
                                <img id="previewImg" src="" alt="Aperçu" 
                                     class="rounded-lg border border-gray-300" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div>
                                <a href="{{ route('users.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                                    <i class="fas fa-times"></i>
                                    Annuler
                                </a>
                            </div>
                            <div class="flex gap-3">
                                <button 
                                    type="submit" 
                                    id="submitBtn"
                                    class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2"
                                >
                                    <i class="fas fa-save"></i>
                                    <span class="btn-text">Créer l'Utilisateur</span>
                                </button>
                                <button 
                                    type="submit" 
                                    id="submitAndNextBtn" 
                                    name="action" 
                                    value="create_and_next"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2"
                                >
                                    <i class="fas fa-plus"></i>
                                    <span class="btn-text">Créer et Ajouter un Autre</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>

        <!-- Sidebar with Help and Information -->
        <div class="lg:col-span-1">
            <!-- Help Card -->
            <x-card 
                title="Aide" 
                subtitle="Informations utiles pour créer un utilisateur"
                variant="colored"
                color="blue"
                icon="fas fa-question-circle"
                padding="normal"
            >
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h6 class="text-blue-800 font-semibold mb-2 flex items-center gap-2">
                            <i class="fas fa-asterisk text-blue-600"></i>
                            Champs obligatoires
                        </h6>
                        <p class="text-blue-700 text-sm">Les champs marqués d'un <span class="text-red-500 font-semibold">*</span> sont obligatoires.</p>
                    </div>
                    
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <h6 class="text-green-800 font-semibold mb-2 flex items-center gap-2">
                            <i class="fas fa-lock text-green-600"></i>
                            Mot de passe
                        </h6>
                        <p class="text-green-700 text-sm">Le mot de passe doit contenir au moins 8 caractères avec lettres et chiffres.</p>
                    </div>
                    
                    <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <h6 class="text-purple-800 font-semibold mb-2 flex items-center gap-2">
                            <i class="fas fa-user-tag text-purple-600"></i>
                            Rôles
                        </h6>
                        <p class="text-purple-700 text-sm">Sélectionnez un ou plusieurs rôles pour définir les permissions de l'utilisateur.</p>
                    </div>
                    
                    <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                        <h6 class="text-orange-800 font-semibold mb-2 flex items-center gap-2">
                            <i class="fas fa-image text-orange-600"></i>
                            Image de profil
                        </h6>
                        <p class="text-orange-700 text-sm">L'image sera automatiquement redimensionnée et optimisée.</p>
                    </div>
                </div>
            </x-card>

            <!-- Available Roles Card -->
            <x-card 
                title="Rôles Disponibles" 
                subtitle="Liste des rôles que vous pouvez attribuer"
                variant="colored"
                color="green"
                icon="fas fa-shield-alt"
                padding="normal"
            >
                <div class="space-y-3">
                    @foreach($roles as $role)
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                            <span class="px-3 py-1 bg-green-600 text-white text-sm font-medium rounded-full">{{ $role->name }}</span>
                            <span class="text-green-700 text-sm">{{ $role->permissions->count() }} permissions</span>
                        </div>
                    @endforeach
                </div>
            </x-card>
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

/* Password strength indicator */
.password-strength .progress-bar {
    transition: all 0.3s ease;
}

.password-strength .progress-bar.bg-danger {
    background-color: #dc3545 !important;
}

.password-strength .progress-bar.bg-warning {
    background-color: #ffc107 !important;
}

.password-strength .progress-bar.bg-success {
    background-color: #28a745 !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userCreateForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn ? submitBtn.querySelector('.btn-text') : null;
    
    // Traditional form submission (no AJAX)
    console.log('Form element:', form);
    console.log('Submit button:', submitBtn);
    console.log('Button text element:', btnText);
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');
    const progressBar = passwordStrength.querySelector('.progress-bar');
    
    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = '';
        
        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]/)) strength += 1;
        if (password.match(/[A-Z]/)) strength += 1;
        if (password.match(/[0-9]/)) strength += 1;
        if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
        
        const percentage = (strength / 5) * 100;
        
        if (strength < 2) {
            feedback = 'Très faible';
            progressBar.className = 'progress-bar bg-danger';
        } else if (strength < 3) {
            feedback = 'Faible';
            progressBar.className = 'progress-bar bg-warning';
        } else if (strength < 4) {
            feedback = 'Moyen';
            progressBar.className = 'progress-bar bg-warning';
        } else {
            feedback = 'Fort';
            progressBar.className = 'progress-bar bg-success';
        }
        
        progressBar.style.width = percentage + '%';
        strengthText.textContent = `Force du mot de passe: ${feedback}`;
        
        return strength >= 3;
    }
    
    // Password visibility toggle
    window.togglePassword = function(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + 'Icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };
    
    // Real-time password strength checking
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password.length > 0) {
            passwordStrength.style.display = 'block';
            checkPasswordStrength(password);
        } else {
            passwordStrength.style.display = 'none';
        }
    });
    
    // Password confirmation validation
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmation = this.value;
        
        if (confirmation.length > 0) {
            if (password === confirmation) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });
    
    // Enhanced form validation
    function validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        const fieldType = field.type;
        
        // Remove existing validation classes
        field.classList.remove('is-valid', 'is-invalid');
        
        if (field.hasAttribute('required') && value === '') {
            field.classList.add('is-invalid');
            return false;
        }
        
        // Specific validations
        if (fieldName === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add('is-invalid');
                return false;
            }
        }
        
        if (fieldName === 'ppr' && value) {
            const pprRegex = /^[0-9]{8}$/;
            if (!pprRegex.test(value)) {
                field.classList.add('is-invalid');
                return false;
            }
        }
        
        if (fieldName === 'password' && value) {
            if (value.length < 8) {
                field.classList.add('is-invalid');
                return false;
            }
            if (!checkPasswordStrength(value)) {
                field.classList.add('is-invalid');
                return false;
            }
        }
        
        if (fieldName === 'password_confirmation' && value) {
            if (value !== passwordInput.value) {
                field.classList.add('is-invalid');
                return false;
            }
        }
        
        if (value !== '') {
            field.classList.add('is-valid');
        }
        
        return true;
    }
    
    // Real-time validation
    form.querySelectorAll('input, select').forEach(field => {
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
        const fields = form.querySelectorAll('input[required], select[required]');
        let isValid = true;
        
        fields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Check password confirmation
        if (passwordInput && confirmPasswordInput && passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires et vérifier que les mots de passe correspondent.');
            return false;
        }
        
        // Allow normal form submission
        return true;
    });
    
    // Simple input handling
    form.addEventListener('input', function() {
        // Basic input handling without auto-save
    });
    
    // Enhanced PPR input formatting
    const pprInput = document.getElementById('ppr');
    pprInput.addEventListener('input', function() {
        // Only allow numbers
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limit to 8 digits
        if (this.value.length > 8) {
            this.value = this.value.substring(0, 8);
        }
    });
    
    // Enhanced email validation with domain suggestions
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', function() {
        const email = this.value;
        if (email && !email.includes('@')) {
            // Could add domain suggestions here
        }
    });
});
</script>
@endpush
