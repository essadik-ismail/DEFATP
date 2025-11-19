@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="container mx-auto px-4 py-8">
        <!-- Header Content -->
        <div class="mb-8">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">Rapports</h1>
                        <p class="text-gray-600 text-lg mt-2">Générez et consultez différents types de rapports pour analyser vos données</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <!-- <div class="mb-8">
            <x-card 
                title="Filtres de Date" 
                subtitle="Sélectionnez une période pour filtrer les rapports"
                variant="colored"
                color="blue"
                icon="fas fa-calendar-alt"
                padding="compact"
            >
                <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus text-blue-500 mr-2"></i>
                            Date de début
                        </label>
                        <input 
                            type="date" 
                            id="start_date" 
                            name="start_date" 
                            value="{{ request('start_date') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                    </div>
                    
                    <div class="flex-1">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-minus text-blue-500 mr-2"></i>
                            Date de fin
                        </label>
                        <input 
                            type="date" 
                            id="end_date" 
                            name="end_date" 
                            value="{{ request('end_date') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                    </div>
                    
                    <div class="flex gap-2">
                        <button 
                            type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                        >
                            <i class="fas fa-filter"></i>
                            Filtrer
                        </button>
                        
                        <a 
                            href="{{ route('reports.index') }}" 
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                        >
                            <i class="fas fa-times"></i>
                            Effacer
                        </a>
                    </div>
                </form>
                
                @if(request('start_date') || request('end_date'))
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center gap-2 text-blue-800">
                            <i class="fas fa-info-circle"></i>
                            <span class="font-medium">Période sélectionnée :</span>
                            <span>
                                @if(request('start_date') && request('end_date'))
                                    Du {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }} au {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                                @elseif(request('start_date'))
                                    À partir du {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                                @elseif(request('end_date'))
                                    Jusqu'au {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                                @endif
                            </span>
                        </div>
                    </div>
                @endif
            </x-card>
        </div> -->

        <!-- Reports Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Product Quantities Charts -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('reports.product-quantities-charts') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rapport des Articles Général</h3>
                <p class="text-gray-600">Générez un rapport complet des articles avec statistiques détaillées</p>
            </div>

            <!-- Legacy Quantities Charts -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('reports.legacy-quantities-charts') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rapport des Articles Historiques</h3>
                <p class="text-gray-600">Consultez les rapports des articles historiques avec analyses approfondies</p>
            </div>

            <!-- Article Quantities Charts -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('reports.article-quantities-charts') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rapport des Articles</h3>
                <p class="text-gray-600">Analysez les articles actuels avec graphiques et statistiques détaillées</p>
            </div>

            <!-- Contracts Report -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('reports.contracts') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-handshake text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rapport des Contrats</h3>
                <p class="text-gray-600">Analysez les contrats de partenariat avec statistiques détaillées par année, localisation et situation</p>
            </div>

            <!-- Exploitants Report -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('reports.exploitants') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-tie text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rapport des Exploitants</h3>
                <p class="text-gray-600">Analysez les exploitants avec statistiques par catégorie, activité, localisation et qualification</p>
            </div>

            <!-- ODFs Report -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('reports.odfs') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rapport des ODFs</h3>
                <p class="text-gray-600">Analysez les Organisations de la Femme avec statistiques par localisation, situation administrative et type de membre</p>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  function renderBarChart(canvasId, labels, data, color) {
    const el = document.getElementById(canvasId);
    if (!el || !window.Chart) return;
    new Chart(el, {
      type: 'bar',
      data: { labels, datasets: [{ data, backgroundColor: color || '#3b82f6' }] },
      options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
  }
});
</script>
@endpush
