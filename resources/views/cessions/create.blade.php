@extends('layouts.app')

@section('title', 'Nouvelle Cession')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="bc-item active">Nouvelle cession</li>
@endsection

@section('content')
<div
    class="min-w-0 max-w-full overflow-x-hidden"
    x-data="{ type: '{{ old('type', 'adjudication') }}' }"
>

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Nouvelle cession"
        icon="fas fa-gavel"
    >
        <x-slot name="actions">
            <x-button href="{{ route('cessions.index') }}" variant="secondary" icon="fas fa-arrow-left" size="sm">
                Retour à la liste
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

    <form action="{{ route('cessions.store') }}" method="POST">
        @csrf
        <input type="hidden" name="type" :value="type">

        <x-form-card title="Paramètres de la cession" max-width="3xl">

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
                            <option value="{{ $dranef->id }}" @selected(old('dranef_id') == $dranef->id)>
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
                        :value="old('annee_exercice', now()->year)"
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
                        :value="old('date_adjudication')"
                        x-bind:required="type === 'adjudication'"
                        x-bind:disabled="type !== 'adjudication'"
                    />
                </div>

                {{-- Appel d'offre fields --}}
                <div x-show="type === 'appel_offre'" x-transition class="space-y-4">
                    <x-form-input
                        name="numero_ao"
                        label="Numéro AO"
                        placeholder="Ex : AO-2026-001"
                        :value="old('numero_ao')"
                        x-bind:required="type === 'appel_offre'"
                        x-bind:disabled="type !== 'appel_offre'"
                    />
                    <x-form-input
                        name="date_attribution"
                        type="date"
                        label="Date d'attribution"
                        :value="old('date_attribution')"
                        x-bind:required="type === 'appel_offre'"
                        x-bind:disabled="type !== 'appel_offre'"
                    />
                </div>

            </div>

            <x-slot name="footer">
                <x-button href="{{ route('cessions.index') }}" variant="secondary">
                    Annuler
                </x-button>
                <x-button type="submit" icon="fas fa-save">
                    Enregistrer
                </x-button>
            </x-slot>

        </x-form-card>

    </form>

</div>
@endsection
