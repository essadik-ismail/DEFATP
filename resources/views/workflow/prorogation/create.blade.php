@extends('layouts.app')

@section('title', 'Demande de prorogation - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
<li class="bc-item active">Demande de prorogation</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-2xl">

        <x-page-header
            title="Demande de prorogation"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-calendar-plus"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        @if($errors->has('prorogation'))
            <x-alert type="error" title="Erreur!" dismissible>{{ $errors->first('prorogation') }}</x-alert>
        @endif

        <!-- Contract info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-2 text-blue-800 text-sm font-medium mb-1">
                <i class="fas fa-info-circle"></i> Contrat de vente
            </div>
            <p class="text-sm text-blue-700">
                Exploitant : <strong>{{ $contract->exploitant?->nom_complet ?? '—' }}</strong>
                @if($contract->date_expiration)
                    &nbsp;|&nbsp; Date d'expiration actuelle : <strong>{{ $contract->date_expiration->format('d/m/Y') }}</strong>
                @endif
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('workflow.prorogation.store', $article) }}" method="POST" class="space-y-6">
                @csrf

                <x-validation-errors />

                <!-- Durée -->
                <div class="form-group">
                    <label for="duration_months" class="block text-sm font-semibold text-gray-700 mb-2">
                        Durée de prorogation (mois) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="duration_months" id="duration_months"
                           value="{{ old('duration_months') }}"
                           min="1" max="60"
                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Entre 1 et 60 mois.</p>
                </div>

                <!-- Motif -->
                <div class="form-group">
                    <label for="motif" class="block text-sm font-semibold text-gray-700 mb-2">
                        Motif <span class="text-red-500">*</span>
                    </label>
                    <textarea name="motif" id="motif" rows="5" maxlength="1000"
                              class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              required placeholder="Décrivez le motif de la demande de prorogation...">{{ old('motif') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Maximum 1000 caractères.</p>
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('articles.show', $article) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                        <i class="fas fa-paper-plane"></i> Soumettre la demande
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
