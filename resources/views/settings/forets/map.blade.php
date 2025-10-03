@extends('layouts.app')

@section('title', 'Carte des Forêts - DEFATP')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<style>
    .map-container {
        position: relative;
        height: 70vh;
        min-height: 500px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .map-controls {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .map-controls .btn {
        padding: 8px 12px;
        font-size: 14px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .legend {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        position: absolute;
        bottom: 20px;
        left: 20px;
        z-index: 1000;
        min-width: 200px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        margin-right: 8px;
        border: 2px solid white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }
    
    .legend-text {
        font-size: 0.875rem;
        color: #374151;
        font-weight: 500;
    }
    
    .fullscreen-map {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9999 !important;
        border-radius: 0 !important;
    }
    
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1001;
        border-radius: 12px;
    }
    
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #e5e7eb;
        border-top: 4px solid #10b981;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="main-content">
    <!-- Header Section -->
    <div class="header-section mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Carte des Forêts</h1>
                <p class="text-gray-600">Visualisation géographique de toutes les forêts du système</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('settings.forets') }}" class="btn btn-outline">
                    <i class="fas fa-list me-2"></i>Liste des Forêts
                </a>
                <a href="{{ route('settings.forets.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouvelle Forêt
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number text-blue-600">{{ $stats['total'] }}</div>
            <div class="stat-label">Total des Forêts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number text-green-600">{{ $stats['geolocated'] }}</div>
            <div class="stat-label">Forêts Géolocalisées</div>
        </div>
        <div class="stat-card">
            <div class="stat-number text-orange-600">{{ $stats['non_geolocated'] }}</div>
            <div class="stat-label">Sans Coordonnées</div>
        </div>
        <div class="stat-card">
            <div class="stat-number text-purple-600">{{ $stats['percentage'] }}%</div>
            <div class="stat-label">Taux de Géolocalisation</div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="glassmorphism-card p-6">
        <div class="map-container" id="forestMap">
            <div class="loading-overlay" id="loadingOverlay">
                <div class="loading-spinner"></div>
            </div>
            
            <!-- Map Controls -->
            <div class="map-controls">
                <button id="toggleFullscreen" class="btn btn-outline">
                    <i class="fas fa-expand-arrows-alt me-2"></i>Plein écran
                </button>
                <button id="centerMap" class="btn btn-primary">
                    <i class="fas fa-crosshairs me-2"></i>Centrer
                </button>
                <button id="resetZoom" class="btn btn-outline">
                    <i class="fas fa-search-minus me-2"></i>Zoom initial
                </button>
            </div>
            
            <!-- Map Legend -->
            <div class="legend">
                <h3 class="font-semibold text-gray-900 mb-3">Légende</h3>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #3b82f6;"></div>
                    <span class="legend-text">Forêts du Nord</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #f59e0b;"></div>
                    <span class="legend-text">Forêts du Centre</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #ef4444;"></div>
                    <span class="legend-text">Forêts du Sud</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background-color: #10b981;"></div>
                    <span class="legend-text">Forêts Actives</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Forest List Section -->
    @if($forests->count() > 0)
    <div class="mt-8">
        <div class="glassmorphism-card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-list me-2"></i>Liste des Forêts Géolocalisées ({{ $forests->count() }})
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($forests as $forest)
                <div class="forest-item p-4 bg-white bg-opacity-50 rounded-lg border border-gray-200 hover:shadow-md transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 mb-1">{{ $forest->foret }}</h4>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><i class="fas fa-map-marker-alt me-1"></i>{{ number_format($forest->lat, 6) }}, {{ number_format($forest->log, 6) }}</p>
                                <p><i class="fas fa-globe me-1"></i>{{ getRegionName($forest->lat) }}</p>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="w-3 h-3 rounded-full {{ getRegionColor($forest->lat) }} border border-white shadow-sm"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeForestMap();
});

