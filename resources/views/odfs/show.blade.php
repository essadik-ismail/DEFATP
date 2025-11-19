@extends('layouts.app')

@section('title', 'Détails ODF - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        Détails ODF
                    </h1>
                    <p class="text-gray-600 text-lg mt-2">Informations détaillées de l'Organisation développement forestier (ODF)</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('odfs.edit', $odf) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-600 text-white rounded-xl hover:from-orange-600 hover:to-amber-700 transition-all duration-300 shadow-lg">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                <a href="{{ route('odfs.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
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

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Information -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Members Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold" style="color: #10b981;">Membres ({{ $odf->members->count() }})</h3>
                    </div>
                    <button type="button" onclick="toggleMemberForm()" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-300">
                        <i class="fas fa-plus"></i>
                        <span>Ajouter</span>
                    </button>
                </div>

                <!-- Add Member Form (Hidden by default) -->
                <div id="memberForm" class="hidden mb-6 bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-4">Nouveau Membre</h4>
                    <form action="{{ route('odfs.members.store', $odf) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                    <span>Nom <span class="text-red-500">*</span></span>
                                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nom complet du membre"></i>
                                </label>
                                <input type="text" name="nom" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                    <span>Téléphone</span>
                                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Numéro de téléphone du membre"></i>
                                </label>
                                <input type="text" name="tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                    <span>Type</span>
                                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Type de membre: Association, Coopérative, Entreprise, Élu, ou Citoyen"></i>
                                </label>
                                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Sélectionner un type</option>
                                    <option value="Association">Association</option>
                                    <option value="Coopérative">Coopérative</option>
                                    <option value="Entreprise">Entreprise</option>
                                    <option value="Élu">Élu</option>
                                    <option value="Citoyen">Citoyen</option>
                                </select>
                            </div>
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
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors" id="member-{{ $member->id }}">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $member->nom }}</p>
                                        <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                                            @if($member->tel)
                                                <span><i class="fas fa-phone mr-1"></i>{{ $member->tel }}</span>
                                            @endif
                                            @if($member->type)
                                                <span><i class="fas fa-tag mr-1"></i>{{ $member->type }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="editMember({{ $member->id }}, @json($member->nom), @json($member->tel ?? ''), @json($member->type ?? ''))" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Modifier">
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

            <!-- Activities Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold" style="color: #3b82f6;">Activités ({{ $odf->activities->count() }})</h3>
                    </div>
                    <button type="button" onclick="toggleActivityForm()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300">
                        <i class="fas fa-plus"></i>
                        <span>Ajouter</span>
                    </button>
                </div>

                <!-- Add Activity Form (Hidden by default) -->
                <div id="activityForm" class="hidden mb-6 bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <h4 class="font-semibold text-gray-900 mb-4">Nouvelle Activité</h4>
                    <form action="{{ route('odfs.activities.store', $odf) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                        <span>Objet <span class="text-red-500">*</span></span>
                                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sujet ou titre de l'activité"></i>
                                    </label>
                                    <input type="text" name="objet" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                        <span>Date <span class="text-red-500">*</span></span>
                                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Date à laquelle l'activité s'est déroulée"></i>
                                    </label>
                                    <input type="date" name="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                    <span>Lieu</span>
                                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Lieu où l'activité s'est déroulée"></i>
                                </label>
                                <input type="text" name="lieu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                    <span>Description</span>
                                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Description détaillée de l'activité"></i>
                                </label>
                                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                                    <span>Participants</span>
                                    <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Liste des participants à l'activité"></i>
                                </label>
                                <textarea name="participants" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Enregistrer</button>
                            <button type="button" onclick="toggleActivityForm()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Annuler</button>
                        </div>
                    </form>
                </div>

                <!-- Activities List -->
                <div class="space-y-3">
                    @forelse($odf->activities as $activity)
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors" id="activity-{{ $activity->id }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-calendar text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $activity->objet }}</p>
                                            <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                                                <span><i class="fas fa-calendar-day mr-1"></i>{{ $activity->date ? $activity->date->format('d/m/Y') : 'N/A' }}</span>
                                                @if($activity->lieu)
                                                    <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $activity->lieu }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($activity->description)
                                        <p class="text-sm text-gray-700 mt-2 ml-13">{{ Str::limit($activity->description, 100) }}</p>
                                    @endif
                                    @if($activity->participants)
                                        <p class="text-xs text-gray-500 mt-1 ml-13"><i class="fas fa-users mr-1"></i>{{ Str::limit($activity->participants, 80) }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <button onclick="editActivity({{ $activity->id }}, @json($activity->objet), @json($activity->date ? $activity->date->format('Y-m-d') : ''), @json($activity->lieu ?? ''), @json($activity->description ?? ''), @json($activity->participants ?? ''))" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('odfs.activities.destroy', [$odf, $activity]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette activité ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune activité enregistrée</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Informations de Base -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #8b5cf6;">Informations de Base</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-purple-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Présidente</p>
                            <p class="text-lg font-medium text-gray-900">{{ $odf->présidente ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Vice-Présidente</p>
                            <p class="text-lg font-medium text-gray-900">{{ $odf->vice_présidente ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Trésorière</p>
                            <p class="text-lg font-medium text-gray-900">{{ $odf->trésorière ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Localisation et Situation Administrative -->
            @if($odf->localisation || $odf->situationAdministrative)
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #10b981;">Localisation et Situation Administrative</h3>
                </div>
                <div class="space-y-4">
                    @if($odf->localisation)
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Localisation</p>
                            <p class="text-lg font-medium text-gray-900">{{ $odf->localisation->CODE }} - {{ $odf->localisation->DRANEF }} - {{ $odf->localisation->DPANEF }} - {{ $odf->localisation->ENTITE }}</p>
                        </div>
                    </div>
                    @endif
                    @if($odf->situationAdministrative)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-building text-emerald-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Situation Administrative</p>
                            <p class="text-lg font-medium text-gray-900">{{ $odf->situationAdministrative->commune }}@if($odf->situationAdministrative->province) - {{ $odf->situationAdministrative->province }}@endif</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Détails -->
            @if($odf->reçu_du_dépôt || $odf->constitution)
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #2563eb);">
                        <i class="fas fa-file-alt text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold" style="color: #3b82f6;">Détails</h3>
                </div>
                <div class="space-y-6">
                    @if($odf->reçu_du_dépôt)
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-2">Reçu du Dépôt</p>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $odf->reçu_du_dépôt }}</p>
                    </div>
                    @endif
                    @if($odf->constitution)
                    <div>
                        <p class="text-sm font-semibold text-gray-500 mb-2">Constitution</p>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $odf->constitution }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Member Modal -->
<div id="editMemberModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Modifier le Membre</h3>
        <form id="editMemberForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Nom <span class="text-red-500">*</span></span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Nom complet du membre"></i>
                    </label>
                    <input type="text" name="nom" id="edit_member_nom" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
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
                        <span>Type</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Type de membre: Association, Coopérative, Entreprise, Élu, ou Citoyen"></i>
                    </label>
                    <select name="type" id="edit_member_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Sélectionner un type</option>
                        <option value="Association">Association</option>
                        <option value="Coopérative">Coopérative</option>
                        <option value="Entreprise">Entreprise</option>
                        <option value="Élu">Élu</option>
                        <option value="Citoyen">Citoyen</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-2 mt-6">
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Enregistrer</button>
                <button type="button" onclick="closeEditMemberModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Annuler</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Activity Modal -->
<div id="editActivityModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Modifier l'Activité</h3>
        <form id="editActivityForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                            <span>Objet <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Sujet ou titre de l'activité"></i>
                        </label>
                        <input type="text" name="objet" id="edit_activity_objet" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                            <span>Date <span class="text-red-500">*</span></span>
                            <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Date à laquelle l'activité s'est déroulée"></i>
                        </label>
                        <input type="date" name="date" id="edit_activity_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Lieu</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Lieu où l'activité s'est déroulée"></i>
                    </label>
                    <input type="text" name="lieu" id="edit_activity_lieu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Description</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Description détaillée de l'activité"></i>
                    </label>
                    <textarea name="description" id="edit_activity_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Participants</span>
                        <i class="fas fa-question-circle text-amber-600 text-sm cursor-help" title="Liste des participants à l'activité"></i>
                    </label>
                    <textarea name="participants" id="edit_activity_participants" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>
            <div class="flex gap-2 mt-6">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Enregistrer</button>
                <button type="button" onclick="closeEditActivityModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Annuler</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleMemberForm() {
        const form = document.getElementById('memberForm');
        form.classList.toggle('hidden');
    }

    function toggleActivityForm() {
        const form = document.getElementById('activityForm');
        form.classList.toggle('hidden');
    }

    function editMember(id, nom, tel, type) {
        document.getElementById('edit_member_nom').value = nom;
        document.getElementById('edit_member_tel').value = tel || '';
        const typeSelect = document.getElementById('edit_member_type');
        typeSelect.value = type || '';
        document.getElementById('editMemberForm').action = '{{ route("odfs.members.update", [$odf, ":id"]) }}'.replace(':id', id);
        document.getElementById('editMemberModal').classList.remove('hidden');
    }

    function closeEditMemberModal() {
        document.getElementById('editMemberModal').classList.add('hidden');
    }

    function editActivity(id, objet, date, lieu, description, participants) {
        document.getElementById('edit_activity_objet').value = objet;
        document.getElementById('edit_activity_date').value = date || '';
        document.getElementById('edit_activity_lieu').value = lieu || '';
        document.getElementById('edit_activity_description').value = description || '';
        document.getElementById('edit_activity_participants').value = participants || '';
        document.getElementById('editActivityForm').action = '{{ route("odfs.activities.update", [$odf, ":id"]) }}'.replace(':id', id);
        document.getElementById('editActivityModal').classList.remove('hidden');
    }

    function closeEditActivityModal() {
        document.getElementById('editActivityModal').classList.add('hidden');
    }

    // Close modals when clicking outside
    document.getElementById('editMemberModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditMemberModal();
        }
    });

    document.getElementById('editActivityModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditActivityModal();
        }
    });
</script>
@endpush
@endsection

