@extends('layouts.app')

@section('title', 'PV de Récolement - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
<li class="breadcrumb-item active">PV de Récolement</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-2xl">

        <x-page-header
            title="PV de Récolement"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-clipboard-check"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        @if(session('success'))
            <x-alert type="success" title="Succès!" dismissible>{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error" title="Erreur!" dismissible>{{ session('error') }}</x-alert>
        @endif

        @php $isSubmitted = $recolement->status !== \App\Models\Recolement::STATUS_PENDING_PV && $recolement->exists; @endphp

        @if($isSubmitted)
        <!-- Existing recolement info -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-2 text-green-800 text-sm font-semibold mb-2">
                <i class="fas fa-check-circle"></i> PV déjà soumis
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm text-green-800">
                <div><span class="font-medium">N° PV :</span> {{ $recolement->num_pv }}</div>
                <div><span class="font-medium">Date :</span> {{ $recolement->date_pv?->format('d/m/Y') }}</div>
                @if($recolement->num_mainlevee)
                <div><span class="font-medium">N° Mainlevée :</span> {{ $recolement->num_mainlevee }}</div>
                <div><span class="font-medium">Date mainlevée :</span> {{ $recolement->date_mainlevee?->format('d/m/Y') }}</div>
                @endif
            </div>
        </div>

        @if($recolement->status === \App\Models\Recolement::STATUS_PV_SUBMITTED)
        <!-- Mainlevée form -->
        @can('mainlevee.issue')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-stamp text-green-600"></i> Émettre la Mainlevée
            </h3>
            <form action="{{ route('workflow.mainlevee.issue', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <x-validation-errors />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="form-group">
                        <label for="num_mainlevee" class="block text-sm font-semibold text-gray-700 mb-2">
                            N° Mainlevée <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="num_mainlevee" id="num_mainlevee" value="{{ old('num_mainlevee') }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               required maxlength="80">
                    </div>
                    <div class="form-group">
                        <label for="date_mainlevee" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date mainlevée <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_mainlevee" id="date_mainlevee" value="{{ old('date_mainlevee', now()->format('Y-m-d')) }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               required>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label for="fichier_mainlevee" class="block text-sm font-semibold text-gray-700 mb-2">Fichier mainlevée (PDF)</label>
                        <input type="file" name="fichier_mainlevee" id="fichier_mainlevee" accept=".pdf"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                        <i class="fas fa-stamp"></i> Émettre la mainlevée
                    </button>
                </div>
            </form>
        </div>
        @endcan
        @endif

        @else
        <!-- PV submission form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('workflow.recolement.store', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <x-validation-errors />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="num_pv" class="block text-sm font-semibold text-gray-700 mb-2">
                            N° PV <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="num_pv" id="num_pv" value="{{ old('num_pv', $recolement->num_pv) }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               required maxlength="80">
                    </div>
                    <div class="form-group">
                        <label for="date_pv" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date PV <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_pv" id="date_pv"
                               value="{{ old('date_pv', $recolement->date_pv?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               required>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label for="observations" class="block text-sm font-semibold text-gray-700 mb-2">Observations</label>
                        <textarea name="observations" id="observations" rows="4"
                                  class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                  placeholder="Observations éventuelles...">{{ old('observations', $recolement->observations) }}</textarea>
                    </div>
                    <div class="form-group md:col-span-2">
                        <label for="fichier_pv" class="block text-sm font-semibold text-gray-700 mb-2">Fichier PV (PDF)</label>
                        <input type="file" name="fichier_pv" id="fichier_pv" accept=".pdf"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('articles.show', $article) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                        <i class="fas fa-save"></i> Soumettre le PV
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection
