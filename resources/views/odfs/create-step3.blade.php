@extends('layouts.app')

@section('title', 'Nouvelle ODF - Étape 3 - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl flex items-center justify-center">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Nouvelle ODF - Étape 3/5
                </h1>
                <p class="text-gray-600 mt-1">
                    Membres pour l'ODF #{{ $odf->id }}
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
        <div class="mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #ec4899, #db2777);">
                    <i class="fas fa-users text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Étape 3 - Membres</h2>
                    <p class="text-sm text-gray-600">Ajoutez un ou plusieurs membres associés à cette ODF.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('odfs.store.step3', $odf) }}" method="POST" id="membersForm">
            @csrf

            <div id="members-container" class="space-y-4">
                <div class="member-row bg-pink-50 border border-pink-200 rounded-2xl p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-md font-semibold text-pink-700">Membre #1</h3>
                        <button type="button"
                                class="remove-member hidden text-red-600 hover:text-red-800 text-sm"
                                onclick="removeMemberRow(this)">
                            <i class="fas fa-trash mr-1"></i>Supprimer
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                            <input type="text" name="members[0][type]" class="form-input w-full" placeholder="Type de membre (ex: Présidente)" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Diagnostic associé (optionnel)</label>
                            <select name="members[0][odf_diagnostic_id]" class="form-input w-full">
                                <option value="">Aucun</option>
                                @foreach ($diagnostics as $diag)
                                    <option value="{{ $diag->id }}">
                                        {{ $diag->type }} @if($diag->nom) - {{ $diag->nom }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="button"
                        onclick="addMemberRow()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all duration-300">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter un membre</span>
                </button>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('odfs.create.step2', $odf) }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour étape 2</span>
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-xl hover:from-pink-600 hover:to-rose-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
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
let memberIndex = 1;

function addMemberRow() {
    const container = document.getElementById('members-container');
    const row = document.createElement('div');
    row.className = 'member-row bg-pink-50 border border-pink-200 rounded-2xl p-4 mt-4';
    let optionsHtml = '<option value=\"\">Aucun</option>';
    @foreach ($diagnostics as $diag)
        optionsHtml += '<option value="{{ $diag->id }}">' +
            '{{ $diag->type }}' + {!! $diag->nom ? " ' - ' + '{{ $diag->nom }}'" : "''" !!} +
            '</option>';
    @endforeach

    row.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-md font-semibold text-pink-700">Membre #${memberIndex + 1}</h3>
            <button type="button"
                    class="remove-member text-red-600 hover:text-red-800 text-sm"
                    onclick="removeMemberRow(this)">
                <i class="fas fa-trash mr-1"></i>Supprimer
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <input type="text" name="members[${memberIndex}][type]" class="form-input w-full" placeholder="Type de membre" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Diagnostic associé (optionnel)</label>
                <select name="members[${memberIndex}][odf_diagnostic_id]" class="form-input w-full">
                    ${optionsHtml}
                </select>
            </div>
        </div>
    `;
    container.appendChild(row);
    memberIndex++;
}

function removeMemberRow(button) {
    const row = button.closest('.member-row');
    if (row) {
        row.remove();
    }
}
</script>
@endpush


