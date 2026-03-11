@extends('layouts.app')

@section('title', 'Modifier la Cession')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('cessions.index') }}">Cessions</a></li>
<li class="breadcrumb-item active">Modifier Cession #{{ $cession->id }}</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden" x-data="{ type: '{{ old('type', $cession->type) }}' }">
    <x-page-header
        title="Modifier la Cession #{{ $cession->id }}"
        :subtitle="($cession->dranef->dranef ?? '') . ' — ' . ($cession->type === 'appel_offre' ? \"Appel d'offre\" : 'Adjudication') . ' ' . ($cession->annee_exercice ?? '')"
        icon="fas fa-gavel"
    >
        <x-slot name="actions">
            <a href="{{ route('cessions.index') }}"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">
                <i class="fas fa-arrow-left"></i>
                <span>Retour à la liste</span>
            </a>
            <a href="{{ route('cessions.show', $cession) }}"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-white"
               style="background: var(--primary-gradient); box-shadow: var(--shadow-sm);">
                <i class="fas fa-eye"></i>
                <span>Voir la cession</span>
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-3xl">
        <div class="rounded-2xl border bg-white p-6"
             style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);">
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold mb-1">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cessions.update', $cession) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- DRANEF -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            DRANEF <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="dranef_id"
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                            required
                        >
                            <option value="">Sélectionner une DRANEF</option>
                            @foreach($dranefs as $dranef)
                                <option value="{{ $dranef->id }}" @selected(old('dranef_id', $cession->dranef_id) == $dranef->id)>
                                    {{ $dranef->dranef }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Année / Exercice -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Année / Exercice <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="annee_exercice"
                            min="2000"
                            max="{{ now()->year + 1 }}"
                            value="{{ old('annee_exercice', $cession->annee_exercice) }}"
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                            required
                        />
                    </div>

                    <!-- Type -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Type de cession <span class="text-red-500">*</span>
                        </label>
                        <div class="inline-flex rounded-full border border-gray-200 bg-gray-50 p-0.5 text-xs">
                            <button
                                type="button"
                                @click="type = 'adjudication'"
                                :class="type === 'adjudication' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600'"
                                class="px-3 py-1 rounded-full transition"
                            >
                                Adjudication
                            </button>
                            <button
                                type="button"
                                @click="type = 'appel_offre'"
                                :class="type === 'appel_offre' ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-600'"
                                class="px-3 py-1 rounded-full transition"
                            >
                                Appel d'offre
                            </button>
                        </div>
                        <input type="hidden" name="type" :value="type">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                    <!-- Adjudication fields -->
                    <div x-show="type === 'adjudication'" x-transition>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Date d'adjudication <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="date_adjudication"
                            value="{{ old('date_adjudication', optional($cession->date_adjudication)->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                        />
                    </div>

                    <!-- Appel d'offre fields -->
                    <div class="space-y-3" x-show="type === 'appel_offre'" x-transition>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Numéro AO <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="numero_ao"
                                value="{{ old('numero_ao', $cession->numero_ao) }}"
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Ex: AO-2026-001"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Date d'attribution <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="date"
                                name="date_attribution"
                                value="{{ old('date_attribution', optional($cession->date_attribution)->format('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('cessions.show', $cession) }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">
                        Annuler
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm"
                        style="background: var(--primary-gradient);"
                    >
                        <i class="fas fa-save mr-1.5"></i>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