function initializeForestMap() {
    // Forest data from Laravel
    const forests = @json($forests);
    
    if (forests.length === 0) {
        document.getElementById('forestMap').innerHTML = `
            <div class="flex items-center justify-center h-full text-gray-500">
                <div class="text-center">
                    <i class="fas fa-map-marked-alt text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Aucune forêt géolocalisée</h3>
                    <p class="text-gray-600">Ajoutez des coordonnées aux forêts pour les voir sur la carte</p>
                </div>
            </div>
        `;
        return;
    }
    
    // Initialize map centered on Morocco
    const map = L.map('forestMap', {
        zoomControl: false
    }).setView([31.6295, -7.9811], 6);
    
    // Add zoom control to top left
    L.control.zoom({
        position: 'topleft'
    }).addTo(map);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);
    
    // Create marker clusters
    const markers = L.markerClusterGroup({
        chunkedLoading: true,
        maxClusterRadius: 50,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true
    });
    
    // Add forest markers
    forests.forEach(forest => {
        if (forest.lat && forest.log && forest.lat !== '0' && forest.log !== '0') {
            const lat = parseFloat(forest.lat);
            const lng = parseFloat(forest.log);
            
            // Determine marker color based on region
            const markerColor = getRegionColor(lat);
            
            // Create custom icon
            const customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${markerColor}; width: 14px; height: 14px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.4);"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });
            
            // Create marker
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            // Add popup with forest information
            marker.bindPopup(`
                <div class="p-3 min-w-[250px]">
                    <h3 class="font-semibold text-gray-900 mb-3 text-lg">${forest.foret}</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                            <span><strong>Coordonnées:</strong> ${lat.toFixed(6)}, ${lng.toFixed(6)}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-globe w-4 mr-2"></i>
                            <span><strong>Région:</strong> ${getRegionName(lat)}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-circle w-4 mr-2" style="color: ${markerColor};"></i>
                            <span><strong>Statut:</strong> Forêt active</span>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <a href="{{ route('settings.forets') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-external-link-alt me-1"></i>Voir dans la liste
                        </a>
                    </div>
                </div>
            `);
            
            markers.addLayer(marker);
        }
    });
    
    // Add markers to map
    map.addLayer(markers);
    
    // Center map on all markers if there are any
    if (forests.length > 0) {
        const group = new L.featureGroup(markers.getLayers());
        if (group.getBounds().isValid()) {
            map.fitBounds(group.getBounds().pad(0.1));
        }
    }
    
    // Hide loading overlay
    document.getElementById('loadingOverlay').style.display = 'none';
    
    // Map control buttons
    document.getElementById('centerMap').addEventListener('click', function() {
        if (forests.length > 0) {
            const group = new L.featureGroup(markers.getLayers());
            if (group.getBounds().isValid()) {
                map.fitBounds(group.getBounds().pad(0.1));
            }
        } else {
            map.setView([31.6295, -7.9811], 6);
        }
    });
    
    document.getElementById('resetZoom').addEventListener('click', function() {
        map.setView([31.6295, -7.9811], 6);
    });
    
    document.getElementById('toggleFullscreen').addEventListener('click', function() {
        const mapContainer = document.getElementById('forestMap');
        const button = this;
        
        if (mapContainer.classList.contains('fullscreen-map')) {
            // Exit fullscreen
            mapContainer.classList.remove('fullscreen-map');
            mapContainer.style.height = '70vh';
            mapContainer.style.minHeight = '500px';
            button.innerHTML = '<i class="fas fa-expand-arrows-alt me-2"></i>Plein écran';
            document.body.style.overflow = '';
        } else {
            // Enter fullscreen
            mapContainer.classList.add('fullscreen-map');
            button.innerHTML = '<i class="fas fa-compress-arrows-alt me-2"></i>Sortir du plein écran';
            document.body.style.overflow = 'hidden';
            
            // Resize map after entering fullscreen
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        }
    });
}

function getRegionName(lat) {
    if (lat > 33) {
        return 'Nord du Maroc';
    } else if (lat > 30) {
        return 'Centre du Maroc';
    } else {
        return 'Sud du Maroc';
    }
}

function getRegionColor(lat) {
    if (lat > 33) {
        return '#3b82f6'; // Blue for North
    } else if (lat > 30) {
        return '#f59e0b'; // Orange for Center
    } else {
        return '#ef4444'; // Red for South
    }
}
</script>
@endpush

@php
function getRegionName($lat) {
    if ($lat > 33) {
        return 'Nord du Maroc';
    } else if ($lat > 30) {
        return 'Centre du Maroc';
    } else {
        return 'Sud du Maroc';
    }
}

function getRegionColor($lat) {
    if ($lat > 33) {
        return 'bg-blue-500'; // Blue for North
    } else if ($lat > 30) {
        return 'bg-orange-500'; // Orange for Center
    } else {
        return 'bg-red-500'; // Red for South
    }
}
@endphp
