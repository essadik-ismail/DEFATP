@extends('layouts.app')

@section('title', 'Gestion des Cessions')

@section('breadcrumb')
<li class="breadcrumb-item active">Cessions</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden" x-data="{ activeTab: 'adjudication' }">
    <!-- Header -->
    <x-page-header
        title="Gestion des Cessions"
        subtitle="Pilotez les cessions par adjudication et appel d'offre"
        icon="fas fa-gavel"
    >
        <x-slot name="actions">
            <a href="{{ route('cessions.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm"
               style="background: var(--primary-gradient); box-shadow: var(--shadow-md);">
                <i class="fas fa-plus"></i>
                <span>Ajouter Cession</span>
            </a>
        </x-slot>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success" title="Succès!" dismissible class="mb-4">
            {{ session('success') }}
        </x-alert>
    @endif

    <!-- Tabs -->
    <div class="rounded-2xl border max-w-full overflow-hidden mb-4"
         style="background:#FFFFFF;border-color:rgba(154,179,163,0.4);box-shadow:var(--shadow-card);">
        <div class="border-b border-gray-100 px-4 pt-3">
            <nav class="flex space-x-4 text-sm font-medium">
                <button
                    type="button"
                    class="px-3 py-2 rounded-t-lg border-b-2 -mb-px transition"
                    :class="activeTab === 'adjudication'
                        ? 'border-emerald-600 text-emerald-700'
                        : 'border-transparent text-gray-500 hover:text-gray-700'"
                    @click="activeTab = 'adjudication'"
                >
                    <i class="fas fa-gavel mr-1.5 text-xs"></i> Adjudication
                </button>
                <button
                    type="button"
                    class="px-3 py-2 rounded-t-lg border-b-2 -mb-px transition"
                    :class="activeTab === 'appel_offre'
                        ? 'border-emerald-600 text-emerald-700'
                        : 'border-transparent text-gray-500 hover:text-gray-700'"
                    @click="activeTab = 'appel_offre'"
                >
                    <i class="fas fa-file-signature mr-1.5 text-xs"></i> Appel d'offre
                </button>
            </nav>
        </div>

        <div class="p-4">
            <!-- Tab: Adjudication -->
            <div x-show="activeTab === 'adjudication'" x-cloak>
                <div class="table-responsive overflow-x-auto max-w-full">
                    <table id="adjudicationTable"
                           data-table="adjudicationTable"
                           class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                DRANEF
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Année / Exercice
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date d'adjudication
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nombre d'articles
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach($adjudications as $cession)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    {{ $cession->dranef->dranef ?? '-' }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    {{ $cession->annee_exercice ?? '-' }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    {{ optional($cession->date_adjudication)->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center rounded-full bg-emerald-50 text-emerald-700 text-xs px-2 py-0.5 font-medium">
                                        {{ $cession->articles_count }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @php
                                        $status = $cession->status ?? 'brouillon';
                                        $map = [
                                            'brouillon' => ['label' => 'Brouillon', 'class' => 'bg-gray-100 text-gray-800'],
                                            'en_cours' => ['label' => 'En cours', 'class' => 'bg-amber-100 text-amber-800'],
                                            'cloture' => ['label' => 'Clôturée', 'class' => 'bg-emerald-100 text-emerald-800'],
                                        ];
                                        $badge = $map[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badge['class'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center justify-center gap-1">
                                        <a href="{{ route('cessions.show', $cession) }}"
                                           class="inline-flex items-center justify-center w-7 h-7 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full transition-colors duration-200"
                                           title="Voir la cession">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('cessions.edit', $cession) }}"
                                           class="inline-flex items-center justify-center w-7 h-7 bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-full transition-colors duration-200"
                                           title="Modifier la cession">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <a href="{{ route('articles.create', ['cession_id' => $cession->id]) }}"
                                           class="inline-flex items-center justify-center w-7 h-7 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-full transition-colors duration-200"
                                           title="Ajouter un article">
                                            <i class="fas fa-plus text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Appel d'offre -->
            <div x-show="activeTab === 'appel_offre'" x-cloak>
                <div class="table-responsive overflow-x-auto max-w-full">
                    <table id="appelOffreTable"
                           data-table="appelOffreTable"
                           class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                DRANEF
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Année / Exercice
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                N° AO
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date d'attribution
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nombre d'articles
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach($appelOffres as $cession)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    {{ $cession->dranef->dranef ?? '-' }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    {{ $cession->annee_exercice ?? '-' }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    {{ $cession->numero_ao ?? '-' }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    {{ optional($cession->date_attribution)->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center rounded-full bg-emerald-50 text-emerald-700 text-xs px-2 py-0.5 font-medium">
                                        {{ $cession->articles_count }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @php
                                        $status = $cession->status ?? 'brouillon';
                                        $map = [
                                            'brouillon' => ['label' => 'Brouillon', 'class' => 'bg-gray-100 text-gray-800'],
                                            'en_cours' => ['label' => 'En cours', 'class' => 'bg-amber-100 text-amber-800'],
                                            'cloture' => ['label' => 'Clôturée', 'class' => 'bg-emerald-100 text-emerald-800'],
                                        ];
                                        $badge = $map[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-gray-100 text-gray-800'];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badge['class'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center justify-center gap-1">
                                        <a href="{{ route('cessions.show', $cession) }}"
                                           class="inline-flex items-center justify-center w-7 h-7 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full transition-colors duration-200"
                                           title="Voir la cession">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('cessions.edit', $cession) }}"
                                           class="inline-flex items-center justify-center w-7 h-7 bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-full transition-colors duration-200"
                                           title="Modifier la cession">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <a href="{{ route('articles.create', ['cession_id' => $cession->id]) }}"
                                           class="inline-flex items-center justify-center w-7 h-7 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-full transition-colors duration-200"
                                           title="Ajouter un article">
                                            <i class="fas fa-plus text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof initDataTableWithFilters === 'function') {
            initDataTableWithFilters('adjudicationTable', {
                order: [[1, 'desc']]
            });
            initDataTableWithFilters('appelOffreTable', {
                order: [[1, 'desc']]
            });
        }
    });
</script>
@endpush

