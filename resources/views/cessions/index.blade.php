@extends('layouts.app')

@section('title', 'Gestion des Cessions')

@section('breadcrumb')
<li class="bc-item active">Cessions</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden" x-data="{ activeTab: '{{ request('tab', 'adjudication') }}' }">

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Cessions"
        icon="fas fa-gavel"
    >
        <x-slot name="actions">
            <x-button href="{{ route('cessions.create') }}" icon="fas fa-plus">
                Nouvelle cession
            </x-button>
        </x-slot>
    </x-page-header>

    {{-- ─── Tabbed table card ───────────────────────────────────────── --}}
    <div
        class="rounded-2xl border bg-white overflow-hidden"
        style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);"
    >
        {{-- Tab bar --}}
        <div class="border-b border-gray-100 bg-gray-50/60 px-4 pt-0">
            <nav class="-mb-px flex gap-1 text-sm font-medium">
                <button
                    type="button"
                    class="inline-flex items-center gap-1.5 px-4 py-3 border-b-2 transition-colors"
                    :class="activeTab === 'adjudication'
                        ? 'border-emerald-600 text-emerald-700'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    @click="activeTab = 'adjudication'"
                >
                    <i class="fas fa-gavel text-xs"></i>
                    Adjudication
                    <span
                        class="ml-1 rounded-full px-1.5 py-0.5 text-[10px] font-semibold"
                        :class="activeTab === 'adjudication' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'"
                    >{{ $adjudications->count() }}</span>
                </button>
                <button
                    type="button"
                    class="inline-flex items-center gap-1.5 px-4 py-3 border-b-2 transition-colors"
                    :class="activeTab === 'appel_offre'
                        ? 'border-emerald-600 text-emerald-700'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    @click="activeTab = 'appel_offre'"
                >
                    <i class="fas fa-file-signature text-xs"></i>
                    Appel d'offre
                    <span
                        class="ml-1 rounded-full px-1.5 py-0.5 text-[10px] font-semibold"
                        :class="activeTab === 'appel_offre' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'"
                    >{{ $appelOffres->count() }}</span>
                </button>
            </nav>
        </div>

        {{-- ── Tab: Adjudication ──────────────────────────────────────── --}}
        <div x-show="activeTab === 'adjudication'" x-cloak>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 index-thead">
                        <tr>
                            <th>DRANEF</th>
                            <th>Année</th>
                            <th>Date d'adjudication</th>
                            <th class="text-center">Articles</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white index-tbody">
                        @forelse($adjudications as $cession)
                            <tr>
                                <td class="font-medium text-gray-900">{{ $cession->dranef->dranef ?? '—' }}</td>
                                <td>{{ $cession->annee_exercice ?? '—' }}</td>
                                <td>{{ optional($cession->date_adjudication)->format('d/m/Y') ?? '—' }}</td>
                                <td class="text-center">
                                    <x-status-badge type="info">{{ $cession->articles_count }}</x-status-badge>
                                </td>
                                <td>
                                    @php
                                        $statusMap = ['ouvert'=>['type'=>'info','label'=>'Ouvert'],'en_cours'=>['type'=>'warning','label'=>'En cours'],'cloture'=>['type'=>'success','label'=>'Clôturée']];
                                        $s = $statusMap[$cession->status ?? ''] ?? ['type'=>'pending','label'=>$cession->status ? ucfirst($cession->status) : 'Ouvert'];
                                        $canClose = $cession->articles->isNotEmpty() && $cession->articles->every(fn($a) => $a->contractVentes->first()?->recolement !== null);
                                    @endphp
                                    <x-status-badge :type="$s['type']">{{ $s['label'] }}</x-status-badge>
                                </td>
                                <td class="text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('cessions.show', $cession) }}"
                                           class="tbl-action bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 focus:ring-blue-300"
                                           title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(($cession->status ?? '') !== 'cloture')
                                            <a href="{{ route('cessions.edit', $cession) }}"
                                               class="tbl-action bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 focus:ring-amber-300"
                                               title="Modifier">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a href="{{ route('articles.create', ['cession_id' => $cession->id]) }}"
                                               class="tbl-action bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 focus:ring-emerald-300"
                                               title="Ajouter un article">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            @if($canClose)
                                            <button
                                                type="button"
                                                class="tbl-action bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 focus:ring-teal-300"
                                                title="Clôturer la cession"
                                                @click="$dispatch('delete-confirm', {
                                                    action : '{{ route('cessions.cloture', $cession) }}',
                                                    label  : 'la cession {{ $cession->dranef->dranef ?? '' }} {{ $cession->annee_exercice ?? '' }}',
                                                    method : 'PATCH',
                                                    title  : 'Clôturer la cession',
                                                    btnText: 'Clôturer',
                                                    danger : false
                                                })"
                                            >
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            @else
                                            <button
                                                type="button"
                                                class="tbl-action bg-gray-50 text-gray-300 border border-gray-200 cursor-not-allowed"
                                                title="Clôture impossible : récolements non complétés"
                                                disabled
                                            >
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4">
                                    <x-empty-state
                                        icon="fas fa-gavel"
                                        title="Aucune cession par adjudication"
                                        message="Créez votre première cession."
                                        color="green"
                                    >
                                        <x-button href="{{ route('cessions.create') }}" icon="fas fa-plus" size="sm">
                                            Nouvelle cession
                                        </x-button>
                                    </x-empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Tab: Appel d'offre ─────────────────────────────────────── --}}
        <div x-show="activeTab === 'appel_offre'" x-cloak>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50 index-thead">
                        <tr>
                            <th>DRANEF</th>
                            <th>Année</th>
                            <th>N° AO</th>
                            <th>Date d'attribution</th>
                            <th class="text-center">Articles</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white index-tbody">
                        @forelse($appelOffres as $cession)
                            <tr>
                                <td class="font-medium text-gray-900">{{ $cession->dranef->dranef ?? '—' }}</td>
                                <td>{{ $cession->annee_exercice ?? '—' }}</td>
                                <td class="font-mono text-gray-600">{{ $cession->numero_ao ?? '—' }}</td>
                                <td>{{ optional($cession->date_attribution)->format('d/m/Y') ?? '—' }}</td>
                                <td class="text-center">
                                    <x-status-badge type="info">{{ $cession->articles_count }}</x-status-badge>
                                </td>
                                <td>
                                    @php
                                        $statusMap = ['ouvert'=>['type'=>'info','label'=>'Ouvert'],'en_cours'=>['type'=>'warning','label'=>'En cours'],'cloture'=>['type'=>'success','label'=>'Clôturée']];
                                        $s = $statusMap[$cession->status ?? ''] ?? ['type'=>'pending','label'=>$cession->status ? ucfirst($cession->status) : 'Ouvert'];
                                        $canClose = $cession->articles->isNotEmpty() && $cession->articles->every(fn($a) => $a->contractVentes->first()?->recolement !== null);
                                    @endphp
                                    <x-status-badge :type="$s['type']">{{ $s['label'] }}</x-status-badge>
                                </td>
                                <td class="text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('cessions.show', $cession) }}"
                                           class="tbl-action bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 focus:ring-blue-300"
                                           title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(($cession->status ?? '') !== 'cloture')
                                            <a href="{{ route('cessions.edit', $cession) }}"
                                               class="tbl-action bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 focus:ring-amber-300"
                                               title="Modifier">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a href="{{ route('articles.create', ['cession_id' => $cession->id]) }}"
                                               class="tbl-action bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 focus:ring-emerald-300"
                                               title="Ajouter un article">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            @if($canClose)
                                            <button
                                                type="button"
                                                class="tbl-action bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 focus:ring-teal-300"
                                                title="Clôturer la cession"
                                                @click="$dispatch('delete-confirm', {
                                                    action : '{{ route('cessions.cloture', $cession) }}',
                                                    label  : 'la cession {{ $cession->dranef->dranef ?? '' }} {{ $cession->annee_exercice ?? '' }}',
                                                    method : 'PATCH',
                                                    title  : 'Clôturer la cession',
                                                    btnText: 'Clôturer',
                                                    danger : false
                                                })"
                                            >
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            @else
                                            <button
                                                type="button"
                                                class="tbl-action bg-gray-50 text-gray-300 border border-gray-200 cursor-not-allowed"
                                                title="Clôture impossible : récolements non complétés"
                                                disabled
                                            >
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4">
                                    <x-empty-state
                                        icon="fas fa-file-signature"
                                        title="Aucune cession par appel d'offre"
                                        message="Créez votre première cession par appel d'offre."
                                        color="green"
                                    >
                                        <x-button href="{{ route('cessions.create') }}" icon="fas fa-plus" size="sm">
                                            Nouvelle cession
                                        </x-button>
                                    </x-empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<x-delete-confirm />
@endsection
