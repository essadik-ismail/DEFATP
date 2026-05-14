@extends('layouts.app')

@section('title', 'Série de carnets')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('carnets.index') }}">Carnets</a></li>
<li class="bc-item active">
    Série {{ $seriesSummary->serie }} – {{ \Illuminate\Support\Carbon::parse($seriesSummary->created_date)->format('d/m/Y') }}
</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Série {{ $seriesSummary->serie }}"
        subtitle="{{ $seriesSummary->type }} · Créée le {{ \Illuminate\Support\Carbon::parse($seriesSummary->created_date)->format('d/m/Y') }} · {{ $seriesSummary->total_carnets }} carnet(s)"
        icon="fas fa-book-open"
    >
        <x-slot name="actions">
            <x-button href="{{ route('carnets.create') }}" icon="fas fa-plus" size="sm">
                Nouvelle série
            </x-button>
            <x-button href="{{ route('carnets.index') }}" variant="secondary" icon="fas fa-arrow-left" size="sm">
                Retour à la liste
            </x-button>
        </x-slot>
    </x-page-header>

    {{-- ─── Stats mini-cards ────────────────────────────────────────── --}}
    <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
        @foreach([
            ['label' => 'Disponibles', 'count' => $seriesSummary->disponible_count, 'color' => 'emerald', 'icon' => 'fas fa-check-circle'],
            ['label' => 'Épuisés',     'count' => $seriesSummary->epuise_count,     'color' => 'gray',    'icon' => 'fas fa-ban'],
            ['label' => 'Utilisés',    'count' => $seriesSummary->utilise_count,    'color' => 'amber',   'icon' => 'fas fa-file-signature'],
            ['label' => 'Perdus',      'count' => $seriesSummary->perdu_count,      'color' => 'red',     'icon' => 'fas fa-times-circle'],
        ] as $stat)
        <div
            class="rounded-2xl border bg-white p-4 flex items-center gap-3"
            style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);"
        >
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-{{ $stat['color'] }}-100">
                <i class="{{ $stat['icon'] }} text-{{ $stat['color'] }}-600 text-base"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
                <p class="text-xl font-bold text-{{ $stat['color'] }}-700">{{ $stat['count'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ─── Carnets table with search ───────────────────────────────── --}}
    <div
        x-data="{ search: '' }"
        class="rounded-2xl border bg-white overflow-hidden"
        style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);"
    >
        {{-- Table header / toolbar --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 bg-gray-50/60 px-4 py-3">
            <div>
                <h2 class="text-sm font-semibold text-gray-800">Carnets de la série</h2>
                <p class="mt-0.5 text-xs text-gray-500">
                    Type : <span class="font-medium text-gray-700">{{ $seriesSummary->type }}</span>
                    <span class="mx-1.5 text-gray-300">·</span>
                    Plage : <span class="font-medium text-gray-700">{{ $seriesSummary->first_num }} – {{ $seriesSummary->last_num }}</span>
                </p>
            </div>
            <div class="relative w-full sm:w-60">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                <input
                    x-model="search"
                    type="search"
                    placeholder="Filtrer par numéro, statut…"
                    class="w-full rounded-xl border border-gray-200 bg-white pl-8 pr-4 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400
                           placeholder:text-gray-400 transition"
                >
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Num</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($carnets as $carnet)
                        @php
                            $statusMap   = ['disponible'=>'success','epuise'=>'pending','perdu'=>'danger','utilise'=>'warning'];
                            $searchable  = strtolower($carnet->num . ' ' . $carnet->type . ' ' . $carnet->status);
                        @endphp
                        <tr
                            class="hover:bg-emerald-50/40 transition-colors"
                            x-show="!search || '{{ $searchable }}'.includes(search.toLowerCase())"
                        >
                            <td class="px-4 py-3 text-sm text-gray-500">#{{ $carnet->id }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900 font-mono">{{ $carnet->num }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $carnet->type }}</td>
                            <td class="px-4 py-3">
                                <x-status-badge :type="$statusMap[$carnet->status] ?? 'default'">
                                    {{ ucfirst($carnet->status) }}
                                </x-status-badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1.5">

                                    {{-- Edit --}}
                                    <a href="{{ route('carnets.edit', $carnet) }}"
                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-100"
                                       title="Modifier">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>

                                    {{-- Mark lost (only when applicable) --}}
                                    @if($carnet->canBeMarkedPerdu())
                                        <button
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 transition hover:bg-amber-100"
                                            title="Marquer comme perdu"
                                            @click="$dispatch('delete-confirm', {
                                                action : '{{ route('carnets.mark-perdu', $carnet) }}',
                                                label  : 'carnet #{{ $carnet->num }}',
                                                method : 'PATCH',
                                                title  : 'Marquer comme perdu',
                                                btnText: 'Confirmer',
                                                danger : false
                                            })"
                                        >
                                            <i class="fas fa-times-circle text-xs"></i>
                                        </button>
                                    @endif

                                    {{-- Delete --}}
                                    <button
                                        type="button"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100"
                                        title="Supprimer"
                                        @click="$dispatch('delete-confirm', {
                                            action : '{{ route('carnets.destroy', $carnet) }}',
                                            label  : 'carnet #{{ $carnet->num }}'
                                        })"
                                    >
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4">
                                <x-empty-state
                                    icon="fas fa-book-open"
                                    title="Aucun carnet dans cette série"
                                    color="gray"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($carnets->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">
                {{ $carnets->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Global delete / action confirmation modal --}}
<x-delete-confirm />

@endsection
