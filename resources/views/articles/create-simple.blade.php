@extends('layouts.app')

@section('title', 'Création Simple d\'Article - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-file-excel text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                    Création Simple d'Article
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez un article rapidement avec Excel ou le formulaire simplifié</p>
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

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
        <div class="font-semibold mb-2">Erreurs de validation:</div>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Excel Template Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20 mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-download text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">1. Télécharger le Modèle Excel</h2>
                <p class="text-gray-600">Téléchargez le modèle Excel avec des données d'exemple</p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-blue-800 mb-2">
                        <i class="fas fa-file-excel mr-2"></i>Modèle Excel pour Articles
                    </h3>
                    <p class="text-blue-700 text-sm mb-4">
                        Le modèle contient toutes les colonnes nécessaires avec des données d'exemple. 
                        Vous pouvez modifier ou supprimer les données d'exemple selon vos besoins.
                    </p>
                    <ul class="text-blue-700 text-sm space-y-1">
                        <li>• <strong>Onglet 1:</strong> Données des articles avec exemple</li>
                        <li>• <strong>Onglet 2:</strong> Instructions détaillées</li>
                        <li>• <strong>Format:</strong> Compatible Excel (.xlsx)</li>
                    </ul>
                </div>
                <a href="{{ route('articles.template.download') }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-download"></i>
                    <span class="font-semibold">Télécharger le Modèle</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Simple Form Section -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">2. Créer l'Article</h2>
                <p class="text-gray-600">Utilisez le formulaire simplifié ou importez votre fichier Excel</p>
            </div>
        </div>

        <form action="{{ route('articles.store.simple') }}" method="POST" id="simpleArticleForm" class="space-y-8" enctype="multipart/form-data">
            @csrf

            <!-- Multiple Selects Section -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-green-900">Sélections Multiples</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="foret_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Forêts <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'foret_ids')">
                        <select multiple required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="foret_ids" name="foret_ids[]">
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ collect(old('foret_ids', []))->contains($foret->id) ? 'selected' : '' }}>
                                    {{ $foret->foret }}
                                </option>
                            @endforeach
                        </select>
                        @error('foret_ids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="essence_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Essences <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'essence_ids')">
                        <select multiple required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="essence_ids" name="essence_ids[]">
                            @foreach($essences as $essence)
                                <option value="{{ $essence->id }}" {{ collect(old('essence_ids', []))->contains($essence->id) ? 'selected' : '' }}>
                                    {{ $essence->essence }}
                                </option>
                            @endforeach
                        </select>
                        @error('essence_ids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="localisation_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Localisations <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'localisation_ids')">
                        <select multiple required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="localisation_ids" name="localisation_ids[]">
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ collect(old('localisation_ids', []))->contains($localisation->id) ? 'selected' : '' }}>
                                    {{ $localisation->CODE }} - {{ $localisation->DRANEF }} - {{ $localisation->DPANEF }} - {{ $localisation->ENTITE }}
                                </option>
                            @endforeach
                        </select>
                        @error('localisation_ids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="situation_administrative_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Situation Administrative <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'situation_administrative_ids')">
                        <select multiple required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="situation_administrative_ids" name="situation_administrative_ids[]">
                            @foreach($situationAdministratives as $situation)
                                <option value="{{ $situation->id }}" {{ collect(old('situation_administrative_ids', []))->contains($situation->id) ? 'selected' : '' }}>
                                    {{ $situation->commune }} - {{ $situation->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('situation_administrative_ids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nature_de_coupe_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Natures de Coupe <span class="text-red-500">*</span>
                        </label>
                        <input type="text" placeholder="Rechercher..." class="form-input w-full mb-2 px-4 py-2 border border-gray-300 rounded-lg" onkeyup="filterSelectOptions(this, 'nature_de_coupe_ids')">
                        <select multiple required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                                id="nature_de_coupe_ids" name="nature_de_coupe_ids[]">
                            @foreach($natureDeCoupes as $natureDeCoupe)
                                <option value="{{ $natureDeCoupe->id }}" {{ collect(old('nature_de_coupe_ids', []))->contains($natureDeCoupe->id) ? 'selected' : '' }}>
                                    {{ $natureDeCoupe->nature_de_coupe }}
                                </option>
                            @endforeach
                        </select>
                        @error('nature_de_coupe_ids')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Excel Import Section -->
            <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-upload text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-purple-900">Import Excel (Optionnel)</h3>
                </div>
                
                <div class="bg-white rounded-xl p-6 border border-purple-200">
                    <div class="mb-4">
                        <label for="excel_file" class="block text-sm font-semibold text-gray-700 mb-2">
                            Fichier Excel des Articles
                        </label>
                        <input type="file" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="excel_file" name="excel_file" 
                               accept=".xlsx,.xls,.csv">
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Utilisez le modèle téléchargé ci-dessus. Le fichier sera traité après la création de l'article.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Créer l'Article</span>
                </button>
                
                <a 
                    href="{{ route('articles.index') }}" 
                    class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

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

@push('scripts')
<script>
// Toggle numero_adjudication field based on type selection
function toggleNumeroAdjudication() {
    const typeSelect = document.getElementById('type');
    const numeroAdjudicationGroup = document.getElementById('numero_adjudication_group');
    
    if (typeSelect.value === 'appel_doffre') {
        numeroAdjudicationGroup.style.display = 'block';
    } else {
        numeroAdjudicationGroup.style.display = 'none';
        document.getElementById('numero_adjudication').value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('simpleArticleForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Initialize numero_adjudication toggle
    toggleNumeroAdjudication();
    
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
            let hasValue = false;
            
            if (field.type === 'checkbox' || field.type === 'radio') {
                hasValue = field.checked;
            } else if (field.multiple) {
                // For multi-select fields, check if at least one option is selected
                hasValue = field.selectedOptions.length > 0;
            } else {
                hasValue = field.value.trim() !== '';
            }
            
            if (!hasValue) {
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
    
    // Generic select filter
    window.filterSelectOptions = function(inputEl, selectId) {
        const filter = inputEl.value.toLowerCase();
        const select = document.getElementById(selectId);
        if (!select) return;
        Array.from(select.options).forEach(function(opt) {
            const text = (opt.text || '').toLowerCase();
            const match = text.indexOf(filter) !== -1;
            opt.style.display = match ? '' : 'none';
        });
    };
});
</script>
@endpush
