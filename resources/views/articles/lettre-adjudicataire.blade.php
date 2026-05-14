@extends('layouts.app')

@section('title', 'Lettre Adjudicataire - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="bc-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
<li class="bc-item active">Lettre adjudicataire</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full">

    <x-page-header
        title="Lettre Adjudicataire"
        :subtitle="'Article #' . ($article->numero ?? $article->id)"
        icon="fas fa-file-word"
        :backRoute="route('articles.show', $article)"
        backText="Retour"
    />

    {{-- ── Status Overview ─────────────────────────────────────── --}}
    <div class="rounded-2xl border bg-white overflow-hidden mb-5" style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);">

        {{-- Card header --}}
        <div class="px-6 py-4 border-b flex items-center gap-3" style="border-color: rgba(154,179,163,0.25); background: #f9fbfa;">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg,#2563eb,#1d4ed8); box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                <i class="fas fa-file-word text-white text-sm"></i>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-900">Téléchargement du modèle DOCX</h2>
                <p class="text-xs text-gray-500 mt-0.5">Les champs <code class="bg-gray-100 px-1 rounded text-xs">&#123;&#123;attribut&#125;&#125;</code> sont remplacés automatiquement par les données de la base.</p>
            </div>
        </div>

        <div class="p-6">
            {{-- Status tiles --}}
            <div class="grid gap-4 md:grid-cols-3 mb-6">

                {{-- Template status --}}
                <div class="rounded-xl border p-4 flex items-start gap-3
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
                            {{ $templateAvailable
                                ? 'Modèle "Lettre d\'Adjudicataire.docx" prêt.'
                                : 'Modèle introuvable sur le serveur.' }}
                        </p>
                    </div>
                </div>

                {{-- Exploitant --}}
                @php $exploitantName = ($resolvedPlaceholders['Exploitant'] ?? '') !== '' ? $resolvedPlaceholders['Exploitant'] : null; @endphp
                <div class="rounded-xl border p-4 flex items-start gap-3
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
                <div class="rounded-xl border p-4 flex items-start gap-3
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
                            {{ $pdfAvailable
                                ? 'Génération PDF disponible.'
                                : 'PDF non disponible sur ce serveur.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Download actions --}}
            <div class="flex flex-wrap items-center justify-end gap-3 pt-5 border-t" style="border-color: rgba(154,179,163,0.2);">

                <a href="{{ route('articles.show', $article) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                   style="border-color: rgba(154,179,163,0.5);">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Retour à l'article
                </a>

                <a href="{{ route('articles.lettre-adjudicataire.print', $article) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white transition-all hover:brightness-110 hover:-translate-y-0.5 active:translate-y-0"
                   style="background: linear-gradient(135deg, #1a3a1a, #2d5a2d); box-shadow: 0 3px 10px rgba(26,58,26,0.3);">
                    <i class="fas fa-print text-xs"></i>
                    Imprimer
                </a>

                @can('adjudicataire_letter.download')
                @if($contractVente->is_validated)
                <a href="{{ route('articles.lettre-adjudicataire.download', $article) }}"
                   class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white transition-all {{ $templateAvailable ? 'hover:brightness-110 hover:-translate-y-0.5 active:translate-y-0' : 'opacity-50 pointer-events-none' }}"
                   style="background: linear-gradient(135deg, #2563eb, #1d4ed8); box-shadow: {{ $templateAvailable ? '0 3px 10px rgba(37,99,235,0.3)' : 'none' }};">
                    <i class="fas fa-download text-xs"></i>
                    Télécharger DOCX
                </a>

                <a href="{{ route('articles.lettre-adjudicataire.download-pdf', $article) }}"
                   class="inline-flex items-center gap-2 px-5 py-2 rounded-lg text-sm font-semibold text-white transition-all {{ $pdfAvailable ? 'hover:brightness-110 hover:-translate-y-0.5 active:translate-y-0' : 'opacity-50 pointer-events-none' }}"
                   style="background: linear-gradient(135deg, #dc2626, #b91c1c); box-shadow: {{ $pdfAvailable ? '0 3px 10px rgba(220,38,38,0.3)' : 'none' }};">
                    <i class="fas fa-file-pdf text-xs"></i>
                    Télécharger PDF
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

    {{-- ── Placeholder mapping table ────────────────────────────── --}}
    <div class="rounded-2xl border bg-white overflow-hidden" style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);">

        <div class="px-6 py-4 border-b flex items-center gap-3" style="border-color: rgba(154,179,163,0.25); background: #f9fbfa;">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg,#059669,#047857); box-shadow: 0 2px 6px rgba(5,150,105,0.25);">
                <i class="fas fa-table text-white text-sm"></i>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-900">Correspondance des champs</h2>
                <p class="text-xs text-gray-500 mt-0.5">Placeholders détectés et valeurs qui seront injectées avant téléchargement.</p>
            </div>
            @if(count($resolvedPlaceholders) > 0)
            <span class="ml-auto inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                <i class="fas fa-check-circle text-xs"></i>
                {{ count($resolvedPlaceholders) }} champ(s)
            </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr style="background: #f4f7f5;">
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
                    <tr class="hover:bg-emerald-50/40 transition-colors border-b" style="border-color: rgba(154,179,163,0.12);">
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

</div>
@endsection
