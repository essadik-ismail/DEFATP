@extends('layouts.app')

@section('title', 'Modifier ODF - Étape 5 - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-file-contract text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Modifier ODF - Étape 5/5
                </h1>
                <p class="text-gray-600 mt-1">
                    Constitution pour l'ODF #{{ $odf->id }}
                </p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            <div class="font-semibold mb-2">Erreurs de validation :</div>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #f59e0b, #d97706);">
                    <i class="fas fa-file-contract text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Constitution</h2>
                    <p class="text-sm text-gray-600">Modifiez les informations de constitution (facultatif mais recommandé).</p>
                </div>
            </div>
        </div>

        <form action="{{ route('odfs.update.step5', $odf) }}" method="POST" id="constitutionForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
                    <input type="date" name="constitution[date]" class="form-input w-full" value="{{ old('constitution.date', $constitution->date ?? '') }}">
                    @error('constitution.date')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lieu</label>
                    <input type="text" name="constitution[lieu]" class="form-input w-full" placeholder="Lieu de constitution" value="{{ old('constitution.lieu', $constitution->lieu ?? '') }}">
                    @error('constitution.lieu')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Participant</label>
                    <textarea name="constitution[participant]" rows="3" class="form-input w-full" placeholder="Liste des participants...">{{ old('constitution.participant', $constitution->participant ?? '') }}</textarea>
                    @error('constitution.participant')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Date de Dépôt ODF</label>
                    <input type="date" name="constitution[date_depot_odf]" class="form-input w-full" value="{{ old('constitution.date_depot_odf', $constitution->date_depot_odf ?? '') }}">
                    @error('constitution.date_depot_odf')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lieu de Dépôt ODF</label>
                    <input type="text" name="constitution[lieu_depot_odf]" class="form-input w-full" placeholder="Lieu de dépôt" value="{{ old('constitution.lieu_depot_odf', $constitution->lieu_depot_odf ?? '') }}">
                    @error('constitution.lieu_depot_odf')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fichier Joint Dépôt ODF</label>
                    <input type="text" name="constitution[fichier_joint_depot_odf]" class="form-input w-full" placeholder="Nom du fichier" value="{{ old('constitution.fichier_joint_depot_odf', $constitution->fichier_joint_depot_odf ?? '') }}">
                    @error('constitution.fichier_joint_depot_odf')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Date de Réception Définitive</label>
                    <input type="date" name="constitution[date_reçu_définitive]" class="form-input w-full" value="{{ old('constitution.date_reçu_définitive', $constitution->date_reçu_définitive ?? '') }}">
                    @error('constitution.date_reçu_définitive')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lieu de Réception Définitive</label>
                    <input type="text" name="constitution[lieu_reçu_définitive]" class="form-input w-full" placeholder="Lieu de réception" value="{{ old('constitution.lieu_reçu_définitive', $constitution->lieu_reçu_définitive ?? '') }}">
                    @error('constitution.lieu_reçu_définitive')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fichier Joint Réception Définitive</label>
                    <input type="text" name="constitution[fichier_joint_reçu_définitive]" class="form-input w-full" placeholder="Nom du fichier" value="{{ old('constitution.fichier_joint_reçu_définitive', $constitution->fichier_joint_reçu_définitive ?? '') }}">
                    @error('constitution.fichier_joint_reçu_définitive')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('odfs.edit.step4', $odf) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour étape 4</span>
                </a>

                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <span>Enregistrer et terminer</span>
                    <i class="fas fa-check"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

