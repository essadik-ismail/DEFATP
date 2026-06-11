@extends('layouts.app')

@section('title', 'PV d\'Installation - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="bc-item"><a href="{{ route('articles.show', $article) }}">Détail #{{ $article->numero ?? $article->id }}</a></li>
<li class="bc-item active">PV d'installation</li>
@endsection

@section('content')
<div>
    <x-page-header
        title="PV d'Installation"
        :subtitle="'Article #' . ($article->numero ?? $article->id)"
        icon="fas fa-clipboard-check"
        :backRoute="route('articles.show', $article)"
        backText="Retour"
    />

    <div class="space-y-6">

        <!-- Gérer les véhicules -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600">
                    <i class="fas fa-truck text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Véhicules</h3>
            </div>
            <p class="text-sm text-gray-600 mb-4">Déclarez et gérez les véhicules associés à cet article.</p>
            <a href="{{ route('vehicles.index', $article) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                <i class="fas fa-truck"></i> Gérer les véhicules
            </a>
        </div>

        <!-- Importer le PV d'installation signé -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-600">
                    <i class="fas fa-file-signature text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Importer le PV d'installation signé</h3>
            </div>

            @if(isset($pvInstallation) && $pvInstallation && $pvInstallation->fichier_pv_signe)
                <div class="flex items-center gap-3 mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-emerald-800">PV signé importé</p>
                        @if($pvInstallation->pv_signed_at)
                            <p class="text-xs text-emerald-600">Le {{ $pvInstallation->pv_signed_at->format('d/m/Y à H:i') }}</p>
                        @endif
                    </div>
                    <a href="{{ route('workflow.view-signed-pv', $article) }}" target="_blank"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-white border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50">
                        <i class="fas fa-eye"></i> Voir
                    </a>
                </div>
            @endif

            <form action="{{ route('workflow.upload-signed-pv', $article) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-end gap-3 flex-wrap">
                    <div class="flex-1 min-w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ (isset($pvInstallation) && $pvInstallation?->fichier_pv_signe) ? 'Remplacer le PV signé' : 'Importer le PV signé' }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="fichier_pv_signe" accept=".pdf,.jpg,.jpeg,.png"
                               class="block w-full text-sm text-gray-600 file:mr-2 file:rounded file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:font-medium file:text-emerald-700 hover:file:bg-emerald-100"
                               required>
                        <p class="text-xs text-gray-400 mt-1">PDF / JPG / PNG, max 10 Mo</p>
                    </div>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-upload"></i> Importer
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
