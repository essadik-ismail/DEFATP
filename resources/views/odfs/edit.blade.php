@extends('layouts.app')

@section('title', 'Modifier ODF - DEFATP')

@section('content')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slide-down {
        from {
            opacity: 0;
            transform: translateY(-20px);
            max-height: 0;
        }
        to {
            opacity: 1;
            transform: translateY(0);
            max-height: 1000px;
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }
    
    .animate-slide-down {
        animation: slide-down 0.3s ease-out;
    }
    
    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }
    
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }
    
    .form-input:hover:not(:focus) {
        border-color: #cbd5e1;
    }
</style>
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8 animate-fade-in">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg transform hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        Modifier ODF
                    </h1>
                    <p class="text-gray-600 text-lg mt-2">Modifiez les informations de l'Organisme de développement forestier (ODF)</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('odfs.show', $odf) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition-all duration-300 border border-blue-200">
                    <i class="fas fa-eye"></i>
                    <span>Voir</span>
                </a>
                <a href="{{ route('odfs.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition-all duration-300 border border-gray-200">
                    <i class="fas fa-list"></i>
                    <span>Liste</span>
                </a>
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

    <!-- ODF Information Form -->
    <form id="odfMainForm" action="{{ route('odfs.update', $odf) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Section 1: Informations de Base -->
        <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <!-- Section Header with Collapse -->
            <div class="flex items-center justify-between p-5 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-purple-200 cursor-pointer hover:from-purple-100 hover:to-indigo-100 transition-all duration-300 group" onclick="toggleCollapse('info-base-section')">
                <div class="flex items-center gap-4">
                    <i class="fas fa-chevron-down text-purple-500 transition-transform duration-300 group-hover:text-purple-700" id="icon-info-base-section"></i>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-md transform group-hover:scale-110 transition-transform duration-300" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
                        <i class="fas fa-info-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Informations de Base</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Données principales de l'ODF</p>
                    </div>
                </div>
                <button type="submit" 
                        form="odfMainForm"
                        onclick="event.stopPropagation();"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95">
                    <i class="fas fa-save"></i>
                    <span class="text-sm font-semibold">Enregistrer les modifications</span>
                </button>
            </div>
            <!-- Section Content -->
            <div id="info-base-section" class="p-6 bg-gray-50/50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="odf_entite_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-building text-purple-600"></i>
                        <span>ODF Entité</span>
                        <div class="relative group">
                            <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                               onclick="showHelpModal('odf_entite_help')"></i>
                        </div>
                    </label>
                    <div class="relative">
                        <select 
                            class="form-input w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-purple-300 bg-white shadow-sm" 
                            id="odf_entite_id" 
                            name="odf_entite_id">
                            <option value="">Sélectionner une entité ODF</option>
                            @foreach($odfEntites as $entite)
                                <option value="{{ $entite->id }}" {{ old('odf_entite_id', $odf->odf_entite_id) == $entite->id ? 'selected' : '' }}>
                                    {{ $entite->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    @error('odf_entite_id')
                        <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                
                <!-- ODF Entité Information Display -->
                <div id="odfEntiteInfo" class="hidden col-span-2 mt-4 p-5 bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 rounded-xl border-2 border-blue-200 shadow-md animate-slide-down">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-info-circle text-white text-sm"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800">Informations de l'ODF Entité</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div id="localisationInfo" class="hidden p-3 bg-white rounded-lg border border-blue-100 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-map-marker-alt text-blue-600 text-xs"></i>
                                <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Localisation</div>
                            </div>
                            <div class="text-sm text-gray-900 font-medium" id="localisationText"></div>
                        </div>
                        <div id="situationInfo" class="hidden p-3 bg-white rounded-lg border border-indigo-100 shadow-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-building text-indigo-600 text-xs"></i>
                                <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Situation Administrative</div>
                            </div>
                            <div class="text-sm text-gray-900 font-medium" id="situationText"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="constitution" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-check-circle text-purple-600"></i>
                        <span>Constitution</span>
                        <div class="relative group">
                            <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                               onclick="showHelpModal('constitution_help')"></i>
                        </div>
                    </label>
                    <div class="relative">
                        <select 
                            class="form-input w-full px-4 py-3 pl-10 border-2 border-gray-200 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-purple-300 bg-white shadow-sm appearance-none" 
                            id="constitution" 
                            name="constitution">
                            <option value="">Sélectionner</option>
                            <option value="1" {{ old('constitution', $odf->constitution) == '1' || $odf->constitution == true ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ old('constitution', $odf->constitution) == '0' || $odf->constitution == false ? 'selected' : '' }}>Non</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    @error('constitution')
                        <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="form-group md:col-span-2">
                    <label for="commentaire" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-comment-dots text-amber-600"></i>
                        <span>Commentaire</span>
                        <div class="relative group">
                            <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                               onclick="showHelpModal('commentaire_help')"></i>
                        </div>
                    </label>
                    <textarea 
                        class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-amber-300 bg-white shadow-sm resize-none" 
                        id="commentaire" 
                        name="commentaire" 
                        rows="4"
                        placeholder="Saisissez vos commentaires ici...">{{ old('commentaire', $odf->commentaire) }}</textarea>
                    <div class="mt-1 text-xs text-gray-500 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        <span>Les commentaires sont optionnels</span>
                    </div>
                    @error('commentaire')
                        <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Dépôt ODF Section (only show if not constituted) -->
                @if($odf->constitution)
                <div class="form-group md:col-span-2">
                    <div class="bg-gradient-to-br from-white to-blue-50/50 rounded-xl p-5 border-2 border-blue-200 shadow-sm">
                        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-blue-100">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                                <i class="fas fa-file-upload text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-gray-800">Dépôt ODF</h4>
                                <p class="text-xs text-gray-500">Informations de dépôt</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="date_depot_odf" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-calendar text-blue-600 text-xs"></i>
                                    <span>Date de Dépôt ODF</span>
                                    <div class="relative group">
                                        <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                                           onclick="showHelpModal('date_depot_odf_help')"></i>
                                    </div>
                                </label>
                                <input type="date" 
                                    class="form-input w-full px-3 py-2 border-2 border-gray-200 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-300 bg-white shadow-sm" 
                                    id="date_depot_odf" 
                                    name="date_depot_odf" 
                                    value="{{ old('date_depot_odf', $odf->date_depot_odf ? \Carbon\Carbon::parse($odf->date_depot_odf)->format('Y-m-d') : '') }}">
                                @error('date_depot_odf')
                                    <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fichier_joint_depot_odf" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-file-upload text-blue-600 text-xs"></i>
                                    <span>Fichier Joint Dépôt ODF</span>
                                    <div class="relative group">
                                        <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                                           onclick="showHelpModal('fichier_joint_depot_odf_help')"></i>
                                    </div>
                                </label>
                                @if($odf->fichier_joint_depot_odf)
                                    <div class="mb-2 p-2 bg-blue-50 rounded-lg border-2 border-blue-200 flex items-center gap-2">
                                        <i class="fas fa-file-pdf text-blue-600 text-xs"></i>
                                        <span class="text-xs text-blue-700 font-medium">Fichier actuel: {{ basename($odf->fichier_joint_depot_odf) }}</span>
                                    </div>
                                @endif
                                <input type="file" 
                                    class="form-input w-full px-3 py-2 border-2 border-gray-200 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-300 bg-white shadow-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                                    id="fichier_joint_depot_odf" 
                                    name="fichier_joint_depot_odf" 
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <i class="fas fa-info-circle text-xs"></i>
                                    <span>Formats acceptés: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</span>
                                </p>
                                @error('fichier_joint_depot_odf')
                                    <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Réçu du Définition Section -->
                <div class="form-group md:col-span-2">
                    <div class="bg-gradient-to-br from-white to-cyan-50/50 rounded-xl p-5 border-2 border-cyan-200 shadow-sm">
                        <div class="flex items-center gap-3 mb-4 pb-3 border-b border-cyan-100">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm" style="background: linear-gradient(to bottom right, #06b6d4, #0891b2);">
                                <i class="fas fa-file-download text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-gray-800">Réçu du Définition</h4>
                                <p class="text-xs text-gray-500">Informations de réception</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="date_reçu_du_définition" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-calendar text-cyan-600 text-xs"></i>
                                    <span>Date Réçu du Définition</span>
                                    <div class="relative group">
                                        <i class="fas fa-question-circle text-cyan-500 text-xs cursor-pointer hover:text-cyan-600 transition-colors" 
                                           onclick="showHelpModal('date_reçu_du_définition_help')"></i>
                                    </div>
                                </label>
                                <input type="date" 
                                    class="form-input w-full px-3 py-2 border-2 border-gray-200 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 hover:border-cyan-300 bg-white shadow-sm" 
                                    id="date_reçu_du_définition" 
                                    name="date_reçu_du_définition" 
                                    value="{{ old('date_reçu_du_définition', $odf->date_reçu_du_définition ? \Carbon\Carbon::parse($odf->date_reçu_du_définition)->format('Y-m-d') : '') }}">
                                @error('date_reçu_du_définition')
                                    <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fichier_joint_reçu_du_définition" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <i class="fas fa-file-download text-cyan-600 text-xs"></i>
                                    <span>Fichier Joint Réçu du Définition</span>
                                    <div class="relative group">
                                        <i class="fas fa-question-circle text-cyan-500 text-xs cursor-pointer hover:text-cyan-600 transition-colors" 
                                           onclick="showHelpModal('fichier_joint_reçu_du_définition_help')"></i>
                                    </div>
                                </label>
                                @if($odf->fichier_joint_reçu_du_définition)
                                    <div class="mb-2 p-2 bg-cyan-50 rounded-lg border-2 border-cyan-200 flex items-center gap-2">
                                        <i class="fas fa-file-pdf text-cyan-600 text-xs"></i>
                                        <span class="text-xs text-cyan-700 font-medium">Fichier actuel: {{ basename($odf->fichier_joint_reçu_du_définition) }}</span>
                                    </div>
                                @endif
                                <input type="file" 
                                    class="form-input w-full px-3 py-2 border-2 border-gray-200 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 hover:border-cyan-300 bg-white shadow-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100" 
                                    id="fichier_joint_reçu_du_définition" 
                                    name="fichier_joint_reçu_du_définition" 
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <i class="fas fa-info-circle text-xs"></i>
                                    <span>Formats acceptés: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</span>
                                </p>
                                @error('fichier_joint_reçu_du_définition')
                                    <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                </div>
            </div>
        </div>
    </form>

    <!-- ODF Steps CRUD Section (only show if not constituted) -->
    @if(!$odf->constitution)
    <!-- ODF Steps CRUD Section -->
    <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
        <!-- Section Header with Collapse -->
        <div class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-200 cursor-pointer hover:from-blue-100 hover:to-indigo-100 transition-all duration-300 group" onclick="toggleCollapse('steps-section')">
            <div class="flex items-center gap-4">
                <i class="fas fa-chevron-down text-blue-500 transition-transform duration-300 group-hover:text-blue-700" id="icon-steps-section"></i>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-md transform group-hover:scale-110 transition-transform duration-300 bg-gradient-to-br from-blue-500 to-indigo-600">
                    <i class="fas fa-list-ol text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Étapes ODF</h3>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $odf->odfEtaps->count() }} étape(s) enregistrée(s)</p>
                </div>
            </div>
            <button type="button" 
                    onclick="event.stopPropagation(); openAddStepForm();"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95">
                <i class="fas fa-plus"></i>
                <span class="font-medium">Ajouter une étape</span>
            </button>
        </div>

        <!-- Steps Content -->
        <div id="steps-section" class="p-6 space-y-6 bg-gray-50/30">
            <!-- Add/Edit Step Form Section -->
            <div id="stepFormSection" class="bg-gradient-to-br from-white to-blue-50/50 rounded-2xl shadow-lg p-6 border-2 border-blue-200 hidden animate-slide-down">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600">
                                <i class="fas fa-plus text-white"></i>
                            </div>
                            <h3 id="stepFormTitle" class="text-xl font-bold text-gray-800">Ajouter une étape</h3>
                        </div>
                        <button type="button" 
                                onclick="closeStepForm()"
                                class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form id="stepForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div id="stepFormMethod"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="step_date" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <span>Date *</span>
                                    <div class="relative group">
                                        <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                                           onclick="showHelpModal('step_date_help')"></i>
                                    </div>
                                </label>
                                <input type="date" 
                                    id="step_date" 
                                    name="date" 
                                    required
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="step_lieu" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                    <span>Lieu</span>
                                    <div class="relative group">
                                        <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                                           onclick="showHelpModal('step_lieu_help')"></i>
                                    </div>
                                </label>
                                <input type="text" 
                                    id="step_lieu" 
                                    name="lieu" 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="step_participant" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <span>Participant</span>
                                <div class="relative group">
                                    <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                                       onclick="showHelpModal('step_participant_help')"></i>
                                </div>
                            </label>
                            <textarea id="step_participant" 
                                    name="participant" 
                                    rows="3"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="step_description" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <span>Description</span>
                                <div class="relative group">
                                    <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                                       onclick="showHelpModal('step_description_help')"></i>
                                </div>
                            </label>
                            <textarea id="step_description" 
                                    name="description" 
                                    rows="3"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="step_resultat" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <span>Résultat</span>
                                <div class="relative group">
                                    <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                                       onclick="showHelpModal('step_resultat_help')"></i>
                                </div>
                            </label>
                            <textarea id="step_resultat" 
                                    name="resultat" 
                                    rows="3"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="step_fichierjoin" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <span>Fichier Joint</span>
                                <div class="relative group">
                                    <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" 
                                       onclick="showHelpModal('step_fichierjoin_help')"></i>
                                </div>
                            </label>
                            <div id="currentFileDisplay" class="mb-2 hidden p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-center gap-2 text-sm text-blue-700">
                                    <i class="fas fa-file"></i>
                                    <span id="currentFileName"></span>
                                </div>
                            </div>
                            <input type="file" 
                                id="step_fichierjoin" 
                                name="fichierjoin" 
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX, JPG, JPEG, PNG</p>
                        </div>

                        <div class="flex justify-end gap-4 pt-4 border-t">
                            <button type="button" 
                                    onclick="closeStepForm()"
                                    class="px-6 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200">
                                Annuler
                            </button>
                            <button type="submit" 
                                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>

            <!-- Steps List Section -->
            <div class="bg-white rounded-xl p-4 border-2 border-gray-200 shadow-sm">

                <!-- Steps List -->
                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lieu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Résultat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($odf->odfEtaps as $etap)
                                <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar text-blue-500 text-xs"></i>
                                            <span class="text-sm font-medium text-gray-900">{{ $etap->date ? \Carbon\Carbon::parse($etap->date)->format('d/m/Y') : '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-gray-400 text-xs"></i>
                                            <span class="text-sm text-gray-900">{{ $etap->lieu ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($etap->participant ?? '-', 50) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($etap->description ?? '-', 50) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($etap->resultat ?? '-', 50) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <button onclick="openEditStepForm({{ $etap->id }})" 
                                                    class="p-2 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-all duration-200 transform hover:scale-110"
                                                    title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('odfs.odf-etaps.destroy', [$odf, $etap]) }}" 
                                                method="POST" 
                                                class="inline"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette étape?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200 transform hover:scale-110" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-list-ol text-2xl text-gray-400"></i>
                                            </div>
                                            <p class="text-sm font-medium text-gray-500">Aucune étape ajoutée pour le moment</p>
                                            <p class="text-xs text-gray-400 mt-1">Cliquez sur "Ajouter une étape" pour commencer</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
            </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Dépôt ODF and Réçu du Définition Section (only show if not constituted) -->
    @if(!$odf->constitution)
    <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
        <!-- Section Header with Collapse -->
        <div class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-50 via-cyan-50 to-indigo-50 border-b border-blue-200 cursor-pointer hover:from-blue-100 hover:via-cyan-100 hover:to-indigo-100 transition-all duration-300 group" onclick="toggleCollapse('depot-section')">
            <div class="flex items-center gap-4">
                <i class="fas fa-chevron-down text-blue-500 transition-transform duration-300 group-hover:text-blue-700" id="icon-depot-section"></i>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-md transform group-hover:scale-110 transition-transform duration-300" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                    <i class="fas fa-file-upload text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Dépôt ODF et Réçu du Définition</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Documents et fichiers joints</p>
                </div>
            </div>
        </div>

        <!-- Depot Content -->
        <div id="depot-section" class="p-6 bg-gray-50/30">
            <form action="{{ route('odfs.update', $odf) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Section: Dépôt ODF -->
                <div class="bg-gradient-to-br from-white to-blue-50/50 rounded-2xl p-6 border-2 border-blue-200 shadow-md">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-blue-100">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                            <i class="fas fa-file-upload text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Dépôt ODF</h3>
                            <p class="text-xs text-gray-500">Informations de dépôt</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="date_depot_odf" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-calendar text-blue-600"></i>
                                <span>Date de Dépôt ODF</span>
                            </label>
                            <input type="date" 
                                class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-300 bg-white shadow-sm" 
                                id="date_depot_odf" 
                                name="date_depot_odf" 
                                value="{{ old('date_depot_odf', $odf->date_depot_odf ? \Carbon\Carbon::parse($odf->date_depot_odf)->format('Y-m-d') : '') }}">
                            @error('date_depot_odf')
                                <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="fichier_joint_depot_odf" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-file-upload text-blue-600"></i>
                                <span>Fichier Joint Dépôt ODF</span>
                            </label>
                            @if($odf->fichier_joint_depot_odf)
                                <div class="mb-3 p-3 bg-blue-50 rounded-lg border-2 border-blue-200 flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-blue-600"></i>
                                    <span class="text-sm text-blue-700 font-medium">Fichier actuel: {{ basename($odf->fichier_joint_depot_odf) }}</span>
                                </div>
                            @endif
                            <div class="relative">
                                <input type="file" 
                                    class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-300 bg-white shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                                    id="fichier_joint_depot_odf" 
                                    name="fichier_joint_depot_odf" 
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>
                                <span>Formats acceptés: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</span>
                            </p>
                            @error('fichier_joint_depot_odf')
                                <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Réçu du Définition -->
                <div class="bg-gradient-to-br from-white to-cyan-50/50 rounded-2xl p-6 border-2 border-cyan-200 shadow-md">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-cyan-100">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm" style="background: linear-gradient(to bottom right, #06b6d4, #0891b2);">
                            <i class="fas fa-file-download text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Réçu du Définition</h3>
                            <p class="text-xs text-gray-500">Informations de réception</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="date_reçu_du_définition" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-calendar text-cyan-600"></i>
                                <span>Date Réçu du Définition</span>
                            </label>
                            <input type="date" 
                                class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 hover:border-cyan-300 bg-white shadow-sm" 
                                id="date_reçu_du_définition" 
                                name="date_reçu_du_définition" 
                                value="{{ old('date_reçu_du_définition', $odf->date_reçu_du_définition ? \Carbon\Carbon::parse($odf->date_reçu_du_définition)->format('Y-m-d') : '') }}">
                            @error('date_reçu_du_définition')
                                <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="fichier_joint_reçu_du_définition" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-file-download text-cyan-600"></i>
                                <span>Fichier Joint Réçu du Définition</span>
                            </label>
                            @if($odf->fichier_joint_reçu_du_définition)
                                <div class="mb-3 p-3 bg-cyan-50 rounded-lg border-2 border-cyan-200 flex items-center gap-2">
                                    <i class="fas fa-file-pdf text-cyan-600"></i>
                                    <span class="text-sm text-cyan-700 font-medium">Fichier actuel: {{ basename($odf->fichier_joint_reçu_du_définition) }}</span>
                                </div>
                            @endif
                            <div class="relative">
                                <input type="file" 
                                    class="form-input w-full px-4 py-3 border-2 border-gray-200 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 hover:border-cyan-300 bg-white shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100" 
                                    id="fichier_joint_reçu_du_définition" 
                                    name="fichier_joint_reçu_du_définition" 
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <i class="fas fa-info-circle"></i>
                                <span>Formats acceptés: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</span>
                            </p>
                            @error('fichier_joint_reçu_du_définition')
                                <div class="text-red-500 text-sm mt-2 flex items-center gap-2 animate-shake">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t-2 border-gray-200 bg-gray-50 -mx-6 -mb-6 px-6 py-4 rounded-b-2xl">
                    <a href="{{ route('odfs.index') }}" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 border-2 border-gray-200 hover:border-gray-300 shadow-sm hover:shadow-md">
                        <i class="fas fa-times"></i>
                        <span class="font-medium">Annuler</span>
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95">
                        <i class="fas fa-save"></i>
                        <span class="font-semibold">Enregistrer les modifications</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Members Section (only show if constitution is true) -->
    @if($odf->constitution)
    <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
        <!-- Section Header with Collapse -->
        <div class="flex items-center justify-between p-5 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200 cursor-pointer hover:from-green-100 hover:to-emerald-100 transition-all duration-300 group" onclick="toggleCollapse('members-section')">
            <div class="flex items-center gap-4">
                <i class="fas fa-chevron-down text-green-500 transition-transform duration-300 group-hover:text-green-700" id="icon-members-section"></i>
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-md transform group-hover:scale-110 transition-transform duration-300" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Membres</h3>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $odf->members->count() }} membre(s) enregistré(s)</p>
                </div>
            </div>
            <button type="button" onclick="event.stopPropagation(); toggleMemberForm();" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95">
                <i class="fas fa-plus"></i>
                <span class="font-medium">Ajouter</span>
            </button>
        </div>

        <!-- Members Content -->
        <div id="members-section" class="p-6 bg-gray-50/30">
            <!-- Add Member Form (Hidden by default) -->
            <div id="memberForm" class="hidden mb-6 bg-gradient-to-br from-white to-green-50/50 rounded-xl p-5 border-2 border-green-200 shadow-md animate-slide-down">
                <h4 class="font-semibold text-gray-900 mb-4">Nouveau Membre</h4>
                <form action="{{ route('odfs.members.store', $odf) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                <span>Type Membre</span>
                                <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('type_membre_help')"></i>
                            </label>
                            <select name="type_membre" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Sélectionner</option>
                                <option value="présidente">Présidente</option>
                                <option value="vice_présidente">Vice-Présidente</option>
                                <option value="trésorière">Trésorière</option>
                                <option value="membre">Membre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                <span>Nom <span class="text-red-500">*</span></span>
                                <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('nom_membre_help')"></i>
                            </label>
                            <input type="text" name="nom" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                <span>N° CIN</span>
                                <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('n_cin_help')"></i>
                            </label>
                            <input type="text" name="n_cin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                <span>Téléphone</span>
                                <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('telephone_membre_help')"></i>
                            </label>
                            <input type="text" name="tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                <span>Email</span>
                                <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('email_membre_help')"></i>
                            </label>
                            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                <span>Type ODF</span>
                                <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('type_odf_help')"></i>
                            </label>
                            <select name="type_odf" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Sélectionner</option>
                                <option value="Association">Association</option>
                                <option value="Coopérative">Coopérative</option>
                                <option value="Entreprise">Entreprise</option>
                                <option value="Élu">Élu</option>
                                <option value="Citoyen">Citoyen</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                <span>Domaine d'Activité</span>
                                <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('domaine_activite_help')"></i>
                            </label>
                            <input type="text" name="type_odf_domaine_activite" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                <span>Nombre de Membres</span>
                                <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('nombre_membres_help')"></i>
                            </label>
                            <input type="number" name="type_odf_nombre_de_membres" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                            <span>Commentaire</span>
                            <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('commentaire_membre_help')"></i>
                        </label>
                        <textarea name="commentaire" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Enregistrer</button>
                        <button type="button" onclick="toggleMemberForm()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Annuler</button>
                    </div>
                </form>
            </div>

            <!-- Members List -->
            <div class="space-y-3">
                @forelse($odf->members as $member)
                    <div class="flex items-center justify-between p-4 bg-white rounded-xl border-2 border-gray-200 hover:border-green-300 hover:shadow-md transition-all duration-300 transform hover:scale-[1.01]" id="member-{{ $member->id }}">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user text-green-600"></i>
                                    </div>
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $member->nom }}
                                        @if($member->type_membre)
                                            <span class="text-xs text-gray-500 ml-2">({{ ucfirst(str_replace('_', ' ', $member->type_membre)) }})</span>
                                        @endif
                                    </p>
                                    <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                                        @if($member->n_cin)
                                            <span><i class="fas fa-id-card mr-1"></i>CIN: {{ $member->n_cin }}</span>
                                        @endif
                                        @if($member->tel)
                                            <span><i class="fas fa-phone mr-1"></i>{{ $member->tel }}</span>
                                        @endif
                                        @if($member->email)
                                            <span><i class="fas fa-envelope mr-1"></i>{{ $member->email }}</span>
                                        @endif
                                        @if($member->type_odf)
                                            <span><i class="fas fa-tag mr-1"></i>{{ $member->type_odf }}</span>
                                        @endif
                                    </div>
                                    @if($member->type_odf_domaine_activite || $member->type_odf_nombre_de_membres)
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if($member->type_odf_domaine_activite)
                                                <span>Domaine: {{ $member->type_odf_domaine_activite }}</span>
                                            @endif
                                            @if($member->type_odf_nombre_de_membres)
                                                <span class="ml-2">Membres: {{ $member->type_odf_nombre_de_membres }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="editMember({{ $member->id }}, @json($member->type_membre ?? ''), @json($member->nom), @json($member->n_cin ?? ''), @json($member->tel ?? ''), @json($member->email ?? ''), @json($member->type_odf ?? ''), @json($member->type_odf_domaine_activite ?? ''), @json($member->type_odf_nombre_de_membres ?? ''), @json($member->commentaire ?? ''))" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('odfs.members.destroy', [$odf, $member]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucun membre enregistré</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
</div>

<script>
let currentStepId = null;

// Load ODF Entité information when selected
document.addEventListener('DOMContentLoaded', function() {
    const odfEntiteSelect = document.getElementById('odf_entite_id');
    const odfEntiteInfo = document.getElementById('odfEntiteInfo');
    const localisationInfo = document.getElementById('localisationInfo');
    const situationInfo = document.getElementById('situationInfo');
    const localisationText = document.getElementById('localisationText');
    const situationText = document.getElementById('situationText');
    
    // Load info if ODF Entité is already selected
    if (odfEntiteSelect.value) {
        loadOdfEntiteInfo(odfEntiteSelect.value);
    }
    
    // Listen for changes
    odfEntiteSelect.addEventListener('change', function() {
        if (this.value) {
            loadOdfEntiteInfo(this.value);
        } else {
            odfEntiteInfo.classList.add('hidden');
            localisationInfo.classList.add('hidden');
            situationInfo.classList.add('hidden');
        }
    });
    
    function loadOdfEntiteInfo(odfEntiteId) {
        fetch(`{{ url('/api/odf-entites') }}/${odfEntiteId}`)
            .then(response => response.json())
            .then(data => {
                // Show info container
                odfEntiteInfo.classList.remove('hidden');
                
                // Display localisation
                if (data.localisation) {
                    localisationText.textContent = `${data.localisation.code} - ${data.localisation.dranef} - ${data.localisation.entite}`;
                    localisationInfo.classList.remove('hidden');
                } else {
                    localisationInfo.classList.add('hidden');
                }
                
                // Display situation administrative
                if (data.situation_administrative) {
                    let situationTextContent = data.situation_administrative.commune;
                    if (data.situation_administrative.province) {
                        situationTextContent += ` - ${data.situation_administrative.province}`;
                    }
                    situationText.textContent = situationTextContent;
                    situationInfo.classList.remove('hidden');
                } else {
                    situationInfo.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error loading ODF Entité info:', error);
                odfEntiteInfo.classList.add('hidden');
            });
    }
});

function openAddStepForm() {
    currentStepId = null;
    document.getElementById('stepFormTitle').textContent = 'Ajouter une étape';
    document.getElementById('stepForm').action = '{{ route("odfs.odf-etaps.store", $odf) }}';
    document.getElementById('stepFormMethod').innerHTML = '';
    document.getElementById('stepForm').reset();
    // Hide current file display
    document.getElementById('currentFileDisplay').classList.add('hidden');
    document.getElementById('stepFormSection').classList.remove('hidden');
    // Scroll to form
    document.getElementById('stepFormSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function openEditStepForm(stepId) {
    currentStepId = stepId;
    document.getElementById('stepFormTitle').textContent = 'Modifier une étape';
    document.getElementById('stepForm').action = '{{ route("odfs.odf-etaps.update", [$odf, ":id"]) }}'.replace(':id', stepId);
    document.getElementById('stepFormMethod').innerHTML = '@method("PUT")';
    
    // Fetch step data and populate form
    fetch(`{{ url('/odfs/' . $odf->id . '/odf-etaps') }}/${stepId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('step_date').value = data.date || '';
            document.getElementById('step_lieu').value = data.lieu || '';
            document.getElementById('step_participant').value = data.participant || '';
            document.getElementById('step_description').value = data.description || '';
            document.getElementById('step_resultat').value = data.resultat || '';
            
            // Handle file display
            if (data.fichierjoin) {
                const fileName = data.fichierjoin.split('/').pop();
                document.getElementById('currentFileName').textContent = fileName;
                document.getElementById('currentFileDisplay').classList.remove('hidden');
            } else {
                document.getElementById('currentFileDisplay').classList.add('hidden');
            }
            
            // Reset file input
            document.getElementById('step_fichierjoin').value = '';
            
            document.getElementById('stepFormSection').classList.remove('hidden');
            // Scroll to form
            document.getElementById('stepFormSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors du chargement des données');
        });
}

function closeStepForm() {
    document.getElementById('stepFormSection').classList.add('hidden');
    document.getElementById('stepForm').reset();
    currentStepId = null;
}

// Collapse functionality
function toggleCollapse(sectionId) {
    const section = document.getElementById(sectionId);
    const icon = document.getElementById('icon-' + sectionId);
    
    if (section) {
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
            if (icon) {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            }
        } else {
            section.classList.add('hidden');
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }
        }
    }
}

function toggleMemberForm() {
    const form = document.getElementById('memberForm');
    form.classList.toggle('hidden');
}

function editMember(id, typeMembre, nom, nCin, tel, email, typeOdf, domaineActivite, nombreMembres, commentaire) {
    document.getElementById('edit_member_type_membre').value = typeMembre || '';
    document.getElementById('edit_member_nom').value = nom;
    document.getElementById('edit_member_n_cin').value = nCin || '';
    document.getElementById('edit_member_tel').value = tel || '';
    document.getElementById('edit_member_email').value = email || '';
    document.getElementById('edit_member_type_odf').value = typeOdf || '';
    document.getElementById('edit_member_type_odf_domaine_activite').value = domaineActivite || '';
    document.getElementById('edit_member_type_odf_nombre_de_membres').value = nombreMembres || '';
    document.getElementById('edit_member_commentaire').value = commentaire || '';
    document.getElementById('editMemberForm').action = '{{ route("odfs.members.update", [$odf, ":id"]) }}'.replace(':id', id);
    document.getElementById('editMemberModal').classList.remove('hidden');
}

function closeEditMemberModal() {
    document.getElementById('editMemberModal').classList.add('hidden');
}
</script>

<!-- Edit Member Modal -->
<div id="editMemberModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Modifier le Membre</h3>
        <form id="editMemberForm" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Type Membre</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Type de membre dans l'ODF"></i>
                    </label>
                    <select name="type_membre" id="edit_member_type_membre" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Sélectionner</option>
                        <option value="présidente">Présidente</option>
                        <option value="vice_présidente">Vice-Présidente</option>
                        <option value="trésorière">Trésorière</option>
                        <option value="membre">Membre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Nom <span class="text-red-500">*</span></span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nom complet du membre"></i>
                    </label>
                    <input type="text" name="nom" id="edit_member_nom" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>N° CIN</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro de Carte d'Identité Nationale"></i>
                    </label>
                    <input type="text" name="n_cin" id="edit_member_n_cin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Téléphone</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro de téléphone du membre"></i>
                    </label>
                    <input type="text" name="tel" id="edit_member_tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Email</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Adresse email du membre"></i>
                    </label>
                    <input type="email" name="email" id="edit_member_email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Type ODF</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Type d'ODF: Association, Coopérative, Entreprise, Élu, ou Citoyen"></i>
                    </label>
                    <select name="type_odf" id="edit_member_type_odf" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Sélectionner</option>
                        <option value="Association">Association</option>
                        <option value="Coopérative">Coopérative</option>
                        <option value="Entreprise">Entreprise</option>
                        <option value="Élu">Élu</option>
                        <option value="Citoyen">Citoyen</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Domaine d'Activité</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Domaine d'activité de l'ODF"></i>
                    </label>
                    <input type="text" name="type_odf_domaine_activite" id="edit_member_type_odf_domaine_activite" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Nombre de Membres</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nombre de membres dans l'ODF"></i>
                    </label>
                    <input type="number" name="type_odf_nombre_de_membres" id="edit_member_type_odf_nombre_de_membres" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <span>Commentaire</span>
                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Commentaires additionnels"></i>
                </label>
                <textarea name="commentaire" id="edit_member_commentaire" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
            </div>
            <div class="flex gap-2 mt-6">
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Enregistrer</button>
                <button type="button" onclick="closeEditMemberModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
// Close modal when clicking outside
document.getElementById('editMemberModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditMemberModal();
    }
});
</script>
<!-- Help Modal -->
<div id="helpModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Aide</h3>
                </div>
                <button onclick="closeHelpModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="helpModalContent" class="text-gray-700">
                <!-- Content will be inserted here -->
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeHelpModal()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showHelpModal(helpId) {
    const helpContents = {
        'odf_entite_help': {
            title: 'ODF Entité',
            content: 'Sélectionnez l\'entité ODF (Organisation de Développement Forestier) associée à cette ODF. Cette entité contient les informations de localisation (DRANEF, DPANEF, ENTITE) et de situation administrative (commune, province).'
        },
        'constitution_help': {
            title: 'Constitution',
            content: 'Indiquez si l\'ODF est constituée (Oui) ou non (Non). Une ODF constituée peut avoir des sections supplémentaires pour le dépôt ODF et la réception de définition. Si l\'ODF n\'est pas constituée, vous pourrez ajouter des étapes de constitution.'
        },
        'commentaire_help': {
            title: 'Commentaire',
            content: 'Ajoutez des commentaires ou notes additionnelles concernant cette ODF. Ce champ est optionnel et peut être utilisé pour documenter des informations supplémentaires ou des observations importantes.'
        },
        'date_depot_odf_help': {
            title: 'Date de Dépôt ODF',
            content: 'Saisissez la date à laquelle l\'ODF a été déposée. Cette date est importante pour le suivi administratif et légal de l\'organisation.'
        },
        'fichier_joint_depot_odf_help': {
            title: 'Fichier Joint Dépôt ODF',
            content: 'Téléchargez le fichier associé au dépôt de l\'ODF. Les formats acceptés sont : PDF, DOC, DOCX, JPG, JPEG, PNG. La taille maximale est de 10MB.'
        },
        'date_reçu_du_définition_help': {
            title: 'Date Réçu du Définition',
            content: 'Saisissez la date à laquelle le document de définition a été reçu. Ce champ est utilisé pour suivre la réception des documents officiels.'
        },
        'fichier_joint_reçu_du_définition_help': {
            title: 'Fichier Joint Réçu du Définition',
            content: 'Téléchargez le fichier du document de définition reçu. Les formats acceptés sont : PDF, DOC, DOCX, JPG, JPEG, PNG. La taille maximale est de 10MB.'
        },
        'step_date_help': {
            title: 'Date de l\'Étape',
            content: 'Saisissez la date à laquelle cette étape a eu lieu. Ce champ est obligatoire et permet de suivre chronologiquement les étapes de constitution de l\'ODF.'
        },
        'step_lieu_help': {
            title: 'Lieu de l\'Étape',
            content: 'Indiquez le lieu où cette étape s\'est déroulée. Ce champ est optionnel mais peut être utile pour la documentation.'
        },
        'step_participant_help': {
            title: 'Participants',
            content: 'Listez les participants à cette étape. Vous pouvez inclure les noms, rôles ou organisations des personnes présentes.'
        },
        'step_description_help': {
            title: 'Description de l\'Étape',
            content: 'Décrivez en détail ce qui s\'est passé lors de cette étape. Incluez les points importants discutés ou les actions entreprises.'
        },
        'step_resultat_help': {
            title: 'Résultat de l\'Étape',
            content: 'Indiquez les résultats ou conclusions de cette étape. Quels objectifs ont été atteints ou quelles décisions ont été prises ?'
        },
        'step_fichierjoin_help': {
            title: 'Fichier Joint de l\'Étape',
            content: 'Téléchargez un fichier associé à cette étape (compte-rendu, photos, documents, etc.). Les formats acceptés sont : PDF, DOC, DOCX, JPG, JPEG, PNG.'
        },
        'type_membre_help': {
            title: 'Type de Membre',
            content: 'Sélectionnez le type de membre dans l\'ODF : Présidente, Vice-Présidente, Trésorière, ou Membre. Ce champ permet de définir le rôle de la personne dans l\'organisation.'
        },
        'nom_membre_help': {
            title: 'Nom du Membre',
            content: 'Saisissez le nom complet du membre. Ce champ est obligatoire et doit contenir le prénom et le nom de famille.'
        },
        'n_cin_help': {
            title: 'Numéro de Carte d\'Identité Nationale',
            content: 'Saisissez le numéro de la Carte d\'Identité Nationale (CIN) du membre. Ce champ est optionnel mais recommandé pour l\'identification.'
        },
        'telephone_membre_help': {
            title: 'Téléphone du Membre',
            content: 'Saisissez le numéro de téléphone du membre. Ce champ est optionnel mais utile pour les communications.'
        },
        'email_membre_help': {
            title: 'Email du Membre',
            content: 'Saisissez l\'adresse email du membre. Ce champ est optionnel mais permet d\'envoyer des communications électroniques.'
        },
        'type_odf_help': {
            title: 'Type d\'ODF',
            content: 'Sélectionnez le type d\'ODF : Association, Coopérative, Entreprise, Élu, ou Citoyen. Ce champ définit la nature juridique ou le statut de l\'organisation.'
        },
        'domaine_activite_help': {
            title: 'Domaine d\'Activité',
            content: 'Indiquez le domaine d\'activité de l\'ODF. Ce champ décrit le secteur ou le type d\'activité principal de l\'organisation.'
        },
        'nombre_membres_help': {
            title: 'Nombre de Membres',
            content: 'Saisissez le nombre total de membres dans l\'ODF. Ce champ est optionnel mais permet de connaître la taille de l\'organisation.'
        },
        'commentaire_membre_help': {
            title: 'Commentaire',
            content: 'Ajoutez des commentaires ou notes additionnelles concernant ce membre. Ce champ est optionnel et peut être utilisé pour documenter des informations supplémentaires.'
        }
    };
    
    const help = helpContents[helpId];
    if (help) {
        document.getElementById('helpModalContent').innerHTML = `
            <h4 class="font-semibold text-gray-800 mb-2">${help.title}</h4>
            <p class="text-gray-600">${help.content}</p>
        `;
        document.getElementById('helpModal').classList.remove('hidden');
    }
}

function closeHelpModal() {
    document.getElementById('helpModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('helpModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeHelpModal();
    }
});
</script>
@endsection
