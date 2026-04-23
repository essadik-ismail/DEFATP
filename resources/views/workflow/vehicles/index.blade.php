@extends('layouts.app')

@section('title', 'Véhicules déclarés - DEFATP')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('articles.show', $article) }}">Article #{{ $article->numero ?? $article->id }}</a></li>
<li class="breadcrumb-item active">Véhicules</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-5xl">

        <x-page-header
            title="Véhicules déclarés"
            :subtitle="'Article #' . ($article->numero ?? $article->id)"
            icon="fas fa-truck"
            :backRoute="route('articles.show', $article)"
            backText="Retour"
        />

        @if(session('success'))
            <x-alert type="success" title="Succès!" dismissible>{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error" title="Erreur!" dismissible>{{ session('error') }}</x-alert>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-list text-gray-500"></i>
                    Liste des véhicules
                </h2>
                @can('vehicle.declare')
                <a href="{{ route('vehicles.create', $article) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors text-sm">
                    <i class="fas fa-plus"></i> Déclarer un véhicule
                </a>
                @endcan
            </div>

            <div class="p-6">
                @if($vehicles->isEmpty())
                    <x-empty-state
                        icon="fas fa-truck"
                        title="Aucun véhicule déclaré"
                        message="Aucun véhicule n'a encore été déclaré pour ce contrat."
                        color="green"
                    />
                @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-green-50 border-b-2 border-green-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Immatriculation</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Marque</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Capacité</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Chauffeur</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-green-800 uppercase">Déclaré par</th>
                                @can('vehicle.declare')
                                <th class="px-4 py-3 text-center text-xs font-semibold text-green-800 uppercase">Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-green-100">
                            @foreach($vehicles as $i => $vehicle)
                            <tr class="hover:bg-green-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-semibold text-green-900">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $vehicle->immatriculation }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $vehicle->marque ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($vehicle->capacite)
                                        {{ number_format($vehicle->capacite, 2) }} {{ $vehicle->capacite_unite }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $vehicle->chauffeur_nom ?? '—' }}
                                    @if($vehicle->chauffeur_cin)
                                        <span class="text-xs text-gray-500">({{ $vehicle->chauffeur_cin }})</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-blue-700">
                                    {{ $vehicle->date_declaration ? $vehicle->date_declaration->format('d/m/Y') : '—' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $vehicle->declaredBy?->name ?? '—' }}</td>
                                @can('vehicle.declare')
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('vehicles.destroy', [$article, $vehicle]) }}" method="POST"
                                          onsubmit="return confirm('Supprimer ce véhicule ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition-colors">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
