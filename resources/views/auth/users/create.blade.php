@extends('layouts.app')

@section('title', 'Créer un Utilisateur')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('auth.users.index') }}">Utilisateurs</a></li>
<li class="bc-item active">Nouveau</li>
@endsection

@section('page-actions')
    <a href="{{ route('auth.users.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour aux Utilisateurs
    </a>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    <x-page-header
        title="Créer un utilisateur"
        subtitle="Ajouter un nouvel utilisateur au système"
        icon="fas fa-user-plus"
        :backRoute="route('auth.users.index')"
        backText="Retour"
    />

    <!-- Create Form -->
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Formulaire de création</h2>
                <p class="text-gray-600">Remplissez les informations pour créer un nouvel utilisateur</p>
            </div>
        </div>
        <form action="{{ route('auth.users.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Section 1: Informations de Base -->
            <div style="background:#F3F6F4; border-radius:0.75rem; padding:1.25rem; border:1px solid #DDE5E1; margin-bottom:1rem;">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-900">Section 1: Informations de Base</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom complet <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 @error('name') border-red-500 @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="Ex: Jean Dupont" required>
                        @error('name')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ppr" class="block text-sm font-semibold text-gray-700 mb-2">
                            PPR <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 @error('ppr') border-red-500 @enderror" 
                               id="ppr" name="ppr" value="{{ old('ppr') }}" 
                               placeholder="Ex: 12345678" required>
                        @error('ppr')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text mt-2">
                            <i class="fas fa-info-circle"></i> Le PPR doit être unique dans le système
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Sécurité -->
            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-2xl p-6 border border-orange-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shield-alt text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-orange-900">Section 2: Sécurité</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Mot de passe <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400 @error('password') border-red-500 @enderror" 
                                   id="password" name="password" placeholder="Mot de passe" required>
                        </div>
                        @error('password')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text mt-2">
                            <i class="fas fa-shield-alt"></i> Le mot de passe doit contenir au moins 8 caractères
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Confirmer le mot de passe <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400 @error('password_confirmation') border-red-500 @enderror" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirmer le mot de passe" required>
                        </div>
                        @error('password_confirmation')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Informations de Contact -->
            <div style="background:#F0FFF4; border-radius:0.75rem; padding:1.25rem; border:1px solid #C6F6D5; margin-bottom:1rem;">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-green-900">Section 3: Informations de Contact</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400 @error('email') border-red-500 @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="exemple@email.com" required>
                        @error('email')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Téléphone
                        </label>
                        <input type="tel" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400 @error('phone') border-red-500 @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" 
                               placeholder="Ex: +212 6 12 34 56 78">
                        @error('phone')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Rôle et Permissions -->
            <div style="background:#F3F6F4; border-radius:0.75rem; padding:1.25rem; border:1px solid #DDE5E1; margin-bottom:1rem;">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-tag text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-purple-900">Section 4: Rôle et Permissions</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                            Rôle <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400 @error('role') border-red-500 @enderror" 
                                id="role" name="role" required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            <option value="agency" {{ old('role') == 'agency' ? 'selected' : '' }}>Agence</option>
                        </select>
                        @error('role')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Statut <span class="text-red-500">*</span>
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400 @error('status') border-red-500 @enderror" 
                                id="status" name="status" required>
                            <option value="">Sélectionner un statut</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('status')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('auth.users.index') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Créer l'Utilisateur</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .form-input {
        background-image: none;
    }
    
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Prevent duplicate error messages */
    .form-group .text-red-500:not(:first-child) {
        display: none;
    }
    
</style>
@endpush

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
@endsection 