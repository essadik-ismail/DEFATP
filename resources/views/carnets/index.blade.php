@extends('layouts.app')

@section('title', 'Carnets')

@section('breadcrumb')
<li class="breadcrumb-item active">Carnets</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Carnets"
        subtitle="Séries de carnets par série et date de création"
        icon="fas fa-book"
    >
        <x-slot name="actions">
            <x-button href="{{ route('carnets.create') }}" icon="fas fa-plus">
                Créer des numéros
            </x-button>
        </x-slot>
    </x-page-header>

    {{-- ─── Flash messages ──────────────────────────────────────────── --}}
    @if(session('success'))
        <x-alert type="success" title="Succès !" dismissible class="mb-4">{{ session('success') }}</x-alert>
    @endif
    @if(session('info'))
        <x-alert type="info" title="Information" dismissible class="mb-4">{{ session('info') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error" title="Erreur" dismissible class="mb-4">{{ session('error') }}</x-alert>
    @endif

    {{-- ─── Table with client-side search ──────────────────────────── --}}
    <div
        x-data="{ search: '' }"
        class="rounded-2xl border bg-white overflow-hidden"
        style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);"
    >
        {{-- Toolbar: search + count --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 bg-gray-50/60 px-4 py-3">
            <div class="relative w-full sm:w-72">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                <input
                    x-model="search"
                    type="search"
                    placeholder="Rechercher par série, type…"
                    class="w-full rounded-xl border border-gray-200 bg-white pl-9 pr-4 py-2 text-sm text-gray-700
                           focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400
                           placeholder:text-gray-400 transition"
                >
            </div>
            <span class="text-xs text-gray-500">
                {{ $series->total() }} série(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Série</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date création</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plage</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Disponible</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Épuisé</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilisé</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Perdu</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($series as $serieGroup)
                        @php
                            $searchable = strtolower($serieGroup->serie . ' ' . $serieGroup->type);
                        @endphp
                        <tr
                            class="hover:bg-emerald-50/40 transition-colors"
                            x-show="!search || '{{ $searchable }}'.includes(search.toLowerCase())"
                        >
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                {{ $serieGroup->serie }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ \Illuminate\Support\Carbon::parse($serieGroup->created_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $serieGroup->type }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 font-mono">
                                {{ $serieGroup->first_num }} – {{ $serieGroup->last_num }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-sm font-semibold text-gray-800">{{ $serieGroup->total_carnets }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <x-status-badge type="success">{{ $serieGroup->disponible_count }}</x-status-badge>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <x-status-badge type="pending">{{ $serieGroup->epuise_count }}</x-status-badge>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <x-status-badge type="warning">{{ $serieGroup->utilise_count }}</x-status-badge>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <x-status-badge type="danger">{{ $serieGroup->perdu_count }}</x-status-badge>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('carnets.show-serie', ['serie' => $serieGroup->serie, 'createdDate' => $serieGroup->created_date]) }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 transition-colors hover:bg-emerald-100 hover:text-emerald-800">
                                    <i class="fas fa-eye"></i>
                                    Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-4">
                                <x-empty-state
                                    icon="fas fa-book-open"
                                    title="Aucune série de carnet"
                                    message="Commencez par créer une première série de numéros."
                                    color="green"
                                >
                                    <x-button href="{{ route('carnets.create') }}" icon="fas fa-plus" size="sm">
                                        Créer des numéros
                                    </x-button>
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- "No results" row shown when Alpine search finds nothing (data exists but filtered out) --}}
        @if($series->count())
        <div
            x-show="search && !document.querySelector('tbody tr[x-show]:not([style*=\'display: none\'])')"
            class="py-10 text-center text-sm text-gray-500"
        >
            <i class="fas fa-search mb-2 block text-2xl text-gray-300"></i>
            Aucun résultat pour "<span x-text="search" class="font-medium text-gray-700"></span>"
        </div>
        @endif

        @if($series->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">
                {{ $series->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
