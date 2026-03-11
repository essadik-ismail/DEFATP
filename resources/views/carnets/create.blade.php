@extends('layouts.app')

@section('title', 'Créer des numéros de carnet')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('carnets.index') }}">Carnets</a></li>
<li class="breadcrumb-item active">Créer</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <x-page-header
        title="Créer des numéros de carnet"
        subtitle="Saisir l'intervalle De – À pour générer une série"
        icon="fas fa-book"
    >
        <x-slot name="actions">
            <a href="{{ route('carnets.index') }}"
               class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">
                <i class="fas fa-arrow-left"></i>
                <span>Retour à la liste</span>
            </a>
        </x-slot>
    </x-page-header>

    <div class="max-w-xl">
        <div class="rounded-2xl border bg-white p-6" style="border-color: rgba(154,179,163,0.4); box-shadow: var(--shadow-card);">
            @if($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('carnets.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">De <span class="text-red-500">*</span></label>
                        <input type="number" name="de" min="0" value="{{ old('de', 1) }}"
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">À <span class="text-red-500">*</span></label>
                        <input type="number" name="a" min="0" value="{{ old('a', 100) }}"
                               class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500" required>
                    </div>
                </div>
                <p class="text-xs text-gray-500">
                    Une ligne sera créée pour chaque entier entre De et À (série affichée « De-À », ex. 1-100). Les numéros déjà existants pour cette série sont ignorés.
                </p>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('carnets.index') }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white shadow-sm" style="background: var(--primary-gradient);">
                        <i class="fas fa-save mr-1.5"></i>
                        Créer les numéros
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
