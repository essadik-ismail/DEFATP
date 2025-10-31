@extends('layouts.app')

@section('title', 'Graphiques des Quantités de Produits (Legacy)')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #059669, #047857);">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">Graphiques des Quantités de Produits (Legacy)</h1>
                    <p class="text-gray-600 text-lg mt-2">Analysez les quantités de produits par année et par province (Articles historiques uniquement)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Legacy Data Notice -->
    <div class="mb-8">
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center gap-2 text-orange-800">
                <i class="fas fa-info-circle"></i>
                <span class="font-medium">Données Legacy :</span>
                <span>Ces graphiques présentent uniquement les données des articles historiques (legacy).</span>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    @if(isset($error))
    <div class="mb-8">
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-red-900">Erreur</h3>
                    <p class="text-red-700">{{ $error }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Chart 1: Product Quantities by Year -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Quantités de Produits par Année</h3>
            </div>
            
            <!-- Product Selection for Chart 1 -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-filter text-blue-500 mr-2"></i>
                    Sélectionnez les produits à afficher :
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                    @foreach($productFields as $field)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" 
                                   class="product-selector-chart1 rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                   value="{{ $field }}" 
                                   checked
                                   data-chart="chart1">
                            <span class="text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $field)) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            
            <div class="chart-container">
                <canvas id="chart1"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 2: Product Quantities by Province and Year -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-white/20">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Quantités de Produits par Province et Année</h3>
            </div>
            
            <!-- Notice about data sources -->
            <div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                <div class="flex items-center gap-2 text-orange-800">
                    <i class="fas fa-info-circle"></i>
                    <span class="font-medium">Source de données :</span>
                    <span>Articles historiques uniquement (via province).</span>
                </div>
            </div>
            
            <!-- Province Selection for Chart 2 -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-map text-purple-500 mr-2"></i>
                    Sélectionnez la province :
                </label>
                <select id="province-selector" class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Toutes les provinces</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}">{{ $province }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Product Selection for Chart 2 -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-filter text-purple-500 mr-2"></i>
                    Sélectionnez les produits à afficher :
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                    @foreach($productFields as $field)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" 
                                   class="product-selector-chart2 rounded border-gray-300 text-purple-600 focus:ring-purple-500" 
                                   value="{{ $field }}" 
                                   checked
                                   data-chart="chart2">
                            <span class="text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $field)) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            
            <div class="chart-container">
                <canvas id="chart2"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('reports.index') }}" 
           class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Retour aux Rapports
        </a>
        
        <button onclick="exportCharts()" 
                class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors flex items-center gap-2">
            <i class="fas fa-download"></i>
            Exporter les Graphiques
        </button>
    </div>
</div>

@push('styles')
<style>
.chart-container {
    position: relative;
    height: 300px;
    max-height: 300px;
}

@media (max-width: 768px) {
    .chart-container {
        height: 250px;
        max-height: 250px;
    }
}

@media (max-width: 480px) {
    .chart-container {
        height: 200px;
        max-height: 200px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(isset($error))
        console.error('Chart error: {{ $error }}');
        return;
    @endif
    
    // Chart data
    const years = @json($legacyYears);
    const yearlyData = @json($yearlyData);
    const provinceData = @json($provinceData);
    const productFields = @json($productFields);
    
    // Chart configurations
    let chart1, chart2;
    
    // Color palette for products
    const colors = [
        '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
        '#06b6d4', '#84cc16', '#f97316', '#ec4899'
    ];
    
    // Initialize Chart 1
    function initChart1() {
        const ctx1 = document.getElementById('chart1').getContext('2d');
        
        const selectedProducts = Array.from(document.querySelectorAll('.product-selector-chart1:checked'))
            .map(cb => cb.value);
        
        const datasets = selectedProducts.map((field, index) => {
            const data = years.map(year => yearlyData[year] ? (yearlyData[year][field] || 0) : 0);
            return {
                label: field.replace(/_/g, ' ').toUpperCase(),
                data: data,
                borderColor: colors[index % colors.length],
                backgroundColor: colors[index % colors.length] + '20',
                tension: 0.4,
                fill: false
            };
        });
        
        if (chart1) chart1.destroy();
        
        chart1 = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: years,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 2.5,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Évolution des Quantités par Année (Legacy)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantité'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Année'
                        }
                    }
                }
            }
        });
    }
    
    // Initialize Chart 2
    function initChart2() {
        const ctx2 = document.getElementById('chart2').getContext('2d');
        const selectedProvince = document.getElementById('province-selector').value;
        const selectedProducts = Array.from(document.querySelectorAll('.product-selector-chart2:checked'))
            .map(cb => cb.value);
        
        let datasets = [];
        let labels = [];
        
        if (selectedProvince) {
            // Show data for specific province
            const provData = provinceData[selectedProvince];
            if (provData) {
                labels = years;
                datasets = selectedProducts.map((field, index) => {
                    const data = years.map(year => 
                        provData.data[year] ? (provData.data[year][field] || 0) : 0
                    );
                    return {
                        label: field.replace(/_/g, ' ').toUpperCase(),
                        data: data,
                        backgroundColor: colors[index % colors.length] + '80',
                        borderColor: colors[index % colors.length],
                        borderWidth: 1
                    };
                });
            }
        } else {
            // Show aggregated data for all provinces
            labels = years;
            datasets = selectedProducts.map((field, index) => {
                const data = years.map(year => {
                    let total = 0;
                    Object.values(provinceData).forEach(provData => {
                        if (provData.data[year]) {
                            total += provData.data[year][field] || 0;
                        }
                    });
                    return total;
                });
                return {
                    label: field.replace(/_/g, ' ').toUpperCase(),
                    data: data,
                    backgroundColor: colors[index % colors.length] + '80',
                    borderColor: colors[index % colors.length],
                    borderWidth: 1
                };
            });
        }
        
        if (chart2) chart2.destroy();
        
        chart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 2.5,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: selectedProvince ? 
                            `Quantités par Année - ${selectedProvince}` : 
                            'Quantités par Année - Toutes Provinces'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantité'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Année'
                        }
                    }
                }
            }
        });
    }
    
    // Event listeners
    document.querySelectorAll('.product-selector-chart1').forEach(checkbox => {
        checkbox.addEventListener('change', initChart1);
    });
    
    document.querySelectorAll('.product-selector-chart2').forEach(checkbox => {
        checkbox.addEventListener('change', initChart2);
    });
    
    document.getElementById('province-selector').addEventListener('change', initChart2);
    
    // Initialize charts
    initChart1();
    initChart2();
    
    // Export function
    window.exportCharts = function() {
        const canvas1 = document.getElementById('chart1');
        const canvas2 = document.getElementById('chart2');
        
        // Create a temporary link to download the charts
        const link1 = document.createElement('a');
        link1.download = 'chart1-legacy-quantities-by-year.png';
        link1.href = canvas1.toDataURL();
        link1.click();
        
        setTimeout(() => {
            const link2 = document.createElement('a');
            link2.download = 'chart2-legacy-quantities-by-province.png';
            link2.href = canvas2.toDataURL();
            link2.click();
        }, 500);
    };
});
</script>
@endpush
@endsection

