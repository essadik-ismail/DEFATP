@extends('layouts.app')

@section('title', 'Nouvelle archive')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Nouvelle archive</h1>
        <p class="text-gray-600">Créer une archive et ajouter un document.</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="archiveCreateForm" action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6 space-y-4">
        @csrf
        <div id="archive-step-1" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" value="{{ old('date', optional($archive->date ?? null)->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro</label>
                    <input type="text" name="numero" value="{{ old('numero', $archive->numero ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expéditeur</label>
                    <input type="text" name="expediteur" value="{{ old('expediteur', $archive->expediteur ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro expéditeur</label>
                    <input type="text" name="num_expediteur" value="{{ old('num_expediteur', $archive->num_expediteur ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date expéditeur</label>
                    <input type="date" name="date_expediteur" value="{{ old('date_expediteur', optional($archive->date_expediteur ?? null)->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Objet</label>
                    <input type="text" name="object" value="{{ old('object', $archive->object ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Département</label>
                    @php
                        $departements = [
                            "l’Economie Forestièr",
                            "l’Animation Territoriale et du Partenariat",
                        ];
                    @endphp
                    <select name="departement" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                        <option value="">— Sélectionner un département —</option>
                        @foreach($departements as $departementOption)
                            <option value="{{ $departementOption }}" {{ old('departement', $archive->departement ?? '') === $departementOption ? 'selected' : '' }}>
                                {{ $departementOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    @php
                        $services = [
                            'FUP et de l\'accueil du public',
                            'des études et des l\'inventaire forestier national',
                            'organisation de l\'exploitation forestiére',
                            'la valorisation des produit forstiers',
                            'animation territoriale et partenariat',
                            'parcours forestiers et sylvopastoraux',
                        ];
                    @endphp
                    <select name="service" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                        <option value="">— Sélectionner un service —</option>
                        @foreach($services as $serviceOption)
                            <option value="{{ $serviceOption }}" {{ old('service', $archive->service ?? '') === $serviceOption ? 'selected' : '' }}>
                                {{ $serviceOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Placement</label>
                    <input type="text" name="placement" value="{{ old('placement', $archive->placement ?? '') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-green-200">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Suite (fichier)</label>
                    <input type="file" name="suite_file" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @if(!empty($archive?->suite))
                        <p class="mt-2 text-sm">
                            <span class="text-gray-600">Fichier actuel:</span>
                            <a href="{{ asset('storage/' . $archive->suite) }}" target="_blank" class="text-blue-600 hover:underline">
                                Télécharger la suite
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div id="archive-step-2" class="mt-4 border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Documents (optionnels)</h3>
            <div class="space-y-2">
                <div id="archive-documents-container" class="flex flex-col gap-2">
                    <!-- Document rows added dynamically -->
                </div>
                <button type="button"
                        onclick="addArchiveDocumentInput()"
                        class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter un document</span>
                </button>
                <p class="mt-1 text-xs text-gray-500">Vous pouvez ajouter plusieurs fichiers en créant plusieurs lignes.</p>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3 pt-4 border-t">
            <a href="{{ route('archives.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Annuler</a>
            <div class="flex items-center gap-3">
                <button type="button"
                        id="archivePrevBtn"
                        onclick="changeArchiveStep(-1)"
                        class="hidden px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Précédent
                </button>
                <button type="button"
                        id="archiveNextBtn"
                        onclick="changeArchiveStep(1)"
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    Suivant
                </button>
                <button type="submit"
                        id="archiveSubmitBtn"
                        class="hidden px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                    Enregistrer
                </button>
            </div>
        </div>
    </form>

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
        row.className = 'flex items-center gap-2';

        row.innerHTML = `
            <input type="file"
                   name="document_files[]"
                   class="border border-gray-300 rounded-lg px-3 py-2 flex-1">
            <button type="button"
                    onclick="this.closest('div').remove()"
                    class="inline-flex items-center justify-center w-9 h-9 bg-red-500 text-white rounded-lg hover:bg-red-600">
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
