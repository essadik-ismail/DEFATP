@extends('layouts.app')

@section('title', 'Détails Partenariat - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-handshake text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Détails Partenariat
                    </h1>
                    <p class="text-gray-600 text-lg mt-2">Informations détaillées du partenariat</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('partenariats.edit', $partenariat) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
                <a href="{{ route('partenariats.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-6">
        <!-- Partenariat Details -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="space-y-6">
                <!-- Informations Association -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Association</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nom Association</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->nom_association ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre d'Adhérents</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->nombre_adherents_association ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date de Création</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->date_creation_association ? $partenariat->date_creation_association->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Localisation (DRANEF - DPANEF - ENTITE)</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->localisation ? $partenariat->localisation->DRANEF . ' - ' . $partenariat->localisation->DPANEF . ' - ' . $partenariat->localisation->ENTITE : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations Terrain -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Terrain</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Superficie</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->superficie ? number_format($partenariat->superficie, 2) : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nom Périmètre</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->nom_périmètre ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Essence</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->essence ? $partenariat->essence->essence : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Superficie (ha)</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->Superficie_ha ? number_format($partenariat->Superficie_ha, 2) : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations Contrat -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Contrat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Object CMD</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium min-h-[80px]">
                                {{ $partenariat->object_cmd ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Numéro Contrat</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->num_contract ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date Signature Contrat</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->date_signature_contract ? $partenariat->date_signature_contract->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations Avenant -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations Avenant</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Numéro Avenant</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->num_avenant ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre d'Avenants</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->nombre_avenant ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date Signature Avenant</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->date_signature_avenant ? $partenariat->date_signature_avenant->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Superficie Contrat Avenant</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->Superficie_Contrat_avenant ? number_format($partenariat->Superficie_Contrat_avenant, 2) : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- État et Évaluation -->
                <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-2xl p-6 border border-orange-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">État et Évaluation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date PV État des Lieux</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->Date_PV_etat_des_lieux ? $partenariat->Date_PV_etat_des_lieux->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Taux de Réussite (%)</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->Taux_de_réussite ? number_format($partenariat->Taux_de_réussite, 2) . '%' : 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">État de la Clôture</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->Etat_de_la_clôture ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">État Peuplement</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium">
                                {{ $partenariat->Etat_peuplement ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">PV Évaluation</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium min-h-[80px]">
                                {{ $partenariat->PV_évaluation ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Observations</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium min-h-[80px]">
                                {{ $partenariat->observations ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Contraintes</label>
                            <div class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 font-medium min-h-[80px]">
                                {{ $partenariat->Contraintes ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suivi Contract Programmes -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Suivi Contract Programmes</h3>
                <a href="{{ route('suivi-contract-programmes.create', ['partenariat_id' => $partenariat->id]) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter Suivi</span>
                </a>
            </div>
            
            @if($partenariat->suiviContractProgrammes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Année</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Projet CP</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Forêt</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Superficie Prévue (ha)</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Montant Prévu (DH)</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Superficie Engagée (ha)</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Montant Engagé (DH)</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Superficie Payée (ha)</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Montant Payé (DH)</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($partenariat->suiviContractProgrammes as $suivi)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $suivi->Année ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $suivi->Projet_CP ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $suivi->foret ? $suivi->foret->foret : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $suivi->Superficie_prévue_CP_ha ? number_format($suivi->Superficie_prévue_CP_ha, 2) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $suivi->Montant_prévu_CP_dh ? number_format($suivi->Montant_prévu_CP_dh, 2) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $suivi->Superficie_engagée_ha ? number_format($suivi->Superficie_engagée_ha, 2) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $suivi->Montant_engagé_dh ? number_format($suivi->Montant_engagé_dh, 2) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $suivi->Superficie_payée_ha ? number_format($suivi->Superficie_payée_ha, 2) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $suivi->Montant_payé_dh ? number_format($suivi->Montant_payé_dh, 2) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('suivi-contract-programmes.edit', $suivi) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg hover:from-blue-600 hover:to-cyan-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                           title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('suivi-contract-programmes.destroy', $suivi) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-105 shadow-sm"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce suivi ?')"
                                                    title="Supprimer">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clipboard-list text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-lg font-medium mb-4">Aucun suivi de contrat programme trouvé</p>
                    <a href="{{ route('suivi-contract-programmes.create', ['partenariat_id' => $partenariat->id]) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-plus"></i>
                        <span>Ajouter Suivi Contract Programme</span>
                    </a>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-6">
            <a href="{{ route('partenariats.edit', $partenariat) }}" 
               class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-edit"></i>
                <span class="font-semibold">Modifier</span>
            </a>
            <a href="{{ route('partenariats.index') }}" 
               class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
        </div>
    </div>
</div>
@endsection

