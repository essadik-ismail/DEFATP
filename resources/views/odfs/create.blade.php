@extends('layouts.app')

@section('title', 'Nouvelle ODF - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br rounded-2xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #8b5cf6, #7c3aed); -webkit-background-clip: text; background-clip: text;">
                    Nouvelle ODF
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez une nouvelle Organisation de la Femme</p>
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
                        @php
                            $uniqueErrors = array_unique($errors->all());
                        @endphp
                        @foreach ($uniqueErrors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
    @endif

    <!-- Create Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold" style="color: #8b5cf6;">Formulaire de création</h2>
                <p class="text-gray-600">Remplissez les informations pour créer une nouvelle ODF</p>
            </div>
        </div>

        <form action="{{ route('odfs.store') }}" method="POST" id="odfForm" class="space-y-8">
        @csrf

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="step-indicator active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Informations de Base</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-indicator" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Diagnostic</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-indicator" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Sélection Membres</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-indicator" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Négociation</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-indicator" data-step="5">
                            <div class="step-number">5</div>
                            <div class="step-label">Constitution</div>
                        </div>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-2 rounded-full transition-all duration-300" id="progress-bar" style="width: 20%"></div>
                </div>
            </div>
            
            <!-- Step 1: Informations de Base -->
            <div class="step-content" data-step="1">
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl p-6 border border-purple-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <h3 class="text-xl font-bold" style="color: #8b5cf6;">Informations de Base</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="odf_entite_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <span>ODF Entité <span class="text-red-500">*</span></span>
                                <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionnez l'entité ODF"></i>
                    </label>
                    <select 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                        id="odf_entite_id" 
                                name="odf_entite_id"
                                required>
                        <option value="">Sélectionner une entité ODF</option>
                        @foreach($odfEntites as $entite)
                            <option value="{{ $entite->id }}" {{ old('odf_entite_id') == $entite->id ? 'selected' : '' }}>
                                {{ $entite->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('odf_entite_id')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

            <div class="form-group">
                <label for="commentaire" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <span>Commentaire</span>
                                <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Commentaires additionnels"></i>
                </label>
                <textarea 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                    id="commentaire" 
                    name="commentaire" 
                    rows="4"
                    placeholder="Commentaires...">{{ old('commentaire') }}</textarea>
                @error('commentaire')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
                </div>
            </div>
            <!-- End Step 1 -->

            <!-- Step 2: Diagnostic -->
            <div class="step-content hidden" data-step="2">
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl p-6 border border-blue-200">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #06b6d4);">
                                <i class="fas fa-stethoscope text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold" style="color: #3b82f6;">Diagnostic</h3>
                        </div>
                        <button type="button" 
                                onclick="addDiagnostic()" 
                                class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg transition-all duration-300 text-sm"
                                style="background: linear-gradient(to right, #3b82f6, #06b6d4);"
                                onmouseover="this.style.background='linear-gradient(to right, #2563eb, #0891b2)'"
                                onmouseout="this.style.background='linear-gradient(to right, #3b82f6, #06b6d4)'">
                            <i class="fas fa-plus"></i>
                            Ajouter Diagnostic
                        </button>
                    </div>
                    
                    <div id="diagnostics-container">
                        <!-- Diagnostics will be added dynamically here -->
                    </div>
                </div>
            </div>
            <!-- End Step 2 -->

            <!-- Step 3: Sélection des Membres -->
            <div class="step-content hidden" data-step="3">
                <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-2xl p-6 border border-pink-200">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #ec4899, #db2777);">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold" style="color: #ec4899;">Sélection des Membres</h3>
                        </div>
                        <button type="button" 
                                onclick="addMember()" 
                                class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg transition-all duration-300 text-sm"
                                style="background: linear-gradient(to right, #ec4899, #db2777);"
                                onmouseover="this.style.background='linear-gradient(to right, #db2777, #be185d)'"
                                onmouseout="this.style.background='linear-gradient(to right, #ec4899, #db2777)'">
                            <i class="fas fa-plus"></i>
                            Ajouter Membre
                        </button>
                    </div>
                    
                    <div id="members-container">
                        <!-- Members will be added dynamically here -->
                    </div>
                </div>
            </div>
            <!-- End Step 3 -->

            <!-- Step 4: Négociation et Mobilisation Participative -->
            <div class="step-content hidden" data-step="4">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                                <i class="fas fa-handshake text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold" style="color: #10b981;">Négociation et Mobilisation Participative</h3>
                        </div>
                        <button type="button" 
                                onclick="addOdfEtap()" 
                                class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-lg transition-all duration-300 text-sm"
                                style="background: linear-gradient(to right, #10b981, #059669);"
                                onmouseover="this.style.background='linear-gradient(to right, #059669, #047857)'"
                                onmouseout="this.style.background='linear-gradient(to right, #10b981, #059669)'">
                            <i class="fas fa-plus"></i>
                            Ajouter Étape
                        </button>
                    </div>
                    
                    <div id="odf-etaps-container">
                        <!-- ODF Etaps will be added dynamically here -->
                    </div>
                </div>
            </div>
            <!-- End Step 4 -->

            <!-- Step 5: Constitution -->
            <div class="step-content hidden" data-step="5">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #f59e0b, #d97706);">
                            <i class="fas fa-file-contract text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold" style="color: #f59e0b;">Constitution</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="constitution_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-amber-500 mr-1"></i>Date
                            </label>
                            <input type="date" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400" 
                                   id="constitution_date" 
                                   name="constitution[date]" 
                                   value="{{ old('constitution.date') }}">
                        </div>

                        <div class="form-group">
                            <label for="constitution_lieu" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-amber-500 mr-1"></i>Lieu
                            </label>
                            <input type="text" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400" 
                                   id="constitution_lieu" 
                                   name="constitution[lieu]" 
                                   value="{{ old('constitution.lieu') }}"
                                   placeholder="Lieu de constitution">
                        </div>

                        <div class="form-group md:col-span-2">
                            <label for="constitution_participant" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-users text-amber-500 mr-1"></i>Participant
                            </label>
                            <textarea 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400" 
                                id="constitution_participant" 
                                name="constitution[participant]" 
                                rows="3"
                                placeholder="Liste des participants...">{{ old('constitution.participant') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="constitution_date_depot_odf" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-plus text-amber-500 mr-1"></i>Date de Dépôt ODF
                            </label>
                            <input type="date" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400" 
                                   id="constitution_date_depot_odf" 
                                   name="constitution[date_depot_odf]" 
                                   value="{{ old('constitution.date_depot_odf') }}">
                        </div>

                        <div class="form-group">
                            <label for="constitution_lieu_depot_odf" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-amber-500 mr-1"></i>Lieu de Dépôt ODF
                            </label>
                            <input type="text" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400" 
                                   id="constitution_lieu_depot_odf" 
                                   name="constitution[lieu_depot_odf]" 
                                   value="{{ old('constitution.lieu_depot_odf') }}"
                                   placeholder="Lieu de dépôt">
                        </div>

                        <div class="form-group">
                            <label for="constitution_fichier_joint_depot_odf" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file text-amber-500 mr-1"></i>Fichier Joint Dépôt ODF
                            </label>
                            <input type="text" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400" 
                                   id="constitution_fichier_joint_depot_odf" 
                                   name="constitution[fichier_joint_depot_odf]" 
                                   value="{{ old('constitution.fichier_joint_depot_odf') }}"
                                   placeholder="Nom du fichier">
                        </div>

                        <div class="form-group">
                            <label for="constitution_date_reçu_définitive" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-check text-amber-500 mr-1"></i>Date de Réception Définitive
                            </label>
                            <input type="date" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400" 
                                   id="constitution_date_reçu_définitive" 
                                   name="constitution[date_reçu_définitive]" 
                                   value="{{ old('constitution.date_reçu_définitive') }}">
                        </div>

                        <div class="form-group">
                            <label for="constitution_lieu_reçu_définitive" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-amber-500 mr-1"></i>Lieu de Réception Définitive
                            </label>
                            <input type="text" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400" 
                                   id="constitution_lieu_reçu_définitive" 
                                   name="constitution[lieu_reçu_définitive]" 
                                   value="{{ old('constitution.lieu_reçu_définitive') }}"
                                   placeholder="Lieu de réception">
                        </div>

                        <div class="form-group">
                            <label for="constitution_fichier_joint_reçu_définitive" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-file text-amber-500 mr-1"></i>Fichier Joint Réception Définitive
                            </label>
                            <input type="text" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 hover:border-gray-400" 
                                   id="constitution_fichier_joint_reçu_définitive" 
                                   name="constitution[fichier_joint_reçu_définitive]" 
                                   value="{{ old('constitution.fichier_joint_reçu_définitive') }}"
                                   placeholder="Nom du fichier">
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Step 4 -->

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-8 sticky bottom-0 bg-white pb-4 z-10">
                <div>
                    <button type="button" 
                            id="prevBtn" 
                            onclick="changeStep(-1)"
                            class="hidden inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>Précédent</span>
                    </button>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('odfs.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                        <i class="fas fa-times"></i>
                        <span>Annuler</span>
                    </a>
                    <button type="button" 
                            id="nextBtn" 
                            onclick="changeStep(1)"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg"
                            style="display: inline-flex !important; visibility: visible !important; opacity: 1 !important;">
                        <span>Suivant</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="button" 
                            id="saveDraftBtn"
                            onclick="saveOdfDraft()"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-file-alt"></i>
                        <span>Enregistrer comme brouillon</span>
                    </button>
                    <button type="submit" 
                            id="submitBtn"
                            class="hidden inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-save"></i>
                        <span>Enregistrer</span>
                    </button>
                </div>
            </div>
        </form>
        <!-- Draft Saved Indicator -->
        <div id="draftIndicator" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>Brouillon ODF enregistré avec succès</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let diagnosticCount = 0;
let memberCount = 0;
let odfEtapCount = 0;

// Add new diagnostic row
function addDiagnostic() {
    diagnosticCount++;
    const container = document.getElementById('diagnostics-container');
    
    const diagnosticRow = document.createElement('div');
    diagnosticRow.className = 'diagnostic-row flex flex-col gap-4 mb-4 p-4 bg-white rounded-xl border border-gray-200';
    diagnosticRow.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h4 class="text-lg font-semibold text-gray-800">Diagnostic #${diagnosticCount}</h4>
                <button type="button"
                        class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold rounded-lg text-white"
                        style="background: linear-gradient(to right, #3b82f6, #06b6d4);">
                    <i class="fas fa-check"></i>
                    Créer
                </button>
            </div>
            <button type="button" 
                    onclick="removeDiagnostic(this)" 
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                <i class="fas fa-minus"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                <select name="diagnostics[${diagnosticCount}][type]" 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                        onchange="updateMemberDiagnosticOptions()">
                    <option value="">Sélectionner un type</option>
                    <option value="associations">Associations</option>
                    <option value="coopératives">Coopératives</option>
                    <option value="titulaires_amodiations">Titulaires d'amodiations</option>
                    <option value="nouabs des collectivités ethniques">Nouabs des collectivités ethniques</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                <input type="text" 
                       name="diagnostics[${diagnosticCount}][nom]" 
                       placeholder="Nom du diagnostic" 
                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Activité</label>
                <input type="text" 
                       name="diagnostics[${diagnosticCount}][activité]" 
                       placeholder="Activité" 
                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Présidente</label>
                <input type="text" 
                       name="diagnostics[${diagnosticCount}][présidente]" 
                       placeholder="Présidente" 
                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre de Membres</label>
                <input type="number" 
                       name="diagnostics[${diagnosticCount}][nombre_de_membres]" 
                       placeholder="Nombre de membres" 
                       min="0"
                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    `;
    
    container.appendChild(diagnosticRow);
    
    // Update member diagnostic options if we're on step 3 or later
    if (currentStep >= 3) {
        updateMemberDiagnosticOptions();
    }
}
// Expose to global scope for inline onclick handlers
window.addDiagnostic = addDiagnostic;

// Remove diagnostic row
function removeDiagnostic(button) {
    const diagnosticRow = button.closest('.diagnostic-row');
    diagnosticRow.remove();
    
    // Update member diagnostic options
    if (currentStep >= 3) {
        updateMemberDiagnosticOptions();
    }
}
window.removeDiagnostic = removeDiagnostic;

// Add new member row
function addMember() {
    memberCount++;
    const container = document.getElementById('members-container');
    
    // Get available diagnostics for this member
    const diagnostics = [];
    document.querySelectorAll('.diagnostic-row').forEach((row, index) => {
        const typeSelect = row.querySelector('select[name*="[type]"]');
        if (typeSelect && typeSelect.value) {
            diagnostics.push({
                index: index + 1,
                type: typeSelect.value
            });
        }
    });
    
    const memberRow = document.createElement('div');
    memberRow.className = 'member-row flex flex-col gap-4 mb-4 p-4 bg-white rounded-xl border border-gray-200';
    memberRow.innerHTML = `
        <div class="flex items-center justify-between">
            <h4 class="text-lg font-semibold text-gray-800">Membre #${memberCount}</h4>
            <button type="button" 
                    onclick="removeMember(this)" 
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                <i class="fas fa-minus"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="members[${memberCount}][type]" 
                       placeholder="Type de membre" 
                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                       required>
            </div>
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Diagnostic Associé</label>
                <select name="members[${memberCount}][odf_diagnostic_index]" 
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    <option value="">Aucun diagnostic</option>
                    ${diagnostics.map(d => `<option value="${d.index}">Diagnostic #${d.index} - ${d.type}</option>`).join('')}
                </select>
                <p class="text-xs text-gray-500 mt-1">Sélectionnez un diagnostic si ce membre y est associé</p>
            </div>
        </div>
    `;
    
    container.appendChild(memberRow);
}
window.addMember = addMember;

// Remove member row
function removeMember(button) {
    const memberRow = button.closest('.member-row');
    memberRow.remove();
}
window.removeMember = removeMember;

// Add new ODF Etap row
function addOdfEtap() {
    odfEtapCount++;
    const container = document.getElementById('odf-etaps-container');
    
    // Build diagnostics list to use as participants
    const diagnostics = [];
    document.querySelectorAll('.diagnostic-row').forEach((row, index) => {
        const typeSelect = row.querySelector('select[name*="[type]"]');
        const nameInput = row.querySelector('input[name*="[nom]"]');
        if (typeSelect && typeSelect.value) {
            let label = typeSelect.value;
            if (nameInput && nameInput.value) {
                label += ' - ' + nameInput.value;
            }
            diagnostics.push({
                index: index + 1,
                label: label
            });
        }
    });
    
    // Build HTML for participants (avoid nested template literals to prevent syntax errors)
    let participantsHtml = '';
    if (diagnostics.length) {
        participantsHtml = diagnostics.map(function(d) {
            return '' +
                '<label class="inline-flex items-center px-3 py-1 rounded-full border border-green-300 bg-green-50 text-xs text-green-800 cursor-pointer">' +
                    '<input type="checkbox" ' +
                           'class="mr-2" ' +
                           'name="odf_etaps[' + odfEtapCount + '][participants][]" ' +
                           'value="' + d.index + '">' +
                    '<span>Diagnostic #' + d.index + ' - ' + d.label + '</span>' +
                '</label>';
        }).join('');
    } else {
        participantsHtml = '<p class="text-xs text-gray-500">Ajoutez d\'abord des diagnostics à l\'étape 2 pour sélectionner des participants.</p>';
    }

    const etapRow = document.createElement('div');
    etapRow.className = 'etap-row flex flex-col gap-4 mb-4 p-4 bg-white rounded-xl border border-gray-200';
    etapRow.innerHTML = `
        <div class="flex items-center justify-between">
            <h4 class="text-lg font-semibold text-gray-800">Étape #${odfEtapCount}</h4>
            <button type="button" 
                    onclick="removeOdfEtap(this)" 
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300">
                <i class="fas fa-minus"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Objet</label>
                <input type="text" 
                       name="odf_etaps[${odfEtapCount}][objet]" 
                       placeholder="Objet de l'étape" 
                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Lieu</label>
                <input type="text" 
                       name="odf_etaps[${odfEtapCount}][lieu]" 
                       placeholder="Lieu" 
                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                <input type="date" 
                       name="odf_etaps[${odfEtapCount}][date]" 
                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="form-group">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                <input type="text" 
                       name="odf_etaps[${odfEtapCount}][type]" 
                       placeholder="Type d'étape" 
                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="form-group md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="odf_etaps[${odfEtapCount}][description]" 
                          placeholder="Description de l'étape" 
                          rows="3"
                          class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
            </div>
            <div class="form-group md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Résultat</label>
                <textarea name="odf_etaps[${odfEtapCount}][resultat]" 
                          placeholder="Résultat de l'étape" 
                          rows="3"
                          class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
            </div>
            <div class="form-group md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Participants (issus des diagnostics)</label>
                <div class="flex flex-wrap gap-2">
                    ${participantsHtml}
                </div>
                <p class="text-xs text-gray-500 mt-1">Les participants sont basés sur les diagnostics (type + nom).</p>
            </div>
        </div>
    `;
    
    container.appendChild(etapRow);
}
window.addOdfEtap = addOdfEtap;

// Remove ODF Etap row
function removeOdfEtap(button) {
    const etapRow = button.closest('.etap-row');
    etapRow.remove();
}
window.removeOdfEtap = removeOdfEtap;

// Multi-step form functionality
let currentStep = 1;
const totalSteps = 5;

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Show current step
    const currentStepContent = document.querySelector(`.step-content[data-step="${step}"]`);
    if (currentStepContent) {
        currentStepContent.classList.remove('hidden');
    }
    
    // Update step indicators
    document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
        const stepNum = index + 1;
        if (stepNum < step) {
            indicator.classList.add('completed');
            indicator.classList.remove('active');
        } else if (stepNum === step) {
            indicator.classList.add('active');
            indicator.classList.remove('completed');
        } else {
            indicator.classList.remove('active', 'completed');
        }
    });
    
    // Update progress bar
    const progressBar = document.getElementById('progress-bar');
    if (progressBar) {
        const progress = (step / totalSteps) * 100;
        progressBar.style.width = progress + '%';
    }
    
    // Update member diagnostic dropdowns when moving to step 3
    if (step === 3) {
        updateMemberDiagnosticOptions();
    }
    
    // Update navigation buttons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    if (step === 1) {
        prevBtn.classList.add('hidden');
    } else {
        prevBtn.classList.remove('hidden');
    }
    
    if (step === totalSteps) {
        nextBtn.classList.add('hidden');
        nextBtn.style.display = 'none';
        submitBtn.classList.remove('hidden');
        submitBtn.style.display = 'inline-flex';
    } else {
        nextBtn.classList.remove('hidden');
        nextBtn.style.display = 'inline-flex';
        submitBtn.classList.add('hidden');
        submitBtn.style.display = 'none';
    }
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function changeStep(direction) {
    const newStep = currentStep + direction;
    
    if (newStep < 1 || newStep > totalSteps) {
        return;
    }
    
    // Validate current step before moving
    if (direction > 0 && !validateStep(currentStep)) {
        return;
    }
    
    currentStep = newStep;
    showStep(currentStep);
}
window.changeStep = changeStep;

function validateStep(step) {
    const stepContent = document.querySelector(`.step-content[data-step="${step}"]`);
    if (!stepContent) return true;
    
    const requiredFields = stepContent.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value || (field.type === 'checkbox' && !field.checked)) {
            isValid = false;
            field.classList.add('border-red-500');
            
            // Remove error class after user interaction
            field.addEventListener('input', function() {
                this.classList.remove('border-red-500');
            }, { once: true });
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        alert('Veuillez remplir tous les champs obligatoires avant de continuer.');
    }
    
    return isValid;
}

// Update member diagnostic options based on available diagnostics
function updateMemberDiagnosticOptions() {
    const diagnostics = [];
    document.querySelectorAll('.diagnostic-row').forEach((row, index) => {
        const typeSelect = row.querySelector('select[name*="[type]"]');
        if (typeSelect && typeSelect.value) {
            diagnostics.push({
                index: index + 1,
                type: typeSelect.value
            });
        }
    });
    
    // Update all member diagnostic selects
    document.querySelectorAll('select[name*="[odf_diagnostic_index]"]').forEach(select => {
        const currentValue = select.value;
        select.innerHTML = '<option value="">Aucun diagnostic</option>' + 
            diagnostics.map(d => `<option value="${d.index}">Diagnostic #${d.index} - ${d.type}</option>`).join('');
        if (currentValue) {
            select.value = currentValue;
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    showStep(1);

    // Proposer de charger le brouillon existant
    try {
        const draftStr = localStorage.getItem('odf_draft');
        if (draftStr) {
            const shouldLoad = confirm('Un brouillon ODF existe. Voulez-vous le charger ?');
            if (shouldLoad) {
                loadOdfDraft();
            }
        }
    } catch (e) {
        console.error('Erreur lors du chargement du brouillon ODF:', e);
    }
});

// --- Gestion des brouillons ODF (localStorage) ---

function collectOdfFormData() {
    const form = document.getElementById('odfForm');
    if (!form) return null;

    const data = {};

    // Champs simples
    data.odf_entite_id = form.querySelector('[name="odf_entite_id"]')?.value || '';
    data.commentaire = form.querySelector('[name="commentaire"]')?.value || '';

    // Diagnostics
    data.diagnostics = [];
    document.querySelectorAll('.diagnostic-row').forEach(row => {
        const type = row.querySelector('select[name*="[type]"]')?.value || '';
        const nom = row.querySelector('input[name*="[nom]"]')?.value || '';
        const activite = row.querySelector('input[name*="[activité]"]')?.value || '';
        const presidente = row.querySelector('input[name*="[présidente]"]')?.value || '';
        const nbMembres = row.querySelector('input[name*="[nombre_de_membres]"]')?.value || '';
        if (type || nom || activite || presidente || nbMembres) {
            data.diagnostics.push({ type, nom, activité: activite, présidente: presidente, nombre_de_membres: nbMembres });
        }
    });

    // Membres
    data.members = [];
    document.querySelectorAll('.member-row').forEach(row => {
        const type = row.querySelector('input[name*="[type]"]')?.value || '';
        const diagIndex = row.querySelector('select[name*="[odf_diagnostic_index]"]')?.value || '';
        if (type || diagIndex) {
            data.members.push({ type, odf_diagnostic_index: diagIndex });
        }
    });

    // Étapes
    data.odf_etaps = [];
    document.querySelectorAll('.etap-row').forEach(row => {
        const objet = row.querySelector('input[name*="[objet]"]')?.value || '';
        const lieu = row.querySelector('input[name*="[lieu]"]')?.value || '';
        const date = row.querySelector('input[name*="[date]"]')?.value || '';
        const type = row.querySelector('input[name*="[type]"]')?.value || '';
        const description = row.querySelector('textarea[name*="[description]"]')?.value || '';
        const resultat = row.querySelector('textarea[name*="[resultat]"]')?.value || '';
        const participants = [];
        row.querySelectorAll('input[type="checkbox"][name*="[participants]"]:checked').forEach(cb => {
            participants.push(cb.value);
        });
        if (objet || lieu || date || type || description || resultat || participants.length) {
            data.odf_etaps.push({ objet, lieu, date, type, description, resultat, participants });
        }
    });

    // Constitution
    data.constitution = {
        date: document.getElementById('constitution_date')?.value || '',
        lieu: document.getElementById('constitution_lieu')?.value || '',
        participant: document.getElementById('constitution_participant')?.value || '',
        date_depot_odf: document.getElementById('constitution_date_depot_odf')?.value || '',
        fichier_joint_depot_odf: document.getElementById('constitution_fichier_joint_depot_odf')?.value || '',
        lieu_depot_odf: document.getElementById('constitution_lieu_depot_odf')?.value || '',
        date_reçu_définitive: document.getElementById('constitution_date_reçu_définitive')?.value || '',
        fichier_joint_reçu_définitive: document.getElementById('constitution_fichier_joint_reçu_définitive')?.value || '',
        lieu_reçu_définitive: document.getElementById('constitution_lieu_reçu_définitive')?.value || '',
    };

    // Étape courante
    data.currentStep = currentStep;

    return data;
}

function saveOdfDraft() {
    const data = collectOdfFormData();
    if (!data) return;

    try {
        const draft = {
            savedAt: new Date().toISOString(),
            data,
        };
        localStorage.setItem('odf_draft', JSON.stringify(draft));

        const indicator = document.getElementById('draftIndicator');
        if (indicator) {
            indicator.classList.remove('hidden');
            setTimeout(() => indicator.classList.add('hidden'), 3000);
        }
    } catch (e) {
        console.error('Erreur lors de l\'enregistrement du brouillon ODF:', e);
        alert('Erreur lors de l\'enregistrement du brouillon.');
    }
}
window.saveOdfDraft = saveOdfDraft;

function loadOdfDraft() {
    try {
        const draftStr = localStorage.getItem('odf_draft');
        if (!draftStr) return;
        const draft = JSON.parse(draftStr);
        const data = draft.data || {};

        const form = document.getElementById('odfForm');
        if (!form) return;

        // Base
        if (data.odf_entite_id !== undefined) {
            const sel = form.querySelector('[name="odf_entite_id"]');
            if (sel) sel.value = data.odf_entite_id;
        }
        if (data.commentaire !== undefined) {
            const ta = form.querySelector('[name="commentaire"]');
            if (ta) ta.value = data.commentaire;
        }

        // Clear existants
        document.querySelectorAll('.diagnostic-row').forEach(r => r.remove());
        diagnosticCount = 0;
        document.querySelectorAll('.member-row').forEach(r => r.remove());
        memberCount = 0;
        document.querySelectorAll('.etap-row').forEach(r => r.remove());
        odfEtapCount = 0;

        // Diagnostics
        if (Array.isArray(data.diagnostics)) {
            data.diagnostics.forEach(d => {
                addDiagnostic();
                const rows = document.querySelectorAll('.diagnostic-row');
                const row = rows[rows.length - 1];
                if (!row) return;
                row.querySelector('select[name*="[type]"]')?.value = d.type || '';
                row.querySelector('input[name*="[nom]"]')?.value = d.nom || '';
                row.querySelector('input[name*="[activité]"]')?.value = d.activité || '';
                row.querySelector('input[name*="[présidente]"]')?.value = d.présidente || '';
                row.querySelector('input[name*="[nombre_de_membres]"]')?.value = d.nombre_de_membres || '';
            });
        }

        // Membres
        if (Array.isArray(data.members)) {
            data.members.forEach(m => {
                addMember();
                const rows = document.querySelectorAll('.member-row');
                const row = rows[rows.length - 1];
                if (!row) return;
                row.querySelector('input[name*="[type]"]')?.value = m.type || '';
                row.querySelector('select[name*="[odf_diagnostic_index]"]')?.value = m.odf_diagnostic_index || '';
            });
        }

        // Étapes
        if (Array.isArray(data.odf_etaps)) {
            data.odf_etaps.forEach(e => {
                addOdfEtap();
                const rows = document.querySelectorAll('.etap-row');
                const row = rows[rows.length - 1];
                if (!row) return;
                row.querySelector('input[name*="[objet]"]')?.value = e.objet || '';
                row.querySelector('input[name*="[lieu]"]')?.value = e.lieu || '';
                row.querySelector('input[name*="[date]"]')?.value = e.date || '';
                row.querySelector('input[name*="[type]"]')?.value = e.type || '';
                row.querySelector('textarea[name*="[description]"]')?.value = e.description || '';
                row.querySelector('textarea[name*="[resultat]"]')?.value = e.resultat || '';
                if (Array.isArray(e.participants)) {
                    row.querySelectorAll('input[type="checkbox"][name*="[participants]"]').forEach(cb => {
                        if (e.participants.includes(cb.value)) cb.checked = true;
                    });
                }
            });
        }

        // Constitution
        if (data.constitution) {
            document.getElementById('constitution_date')?.value = data.constitution.date || '';
            document.getElementById('constitution_lieu')?.value = data.constitution.lieu || '';
            document.getElementById('constitution_participant')?.value = data.constitution.participant || '';
            document.getElementById('constitution_date_depot_odf')?.value = data.constitution.date_depot_odf || '';
            document.getElementById('constitution_fichier_joint_depot_odf')?.value = data.constitution.fichier_joint_depot_odf || '';
            document.getElementById('constitution_lieu_depot_odf')?.value = data.constitution.lieu_depot_odf || '';
            document.getElementById('constitution_date_reçu_définitive')?.value = data.constitution.date_reçu_définitive || '';
            document.getElementById('constitution_fichier_joint_reçu_définitive')?.value = data.constitution.fichier_joint_reçu_définitive || '';
            document.getElementById('constitution_lieu_reçu_définitive')?.value = data.constitution.lieu_reçu_définitive || '';
        }

        // Étape courante
        if (data.currentStep) {
            currentStep = data.currentStep;
            showStep(currentStep);
        }
    } catch (e) {
        console.error('Erreur lors du chargement du brouillon ODF:', e);
        alert('Erreur lors du chargement du brouillon.');
    }
}
</script>

<style>
.step-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.3s ease;
    border: 2px solid #e5e7eb;
}

.step-indicator.active .step-number {
    background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);
    color: white;
    border-color: #8b5cf6;
    transform: scale(1.1);
}

.step-indicator.completed .step-number {
    background: linear-gradient(to bottom right, #a78bfa, #8b5cf6);
    color: white;
    border-color: #a78bfa;
}

.step-label {
    margin-top: 8px;
    font-size: 0.75rem;
    color: #6b7280;
    text-align: center;
    font-weight: 500;
}

.step-indicator.active .step-label {
    color: #8b5cf6;
    font-weight: 600;
}

.step-indicator.completed .step-label {
    color: #a78bfa;
}

.step-line {
    flex: 1;
    height: 2px;
    background: #e5e7eb;
    margin: 0 8px;
    margin-top: -20px;
    z-index: -1;
}

.step-content {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .step-indicator {
        flex: 0 0 auto;
    }
    
    .step-label {
        display: none;
    }
    
    .step-line {
        display: none;
    }
}
</style>
@endpush
