@extends('layouts.app')

@section('title', 'Créer un PDFC')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                        <i class="fas fa-project-diagram text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #10b981, #059669); -webkit-background-clip: text; background-clip: text;">Créer un PDFC</h1>
                        <p class="text-gray-600 text-lg mt-2">Ajouter un nouveau PDFC au système</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pdfcs.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                <div>
                    <h3 class="text-red-800 font-semibold">Erreurs de validation</h3>
                    <ul class="list-disc list-inside text-red-600 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Create Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #10b981, #059669); -webkit-background-clip: text; background-clip: text;">Formulaire de création</h2>
                <p class="text-gray-600">Remplissez les informations pour créer un nouveau PDFC</p>
            </div>
        </div>

        <form action="{{ route('pdfcs.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Section 1: Informations de Base -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #10b981;">Informations de Base</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="date_de_début" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de Début <span class="text-red-500">*</span>
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400 @error('date_de_début') border-red-500 @enderror" 
                               id="date_de_début" 
                               name="date_de_début" 
                               value="{{ old('date_de_début') }}"
                               required>
                        @error('date_de_début')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_de_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de Fin <span class="text-red-500">*</span>
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400 @error('date_de_fin') border-red-500 @enderror" 
                               id="date_de_fin" 
                               name="date_de_fin" 
                               value="{{ old('date_de_fin') }}"
                               required>
                        @error('date_de_fin')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Utilisateur
                        </label>
                        <select class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400 @error('user_id') border-red-500 @enderror" 
                                id="user_id" 
                                name="user_id">
                            <option value="">Sélectionner un utilisateur</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('pdfcs.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Validate date range
    document.getElementById('date_de_fin').addEventListener('change', function() {
        const startDate = new Date(document.getElementById('date_de_début').value);
        const endDate = new Date(this.value);
        
        if (endDate < startDate) {
            alert('La date de fin doit être supérieure ou égale à la date de début.');
            this.value = '';
        }
    });
</script>
@endpush

