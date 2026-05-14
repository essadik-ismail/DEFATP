@extends('layouts.app')

@section('title', 'Créer des numéros de carnet')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('carnets.index') }}">Carnets</a></li>
<li class="bc-item active">Créer</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Créer des numéros de carnet"
        subtitle="Saisir l'intervalle De – À pour générer une série"
        icon="fas fa-book"
    >
        <x-slot name="actions">
            <x-button href="{{ route('carnets.index') }}" variant="secondary" icon="fas fa-arrow-left" size="sm">
                Retour à la liste
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
                <h2 class="mb-5 text-base font-semibold text-gray-800">Paramètres de la série</h2>

                <form action="{{ route('carnets.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Type --}}
                    <x-form-input
                        name="type"
                        type="select"
                        label="Type"
                        required
                        helper="Le type sera appliqué à tous les numéros de la série créée."
                    >
                        <option value="">Choisir un type…</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </x-form-input>

                    {{-- Range: De / À --}}
                    <div class="grid grid-cols-2 gap-4">
                        <x-form-input
                            name="de"
                            type="number"
                            label="De"
                            required
                            min="0"
                            :value="old('de', 1)"
                        />
                        <x-form-input
                            name="a"
                            type="number"
                            label="À"
                            required
                            min="0"
                            :value="old('a', 100)"
                        />
                    </div>

                    <p class="text-xs text-gray-500 leading-relaxed">
                        Une ligne sera créée pour chaque entier entre <strong>De</strong> et <strong>À</strong>
                        (ex. 1 – 100 génère 100 numéros). Les numéros déjà existants pour cette série sont ignorés.
                    </p>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                        <x-button href="{{ route('carnets.index') }}" variant="secondary">
                            Annuler
                        </x-button>
                        <x-button type="submit" icon="fas fa-save">
                            Créer les numéros
                        </x-button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── Help sidebar ────────────────────────────────────────── --}}
        <div class="space-y-4">
            <div
                class="rounded-2xl border bg-emerald-50 p-5"
                style="border-color: rgba(5,150,105,0.2);"
            >
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-lightbulb text-emerald-600"></i>
                    <h3 class="text-sm font-semibold text-emerald-800">Comment ça marche ?</h3>
                </div>
                <ul class="space-y-2 text-xs text-emerald-700 leading-relaxed">
                    <li class="flex gap-2">
                        <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
                        Choisissez le <strong>type</strong> de carnet (appliqué à toute la série).
                    </li>
                    <li class="flex gap-2">
                        <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
                        Indiquez l'intervalle <strong>De – À</strong> pour définir la plage de numéros.
                    </li>
                    <li class="flex gap-2">
                        <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
                        Les numéros existants dans la plage sont automatiquement <strong>ignorés</strong>.
                    </li>
                    <li class="flex gap-2">
                        <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
                        Chaque numéro démarre avec le statut <strong>Disponible</strong>.
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
