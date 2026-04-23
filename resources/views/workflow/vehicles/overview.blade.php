@extends('layouts.app')

@section('title', 'Véhicules - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item active">Véhicules</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    <x-page-header
        title="Véhicules"
        subtitle="Toutes les déclarations de véhicules enregistrées"
        icon="fas fa-truck"
    >
        <x-slot name="actions">
            @can('vehicle.declare')
                <x-button href="{{ route('vehicles.standalone.create') }}" icon="fas fa-plus">
                    Nouveau véhicule
                </x-button>
            @endcan
        </x-slot>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success" title="Succès !" dismissible class="mb-4">{{ session('success') }}</x-alert>
    @endif
    @if(session('info'))
        <x-alert type="info" title="Information" dismissible class="mb-4">{{ session('info') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error" title="Erreur" dismissible class="mb-4">{{ session('error') }}</x-alert>
    @endif

    <div
        x-data="{ search: '' }"
        class="rounded-2xl border bg-white overflow-hidden"
        style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);"
    >
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 bg-gray-50/60 px-4 py-3">
            <div class="relative w-full sm:w-80">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                <input
                    x-model="search"
                    type="search"
                    placeholder="Rechercher par immatriculation, marque, chauffeur..."
                    class="w-full rounded-xl border border-gray-200 bg-white pl-9 pr-4 py-2 text-sm text-gray-700
                           focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400
                           placeholder:text-gray-400 transition"
                >
            </div>
            <span class="text-xs text-gray-500">
                {{ $vehicles->total() }} véhicule(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Immatriculation</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Marque</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Capacité</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Exploitant</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Chauffeur</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Déclaré par</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($vehicles as $vehicle)
                        @php
                            $contract = $vehicle->contractVente;
                            $searchable = strtolower(implode(' ', array_filter([
                                $vehicle->immatriculation,
                                $vehicle->marque,
                                $contract?->exploitant?->nom_complet,
                                $vehicle->chauffeur_nom,
                                $vehicle->chauffeur_cin,
                                $vehicle->declaredBy?->name,
                            ])));
                        @endphp
                        <tr
                            data-search-row
                            class="hover:bg-emerald-50/40 transition-colors"
                            x-show="!search || @js($searchable).includes(search.toLowerCase())"
                        >
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                {{ $vehicle->immatriculation }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $vehicle->marque ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                @if($vehicle->capacite)
                                    <span class="font-medium text-gray-800">{{ number_format($vehicle->capacite, 2) }}</span>
                                    {{ $vehicle->capacite_unite }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $contract?->exploitant?->nom_complet ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                <div>{{ $vehicle->chauffeur_nom ?? '-' }}</div>
                                @if($vehicle->chauffeur_cin)
                                    <div class="text-xs text-gray-500">{{ $vehicle->chauffeur_cin }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $vehicle->date_declaration?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $vehicle->declaredBy?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @can('vehicle.declare')
                                        <a href="{{ route('vehicles.standalone.edit', $vehicle) }}"
                                           class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 transition-colors hover:bg-emerald-100 hover:text-emerald-800">
                                            <i class="fas fa-pen"></i>
                                            Modifier
                                        </a>
                                        <form action="{{ route('vehicles.standalone.destroy', $vehicle) }}" method="POST"
                                              onsubmit="return confirm('Supprimer ce véhicule ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 transition-colors hover:bg-red-100 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                                Supprimer
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4">
                                <x-empty-state
                                    icon="fas fa-truck"
                                    title="Aucun véhicule déclaré"
                                    message="Commencez par enregistrer un premier véhicule."
                                    color="green"
                                >
                                    @can('vehicle.declare')
                                        <x-button href="{{ route('vehicles.standalone.create') }}" icon="fas fa-plus" size="sm">
                                            Nouveau véhicule
                                        </x-button>
                                    @endcan
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vehicles->count())
            <div
                x-show="search && !Array.from($root.querySelectorAll('tr[data-search-row]')).some((el) => el.style.display !== 'none')"
                class="py-10 text-center text-sm text-gray-500"
            >
                <i class="fas fa-search mb-2 block text-2xl text-gray-300"></i>
                Aucun résultat pour "<span x-text="search" class="font-medium text-gray-700"></span>"
            </div>
        @endif

        @if($vehicles->hasPages())
            <div class="border-t border-gray-100 px-4 py-3">
                {{ $vehicles->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
