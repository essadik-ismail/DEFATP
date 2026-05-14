@extends('layouts.app')

@section('title', 'Modifier la Cession')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="bc-item active">Modifier #{{ $cession->id }}</li>
@endsection

@section('content')
@php
    $typeLabel     = $cession->type === 'appel_offre' ? "Appel d'offre" : 'Adjudication';
    $subtitleParts = array_filter([$cession->dranef?->dranef, trim($typeLabel . ' ' . ($cession->annee_exercice ?? ''))]);
@endphp

<div class="min-w-0 max-w-full overflow-x-hidden">

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Modifier la cession #{{ $cession->id }}"
        :subtitle="implode(' · ', $subtitleParts)"
        icon="fas fa-gavel"
    >
        <x-slot name="actions">
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
        <input type="hidden" name="type" value="{{ $cession->type ?? 'adjudication' }}">

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
                        label="Année"
                        required
                        min="2000"
                        :max="now()->year + 1"
                        :value="old('annee_exercice', $cession->annee_exercice)"
                    />
                </div>

                {{-- Type toggle (readonly — cannot change type after creation) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Type de cession
                    </label>
                    <div class="inline-flex rounded-xl border border-gray-200 bg-gray-100 p-1 text-sm pointer-events-none">
                        @if($cession->type === 'adjudication')
                            <span class="rounded-lg px-4 py-1.5 bg-white text-emerald-700 shadow-sm font-semibold">
                                <i class="fas fa-gavel mr-1.5 text-xs"></i>Adjudication
                            </span>
                        @else
                            <span class="rounded-lg px-4 py-1.5 bg-white text-emerald-700 shadow-sm font-semibold">
                                <i class="fas fa-file-signature mr-1.5 text-xs"></i>Appel d'offre
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Adjudication fields --}}
                @if($cession->type === 'adjudication')
                    <x-form-input
                        name="date_adjudication"
                        type="date"
                        label="Date d'adjudication"
                        required
                        :value="old('date_adjudication', optional($cession->date_adjudication)->format('Y-m-d'))"
                    />
                @endif

                {{-- Appel d'offre fields --}}
                @if($cession->type === 'appel_offre')
                    <div class="space-y-4">
                        <x-form-input
                            name="numero_ao"
                            label="Numéro Appel d'offre"
                            placeholder="Ex : AO-2026-001"
                            required
                            :value="old('numero_ao', $cession->numero_ao)"
                        />
                        <x-form-input
                            name="date_attribution"
                            type="date"
                            label="Date d'attribution"
                            required
                            :value="old('date_attribution', optional($cession->date_attribution)->format('Y-m-d'))"
                        />
                    </div>
                @endif

            </div>

            <x-slot name="footer">
                <x-button href="{{ route('cessions.index', ['tab' => $cession->type ?? 'adjudication']) }}" variant="secondary">
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
