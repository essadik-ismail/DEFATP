@extends('layouts.app')

@section('title', 'Rapport des Contrats - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-handshake text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                            Rapport des Contrats
                        </h1>
                        <p class="text-gray-600 text-lg mt-2">Analysez les contrats de partenariat avec des statistiques détaillées</p>
                    </div>
                </div>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-8">
        <x-card 
            title="Filtres" 
            subtitle="Filtrez les contrats selon vos critères"
            variant="colored"
            color="blue"
            icon="fas fa-filter"
            padding="compact"
        >
            <form method="GET" action="{{ route('reports.contracts') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar text-purple-500 mr-1"></i>Année
                        </label>
                        <select name="year" id="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les années</option>
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="localisation_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-green-500 mr-1"></i>Localisation
                        </label>
                        <select name="localisation_id" id="localisation_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les localisations</option>
                            @foreach($localisations as $localisation)
                                <option value="{{ $localisation->id }}" {{ request('localisation_id') == $localisation->id ? 'selected' : '' }}>
                                    {{ $localisation->CODE }} - {{ $localisation->DRANEF }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="situation_administrative_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building text-indigo-500 mr-1"></i>Situation Administrative
                        </label>
                        <select name="situation_administrative_id" id="situation_administrative_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les situations</option>
                            @foreach($situations as $situation)
                                <option value="{{ $situation->id }}" {{ request('situation_administrative_id') == $situation->id ? 'selected' : '' }}>
                                    {{ $situation->commune }} - {{ $situation->province }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="espece_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-leaf text-emerald-500 mr-1"></i>Espèce
                        </label>
                        <select name="espece_id" id="espece_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les espèces</option>
                            @foreach($especes as $espece)
                                <option value="{{ $espece->id }}" {{ request('espece_id') == $espece->id ? 'selected' : '' }}>
                                    {{ $espece->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="foret_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tree text-green-500 mr-1"></i>Forêt
                        </label>
                        <select name="foret_id" id="foret_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les forêts</option>
                            @foreach($forets as $foret)
                                <option value="{{ $foret->id }}" {{ request('foret_id') == $foret->id ? 'selected' : '' }}>
                                    {{ $foret->foret }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="coperative_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users-cog text-cyan-500 mr-1"></i>Coopérative
                        </label>
                        <select name="coperative_id" id="coperative_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Toutes les coopératives</option>
                            @foreach($coperatives as $coperative)
                                <option value="{{ $coperative->id }}" {{ request('coperative_id') == $coperative->id ? 'selected' : '' }}>
                                    {{ $coperative->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300">
                            <i class="fas fa-filter"></i>
                            <span>Appliquer les filtres</span>
                        </button>
                        <a href="{{ route('reports.contracts') }}" class="inline-flex items-center gap-2 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300">
                            <i class="fas fa-redo"></i>
                            <span>Réinitialiser</span>
                        </a>
                    </div>
                </div>
            </form>
        </x-card>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-contract text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Total Contrats</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_contracts']) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Valeur Totale</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_value'], 2) }} DH</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-ruler-combined text-white text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900">Superficie Totale</h3>
                    <p class="text-gray-600 text-sm">{{ number_format($stats['total_superficie'], 2) }} ha</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Contracts by Year Chart -->
        <x-card 
            title="Contrats par Année" 
            subtitle="Distribution des contrats par année"
            variant="gradient"
            color="blue"
            icon="fas fa-calendar-alt"
        >
            <div class="mb-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-4 border border-blue-200" style="position: relative; height: 300px;">
                <canvas id="contractsByYearChart"></canvas>
            </div>
        </x-card>

        <!-- Contracts by Localisation Chart -->
        <x-card 
            title="Contrats par Localisation" 
            subtitle="Top 10 des localisations"
            variant="colored"
            color="green"
            icon="fas fa-map"
        >
            <div class="mb-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-4 border border-green-200" style="position: relative; height: 300px;">
                <canvas id="contractsByLocalisationChart"></canvas>
            </div>
        </x-card>
    </div>

    <!-- Contracts Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Liste des Contrats</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Année</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contrat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Localisation</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Situation</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Superficie</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Valeur</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($contracts as $contract)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $contract->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->annee ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contract->contarct ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contract->localisation->CODE ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contract->situationAdministrative->commune ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contract->superficie ? number_format($contract->superficie, 2) : '-' }} ha
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contract->total_avenant ? number_format($contract->total_avenant, 2) : '-' }} DH
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('contracts.show', $contract) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                Aucun contrat trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($contracts->hasPages())
            <div class="mt-6">
                {{ $contracts->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Contracts by Year Chart
    const yearCtx = document.getElementById('contractsByYearChart');
    if (yearCtx) {
        new Chart(yearCtx, {
            type: 'bar',
            data: {
                labels: @json($stats['by_year']->pluck('annee')),
                datasets: [{
                    label: 'Nombre de contrats',
                    data: @json($stats['by_year']->pluck('total')),
                    backgroundColor: '#3b82f6',
                    borderColor: '#2563eb',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
    
    // Contracts by Localisation Chart
    const locCtx = document.getElementById('contractsByLocalisationChart');
    if (locCtx) {
        new Chart(locCtx, {
            type: 'doughnut',
            data: {
                labels: @json($stats['by_localisation']->pluck('label')),
                datasets: [{
                    data: @json($stats['by_localisation']->pluck('total')),
                    backgroundColor: [
                        '#3b82f6', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6',
                        '#06b6d4', '#ec4899', '#14b8a6', '#f97316', '#6366f1'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'right' }
                }
            }
        });
    }
});
</script>
@endpush
@endsection

