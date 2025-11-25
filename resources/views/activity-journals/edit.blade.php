@extends('layouts.app')

@section('title', 'Modifier l\'Entrée - Journal d\'Activités')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-book text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                    Modifier l'Entrée
                </h1>
                <p class="text-gray-600 text-lg mt-2">Modifiez les informations de cette entrée du journal d'activités</p>
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
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium">Veuillez corriger les erreurs suivantes :</h3>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @php
                        $uniqueErrors = array_unique($errors->all());
                    @endphp
                    @foreach ($uniqueErrors as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('activity-journals.update', $activityJournal) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
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
                    <label for="Objet" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Objet <span class="text-red-500">*</span></span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sujet ou titre de l'activité"></i>
                    </label>
                    <input type="text" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                           id="Objet" 
                           name="Objet" 
                           value="{{ old('Objet', $activityJournal->Objet) }}"
                           required>
                    @error('Objet')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Date" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Date <span class="text-red-500">*</span></span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Date de l'activité"></i>
                    </label>
                    <input type="date" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                           id="Date" 
                           name="Date" 
                           value="{{ old('Date', $activityJournal->Date ? $activityJournal->Date->format('Y-m-d') : '') }}"
                           required>
                    @error('Date')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group md:col-span-2">
                    <label for="Lieu" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Lieu</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Lieu où s'est déroulée l'activité"></i>
                    </label>
                    <input type="text" 
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                           id="Lieu" 
                           name="Lieu" 
                           value="{{ old('Lieu', $activityJournal->Lieu) }}"
                           placeholder="Ex: Bureau principal, Terrain, etc.">
                    @error('Lieu')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group md:col-span-2">
                    <label for="Participants" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Participants</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Liste des participants à l'activité"></i>
                    </label>
                    <textarea 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 hover:border-gray-400" 
                        id="Participants" 
                        name="Participants" 
                        rows="3"
                        placeholder="Listez les participants séparés par des virgules">{{ old('Participants', $activityJournal->Participants) }}</textarea>
                    @error('Participants')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Détails -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
                <h3 class="text-xl font-bold" style="color: #3b82f6;">Détails</h3>
            </div>
            <div class="space-y-6">
                <div class="form-group">
                    <label for="Description" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Description</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Description détaillée de l'activité"></i>
                    </label>
                    <textarea 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                        id="Description" 
                        name="Description" 
                        rows="5"
                        placeholder="Décrivez l'activité en détail...">{{ old('Description', $activityJournal->Description) }}</textarea>
                    @error('Description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Recommandations" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Recommandations</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Recommandations issues de l'activité"></i>
                    </label>
                    <textarea 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                        id="Recommandations" 
                        name="Recommandations" 
                        rows="4"
                        placeholder="Listez les recommandations...">{{ old('Recommandations', $activityJournal->Recommandations) }}</textarea>
                    @error('Recommandations')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="Conclusion" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <span>Conclusion</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Conclusion de l'activité"></i>
                    </label>
                    <textarea 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400" 
                        id="Conclusion" 
                        name="Conclusion" 
                        rows="4"
                        placeholder="Résumez les conclusions...">{{ old('Conclusion', $activityJournal->Conclusion) }}</textarea>
                    @error('Conclusion')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <a href="{{ route('auth.profile') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                <i class="fas fa-arrow-left"></i>
                <span>Annuler</span>
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-save"></i>
                <span>Enregistrer les modifications</span>
            </button>
        </div>
    </form>
</div>
@endsection

