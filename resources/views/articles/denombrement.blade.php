@extends('layouts.app')

@section('title', 'Dénombrement — ' . ($article->numero ?? $article->id))

@section('breadcrumb')
    <li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    <li class="bc-item"><a href="{{ route('articles.show', $article) }}">Dossier #{{ $article->numero ?? $article->id }}</a></li>
    <li class="bc-item active">Dénombrement</li>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-teal-600">
                    <i class="fas fa-clipboard-list text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Dénombrement</h2>
                    <p class="text-sm text-gray-500">Dossier #{{ $article->numero ?? $article->id }}</p>
                </div>
            </div>

            @if($denombrement?->fichier_pv)
                <div class="mb-6 p-4 bg-teal-50 border border-teal-200 rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-2 text-teal-700 text-sm">
                        <i class="fas fa-file-pdf"></i>
                        <span>PV de dénombrement déjà uploadé ({{ $denombrement->date_denombrement?->format('d/m/Y') }})</span>
                    </div>
                    <a href="{{ route('articles.denombrement.download', $article) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                        <i class="fas fa-download"></i> Télécharger
                    </a>
                </div>
            @endif

            <form action="{{ route('articles.store-denombrement', $article) }}" method="POST"
                  enctype="multipart/form-data" class="space-y-6">
                @csrf
                <x-validation-errors />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Date de dénombrement <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_denombrement"
                               value="{{ old('date_denombrement', $denombrement?->date_denombrement?->format('Y-m-d')) }}"
                               required
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Agent responsable</label>
                        <input type="text" name="agent_responsable"
                               value="{{ old('agent_responsable', $denombrement?->agent_responsable) }}"
                               placeholder="Nom de l'agent"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Volume dénombré (m³)</label>
                        <input type="number" name="volume_denombre" step="0.001" min="0"
                               value="{{ old('volume_denombre', $denombrement?->volume_denombre) }}"
                               placeholder="0.000"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">PV de dénombrement (PDF/image)</label>
                        <input type="file" name="fichier_pv"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-4 file:py-2 file:font-medium file:text-teal-700 hover:file:bg-teal-100">
                        <p class="text-xs text-gray-500 mt-1">Laissez vide pour conserver le fichier existant.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Observations</label>
                    <textarea name="observations" rows="4"
                              placeholder="Observations générales..."
                              class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('observations', $denombrement?->observations) }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('articles.show', $article) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition-colors">
                        <i class="fas fa-save"></i>
                        {{ $denombrement ? 'Mettre à jour' : 'Enregistrer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
