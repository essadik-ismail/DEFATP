@extends('layouts.app')

@section('title', 'Détail de la Cession')

@section('breadcrumb')
    <li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
    <li class="bc-item active">Cession #{{ $cession->id }}</li>
@endsection

@section('content')
    @php
        $status = $cession->status ?? '';
        $statusMap = [
            '' => [
                'label' => '—',
                'icon' => 'fa-minus',
                'bg' => 'bg-gray-50',
                'text' => 'text-gray-500',
                'ring' => 'ring-gray-200',
                'dot' => 'bg-gray-400',
            ],
            'ouvert' => [
                'label' => 'Ouverte',
                'icon' => 'fa-folder-open',
                'bg' => 'bg-blue-50',
                'text' => 'text-blue-700',
                'ring' => 'ring-blue-200',
                'dot' => 'bg-blue-500',
            ],
            'en_cours' => [
                'label' => 'En cours',
                'icon' => 'fa-spinner',
                'bg' => 'bg-amber-50',
                'text' => 'text-amber-700',
                'ring' => 'ring-amber-200',
                'dot' => 'bg-amber-400',
            ],
            'cloture' => [
                'label' => 'Clôturée',
                'icon' => 'fa-check-circle',
                'bg' => 'bg-emerald-50',
                'text' => 'text-emerald-700',
                'ring' => 'ring-emerald-200',
                'dot' => 'bg-emerald-500',
            ],
        ];
        $badge = $statusMap[$status] ?? $statusMap['ouvert'];
        $articleCount = $cession->articles->count();
        $allArticlesDone = $cession->articles->isNotEmpty() && $cession->articles->every(
            fn($a) => in_array($a->workflow_state, [\App\Services\ArticleWorkflowService::MAINLEVEE_DONE, \App\Services\ArticleWorkflowService::CLOSED], true)
        );
    @endphp
    <div class="min-w-0 max-w-full overflow-x-hidden">
        <!-- Header -->
        <x-page-header title="Cession #{{ $cession->id }}" :subtitle="($cession->dranef->dranef ?? '') . ' — ' . ($cession->type === 'appel_offre' ? 'Appel d\'offre' : 'Adjudication') . ' ' . ($cession->annee_exercice ?? '')" icon="fas fa-gavel">
            <x-slot name="actions">
                <a href="{{ route('cessions.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Retour
                </a>
                @if (($cession->status ?? '') !== 'cloture')
                    @if($allArticlesDone)
                        <form action="{{ route('cessions.cloture', $cession) }}" method="POST" class="inline"
                            onsubmit="return confirm('Clôturer cette cession ?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition-colors">
                                <i class="fas fa-check-circle"></i>
                                Clôturer la cession
                            </button>
                        </form>
                    @else
                        <button type="button" disabled title="Tous les articles doivent être au statut terminé avant de clôturer."
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-400 cursor-not-allowed">
                            <i class="fas fa-check-circle"></i>
                            Clôturer la cession
                        </button>
                    @endif
                @endif
            </x-slot>
        </x-page-header>

        <!-- Info Details Card -->
        <div class="rounded-2xl border bg-white mb-6"
            style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <div class="px-6 py-4 border-b" style="border-color: rgba(154,179,163,0.3);">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                        <i class="fas fa-info-circle text-white text-sm"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Informations de la cession</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-5">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">DRANEF</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $cession->dranef->dranef ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Type de cession</dt>
                        <dd class="text-sm font-semibold text-gray-900">
                            {{ $cession->type === 'appel_offre' ? "Appel d'offre" : 'Adjudication' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Année / Exercice</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $cession->annee_exercice ?? '-' }}</dd>
                    </div>
                    @if ($cession->type === 'adjudication')
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Date d'adjudication
                            </dt>
                            <dd class="text-sm font-semibold text-gray-900">
                                {{ optional($cession->date_adjudication)->format('d/m/Y') ?? '-' }}</dd>
                        </div>
                    @else
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">N° Appel d'offre</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $cession->numero_ao ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Date d'attribution
                            </dt>
                            <dd class="text-sm font-semibold text-gray-900">
                                {{ optional($cession->date_attribution)->format('d/m/Y') ?? '-' }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Statut</dt>
                        <dd>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $badge['bg'] }} {{ $badge['text'] }} ring-1 {{ $badge['ring'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                {{ $badge['label'] }}
                            </span>
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles Table Card -->
        <div class="rounded-2xl border bg-white overflow-hidden"
            style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <div class="px-6 py-4 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                style="border-color: rgba(154,179,163,0.3);">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-file-alt text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Articles liés</h3>
                        <p class="text-xs text-gray-500">{{ $articleCount }} article(s) dans cette cession</p>
                    </div>
                </div>
                @if (($cession->status ?? '') !== 'cloture')
                    <a href="{{ route('articles.create', ['cession_id' => $cession->id]) }}"
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                        <i class="fas fa-plus"></i>
                        Ajouter article
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto w-full" style="-webkit-overflow-scrolling: touch;">
                <table id="cessionArticlesTable" data-table="cessionArticlesTable" class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Numéro
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Lot
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Superficie (ha)
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Étape actuelle
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Créé le
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($cession->articles as $article)
                            @php
                                $stepLabels = [
                                    'article_cree' => [
                                        'label' => 'Article créé',
                                        'class' => 'bg-slate-100 text-slate-700',
                                    ],
                                    'cahier_affiche' => [
                                        'label' => 'Cahier & Affiche',
                                        'class' => 'bg-gray-100 text-gray-700',
                                    ],
                                    'contrat_vente' => [
                                        'label' => 'Contrat de vente',
                                        'class' => 'bg-green-100 text-green-700',
                                    ],
                                    'paiement_charges' => [
                                        'label' => 'Paiement charges',
                                        'class' => 'bg-blue-100 text-blue-700',
                                    ],
                                    'paiement_tranches' => [
                                        'label' => 'Paiement tranches',
                                        'class' => 'bg-purple-100 text-purple-700',
                                    ],
                                    'recollement' => [
                                        'label' => 'Récollement',
                                        'class' => 'bg-orange-100 text-orange-700',
                                    ],
                                    'main_levee' => [
                                        'label' => 'Main levée',
                                        'class' => 'bg-emerald-100 text-emerald-700',
                                    ],
                                ];
                                $currentStep = $article->current_step ?? 'article_cree';
                                $stepInfo = $stepLabels[$currentStep] ?? [
                                    'label' => ucfirst(str_replace('_', ' ', $currentStep)),
                                    'class' => 'bg-gray-100 text-gray-700',
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($article->numero)
                                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-900">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                            {{ $article->numero }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $article->lot ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                    {{ $article->superficie ? number_format($article->superficie, 2, ',', ' ') : '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $stepInfo['class'] }}">
                                        {{ $stepInfo['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $article->created_at ? $article->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('articles.show', $article) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-medium transition-colors duration-200"
                                            title="Voir l'article">
                                            <i class="fas fa-eye text-xs"></i>
                                            Voir
                                        </a>
                                        @if(($article->workflow_state ?? 'DRAFT_ARTICLE') === 'DRAFT_ARTICLE')
                                        <a href="{{ route('articles.edit', $article) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-medium transition-colors duration-200"
                                            title="Modifier l'article">
                                            <i class="fas fa-edit text-xs"></i>
                                            Éditer
                                        </a>
                                        @endif
                                        <a href="{{ route('workflow.prorogation.create', $article) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-xs font-medium transition-colors duration-200"
                                            title="Prorogation">
                                            <i class="fas fa-calendar-plus text-xs"></i>
                                            Prorogation
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i class="fas fa-file-alt text-2xl text-gray-300"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500 mb-1">Aucun article lié</p>
                                        <p class="text-xs text-gray-400 mb-4">Commencez par ajouter un premier article à
                                            cette cession.</p>
                                        <a href="{{ route('articles.create', ['cession_id' => $cession->id]) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                                            <i class="fas fa-plus"></i>
                                            Ajouter un article
                                        </a>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof initDataTableWithFilters === 'function') {
                initDataTableWithFilters('cessionArticlesTable', {
                    order: [
                        [0, 'asc']
                    ],
                    pageLength: 25
                });
            }
        });
    </script>
@endpush
