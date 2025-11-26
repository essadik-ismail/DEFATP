@extends('layouts.app')

@section('title', 'Modifier ODF - Étape 2 - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-stethoscope text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Modifier ODF - Étape 2/5
                </h1>
                <p class="text-gray-600 mt-1">
                    Diagnostic pour l'ODF #{{ $odf->id }}
                </p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            <div class="font-semibold mb-2">Erreurs de validation :</div>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #3b82f6, #06b6d4);">
                    <i class="fas fa-stethoscope text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Étape 2 - Diagnostic</h2>
                    <p class="text-sm text-gray-600">Modifiez ou ajoutez des diagnostics pour cette ODF.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('odfs.update.step2', $odf) }}" method="POST" id="diagnosticForm">
            @csrf
            @method('PUT')

            <div id="diagnostics-container" class="space-y-4">
                @forelse($diagnostics as $index => $diagnostic)
                    <div class="diagnostic-row bg-blue-50 border border-blue-200 rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-md font-semibold text-blue-700">Diagnostic #{{ $index + 1 }}</h3>
                            <label class="inline-flex items-center text-red-600 hover:text-red-800 text-sm cursor-pointer">
                                <input type="checkbox" name="diagnostics_to_delete[]" value="{{ $diagnostic->id }}" class="mr-2">
                                <i class="fas fa-trash mr-1"></i>Supprimer
                            </label>
                        </div>
                        <input type="hidden" name="diagnostics[{{ $index }}][id]" value="{{ $diagnostic->id }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                                <select name="diagnostics[{{ $index }}][type]" class="form-input w-full" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="associations" {{ old("diagnostics.$index.type", $diagnostic->type) == 'associations' ? 'selected' : '' }}>Associations</option>
                                    <option value="coopératives" {{ old("diagnostics.$index.type", $diagnostic->type) == 'coopératives' ? 'selected' : '' }}>Coopératives</option>
                                    <option value="titulaires_amodiations" {{ old("diagnostics.$index.type", $diagnostic->type) == 'titulaires_amodiations' ? 'selected' : '' }}>Titulaires d'amodiations</option>
                                    <option value="nouabs des collectivités ethniques" {{ old("diagnostics.$index.type", $diagnostic->type) == 'nouabs des collectivités ethniques' ? 'selected' : '' }}>Nouabs des collectivités ethniques</option>
                                    <option value="autre" {{ old("diagnostics.$index.type", $diagnostic->type) == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                                <input type="text" name="diagnostics[{{ $index }}][nom]" class="form-input w-full" placeholder="Nom du diagnostic" value="{{ old("diagnostics.$index.nom", $diagnostic->nom) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Activité</label>
                                <input type="text" name="diagnostics[{{ $index }}][activité]" class="form-input w-full" placeholder="Activité" value="{{ old("diagnostics.$index.activité", $diagnostic->activité) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Présidente</label>
                                <input type="text" name="diagnostics[{{ $index }}][présidente]" class="form-input w-full" placeholder="Présidente" value="{{ old("diagnostics.$index.présidente", $diagnostic->présidente) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de membres</label>
                                <input type="number" min="0" name="diagnostics[{{ $index }}][nombre_de_membres]" class="form-input w-full" placeholder="Nombre de membres" value="{{ old("diagnostics.$index.nombre_de_membres", $diagnostic->nombre_de_membres) }}">
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="diagnostic-row bg-blue-50 border border-blue-200 rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-md font-semibold text-blue-700">Diagnostic #1</h3>
                            <button type="button" class="remove-diagnostic hidden text-red-600 hover:text-red-800 text-sm" onclick="removeDiagnosticRow(this)">
                                <i class="fas fa-trash mr-1"></i>Supprimer
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                                <select name="diagnostics[0][type]" class="form-input w-full" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="associations">Associations</option>
                                    <option value="coopératives">Coopératives</option>
                                    <option value="titulaires_amodiations">Titulaires d'amodiations</option>
                                    <option value="nouabs des collectivités ethniques">Nouabs des collectivités ethniques</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                                <input type="text" name="diagnostics[0][nom]" class="form-input w-full" placeholder="Nom du diagnostic">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Activité</label>
                                <input type="text" name="diagnostics[0][activité]" class="form-input w-full" placeholder="Activité">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Présidente</label>
                                <input type="text" name="diagnostics[0][présidente]" class="form-input w-full" placeholder="Présidente">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de membres</label>
                                <input type="number" min="0" name="diagnostics[0][nombre_de_membres]" class="form-input w-full" placeholder="Nombre de membres">
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                <button type="button" onclick="addDiagnosticRow()" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter un diagnostic</span>
                </button>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('odfs.edit.step1', $odf) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour étape 1</span>
                </a>

                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <span>Enregistrer et continuer</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let diagnosticIndex = {{ $diagnostics->count() }};

function addDiagnosticRow() {
    const container = document.getElementById('diagnostics-container');
    const row = document.createElement('div');
    row.className = 'diagnostic-row bg-blue-50 border border-blue-200 rounded-2xl p-4 mt-4';
    row.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-md font-semibold text-blue-700">Diagnostic #${diagnosticIndex + 1}</h3>
            <button type="button" class="remove-diagnostic text-red-600 hover:text-red-800 text-sm" onclick="removeDiagnosticRow(this)">
                <i class="fas fa-trash mr-1"></i>Supprimer
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="diagnostics[${diagnosticIndex}][type]" class="form-input w-full" required>
                    <option value="">Sélectionner un type</option>
                    <option value="associations">Associations</option>
                    <option value="coopératives">Coopératives</option>
                    <option value="titulaires_amodiations">Titulaires d'amodiations</option>
                    <option value="nouabs des collectivités ethniques">Nouabs des collectivités ethniques</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                <input type="text" name="diagnostics[${diagnosticIndex}][nom]" class="form-input w-full" placeholder="Nom du diagnostic">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Activité</label>
                <input type="text" name="diagnostics[${diagnosticIndex}][activité]" class="form-input w-full" placeholder="Activité">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Présidente</label>
                <input type="text" name="diagnostics[${diagnosticIndex}][présidente]" class="form-input w-full" placeholder="Présidente">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de membres</label>
                <input type="number" min="0" name="diagnostics[${diagnosticIndex}][nombre_de_membres]" class="form-input w-full" placeholder="Nombre de membres">
            </div>
        </div>
    `;
    container.appendChild(row);
    diagnosticIndex++;
}

function removeDiagnosticRow(button) {
    const row = button.closest('.diagnostic-row');
    if (row) {
        row.remove();
    }
}
</script>
@endpush

