@extends('layouts.app')

@section('title', 'Nouvelle ODF - Étape 4 - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-handshake text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Nouvelle ODF - Étape 4/5
                </h1>
                <p class="text-gray-600 mt-1">
                    Négociation et mobilisation participative pour l'ODF #{{ $odf->id }}
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

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <form action="{{ route('odfs.store.step4', $odf) }}" method="POST" id="etapsForm">
            @csrf

            <!-- Étapes -->
            <div class="mb-8">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #10b981, #059669);">
                            <i class="fas fa-handshake text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Étapes de négociation et mobilisation participative</h2>
                            <p class="text-sm text-gray-600">Ajoutez une ou plusieurs étapes pour cette ODF.</p>
                        </div>
                    </div>
                    <button type="button"
                            onclick="addEtapRow()"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                        <i class="fas fa-plus"></i>
                        <span>Ajouter une étape</span>
                    </button>
                </div>

                <div id="etaps-container" class="space-y-4">
                    <div class="etap-row bg-green-50 border border-green-200 rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-md font-semibold text-green-700">Étape #1</h3>
                            <button type="button"
                                    class="remove-etap hidden text-red-600 hover:text-red-800 text-sm"
                                    onclick="removeEtapRow(this)">
                                <i class="fas fa-trash mr-1"></i>Supprimer
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Objet</label>
                                <input type="text" name="odf_etaps[0][objet]" class="form-input w-full" placeholder="Objet de l'étape">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Lieu</label>
                                <input type="text" name="odf_etaps[0][lieu]" class="form-input w-full" placeholder="Lieu">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
                                <input type="date" name="odf_etaps[0][date]" class="form-input w-full">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Type</label>
                                <input type="text" name="odf_etaps[0][type]" class="form-input w-full" placeholder="Type d'étape">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                                <textarea name="odf_etaps[0][description]" rows="3" class="form-input w-full" placeholder="Description de l'étape"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Résultat</label>
                                <textarea name="odf_etaps[0][resultat]" rows="3" class="form-input w-full" placeholder="Résultat de l'étape"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Participants (issus des diagnostics)</label>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($diagnostics as $diag)
                                        <label class="inline-flex items-center px-3 py-1 rounded-full border border-green-300 bg-green-50 text-xs text-green-800 cursor-pointer">
                                            <input type="checkbox"
                                                   class="mr-2"
                                                   name="odf_etaps[0][participants][]"
                                                   value="{{ $diag->id }}">
                                            <span>Diag #{{ $diag->id }} - {{ $diag->type }}@if($diag->nom) - {{ $diag->nom }}@endif</span>
                                        </label>
                                    @empty
                                        <p class="text-xs text-gray-500">Aucun diagnostic saisi pour le moment.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('odfs.create.step3', $odf) }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour étape 3</span>
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
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
let etapIndex = 1;

function addEtapRow() {
    const container = document.getElementById('etaps-container');
    const row = document.createElement('div');
    row.className = 'etap-row bg-green-50 border border-green-200 rounded-2xl p-4 mt-4';

    let participantsHtml = '';
    @foreach($diagnostics as $diag)
        participantsHtml += '<label class="inline-flex items-center px-3 py-1 rounded-full border border-green-300 bg-green-50 text-xs text-green-800 cursor-pointer">' +
            '<input type="checkbox" class="mr-2" name="odf_etaps[' + etapIndex + '][participants][]" value="{{ $diag->id }}">' +
            '<span>Diag #{{ $diag->id }} - {{ $diag->type }}@if($diag->nom) - {{ $diag->nom }}@endif</span>' +
        '</label>';
    @endforeach
    if (!participantsHtml) {
        participantsHtml = '<p class="text-xs text-gray-500">Aucun diagnostic saisi pour le moment.</p>';
    }

    row.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-md font-semibold text-green-700">Étape #${etapIndex + 1}</h3>
            <button type="button"
                    class="remove-etap text-red-600 hover:text-red-800 text-sm"
                    onclick="removeEtapRow(this)">
                <i class="fas fa-trash mr-1"></i>Supprimer
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Objet</label>
                <input type="text" name="odf_etaps[${etapIndex}][objet]" class="form-input w-full" placeholder="Objet de l'étape">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Lieu</label>
                <input type="text" name="odf_etaps[${etapIndex}][lieu]" class="form-input w-full" placeholder="Lieu">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
                <input type="date" name="odf_etaps[${etapIndex}][date]" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Type</label>
                <input type="text" name="odf_etaps[${etapIndex}][type]" class="form-input w-full" placeholder="Type d'étape">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="odf_etaps[${etapIndex}][description]" rows="3" class="form-input w-full" placeholder="Description de l'étape"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Résultat</label>
                <textarea name="odf_etaps[${etapIndex}][resultat]" rows="3" class="form-input w-full" placeholder="Résultat de l'étape"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Participants (issus des diagnostics)</label>
                <div class="flex flex-wrap gap-2">
                    ${participantsHtml}
                </div>
            </div>
        </div>
    `;
    container.appendChild(row);
    etapIndex++;
}

function removeEtapRow(button) {
    const row = button.closest('.etap-row');
    if (row) {
        row.remove();
    }
}
</script>
@endpush


