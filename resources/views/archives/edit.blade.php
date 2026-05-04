@extends('layouts.app')

@section('title', 'Éditer Archive - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('archives.index') }}">Archives</a></li>
<li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <x-page-header 
            title="Éditer l'archive"
            subtitle="Mettre à jour les informations et gérer les documents"
            icon="fas fa-archive"
            :backRoute="route('archives.index')"
            backText="Retour aux archives"
        >
            <x-slot name="actions">
                <a href="{{ route('archives.show', $archive) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-300">
                    <i class="fas fa-eye"></i>
                    <span>Voir</span>
                </a>
            </x-slot>
        </x-page-header>

        <x-validation-errors />

        <form id="archiveEditForm" action="{{ route('archives.update', $archive) }}" method="POST" enctype="multipart/form-data" style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            @csrf
            @method('PUT')

            <!-- Step 1: Archive Information -->
            <div id="archive-step-1">
                @php
                    $departements = [
                        "l'Economie Forestièr",
                        "l'Animation Territoriale et du Partenariat",
                    ];
                    $services = [
                        'FUP et de l\'accueil du public',
                        'des études et des l\'inventaire forestier national',
                        'organisation de l\'exploitation forestiére',
                        'la valorisation des produit forstiers',
                        'animation territoriale et partenariat',
                        'parcours forestiers et sylvopastoraux',
                    ];
                @endphp
                
                <x-form-section
                    title="Informations de l'archive"
                    icon="fas fa-info-circle"
                    color="green"
                    :columns="2"
                >
                    <x-form-input
                        type="date"
                        name="date"
                        label="Date"
                        :value="old('date', optional($archive->date ?? null)->format('Y-m-d'))"
                    />
                    
                    <x-form-input
                        type="text"
                        name="numero"
                        label="Numéro"
                        :value="old('numero', $archive->numero ?? '')"
                    />
                    
                    <x-form-input
                        type="text"
                        name="expediteur"
                        label="Expéditeur"
                        :value="old('expediteur', $archive->expediteur ?? '')"
                    />
                    
                    <x-form-input
                        type="text"
                        name="num_expediteur"
                        label="Numéro expéditeur"
                        :value="old('num_expediteur', $archive->num_expediteur ?? '')"
                    />
                    
                    <x-form-input
                        type="date"
                        name="date_expediteur"
                        label="Date expéditeur"
                        :value="old('date_expediteur', optional($archive->date_expediteur ?? null)->format('Y-m-d'))"
                    />
                    
                    <x-form-input
                        type="text"
                        name="object"
                        label="Objet"
                        :value="old('object', $archive->object ?? '')"
                    />
                    
                    <x-form-input
                        type="select"
                        name="departement"
                        label="Département"
                    >
                        <option value="">— Sélectionner un département —</option>
                        @foreach($departements as $departementOption)
                            <option value="{{ $departementOption }}" {{ old('departement', $archive->departement ?? '') === $departementOption ? 'selected' : '' }}>
                                {{ $departementOption }}
                            </option>
                        @endforeach
                    </x-form-input>
                    
                    <x-form-input
                        type="select"
                        name="service"
                        label="Service"
                    >
                        <option value="">— Sélectionner un service —</option>
                        @foreach($services as $serviceOption)
                            <option value="{{ $serviceOption }}" {{ old('service', $archive->service ?? '') === $serviceOption ? 'selected' : '' }}>
                                {{ $serviceOption }}
                            </option>
                        @endforeach
                    </x-form-input>
                    
                    <x-form-input
                        type="text"
                        name="placement"
                        label="Placement"
                        :value="old('placement', $archive->placement ?? '')"
                    />
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Suite (fichier)</label>
                        <input type="file" name="suite_file" 
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition-all">
                        @if(!empty($archive?->suite))
                            <p class="mt-3 text-sm">
                                <span class="text-gray-600">Fichier actuel:</span>
                                <a href="{{ asset('storage/' . $archive->suite) }}" target="_blank" class="text-green-500 hover:text-green-700 transition-colors font-medium">
                                    Télécharger la suite
                                </a>
                            </p>
                        @endif
                    </div>
                </x-form-section>
            </div>

            <!-- Step 2: Documents -->
            <div id="archive-step-2" class="hidden">
                <div class="bg-green-500 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-file-alt"></i>
                        Documents
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Add New Documents -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-gray-800">Ajouter de nouveaux documents</h3>
                        <div id="archive-documents-container" class="space-y-3">
                            <!-- Document rows added dynamically -->
                        </div>
                        <button type="button"
                                onclick="addArchiveDocumentInput()"
                                class="inline-flex items-center gap-2 px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-plus"></i>
                            <span>Ajouter un document</span>
                        </button>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle text-green-400 mr-1"></i>
                            Vous pouvez ajouter plusieurs fichiers en créant plusieurs lignes.
                        </p>
                    </div>

                    <!-- Existing Documents -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-800 mb-4">Documents existants</h3>
                        @forelse($archive->documents as $document)
                            <div class="flex items-center justify-between p-4 bg-green-50 border border-green-100 rounded-xl hover:bg-green-100 transition-colors {{ !$loop->last ? 'mb-3' : '' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $document->name ?? $document->file }}</p>
                                        <p class="text-xs text-gray-600">{{ $document->file }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($document->path)
                                        <a href="{{ asset('storage/' . $document->path) }}" target="_blank" 
                                           class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-xs font-medium">
                                            <i class="fas fa-download"></i>
                                            <span>Télécharger</span>
                                        </a>
                                    @endif
                                    <form action="{{ route('archives.documents.destroy', [$archive, $document]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium" 
                                                onclick="return confirm('Supprimer ce document ?')">
                                            <i class="fas fa-trash"></i>
                                            <span>Supprimer</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500 text-sm">Aucun document existant</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('archives.index') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour</span>
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="button"
                                id="archivePrevBtn"
                                onclick="changeArchiveStep(-1)"
                                class="hidden inline-flex items-center gap-2 px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100 transition-all duration-300">
                            <i class="fas fa-arrow-left"></i>
                            <span>Précédent</span>
                        </button>
                        <button type="button"
                                id="archiveNextBtn"
                                onclick="changeArchiveStep(1)"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                            <span>Suivant</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="submit"
                                id="archiveSubmitBtn"
                                class="hidden inline-flex items-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-save"></i>
                            <span>Enregistrer</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let archiveDocumentCount = 0;
    let archiveCurrentStep = 1;
    const archiveTotalSteps = 2;

    function addArchiveDocumentInput() {
        const container = document.getElementById('archive-documents-container');
        if (!container) return;

        archiveDocumentCount++;

        const row = document.createElement('div');
        row.className = 'flex items-center gap-3 p-3 bg-green-50 border border-green-100 rounded-xl';

        row.innerHTML = `
            <input type="file"
                   name="document_files[]"
                   class="border border-gray-300 rounded-lg px-4 py-2 flex-1 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition-all">
            <button type="button"
                    onclick="this.closest('div').remove()"
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors shadow-md">
                <i class="fas fa-minus"></i>
            </button>
        `;

        container.appendChild(row);
    }

    function showArchiveStep(step) {
        const step1 = document.getElementById('archive-step-1');
        const step2 = document.getElementById('archive-step-2');
        const prevBtn = document.getElementById('archivePrevBtn');
        const nextBtn = document.getElementById('archiveNextBtn');
        const submitBtn = document.getElementById('archiveSubmitBtn');

        if (!step1 || !step2) return;

        step1.classList.toggle('hidden', step !== 1);
        step2.classList.toggle('hidden', step !== 2);

        if (prevBtn) prevBtn.classList.toggle('hidden', step === 1);
        if (nextBtn) nextBtn.classList.toggle('hidden', step === archiveTotalSteps);
        if (submitBtn) submitBtn.classList.toggle('hidden', step !== archiveTotalSteps);
    }

    function changeArchiveStep(delta) {
        let nextStep = archiveCurrentStep + delta;
        if (nextStep < 1) nextStep = 1;
        if (nextStep > archiveTotalSteps) nextStep = archiveTotalSteps;

        archiveCurrentStep = nextStep;
        showArchiveStep(archiveCurrentStep);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('archive-documents-container');
        if (container && !container.children.length) {
            addArchiveDocumentInput();
        }

        showArchiveStep(1);
    });
</script>
@endpush
