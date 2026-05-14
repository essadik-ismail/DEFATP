@extends('layouts.app')

@section('title', 'Nouveau véhicule - DEFATP')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('vehicles.overview') }}">Véhicules</a></li>
<li class="bc-item active">Nouveau véhicule</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">
    <div class="container mx-auto px-4 max-w-3xl">

        <x-page-header
            title="Déclarer un véhicule"
            subtitle="Véhicule indépendant"
            icon="fas fa-truck"
            :backRoute="route('vehicles.overview')"
            backText="Retour"
        />

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('vehicles.standalone.store') }}" method="POST" class="space-y-6">
                @csrf

                <x-validation-errors />

                <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
                    Ce véhicule sera enregistré comme véhicule indépendant, sans contrat ni article.
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label for="immatriculation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Immatriculation <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="immatriculation" id="immatriculation"
                               value="{{ old('immatriculation') }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               required maxlength="80">
                    </div>

                    <div class="form-group">
                        <label for="marque" class="block text-sm font-semibold text-gray-700 mb-2">Marque</label>
                        <input type="text" name="marque" id="marque"
                               value="{{ old('marque') }}"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="capacite" class="block text-sm font-semibold text-gray-700 mb-2">Capacité</label>
                        <input type="number" name="capacite" id="capacite"
                               value="{{ old('capacite') }}" step="0.01" min="0"
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="form-group">
                        <label for="capacite_unite" class="block text-sm font-semibold text-gray-700 mb-2">
                            Unité <span class="text-red-500">*</span>
                        </label>
                        <select name="capacite_unite" id="capacite_unite"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                required>
                            @foreach(['m3' => 'm³', 'stere' => 'Stère', 'sacs' => 'Sacs', 'tonnes' => 'Tonnes', 'autre' => 'Autre'] as $val => $label)
                                <option value="{{ $val }}" {{ old('capacite_unite', 'm3') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('vehicles.overview') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
