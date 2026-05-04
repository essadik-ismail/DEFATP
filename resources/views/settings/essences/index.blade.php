@extends('layouts.app')

@section('title', 'Gestion des Essences')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Paramètres</a></li>
<li class="breadcrumb-item active">Essences</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Essences"
        subtitle="Administrez les essences forestières du système"
        icon="fas fa-leaf"
    >
        <x-slot name="actions">
            <x-button href="{{ route('settings.essences.create') }}" icon="fas fa-plus">
                Nouvelle essence
            </x-button>
        </x-slot>
    </x-page-header>

    {{-- ─── Import / Export ─────────────────────────────────────────── --}}
    <x-import-export
        export-route="settings.essences.export"
        import-route="excel.import.essences"
        resource-name="essences"
        icon="fas fa-leaf"
    />

    {{-- ─── List card with search ───────────────────────────────────── --}}
    <div
        x-data="{ search: '', status: '' }"
        class="index-table-wrapper"
    >
        {{-- Toolbar --}}
        <div class="index-table-toolbar">
            <div class="flex flex-wrap items-center gap-2">
                {{-- Search --}}
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none"></i>
                    <input
                        x-model="search"
                        type="search"
                        placeholder="Rechercher une essence…"
                        class="w-56 rounded-xl border border-gray-200 bg-white pl-8 pr-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400
                               placeholder:text-gray-400 transition"
                    >
                </div>

                {{-- Status filter --}}
                <select
                    x-model="status"
                    class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700
                           focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400 transition"
                >
                    <option value="">Tous les statuts</option>
                    <option value="active">Active</option>
                    <option value="deleted">Supprimée</option>
                </select>
            </div>

            <span class="text-xs text-gray-500">
                {{ $essences->total() }} essence(s)
            </span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-100">
                <thead class="bg-gray-50 index-thead">
                    <tr>
                        <th>ID</th>
                        <th>Nom de l'essence</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white index-tbody">
                    @forelse($essences as $essence)
                        @php
                            $isDeleted  = (bool) $essence->deleted_at;
                            $statusType = $isDeleted ? 'deleted' : 'active';
                            $rowSearch  = strtolower($essence->essence);
                        @endphp
                        <tr
                            x-show="
                                (!search || '{{ $rowSearch }}'.includes(search.toLowerCase())) &&
                                (!status || status === '{{ $statusType }}')
                            "
                        >
                            <td class="w-16 font-mono text-gray-400">#{{ $essence->id }}</td>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-emerald-100">
                                        <i class="fas fa-leaf text-xs text-emerald-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $essence->essence }}</span>
                                </div>
                            </td>
                            <td>
                                @if($isDeleted)
                                    <x-status-badge type="danger" icon="fas fa-times-circle">Supprimée</x-status-badge>
                                @else
                                    <x-status-badge type="success" icon="fas fa-check-circle">Active</x-status-badge>
                                @endif
                            </td>
                            <td class="text-gray-500">
                                {{ $essence->created_at?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('settings.essences.edit', $essence) }}"
                                       class="tbl-action bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 focus:ring-amber-300"
                                       title="Modifier">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="tbl-action bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 focus:ring-red-300"
                                        title="Supprimer"
                                        @click="$dispatch('delete-confirm', {
                                            action : '{{ route('settings.essences.destroy', $essence) }}',
                                            label  : 'l\'essence &laquo; {{ addslashes($essence->essence) }} &raquo;'
                                        })"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4">
                                <x-empty-state
                                    icon="fas fa-leaf"
                                    title="Aucune essence trouvée"
                                    message="Commencez par ajouter une nouvelle essence forestière."
                                    color="green"
                                >
                                    <x-button href="{{ route('settings.essences.create') }}" icon="fas fa-plus" size="sm">
                                        Ajouter une essence
                                    </x-button>
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($essences->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">
                {{ $essences->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

<x-delete-confirm />
@endsection
