@extends('layouts.app')

@section('title', 'Modifier le carnet')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('carnets.index') }}">Carnets</a></li>
<li class="breadcrumb-item active">Modifier #{{ $carnet->id }}</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Modifier le carnet #{{ $carnet->id }}"
        subtitle="Série {{ $carnet->serie }} · Numéro {{ $carnet->num }}"
        icon="fas fa-book"
    >
        <x-slot name="actions">
            <x-button
                href="{{ route('carnets.show-serie', ['serie' => $carnet->serie, 'createdDate' => $carnet->created_at?->toDateString()]) }}"
                variant="secondary"
                icon="fas fa-arrow-left"
                size="sm"
            >
                Retour à la série
            </x-button>
        </x-slot>
    </x-page-header>

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

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- ─── Form card ──────────────────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div
                class="rounded-2xl border bg-white p-6"
                style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);"
            >
                <h2 class="mb-5 text-base font-semibold text-gray-800">Informations du carnet</h2>

                <form action="{{ route('carnets.update', $carnet) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Série (read-only) --}}
                    <x-form-input
                        name="serie_display"
                        label="Série"
                        :value="$carnet->serie"
                        readonly
                        helper="La série ne peut pas être modifiée."
                    />

                    {{-- Type --}}
                    <x-form-input
                        name="type"
                        type="select"
                        label="Type"
                        required
                        helper="Modifier le type ici met à jour tous les carnets de la série {{ $carnet->serie }}."
                    >
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ old('type', $carnet->type) === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </x-form-input>

                    {{-- Num --}}
                    <x-form-input
                        name="num"
                        type="number"
                        label="Numéro"
                        required
                        min="0"
                        :value="old('num', $carnet->num)"
                    />

                    {{-- Statut --}}
                    <x-form-input
                        name="status"
                        type="select"
                        label="Statut"
                        required
                    >
                        @foreach(['disponible' => 'Disponible', 'epuise' => 'Épuisé', 'perdu' => 'Perdu', 'utilise' => 'Utilisé'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $carnet->status) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </x-form-input>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between gap-3 border-t border-gray-100 pt-4">
                        {{-- Danger: delete --}}
                        <x-button
                            type="button"
                            variant="danger"
                            icon="fas fa-trash-alt"
                            size="sm"
                            @click="$dispatch('delete-confirm', {
                                action : '{{ route('carnets.destroy', $carnet) }}',
                                label  : 'carnet #{{ $carnet->num }} (série {{ $carnet->serie }})'
                            })"
                        >
                            Supprimer
                        </x-button>

                        <div class="flex items-center gap-3">
                            <x-button
                                href="{{ route('carnets.show-serie', ['serie' => $carnet->serie, 'createdDate' => $carnet->created_at?->toDateString()]) }}"
                                variant="secondary"
                            >
                                Annuler
                            </x-button>
                            <x-button type="submit" icon="fas fa-save">
                                Mettre à jour
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── Info sidebar ────────────────────────────────────────── --}}
        <div>
            <div
                class="rounded-2xl border bg-white p-5 space-y-3"
                style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);"
            >
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-info-circle text-emerald-500"></i>
                    Détails actuels
                </h3>
                <dl class="space-y-2.5 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">ID</dt>
                        <dd class="font-medium text-gray-800">#{{ $carnet->id }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Série</dt>
                        <dd class="font-medium text-gray-800">{{ $carnet->serie }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Numéro</dt>
                        <dd class="font-medium text-gray-800">{{ $carnet->num }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-gray-500">Statut actuel</dt>
                        <dd>
                            @php
                                $statusMap = ['disponible'=>'success','epuise'=>'pending','perdu'=>'danger','utilise'=>'warning'];
                            @endphp
                            <x-status-badge :type="$statusMap[$carnet->status] ?? 'default'">
                                {{ ucfirst($carnet->status) }}
                            </x-status-badge>
                        </dd>
                    </div>
                    @if($carnet->created_at)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Créé le</dt>
                        <dd class="font-medium text-gray-800">{{ $carnet->created_at->format('d/m/Y') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

    </div>
</div>

<x-delete-confirm />
@endsection
