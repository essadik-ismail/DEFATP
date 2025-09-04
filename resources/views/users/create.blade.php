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
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="userCreateForm" novalidate data-ajax="true">
                        @csrf
                        
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-id-card me-2"></i>Informations Personnelles
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user me-1"></i>Nom complet <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Ex: Jean Dupont" required autocomplete="name">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>Nom complet de l'utilisateur
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="Ex: jean.dupont@example.com" required autocomplete="email">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>Adresse email valide requise
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="ppr" class="form-label">
                                        <i class="fas fa-id-card me-1"></i>PPR <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('ppr') is-invalid @enderror" 
                                           id="ppr" name="ppr" value="{{ old('ppr') }}" 
                                           placeholder="Ex: 12345678" required pattern="[0-9]{8}" 
                                           title="Le PPR doit contenir exactement 8 chiffres">
                                    @error('ppr')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>Numéro de personnel (8 chiffres)
                                    </div>
                                </div>
                            </div>

                            <!-- Security & Roles -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>Sécurité et Rôles
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>Mot de passe <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" required minlength="8"
                                               placeholder="Minimum 8 caractères">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')" aria-label="Afficher le mot de passe">
                                            <i class="fas fa-eye" id="passwordIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-shield-alt me-1"></i>Minimum 8 caractères avec lettres et chiffres
                                    </div>
                                    <div class="password-strength mt-2" id="passwordStrength" style="display: none;">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted" id="strengthText">Force du mot de passe</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-lock me-1"></i>Confirmer le mot de passe <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required
                                           placeholder="Répétez le mot de passe">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>Doit correspondre au mot de passe
                                    </div>
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
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Annuler
                                        </a>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-2"></i><span class="btn-text">Créer l'Utilisateur</span>
                                        </button>
                                        <button type="submit" class="btn btn-success" id="submitAndNextBtn" name="action" value="create_and_next">
                                            <i class="fas fa-plus me-2"></i><span class="btn-text">Créer et Ajouter un Autre</span>
                                        </button>
                                    </div>
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
    const btnText = submitBtn.querySelector('.btn-text');
    
    // Check if AJAX should be disabled
    const useAjax = form.getAttribute('data-ajax') === 'true' && typeof fetch !== 'undefined';
    console.log('AJAX enabled:', useAjax);
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
    
    // Enhanced form submission
    form.addEventListener('submit', function(e) {
        if (!useAjax) {
            // Use normal form submission
            return;
        }
        
        e.preventDefault();
        
        // Validate all fields
        const fields = form.querySelectorAll('input[required], select[required]');
        let isValid = true;
        
        fields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });
        
        // Check password confirmation
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            if (typeof UXUtils !== 'undefined') {
                UXUtils.showToast('Veuillez corriger les erreurs dans le formulaire', 'error');
            } else {
                alert('Veuillez corriger les erreurs dans le formulaire');
            }
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        // Show loading state
        if (typeof UXUtils !== 'undefined') {
            UXUtils.setLoading(submitBtn, true);
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
        }
        btnText.textContent = 'Création en cours...';
        
        // Check if this is a "create and next" action
        const isCreateAndNext = e.submitter && e.submitter.name === 'action' && e.submitter.value === 'create_and_next';
        
        // Prepare form data
        const formData = new FormData(form);
        if (isCreateAndNext) {
            formData.append('action', 'create_and_next');
        }
        
        // Submit form via AJAX
        const formAction = form.getAttribute('action') || '{{ route("users.store") }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        console.log('Form action:', formAction);
        console.log('CSRF token available:', !!csrfToken);
        
        // Try AJAX first, fallback to normal submission
        try {
            fetch(formAction, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    if (data.create_and_next) {
                        if (typeof UXUtils !== 'undefined') {
                            UXUtils.showSuccess('Utilisateur créé avec succès ! Création d\'un nouvel utilisateur...', {
                                duration: 3000,
                                action: () => {
                                    form.reset();
                                    document.getElementById('name').focus();
                                    UXUtils.showInfo('Formulaire réinitialisé. Vous pouvez créer un nouvel utilisateur.');
                                }
                            });
                        } else {
                            alert('Utilisateur créé avec succès !');
                            form.reset();
                        }
                    } else {
                        if (typeof UXUtils !== 'undefined') {
                            UXUtils.showSuccess('Utilisateur créé avec succès !', () => {
                                window.location.href = '{{ route("users.index") }}';
                            });
                        } else {
                            window.location.href = '{{ route("users.index") }}';
                        }
                    }
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessage = 'Erreurs de validation:\n';
                        for (const field in data.errors) {
                            errorMessage += `• ${data.errors[field].join(', ')}\n`;
                        }
                        if (typeof UXUtils !== 'undefined') {
                            UXUtils.showError(errorMessage);
                        } else {
                            alert(errorMessage);
                        }
                        
                        // Highlight invalid fields
                        for (const field in data.errors) {
                            const fieldElement = form.querySelector(`[name="${field}"]`);
                            if (fieldElement) {
                                fieldElement.classList.add('is-invalid');
                            }
                        }
                    } else {
                        if (typeof UXUtils !== 'undefined') {
                            UXUtils.showError(data.message || 'Erreur lors de la création de l\'utilisateur.');
                        } else {
                            alert(data.message || 'Erreur lors de la création de l\'utilisateur.');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                // Fallback to normal form submission
                console.log('Falling back to normal form submission');
                form.removeEventListener('submit', arguments.callee);
                form.submit();
            })
            .finally(() => {
                // Reset loading state
                if (typeof UXUtils !== 'undefined') {
                    UXUtils.setLoading(submitBtn, false);
                } else {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('loading');
                }
                btnText.textContent = 'Créer l\'Utilisateur';
            });
        } catch (error) {
            console.error('Fetch setup error:', error);
            // Fallback to normal form submission
            form.removeEventListener('submit', arguments.callee);
            form.submit();
        }
    });
    
    // Auto-save draft functionality
    let autoSaveTimeout;
    form.addEventListener('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Auto-save logic could go here
            console.log('Auto-saving user draft...');
        }, 5000);
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
