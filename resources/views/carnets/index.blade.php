@extends('layouts.app')

@section('title', 'Carnets')

@section('breadcrumb')
<li class="breadcrumb-item active">Carnets</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Carnets"
        subtitle="Numéros pour les permis de colportage"
        icon="fas fa-book"
    >
        <x-slot name="actions">
            <a href="{{ route('carnets.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm"
               style="background: var(--primary-gradient); box-shadow: var(--shadow-md);">
                <i class="fas fa-plus"></i>
                <span>Créer des numéros</span>
            </a>
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

    <div class="rounded-2xl border bg-white overflow-hidden" style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Série</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Num</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Statut</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($carnets as $carnet)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $carnet->id }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $carnet->serie }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $carnet->num }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusClasses = [
                                    'disponible' => 'bg-green-100 text-green-800',
                                    'epuise' => 'bg-gray-100 text-gray-800',
                                    'perdu' => 'bg-red-100 text-red-800',
                                    'utilise' => 'bg-amber-100 text-amber-800',
                                ];
                                $class = $statusClasses[$carnet->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $class }}">
                                {{ $carnet->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="inline-flex items-center gap-2">
                                @if($carnet->canBeMarkedPerdu())
                                    <form action="{{ route('carnets.mark-perdu', $carnet) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Marquer ce carnet comme perdu ?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-7 h-7 bg-red-100 hover:bg-red-200 text-red-700 rounded-full transition-colors"
                                                title="Marquer perdu">
                                            <i class="fas fa-times-circle text-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                            Aucun carnet. <a href="{{ route('carnets.create') }}" class="text-green-600 hover:underline">Créer des numéros</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($carnets->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $carnets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
