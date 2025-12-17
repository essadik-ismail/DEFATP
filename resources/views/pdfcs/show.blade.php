@extends('layouts.app')

@section('title', 'Détails du PDFC')

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
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #10b981, #059669); -webkit-background-clip: text; background-clip: text;">PDFC #{{ $pdfc->id }}</h1>
                        <p class="text-gray-600 text-lg mt-2">Détails du PDFC</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    @can('pdfcs.update')
                    <a href="{{ route('pdfcs.edit', $pdfc) }}" class="px-6 py-3 bg-orange-500 text-white rounded-xl hover:bg-orange-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                    @endcan
                    <a href="{{ route('pdfcs.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Cycle de Vie du PDFC -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                            <i class="fas fa-sync-alt text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Cycle de Vie</h2>
                    </div>
                </div>
                
                <!-- Cycle de Vie Timeline -->
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        @php
                            $states = ['Non élaboré', 'élaboré', 'validé', 'validé C.C'];
                            $currentIndex = array_search($pdfc->etat, $states);
                        @endphp
                        @foreach($states as $index => $state)
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2 
                                    {{ $index <= $currentIndex ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}
                                    {{ $index == $currentIndex ? 'ring-4 ring-green-300' : '' }}">
                                    <i class="fas fa-{{ $index == 0 ? 'plus' : ($index == 1 ? 'edit' : ($index == 2 ? 'check' : 'check-double')) }}"></i>
                                </div>
                                <span class="text-xs font-semibold text-center {{ $index <= $currentIndex ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $state }}
                                </span>
                            </div>
                            @if($index < count($states) - 1)
                                <div class="flex-1 h-1 mx-2 {{ $index < $currentIndex ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Informations de Base -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Informations de Base</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Date de Début</label>
                        <p class="text-lg font-medium text-gray-900">
                            {{ $pdfc->date_de_début ? $pdfc->date_de_début->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Date de Fin</label>
                        <p class="text-lg font-medium text-gray-900">
                            {{ $pdfc->date_de_fin ? $pdfc->date_de_fin->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>
                    @if($pdfc->localisation || $pdfc->situationAdministrative)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Localisation et Situation Administrative</label>
                        <div class="space-y-2">
                            @if($pdfc->localisation)
                            <p class="text-lg font-medium text-gray-900">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                <span class="font-semibold">Localisation:</span> {{ $pdfc->localisation->CODE }} - {{ $pdfc->localisation->DRANEF }}
                            </p>
                            @endif
                            @if($pdfc->situationAdministrative)
                            <p class="text-lg font-medium text-gray-900">
                                <i class="fas fa-building text-emerald-500 mr-2"></i>
                                <span class="font-semibold">Situation Administrative:</span> {{ $pdfc->situationAdministrative->commune }}@if($pdfc->situationAdministrative->province) - {{ $pdfc->situationAdministrative->province }}@endif
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">État Actuel</label>
                        <p class="text-lg font-medium text-gray-900">
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
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Durée</label>
                        <p class="text-lg font-medium text-gray-900">
                            @if($pdfc->date_de_début && $pdfc->date_de_fin)
                                {{ $pdfc->date_de_début->diffInDays($pdfc->date_de_fin) }} jours
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Utilisateur -->
            @if($pdfc->user)
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-100">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Utilisateur Assigné</h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-semibold">{{ strtoupper(substr($pdfc->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $pdfc->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $pdfc->user->email }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Étapes PDFC (13 étapes nommées, affichées une par une) -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-fuchsia-100">
                            <i class="fas fa-stream text-fuchsia-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Étapes du PDFC</h2>
                            <p class="text-xs text-gray-500">Parcourir les étapes une par une dans l'ordre.</p>
                        </div>
                    </div>
                </div>

                @php
                    $stepsDefinitions = [
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
                    $totalSteps = count($stepsDefinitions);
                @endphp

                <div id="pdfcStepsContainer" class="space-y-4">
                    @foreach($stepsDefinitions as $num => $step)
                        @php
                            $stepModel = $pdfc->{$step['relation']};
                        @endphp
                        <div class="pdfc-step-card bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl border border-gray-200 p-4 flex flex-col gap-4 {{ $num === 1 ? '' : 'hidden' }}" data-step="{{ $num }}">
                            <div class="flex items-start gap-3">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center bg-fuchsia-600 text-white text-sm font-bold mt-1">
                                    {{ $num }}
                                </span>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">
                                        {{ $step['label'] }}
                                    </h3>
                                    @if($stepModel)
                                        <p class="text-sm text-gray-700 mt-1">
                                            <span class="font-semibold">Titre :</span>
                                            {{ $stepModel->titre ?? '—' }}
                                        </p>
                                        @if($stepModel->description)
                                            <p class="text-xs text-gray-600 mt-1">
                                                {{ Str::limit($stepModel->description, 200) }}
                                            </p>
                                        @endif
                                        <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                            @if($stepModel->document)
                                                <a href="{{ asset('storage/'.$stepModel->document) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-file-alt"></i>
                                                    Voir le document
                                                </a>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-gray-400">
                                                    <i class="fas fa-file"></i>
                                                    Aucun document
                                                </span>
                                            @endif
                                            @if($stepModel->user)
                                                <span class="inline-flex items-center gap-1">
                                                    <i class="fas fa-user-circle"></i>
                                                    {{ $stepModel->user->name }}
                                                </span>
                                            @endif
                                            @if($stepModel->created_at)
                                                <span class="inline-flex items-center gap-1">
                                                    <i class="fas fa-clock"></i>
                                                    {{ $stepModel->created_at->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-400 mt-1">
                                            Aucune information renseignée pour cette étape.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 flex items-center justify-between border-t border-gray-200 pt-4">
                    <button id="pdfcStepPrev"
                            type="button"
                            class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors text-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left text-xs"></i>
                        Précédent
                    </button>
                    <div class="text-xs text-gray-500">
                        Étape <span id="pdfcStepCurrent">1</span> / {{ $totalSteps }}
                    </div>
                    <button id="pdfcStepNext"
                            type="button"
                            class="px-4 py-2 rounded-lg bg-fuchsia-600 text-white hover:bg-fuchsia-700 transition-colors text-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        Suivant
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Phases -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-purple-100">
                            <i class="fas fa-list-ol text-purple-600"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Phases</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            {{ $pdfc->phases->count() }} phase(s)
                        </span>
                        @can('pdfcs.update')
                        @if($pdfc->etat == 'élaboré' || $pdfc->etat == 'Non élaboré')
                            <a href="{{ route('pdfcs.phases.create', $pdfc) }}" class="px-4 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600 transition-all duration-300 flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                                Ajouter Phase
                            </a>
                        @endif
                        @endcan
                    </div>
                </div>
                @if($pdfc->phases->count() > 0)
                    <div class="space-y-4">
                        @foreach($pdfc->phases->sortBy('num') as $phase)
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200" data-phase-id="{{ $phase->id }}">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-200 text-purple-800">
                                            Phase #{{ $phase->num }}
                                        </span>
                                        <h3 class="font-semibold text-gray-900">{{ $phase->nom ?? 'Phase #' . $phase->num }}</h3>
                                        @php
                                            $phaseStateColors = [
                                                'en_cours' => 'bg-blue-100 text-blue-800',
                                                'terminée' => 'bg-yellow-100 text-yellow-800',
                                                'validée' => 'bg-green-100 text-green-800',
                                            ];
                                            $phaseColorClass = $phaseStateColors[$phase->etat] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $phaseColorClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $phase->etat)) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-600">{{ $phase->etapes->count() }} étape(s)</span>
                                        @can('pdfcs.update')
                                        <a href="{{ route('pdfcs.phases.edit', [$pdfc, $phase]) }}" class="text-orange-600 hover:text-orange-800" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($phase->canBeValidated() && $phase->etat != 'validée')
                                            <form action="{{ route('pdfcs.phases.validate', [$pdfc, $phase]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800" title="Valider la phase">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('pdfcs.phases.destroy', [$pdfc, $phase]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette phase ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                                @if($phase->date_de_début || $phase->date_de_fin || $phase->échéance)
                                    <div class="mb-3 text-sm text-gray-600">
                                        @if($phase->date_de_début)
                                            <span><i class="fas fa-calendar-alt mr-1"></i>Début: {{ $phase->date_de_début->format('d/m/Y') }}</span>
                                        @endif
                                        @if($phase->date_de_fin)
                                            <span class="ml-3"><i class="fas fa-calendar-check mr-1"></i>Fin: {{ $phase->date_de_fin->format('d/m/Y') }}</span>
                                        @endif
                                        @if($phase->échéance)
                                            <span class="ml-3"><i class="fas fa-clock mr-1"></i>Échéance: {{ $phase->échéance->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Étapes de la phase -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-sm font-semibold text-gray-700">Étapes</h4>
                                        @can('pdfcs.update')
                                        @if($pdfc->etat == 'élaboré' || $pdfc->etat == 'Non élaboré')
                                            <a href="{{ route('pdfcs.etapes.create', [$pdfc, $phase]) }}" class="text-xs px-3 py-1 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-all duration-300">
                                                <i class="fas fa-plus mr-1"></i>Ajouter Étape
                                            </a>
                                        @endif
                                        @endcan
                                    </div>
                                    @if($phase->etapes->count() > 0)
                                        <div class="space-y-2">
                                            @foreach($phase->etapes->sortBy('num') as $etape)
                                                <div class="bg-white rounded-lg p-3 border border-gray-200 flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <span class="text-xs font-medium text-gray-600">Étape #{{ $etape->num }}</span>
                                                            <span class="font-semibold text-gray-900">{{ $etape->objet }}</span>
                                                            @php
                                                                $etapeStateColors = [
                                                                    'en_attente' => 'bg-gray-100 text-gray-800',
                                                                    'en_cours' => 'bg-blue-100 text-blue-800',
                                                                    'validée' => 'bg-green-100 text-green-800',
                                                                    'rejetée' => 'bg-red-100 text-red-800',
                                                                ];
                                                                $etapeColorClass = $etapeStateColors[$etape->etat] ?? 'bg-gray-100 text-gray-800';
                                                            @endphp
                                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $etapeColorClass }}">
                                                                {{ ucfirst(str_replace('_', ' ', $etape->etat)) }}
                                                            </span>
                                                        </div>
                                                        @if($etape->content)
                                                            <p class="text-xs text-gray-600 line-clamp-2">{{ Str::limit($etape->content, 100) }}</p>
                                                        @endif
                                                        @if($etape->fichier_joint)
                                                            <a href="{{ asset('storage/' . $etape->fichier_joint) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 mt-1 inline-flex items-center gap-1">
                                                                <i class="fas fa-file"></i>Fichier joint
                                                            </a>
                                                        @endif
                                                        @if($etape->validated_at)
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                Validée le {{ $etape->validated_at->format('d/m/Y') }} par {{ $etape->validator->name ?? 'N/A' }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-2 ml-4">
                                                        @can('pdfcs.update')
                                                        <a href="{{ route('pdfcs.etapes.edit', [$pdfc, $phase, $etape]) }}" class="text-orange-600 hover:text-orange-800" title="Modifier">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                        @if($etape->etat != 'validée' && $etape->etat != 'rejetée')
                                                            <button type="button" onclick="showValidateModal({{ $etape->id }}, '{{ addslashes($etape->objet) }}', {{ $phase->id }})" class="text-green-600 hover:text-green-800" title="Valider">
                                                                <i class="fas fa-check text-sm"></i>
                                                            </button>
                                                            <button type="button" onclick="showRejectModal({{ $etape->id }}, '{{ addslashes($etape->objet) }}', {{ $phase->id }})" class="text-red-600 hover:text-red-800" title="Rejeter">
                                                                <i class="fas fa-times text-sm"></i>
                                                            </button>
                                                        @endif
                                                        <form action="{{ route('pdfcs.etapes.destroy', [$pdfc, $phase, $etape]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette étape ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                                                <i class="fas fa-trash text-sm"></i>
                                                            </button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 text-center py-2">Aucune étape</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p>Aucune phase assignée à ce PDFC</p>
                        @can('pdfcs.update')
                        @if($pdfc->etat == 'élaboré' || $pdfc->etat == 'Non élaboré')
                            <a href="{{ route('pdfcs.phases.create', $pdfc) }}" class="mt-4 inline-block px-4 py-2 bg-purple-500 text-white rounded-xl hover:bg-purple-600 transition-all duration-300">
                                <i class="fas fa-plus mr-2"></i>Créer la première phase
                            </a>
                        @endif
                        @endcan
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informations Système -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gray-100">
                        <i class="fas fa-cog text-gray-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Informations Système</h2>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">ID</label>
                        <p class="text-lg font-medium text-gray-900">#{{ $pdfc->id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Créé le</label>
                        <p class="text-sm text-gray-700">
                            {{ $pdfc->created_at ? $pdfc->created_at->format('d/m/Y à H:i') : 'N/A' }}
                        </p>
                    </div>
                    @if($pdfc->updated_at && $pdfc->updated_at != $pdfc->created_at)
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Modifié le</label>
                        <p class="text-sm text-gray-700">
                            {{ $pdfc->updated_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions Rapides -->
            @can('pdfcs.update')
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-orange-100">
                        <i class="fas fa-bolt text-orange-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Actions Rapides</h2>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('pdfcs.edit', $pdfc) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-orange-500 text-white rounded-xl hover:bg-orange-600 transition-all duration-300">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                    @can('pdfcs.delete')
                    <form action="{{ route('pdfcs.destroy', $pdfc) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce PDFC ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all duration-300">
                            <i class="fas fa-trash"></i>
                            Supprimer
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>

<!-- Validate Etape Modal -->
<div class="modal fade" id="validateEtapeModal" tabindex="-1" aria-labelledby="validateEtapeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="validateEtapeForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="validateEtapeModalLabel">Valider l'Étape</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Valider l'étape : <strong id="etapeNameValidate"></strong></p>
                    <div class="mb-3">
                        <label for="commentaire_validation" class="form-label">Commentaire (optionnel)</label>
                        <textarea class="form-control" id="commentaire_validation" name="commentaire_validation" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Etape Modal -->
<div class="modal fade" id="rejectEtapeModal" tabindex="-1" aria-labelledby="rejectEtapeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectEtapeForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectEtapeModalLabel">Rejeter l'Étape</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Rejeter l'étape : <strong id="etapeNameReject"></strong></p>
                    <div class="mb-3">
                        <label for="commentaire_rejet" class="form-label">Commentaire <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="commentaire_rejet" name="commentaire_validation" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showValidateModal(etapeId, etapeName, phaseId) {
    document.getElementById('etapeNameValidate').textContent = etapeName;
    const form = document.getElementById('validateEtapeForm');
    form.action = `/pdfcs/{{ $pdfc->id }}/phases/${phaseId}/etapes/${etapeId}/validate`;
    
    const modal = new bootstrap.Modal(document.getElementById('validateEtapeModal'));
    modal.show();
}

function showRejectModal(etapeId, etapeName, phaseId) {
    document.getElementById('etapeNameReject').textContent = etapeName;
    const form = document.getElementById('rejectEtapeForm');
    form.action = `/pdfcs/{{ $pdfc->id }}/phases/${phaseId}/etapes/${etapeId}/reject`;
    
    const modal = new bootstrap.Modal(document.getElementById('rejectEtapeModal'));
    modal.show();
}

// Navigation entre les étapes PDFC (affichage une par une)
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.pdfc-step-card');
    if (!cards.length) return;

    let currentStep = 1;
    const totalSteps = cards.length;
    const currentSpan = document.getElementById('pdfcStepCurrent');
    const prevBtn = document.getElementById('pdfcStepPrev');
    const nextBtn = document.getElementById('pdfcStepNext');

    function updateView() {
        cards.forEach(card => {
            const step = parseInt(card.getAttribute('data-step'));
            card.classList.toggle('hidden', step !== currentStep);
        });

        if (currentSpan) {
            currentSpan.textContent = currentStep;
        }

        if (prevBtn) {
            prevBtn.disabled = currentStep === 1;
        }
        if (nextBtn) {
            nextBtn.disabled = currentStep === totalSteps;
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            if (currentStep > 1) {
                currentStep--;
                updateView();
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            if (currentStep < totalSteps) {
                currentStep++;
                updateView();
            }
        });
    }

    updateView();
});
</script>
@endpush

