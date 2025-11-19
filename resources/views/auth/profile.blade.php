@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-user text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Mon Profil
                </h1>
                <p class="text-gray-600 text-lg mt-2">Gérez vos informations personnelles et paramètres de compte</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 p-6 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-lg">Succès!</h3>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 p-6 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-lg">Erreur!</h3>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Summary Card -->
        <div class="lg:col-span-1">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
                <div class="text-center">
                    <!-- Avatar -->
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user text-white text-3xl"></i>
                    </div>
                    
                    <!-- User Info -->
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $user->name }}</h3>
                    <p class="text-gray-600 mb-4">
                        <i class="fas fa-id-card me-2 text-indigo-500"></i>
                        PPR: {{ $user->ppr }}
                    </p>
                    <p class="text-gray-600 mb-6">
                        <i class="fas fa-calendar me-2 text-indigo-500"></i>
                        Membre depuis {{ $user->created_at->format('d/m/Y') }}
                    </p>
                    
                    <!-- Profile Stats -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-4 border border-indigo-200">
                        <div class="flex items-center justify-center gap-2 mb-2">
                            <i class="fas fa-shield-alt text-indigo-500"></i>
                            <span class="text-sm font-semibold text-indigo-700">Compte Sécurisé</span>
                        </div>
                        <p class="text-xs text-indigo-600">Votre compte est protégé et sécurisé</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-edit text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Modifier mon profil</h2>
                        <p class="text-gray-600">Mettez à jour vos informations personnelles</p>
                    </div>
                </div>

                <form action="{{ route('auth.profile.update') }}" method="POST" class="space-y-8" data-server-validation>
                    @csrf
                    @method('PUT')
                    
                    <!-- Profile Information Section -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-blue-900">Informations Personnelles</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
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
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                                       id="ppr" 
                                       name="ppr" 
                                       value="{{ old('ppr', $user->ppr) }}" 
                                       required>
                                @error('ppr')
                                    <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Change Section -->
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-200">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                                <span class="text-white text-lg">🔒</span>
                            </div>
                            <h3 class="text-xl font-bold text-orange-900">Changer le mot de passe</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group">
                                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Mot de passe actuel
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400" 
                                           id="current_password" 
                                           name="current_password" 
                                           placeholder="Mot de passe actuel">
                                </div>
                                @error('current_password')
                                    <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nouveau mot de passe
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400" 
                                           id="new_password" 
                                           name="new_password" 
                                           placeholder="Nouveau mot de passe">
                                </div>
                                @error('new_password')
                                    <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Confirmer le nouveau mot de passe
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 hover:border-gray-400" 
                                           id="new_password_confirmation" 
                                           name="new_password_confirmation" 
                                           placeholder="Confirmer le nouveau mot de passe">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password Info -->
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mt-6">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-orange-500 mt-1"></i>
                                <div>
                                    <h4 class="text-sm font-semibold text-orange-800 mb-1">Note importante</h4>
                                    <p class="text-sm text-orange-700">Pour changer votre mot de passe, vous devez fournir votre mot de passe actuel. Laissez les champs de mot de passe vides si vous ne souhaitez pas le modifier.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-save"></i>
                            <span class="font-semibold">Mettre à jour le profil</span>
                        </button>
                        
                        <a href="{{ route('articles.index') }}" 
                           class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                            <i class="fas fa-arrow-left"></i>
                            <span>Retour</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Activity Journals Section -->
    <div class="mt-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Journal d'Activités</h2>
                        <p class="text-gray-600">Historique de vos activités et réunions</p>
                    </div>
                </div>
                <a href="{{ route('activity-journals.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle entrée</span>
                </a>
            </div>

            @if($activityJournals->count() > 0)
                <div class="space-y-4">
                    @foreach($activityJournals as $journal)
                        <div class="bg-gradient-to-r from-gray-50 to-green-50 rounded-xl p-6 border border-gray-200 hover:shadow-md transition-all duration-300">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $journal->Objet }}</h3>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $journal->formatted_date }}
                                        </span>
                                    </div>
                                    @if($journal->Lieu)
                                        <p class="text-sm text-gray-600 mb-2">
                                            <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                            {{ $journal->Lieu }}
                                        </p>
                                    @endif
                                    @if($journal->Participants)
                                        <p class="text-sm text-gray-600 mb-2">
                                            <i class="fas fa-users text-blue-500 mr-2"></i>
                                            {{ $journal->Participants }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('activity-journals.edit', $journal) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('activity-journals.destroy', $journal) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            @if($journal->Description)
                                <div class="mb-3">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-1">Description :</h4>
                                    <p class="text-sm text-gray-600">{{ $journal->Description }}</p>
                                </div>
                            @endif
                            
                            @if($journal->Recommandations)
                                <div class="mb-3">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-1">Recommandations :</h4>
                                    <p class="text-sm text-gray-600">{{ $journal->Recommandations }}</p>
                                </div>
                            @endif
                            
                            @if($journal->Conclusion)
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-1">Conclusion :</h4>
                                    <p class="text-sm text-gray-600">{{ $journal->Conclusion }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $activityJournals->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-600 mb-4">Aucune entrée dans le journal d'activités</p>
                    <a href="{{ route('activity-journals.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                        <i class="fas fa-plus"></i>
                        <span>Créer la première entrée</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-input {
        background-image: none;
    }
    
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    /* Prevent duplicate error messages */
    .form-group .text-red-500:not(:first-child) {
        display: none;
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

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[data-server-validation]');
    
    if (form) {
        // Simple field validation
        function validateField(field) {
            const value = field.value.trim();
            
            // Remove existing validation classes
            field.classList.remove('is-valid', 'is-invalid');
            
            if (field.hasAttribute('required') && value === '') {
                field.classList.add('is-invalid');
                return false;
            }
            
            if (value !== '') {
                field.classList.add('is-valid');
            }
            
            return true;
        }
        
        // Real-time validation
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('blur', function() {
                validateField(this);
            });
            
            field.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
        
        // Form validation
        form.addEventListener('submit', function(e) {
            const fields = form.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            fields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });
    }
});
</script>
@endpush