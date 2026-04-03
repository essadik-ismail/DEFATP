@extends('layouts.app')

@section('title', 'Modifier la Cession')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="breadcrumb-item active">Modifier #{{ $cession->id }}</li>
@endsection

@section('content')
@php
    $selectedType  = old('type', $cession->type ?? 'adjudication');
    $typeLabel     = $selectedType === 'appel_offre' ? "Appel d'offre" : 'Adjudication';
    $subtitleParts = array_filter([$cession->dranef?->dranef, trim($typeLabel . ' ' . ($cession->annee_exercice ?? ''))]);
@endphp

<div
    class="min-w-0 max-w-full overflow-x-hidden"
    x-data='{ type: @js($selectedType) }'
>

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Modifier la cession #{{ $cession->id }}"
        :subtitle="implode(' · ', $subtitleParts)"
        icon="fas fa-gavel"
    >
        <x-slot name="actions">
            <x-button href="{{ route('cessions.show', $cession) }}" variant="secondary" icon="fas fa-eye" size="sm">
                Voir
            </x-button>
            <x-button href="{{ route('cessions.index') }}" variant="secondary" icon="fas fa-arrow-left" size="sm">
                Retour
            </x-button>
        </x-slot>
    </x-page-header>

    {{-- ─── Validation errors ──────────────────────────────────────── --}}
    @if($errors->any())
        <x-alert type="error" title="Veuillez corriger les erreurs suivantes" dismissible class="mb-4">
            <ul class="list-disc list-inside space-y-0.5 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form action="{{ route('cessions.update', $cession) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" :value="type">

        <x-form-card title="Modifier la cession" max-width="3xl">

            <div class="space-y-5">

                {{-- Row 1: DRANEF + Année --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-form-input
                        name="dranef_id"
                        type="select"
                        label="DRANEF"
                        required
                    >
                        <option value="">Sélectionner une DRANEF…</option>
                        @foreach($dranefs as $dranef)
                            <option value="{{ $dranef->id }}" @selected(old('dranef_id', $cession->dranef_id) == $dranef->id)>
                                {{ $dranef->dranef }}
                            </option>
                        @endforeach
                    </x-form-input>

                    <x-form-input
                        name="annee_exercice"
                        type="number"
                        label="Année / Exercice"
                        required
                        min="2000"
                        :max="now()->year + 1"
                        :value="old('annee_exercice', $cession->annee_exercice)"
                    />
                </div>

                {{-- Type toggle --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Type de cession <span class="text-red-500">*</span>
                    </label>
                    <div class="inline-flex rounded-xl border border-gray-200 bg-gray-100 p-1 text-sm gap-1">
                        <button
                            type="button"
                            @click="type = 'adjudication'"
                            :class="type === 'adjudication'
                                ? 'bg-white text-emerald-700 shadow-sm font-semibold'
                                : 'text-gray-500 hover:text-gray-700'"
                            class="rounded-lg px-4 py-1.5 transition-all"
                        >
                            <i class="fas fa-gavel mr-1.5 text-xs"></i>Adjudication
                        </button>
                        <button
                            type="button"
                            @click="type = 'appel_offre'"
                            :class="type === 'appel_offre'
                                ? 'bg-white text-emerald-700 shadow-sm font-semibold'
                                : 'text-gray-500 hover:text-gray-700'"
                            class="rounded-lg px-4 py-1.5 transition-all"
                        >
                            <i class="fas fa-file-signature mr-1.5 text-xs"></i>Appel d'offre
                        </button>
                    </div>
                </div>

                {{-- Adjudication fields --}}
                <div x-show="type === 'adjudication'" x-transition>
                    <x-form-input
                        name="date_adjudication"
                        type="date"
                        label="Date d'adjudication"
                        :value="old('date_adjudication', optional($cession->date_adjudication)->format('Y-m-d'))"
                    />
                </div>

                {{-- Appel d'offre fields --}}
                <div x-show="type === 'appel_offre'" x-transition class="space-y-4">
                    <x-form-input
                        name="numero_ao"
                        label="Numéro AO"
                        placeholder="Ex : AO-2026-001"
                        :value="old('numero_ao', $cession->numero_ao)"
                    />
                    <x-form-input
                        name="date_attribution"
                        type="date"
                        label="Date d'attribution"
                        :value="old('date_attribution', optional($cession->date_attribution)->format('Y-m-d'))"
                    />
                </div>

            </div>

            <x-slot name="footer">
                <x-button href="{{ route('cessions.show', $cession) }}" variant="secondary">
                    Annuler
                </x-button>
                <x-button type="submit" icon="fas fa-save">
                    Mettre à jour
                </x-button>
            </x-slot>

        </x-form-card>

    </form>

</div>
@endsection
