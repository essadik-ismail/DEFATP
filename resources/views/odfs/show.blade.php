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
                    <p class="text-gray-600 text-lg mt-2">Informations détaillées de l'Organisme de développement forestier (ODF)</p>
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
            <!-- Tabs Section -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20">
                <!-- Section Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Contrats, Activités et Modifications</h3>
                </div>

                <!-- Tab Buttons -->
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button onclick="switchTab('contract')" id="tab-contract" class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-blue-600 text-blue-600">
                            <i class="fas fa-file-contract mr-2"></i>
                            Contract
                        </button>
                        <button onclick="switchTab('activite')" id="tab-activite" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Activité
                        </button>
                        <button onclick="switchTab('modification')" id="tab-modification" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-edit mr-2"></i>
                            Modification
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div id="tabs-content" class="p-6">
                    <!-- Contract Tab -->
                    <div id="content-contract" class="tab-content">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Contrats ODF</h3>
                            <button type="button" onclick="toggleContractForm()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300">
                                <i class="fas fa-plus"></i>
                                <span>Ajouter</span>
                            </button>
                        </div>

                        <!-- Add Contract Form -->
                        <div id="contractForm" class="hidden mb-6 bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-4">Nouveau Contrat</h4>
                            <form action="{{ route('odfs.contract-odf.store', $odf) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                                        <input type="date" name="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                                        <input type="text" name="lieu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Signature 1 - Nom</label>
                                        <input type="text" name="signature1_nom" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Signature 1 - Type</label>
                                        <select name="signature1_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Sélectionner un type</option>
                                            <option value="présidente">Présidente</option>
                                            <option value="vice_présidente">Vice-Présidente</option>
                                            <option value="trésorière">Trésorière</option>
                                            <option value="membre">Membre</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Signature 2 - Nom</label>
                                        <input type="text" name="signature2_nom" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Signature 2 - Type</label>
                                        <select name="signature2_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Sélectionner un type</option>
                                            <option value="dranef">DRANEF</option>
                                            <option value="dpanef">DPANEF</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                                        <textarea name="commentaire" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-4">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Enregistrer</button>
                                    <button type="button" onclick="toggleContractForm()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Annuler</button>
                                </div>
                            </form>
                        </div>

                        <!-- Contracts List -->
                        <div class="space-y-3">
                            @forelse($odf->contractOdf as $contract)
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-file-contract text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">Contrat du {{ $contract->date ? \Carbon\Carbon::parse($contract->date)->format('d/m/Y') : 'N/A' }}</p>
                                                    <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                                                        @if($contract->lieu)
                                                            <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $contract->lieu }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if($contract->signature1_nom || $contract->signature2_nom)
                                                <div class="ml-13 text-sm text-gray-700">
                                                    @if($contract->signature1_nom)
                                                        <p><strong>Signature 1:</strong> {{ $contract->signature1_nom }}@if($contract->signature1_type) ({{ $contract->signature1_type }})@endif</p>
                                                    @endif
                                                    @if($contract->signature2_nom)
                                                        <p><strong>Signature 2:</strong> {{ $contract->signature2_nom }}@if($contract->signature2_type) ({{ $contract->signature2_type }})@endif</p>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($contract->commentaire)
                                                <p class="text-sm text-gray-700 mt-2 ml-13">{{ Str::limit($contract->commentaire, 100) }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('odfs.contract-odf.destroy', [$odf, $contract]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')">
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
                                <p class="text-gray-500 text-center py-4">Aucun contrat enregistré</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Activité Tab -->
                    <div id="content-activite" class="tab-content hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Activités ({{ $odf->activities->count() }})</h3>
                            <button type="button" onclick="toggleActivityForm()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300">
                                <i class="fas fa-plus"></i>
                                <span>Ajouter</span>
                            </button>
                        </div>

                        <!-- Add Activity Form -->
                        <div id="activityForm" class="hidden mb-6 bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-4">Nouvelle Activité</h4>
                            <form action="{{ route('odfs.activities.store', $odf) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Objet *</label>
                                            <input type="text" name="objet" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                                            <input type="date" name="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                                        <input type="text" name="lieu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Participants</label>
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

                    <!-- Modification Tab -->
                    <div id="content-modification" class="tab-content hidden">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Modifications ({{ $odf->odfModifications->count() }})</h3>
                            <button type="button" onclick="toggleModificationForm()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300">
                                <i class="fas fa-plus"></i>
                                <span>Ajouter</span>
                            </button>
                        </div>

                        <!-- Add Modification Form -->
                        <div id="modificationForm" class="hidden mb-6 bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-4">Nouvelle Modification</h4>
                            <form action="{{ route('odfs.odf-modifications.store', $odf) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                                            <input type="date" name="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Modification</label>
                                        <textarea name="modification" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Actions</label>
                                        <textarea name="actions" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                                        <textarea name="commentaire" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-4">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Enregistrer</button>
                                    <button type="button" onclick="toggleModificationForm()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Annuler</button>
                                </div>
                            </form>
                        </div>

                        <!-- Modifications List -->
                        <div class="space-y-3">
                            @forelse($odf->odfModifications as $modification)
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-edit text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">Modification du {{ $modification->date ? \Carbon\Carbon::parse($modification->date)->format('d/m/Y') : 'N/A' }}</p>
                                                </div>
                                            </div>
                                            @if($modification->modification)
                                                <p class="text-sm text-gray-700 mt-2 ml-13"><strong>Modification:</strong> {{ Str::limit($modification->modification, 150) }}</p>
                                            @endif
                                            @if($modification->actions)
                                                <p class="text-sm text-gray-700 mt-2 ml-13"><strong>Actions:</strong> {{ Str::limit($modification->actions, 150) }}</p>
                                            @endif
                                            @if($modification->commentaire)
                                                <p class="text-sm text-gray-700 mt-2 ml-13"><strong>Commentaire:</strong> {{ Str::limit($modification->commentaire, 150) }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('odfs.odf-modifications.destroy', [$odf, $modification]) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette modification ?')">
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
                                <p class="text-gray-500 text-center py-4">Aucune modification enregistrée</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Informations de Base -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20">
                <!-- Section Header -->
                <div class="flex items-center gap-3 p-4 border-b border-gray-200">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #8b5cf6, #7c3aed);">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold" style="color: #8b5cf6;">Informations de Base</h3>
                </div>

                <!-- Info Content -->
                <div class="p-6">
                    <div class="space-y-4">
                    @if($odf->odfEntite)
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-building text-purple-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">ODF Entité</p>
                            <p class="text-lg font-medium text-gray-900">{{ $odf->odfEntite->name }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check-circle text-indigo-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Constitution</p>
                            <p class="text-lg font-medium text-gray-900">
                                @if($odf->constitution)
                                    <span class="text-green-600">Oui</span>
                                @else
                                    <span class="text-red-600">Non</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($odf->date_depot_odf)
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Date de Dépôt ODF</p>
                            <p class="text-lg font-medium text-gray-900">{{ \Carbon\Carbon::parse($odf->date_depot_odf)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($odf->date_reçu_du_définition)
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                        <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-file-download text-cyan-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Date Réçu du Définition</p>
                            <p class="text-lg font-medium text-gray-900">{{ \Carbon\Carbon::parse($odf->date_reçu_du_définition)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($odf->commentaire)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-comment text-amber-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Commentaire</p>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $odf->commentaire }}</p>
                        </div>
                    </div>
                    @endif
                    </div>
                </div>
            </div>

            <!-- Localisation et Situation Administrative -->
            @if($odf->localisation || $odf->situationAdministrative)
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20">
                <!-- Section Header -->
                <div class="flex items-center gap-3 p-4 border-b border-gray-200">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                        <i class="fas fa-map-marker-alt text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold" style="color: #10b981;">Localisation et Situation Administrative</h3>
                </div>

                <!-- Location Content -->
                <div class="p-6">
                    <div class="space-y-4">
                    @if($odf->localisation)
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-200">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 mb-1">Localisation</p>
                            <p class="text-lg font-medium text-gray-900">{{ $odf->localisation->CODE }} - {{ $odf->localisation->DRANEF }}@if($odf->localisation->ENTITE) - {{ $odf->localisation->ENTITE }}@endif</p>
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
                        <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('nom_membre_help')"></i>
                    </label>
                    <input type="text" name="nom" id="edit_member_nom" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Téléphone</span>
                        <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('telephone_membre_help')"></i>
                    </label>
                    <input type="text" name="tel" id="edit_member_tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                        <span>Type</span>
                        <i class="fas fa-question-circle text-blue-500 text-xs cursor-pointer hover:text-blue-600 transition-colors" onclick="showHelpModal('type_odf_help')"></i>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Objet *</label>
                        <input type="text" name="objet" id="edit_activity_objet" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                        <input type="date" name="date" id="edit_activity_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                    <input type="text" name="lieu" id="edit_activity_lieu" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="edit_activity_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Participants</label>
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

@push('scripts')
<script>
function showHelpModal(helpId) {
    const helpContents = {
        'nom_membre_help': {
            title: 'Nom du Membre',
            content: 'Saisissez le nom complet du membre. Ce champ est obligatoire et doit contenir le prénom et le nom de famille.'
        },
        'telephone_membre_help': {
            title: 'Téléphone du Membre',
            content: 'Saisissez le numéro de téléphone du membre. Ce champ est optionnel mais utile pour les communications.'
        },
        'type_odf_help': {
            title: 'Type d\'ODF',
            content: 'Sélectionnez le type d\'ODF : Association, Coopérative, Entreprise, Élu, ou Citoyen. Ce champ définit la nature juridique ou le statut de l\'organisation.'
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

    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-blue-600', 'text-blue-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        // Add active class to selected tab
        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.add('active', 'border-blue-600', 'text-blue-600');
        activeTab.classList.remove('border-transparent', 'text-gray-500');
    }

    function toggleContractForm() {
        const form = document.getElementById('contractForm');
        form.classList.toggle('hidden');
    }

    function toggleActivityForm() {
        const form = document.getElementById('activityForm');
        form.classList.toggle('hidden');
    }

    function toggleModificationForm() {
        const form = document.getElementById('modificationForm');
        form.classList.toggle('hidden');
    }

    function toggleMemberForm() {
        const form = document.getElementById('memberForm');
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
