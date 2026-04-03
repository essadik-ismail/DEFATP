@extends('layouts.app')

@section('title', 'Modifier l\'Essence')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Paramètres</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings.essences.index') }}">Essences</a></li>
<li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Modifier l'essence"
        subtitle="{{ $essence->essence }}"
        icon="fas fa-leaf"
    >
        <x-slot name="actions">
            <x-button
                href="{{ route('settings.essences.index') }}"
                variant="secondary"
                icon="fas fa-arrow-left"
                size="sm"
            >
                Retour à la liste
            </x-button>
        </x-slot>
    </x-page-header>

    {{-- ─── Flash messages ──────────────────────────────────────────── --}}
    <x-flash-messages />

    {{-- ─── Validation errors ──────────────────────────────────────── --}}
    @if($errors->any())
        <x-alert type="error" title="Erreurs de validation" dismissible class="mb-4">
            <ul class="list-disc list-inside space-y-0.5 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form action="{{ route('settings.essences.update', $essence) }}" method="POST">
        @csrf
        @method('PUT')

        <x-form-card title="Modifier l'essence" max-width="xl">

            <x-form-input
                name="essence"
                label="Nom de l'essence"
                placeholder="Ex : Moabi, Sapelli, Ayous…"
                required
                :value="old('essence', $essence->essence)"
            />

            <x-slot name="footer">
                {{-- Danger: delete --}}
                <x-button
                    type="button"
                    variant="danger"
                    icon="fas fa-trash-alt"
                    size="sm"
                    @click="$dispatch('delete-confirm', {
                        action : '{{ route('settings.essences.destroy', $essence) }}',
                        label  : 'l\'essence &laquo; {{ addslashes($essence->essence) }} &raquo;'
                    })"
                >
                    Supprimer
                </x-button>

                <div class="flex items-center gap-3 ml-auto">
                    <x-button href="{{ route('settings.essences.index') }}" variant="secondary">
                        Annuler
                    </x-button>
                    <x-button type="submit" icon="fas fa-save">
                        Mettre à jour
                    </x-button>
                </div>
            </x-slot>

            <x-slot name="aside">
                {{-- Metadata card --}}
                <div
                    class="rounded-2xl border bg-white p-5 space-y-3"
                    style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);"
                >
                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-info-circle text-emerald-500"></i>
                        Informations
                    </h3>
                    <dl class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">ID</dt>
                            <dd class="font-medium text-gray-800">#{{ $essence->id }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Créée le</dt>
                            <dd class="font-medium text-gray-800">{{ $essence->created_at->format('d/m/Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Modifiée le</dt>
                            <dd class="font-medium text-gray-800">{{ $essence->updated_at->format('d/m/Y') }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-gray-500">Statut</dt>
                            <dd>
                                @if($essence->deleted_at)
                                    <x-status-badge type="danger">Supprimée</x-status-badge>
                                @else
                                    <x-status-badge type="success">Active</x-status-badge>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </x-slot>

        </x-form-card>

    </form>

</div>

<x-delete-confirm />
@endsection
