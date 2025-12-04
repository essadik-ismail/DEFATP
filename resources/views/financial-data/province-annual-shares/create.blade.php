@extends('layouts.app')

@section('title', 'Nouvelle Part Annuelle de Province')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-map-marked-alt text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    Nouvelle Part Annuelle de Province
                </h1>
                <p class="text-gray-600 text-lg mt-2">Créez une nouvelle part annuelle de province</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 p-6 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-lg">Succès!</h3>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium">Veuillez corriger les erreurs suivantes :</h3>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Formulaire de création</h2>
                <p class="text-gray-600">Remplissez les informations pour créer une nouvelle part annuelle</p>
            </div>
        </div>

        <form action="{{ route('financial-data.province-annual-shares.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="situation_administrative_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Situation Administrative
                    </label>
                    <select name="situation_administrative_id" id="situation_administrative_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionner...</option>
                        @foreach($situationsAdministratives as $situation)
                            <option value="{{ $situation->id }}" {{ old('situation_administrative_id') == $situation->id ? 'selected' : '' }}>
                                {{ $situation->province }} - {{ $situation->commune }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">
                        Année <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="year" id="year" value="{{ old('year', date('Y')) }}" 
                           min="1900" max="2100" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="llege" class="block text-sm font-semibold text-gray-700 mb-2">
                        Liège (DH)
                    </label>
                    <input type="number" name="llege" id="llege" value="{{ old('llege', 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="bols_charbon_tanin" class="block text-sm font-semibold text-gray-700 mb-2">
                        Bols/Charbon/Tanin (DH)
                    </label>
                    <input type="number" name="bols_charbon_tanin" id="bols_charbon_tanin" value="{{ old('bols_charbon_tanin', 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="alfa" class="block text-sm font-semibold text-gray-700 mb-2">
                        Alfa (DH)
                    </label>
                    <input type="number" name="alfa" id="alfa" value="{{ old('alfa', 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="produits_divers" class="block text-sm font-semibold text-gray-700 mb-2">
                        Produits Divers (DH)
                    </label>
                    <input type="number" name="produits_divers" id="produits_divers" value="{{ old('produits_divers', 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="interets_retard" class="block text-sm font-semibold text-gray-700 mb-2">
                        Intérêts Retard (DH)
                    </label>
                    <input type="number" name="interets_retard" id="interets_retard" value="{{ old('interets_retard', 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="total_province" class="block text-sm font-semibold text-gray-700 mb-2">
                        Total Province (DH)
                    </label>
                    <input type="number" name="total_province" id="total_province" value="{{ old('total_province', 0) }}" 
                           step="0.01" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span class="font-semibold">Créer</span>
                </button>
                <a href="{{ route('financial-data.index', ['tab' => 'province-annual-shares']) }}" 
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection


