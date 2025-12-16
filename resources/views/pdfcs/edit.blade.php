@extends('layouts.app')

@section('title', 'Modifier un PDFC')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                        <i class="fas fa-edit text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #10b981, #059669); -webkit-background-clip: text; background-clip: text;">Modifier un PDFC</h1>
                        <p class="text-gray-600 text-lg mt-2">Modifier les informations du PDFC #{{ $pdfc->id }}</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pdfcs.show', $pdfc) }}" class="px-6 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-eye"></i>
                        Voir
                    </a>
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

    <!-- Edit Form -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                <i class="fas fa-edit text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #10b981, #059669); -webkit-background-clip: text; background-clip: text;">Formulaire de modification</h2>
                <p class="text-gray-600">Modifiez les informations du PDFC</p>
            </div>
        </div>

        <!-- Multi-step progress -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex items-center">
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold step-indicator-edit bg-green-600 text-white" data-step="1">
                            1
                        </div>
                        <span class="mt-1 text-xs font-medium text-gray-700">Informations</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 bg-green-500 step-line-edit" data-step="1"></div>
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold step-indicator-edit bg-gray-200 text-gray-600" data-step="2">
                            2
                        </div>
                        <span class="mt-1 text-xs font-medium text-gray-500">Localisation</span>
                    </div>
                    <div class="flex-1 h-1 mx-2 bg-gray-200 step-line-edit" data-step="2"></div>
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold step-indicator-edit bg-gray-200 text-gray-600" data-step="3">
                            3
                        </div>
                        <span class="mt-1 text-xs font-medium text-gray-500">Étapes PDFC</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('pdfcs.update', $pdfc) }}" method="POST" class="space-y-8" enctype="multipart/form-data" id="pdfcEditForm">
            @csrf
            @method('PUT')
            
            <!-- Step 1: Informations de Base -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200 wizard-step-edit" data-step="1">
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
                               value="{{ old('date_de_début', $pdfc->date_de_début ? $pdfc->date_de_début->format('Y-m-d') : '') }}"
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
                               value="{{ old('date_de_fin', $pdfc->date_de_fin ? $pdfc->date_de_fin->format('Y-m-d') : '') }}"
                               required>
                        @error('date_de_fin')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="etat_display" class="block text-sm font-semibold text-gray-700 mb-2">
                            État Actuel (automatique)
                        </label>
                        <div class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50">
                            @php
                                $stateColors = [
                                    'Non élaboré' => 'bg-gray-100 text-gray-800',
                                    'élaboré' => 'bg-blue-100 text-blue-800',
                                    'validé' => 'bg-yellow-100 text-yellow-800',
                                    'validé C.C' => 'bg-green-100 text-green-800',
                                ];
                                $colorClass = $stateColors[$pdfc->etat] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                                {{ $pdfc->etat }}
                            </span>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle"></i> L'état change automatiquement selon le cycle de vie du PDFC.
                            </p>
                        </div>
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
                                <option value="{{ $user->id }}" {{ old('user_id', $pdfc->user_id) == $user->id ? 'selected' : '' }}>
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

            <!-- Step 2: Localisation et Situation Administrative -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200 wizard-step-edit hidden" data-step="2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #3b82f6;">Localisation et Situation Administrative</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="localisation_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Localisation</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionner la localisation du PDFC"></i>
                        </label>
                        <select 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 @error('localisation_id') border-red-500 @enderror" 
                            id="localisation_id" 
                            name="localisation_id">
                            <option value="">Sélectionner une localisation</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ old('localisation_id', $pdfc->localisation_id) == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->CODE }} - {{ $localisation->DRANEF }}
                                </option>
                            @endforeach
                        </select>
                        @error('localisation_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="situation_administrative_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <span>Situation Administrative</span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sélectionner la situation administrative du PDFC"></i>
                        </label>
                        <select 
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-400 @error('situation_administrative_id') border-red-500 @enderror" 
                            id="situation_administrative_id" 
                            name="situation_administrative_id">
                            <option value="">Sélectionner une situation administrative</option>
                            @foreach($situationAdministratives as $situation)
                                <option value="{{ $situation->id }}" {{ old('situation_administrative_id', $pdfc->situation_administrative_id) == $situation->id ? 'selected' : '' }}>
                                    {{ $situation->commune }}@if($situation->province) - {{ $situation->province }}@endif
                                </option>
                            @endforeach
                        </select>
                        @error('situation_administrative_id')
                            <div class="text-red-500 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 3: Étapes du PDFC -->
            <div class="bg-gradient-to-r from-purple-50 to-fuchsia-50 rounded-2xl p-6 border border-purple-200 wizard-step-edit hidden" data-step="3">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-fuchsia-600 text-white">
                        <i class="fas fa-stream"></i>
                    </div>
                    <h3 class="text-xl font-bold text-fuchsia-700">Étapes du PDFC</h3>
                </div>

                @php
                    $steps = [
                        1 => ['label' => 'Diagnostic de la commune', 'relation' => 'etape1DiagnosticCommune'],
                        2 => ['label' => 'Diagnostic de la situation forestière', 'relation' => 'etape2DiagnosticSituationForestiere'],
                        3 => ['label' => 'Analyse des usagers des forêts', 'relation' => 'etape3AnalyseUsagersForet'],
                        4 => ['label' => 'Analyse du degré d’acceptation', 'relation' => 'etape4AnalyseDegreAcceptation'],
                        5 => ['label' => 'Analyse des programmes antérieurs', 'relation' => 'etape5AnalyseProgrammesAnterieur'],
                        6 => ['label' => 'Élaboration du projet / programme', 'relation' => 'etape6ElaborationProjetProgramme'],
                        7 => ['label' => 'Concertation avec la population', 'relation' => 'etape7ConcertationPopulation'],
                        8 => ['label' => 'Validation DPANEF', 'relation' => 'etape8ValidationDPANEF'],
                        9 => ['label' => 'Validation finale par la population', 'relation' => 'etape9ValidationFinalePopulation'],
                        10 => ['label' => 'Finalisation du PCFC', 'relation' => 'etape10FinalisationPCFC'],
                        11 => ['label' => 'Validation du Conseil Communal', 'relation' => 'etape11ValidationConseilCommunal'],
                        12 => ['label' => 'Mise en œuvre du PCFC', 'relation' => 'etape12MiseEnOeuvrePCFC'],
                        13 => ['label' => 'Suivi de la mise en œuvre', 'relation' => 'etape13SuiviMiseEnOeuvre'],
                    ];
                @endphp

                <div class="space-y-6">
                    @foreach($steps as $num => $step)
                        @php
                            $stepModel = $pdfc->{$step['relation']} ?? null;
                        @endphp
                        <div class="bg-white rounded-2xl border border-purple-100 p-5 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center bg-fuchsia-600 text-white text-sm font-bold">
                                        {{ $num }}
                                    </span>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $step['label'] }}</h4>
                                        <p class="text-xs text-gray-500">Modifier le titre, la description ou le document de cette étape.</p>
                                    </div>
                                </div>
                                @if($stepModel && $stepModel->updated_at)
                                    <span class="text-xs text-gray-400">
                                        Dernière mise à jour : {{ $stepModel->updated_at->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        Titre de l'étape
                                    </label>
                                    <input
                                        type="text"
                                        name="steps[{{ $num }}][titre]"
                                        value="{{ old('steps.'.$num.'.titre', $stepModel->titre ?? '') }}"
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                                        placeholder="Titre pour {{ strtolower($step['label']) }}"
                                    >
                                </div>
                                <div class="form-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        Document (optionnel)
                                    </label>
                                    <input
                                        type="file"
                                        name="steps[{{ $num }}][document]"
                                        class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                                    >
                                    @if($stepModel && $stepModel->document)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Document actuel :
                                            <a href="{{ asset('storage/'.$stepModel->document) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                                voir le fichier
                                            </a>
                                            (un nouveau fichier remplacera l'ancien)
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-400 mt-1">
                                            Aucun document actuellement associé à cette étape.
                                        </p>
                                    @endif
                                </div>
                                <div class="form-group md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        Description
                                    </label>
                                    <textarea
                                        name="steps[{{ $num }}][description]"
                                        rows="3"
                                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400"
                                        placeholder="Décrire le contenu de cette étape...">{{ old('steps.'.$num.'.description', $stepModel->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <p class="text-xs text-gray-500 mt-2">
                        Laisser les champs vides conserve les informations déjà enregistrées pour chaque étape.
                    </p>
                </div>
            </div>

            <!-- Wizard Actions -->
            <div class="flex items-center justify-between gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('pdfcs.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300 shadow-sm hover:shadow flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Annuler
                </a>
                <div class="flex gap-3">
                    <button type="button" id="wizardPrevEdit" class="hidden px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-chevron-left"></i>
                        Précédent
                    </button>
                    <button type="button" id="wizardNextEdit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <span>Suivant</span>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button type="submit" id="wizardSubmitEdit" class="hidden px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Enregistrer
                    </button>
                </div>
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
            this.value = document.getElementById('date_de_début').value;
        }
    });

    // Simple multi-step wizard for edit
    (function () {
        const steps = [1, 2, 3];
        let currentStep = 1;

        const stepElements = document.querySelectorAll('.wizard-step-edit');
        const indicators = document.querySelectorAll('.step-indicator-edit');
        const lines = document.querySelectorAll('.step-line-edit');
        const prevBtn = document.getElementById('wizardPrevEdit');
        const nextBtn = document.getElementById('wizardNextEdit');
        const submitBtn = document.getElementById('wizardSubmitEdit');

        function updateView() {
            stepElements.forEach(el => {
                const step = parseInt(el.getAttribute('data-step'));
                el.classList.toggle('hidden', step !== currentStep);
            });

            indicators.forEach(el => {
                const step = parseInt(el.getAttribute('data-step'));
                if (step === currentStep) {
                    el.classList.remove('bg-gray-200', 'text-gray-600');
                    el.classList.add('bg-green-600', 'text-white');
                } else if (step < currentStep) {
                    el.classList.remove('bg-gray-200', 'text-gray-600');
                    el.classList.add('bg-green-500', 'text-white');
                } else {
                    el.classList.add('bg-gray-200', 'text-gray-600');
                    el.classList.remove('bg-green-600', 'bg-green-500', 'text-white');
                }
            });

            lines.forEach(el => {
                const step = parseInt(el.getAttribute('data-step'));
                el.classList.toggle('bg-green-500', step < currentStep);
                el.classList.toggle('bg-gray-200', step >= currentStep);
            });

            prevBtn.classList.toggle('hidden', currentStep === 1);
            nextBtn.classList.toggle('hidden', currentStep === steps.length);
            submitBtn.classList.toggle('hidden', currentStep !== steps.length);
        }

        prevBtn?.addEventListener('click', function () {
            if (currentStep > 1) {
                currentStep--;
                updateView();
            }
        });

        nextBtn?.addEventListener('click', function () {
            if (currentStep < steps.length) {
                currentStep++;
                updateView();
            }
        });

        updateView();
    })();
</script>
@endpush

