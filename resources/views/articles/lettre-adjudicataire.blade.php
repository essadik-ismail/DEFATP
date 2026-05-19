@extends('layouts.app')

@section('title', 'Lettre Adjudicataire - DEFATP')

@section('breadcrumb')
    <li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    <li class="bc-item"><a href="{{ route('articles.show', $article) }}">Dossier #{{ $article->numero ?? $article->id }}</a></li>
    <li class="bc-item active">Lettre adjudicataire</li>
@endsection

@section('content')
    <div class="min-w-0 max-w-full overflow-x-hidden">
        <div class="container mx-auto px-4 max-w-7xl">

            <x-page-header title="Lettre Adjudicataire" :subtitle="'Article #' . ($article->numero ?? $article->id)" icon="fas fa-file-word"
                :backRoute="route('articles.show', $article)" backText="Retour" />

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="space-y-8">

                    <!-- 1. Statut du modèle -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                <i class="fas fa-file-word text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">1. Statut du modèle DOCX</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            {{-- Template status --}}
                            <div class="rounded-lg border p-4 flex items-start gap-3
                                {{ $templateAvailable ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }}">
                                <div class="mt-0.5 w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ $templateAvailable ? 'bg-emerald-500' : 'bg-red-500' }}">
                                    <i class="fas {{ $templateAvailable ? 'fa-check' : 'fa-times' }} text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold {{ $templateAvailable ? 'text-emerald-800' : 'text-red-800' }}">
                                        Modèle DOCX
                                    </p>
                                    <p class="text-xs mt-0.5 {{ $templateAvailable ? 'text-emerald-700' : 'text-red-700' }}">
                                        {{ $templateAvailable ? 'Modèle "Lettre d\'Adjudicataire.docx" prêt.' : 'Modèle introuvable sur le serveur.' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Exploitant --}}
                            @php $exploitantName = ($resolvedPlaceholders['Exploitant'] ?? '') !== '' ? $resolvedPlaceholders['Exploitant'] : null; @endphp
                            <div class="rounded-lg border p-4 flex items-start gap-3
                                {{ $exploitantName ? 'border-slate-200 bg-slate-50' : 'border-amber-200 bg-amber-50' }}">
                                <div class="mt-0.5 w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ $exploitantName ? 'bg-slate-500' : 'bg-amber-500' }}">
                                    <i class="fas fa-user-tie text-white text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold {{ $exploitantName ? 'text-slate-800' : 'text-amber-800' }}">
                                        Exploitant
                                    </p>
                                    <p class="text-xs mt-0.5 truncate {{ $exploitantName ? 'text-slate-700' : 'text-amber-700' }}">
                                        {{ $exploitantName ?? 'Non renseigné' }}
                                    </p>
                                </div>
                            </div>

                            {{-- PDF export --}}
                            <div class="rounded-lg border p-4 flex items-start gap-3
                                {{ $pdfAvailable ? 'border-rose-200 bg-rose-50' : 'border-amber-200 bg-amber-50' }}">
                                <div class="mt-0.5 w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ $pdfAvailable ? 'bg-rose-500' : 'bg-amber-400' }}">
                                    <i class="fas fa-file-pdf text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold {{ $pdfAvailable ? 'text-rose-800' : 'text-amber-800' }}">
                                        Export PDF
                                    </p>
                                    <p class="text-xs mt-0.5 {{ $pdfAvailable ? 'text-rose-700' : 'text-amber-700' }}">
                                        {{ $pdfAvailable ? 'Génération PDF disponible.' : 'PDF non disponible sur ce serveur.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Correspondance des champs -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-green-600">
                                <i class="fas fa-table text-white text-sm"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">2. Correspondance des champs</h3>
                            @if(count($resolvedPlaceholders) > 0)
                                <span class="ml-auto inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fas fa-check-circle text-xs"></i>
                                    {{ count($resolvedPlaceholders) }} champ(s)
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Placeholders détectés et valeurs qui seront injectées avant téléchargement.</p>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500 w-2/5">
                                            Placeholder
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                            Valeur résolue
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($resolvedPlaceholders as $placeholder => $value)
                                        <tr class="hover:bg-emerald-50/40 transition-colors border-b border-gray-100">
                                            <td class="px-6 py-3 align-middle">
                                                <code class="text-xs font-mono font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                                    &#123;&#123;{{ $placeholder }}&#125;&#125;
                                                </code>
                                            </td>
                                            <td class="px-6 py-3 align-middle">
                                                @if($value !== '')
                                                    <span class="text-sm text-gray-800">{{ $value }}</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600">
                                                        <i class="fas fa-exclamation-circle text-xs"></i>
                                                        Non renseigné
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-6 py-10 text-center">
                                                <div class="inline-flex flex-col items-center gap-2 text-gray-400">
                                                    <i class="fas fa-search text-2xl"></i>
                                                    <span class="text-sm">Aucun placeholder détecté dans le modèle DOCX.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-4 pt-2">
                        <a href="{{ route('articles.show', $article) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Retour à l'Article</span>
                        </a>
                        <a href="{{ route('articles.lettre-adjudicataire.print', $article) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <i class="fas fa-print"></i>
                            <span>Imprimer</span>
                        </a>
                        @can('adjudicataire_letter.download')
                            @if($contractVente->is_validated)
                                <a href="{{ route('articles.lettre-adjudicataire.download', $article) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $templateAvailable ? 'bg-blue-600 hover:bg-blue-700' : 'bg-blue-300 pointer-events-none' }}">
                                    <i class="fas fa-download"></i>
                                    <span>Télécharger DOCX</span>
                                </a>
                                <a href="{{ route('articles.lettre-adjudicataire.download-pdf', $article) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 {{ $pdfAvailable ? 'bg-red-600 hover:bg-red-700' : 'bg-red-300 pointer-events-none' }}">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>Télécharger PDF</span>
                                </a>
                            @else
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm text-amber-700 bg-amber-50 border border-amber-200">
                                    <i class="fas fa-lock text-xs"></i>
                                    Téléchargement disponible après validation du contrat
                                </span>
                            @endif
                        @endcan
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
