@props([
    'id' => 'map',
    'latitude' => 46.2276,
    'longitude' => 2.2137,
    'zoom' => 6,
    'height' => '400px',
    'markers' => [],
    'editable' => false,
    'title' => 'Carte'
])

<div class="map-container">
    @if($title)
        <div class="map-header mb-3">
            <h5 class="map-title">
                <i class="fas fa-map-marker-alt me-2"></i>{{ $title }}
            </h5>
        </div>
    @endif
    
    <div id="{{ $id }}" class="leaflet-map" style="height: {{ $height }}; width: 100%; border-radius: 8px; border: 1px solid #dee2e6;"></div>
    
    @if($editable)
        <div class="map-controls mt-3">
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addMarkerToMap('{{ $id }}')">
                    <i class="fas fa-plus me-1"></i>Ajouter un marqueur
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearMapMarkers('{{ $id }}')">
                    <i class="fas fa-trash me-1"></i>Effacer les marqueurs
                </button>
                <button type="button" class="btn btn-sm btn-outline-info" onclick="getMapCenter('{{ $id }}')">
                    <i class="fas fa-crosshairs me-1"></i>Centre de la carte
                </button>
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        .map-container {
            margin-bottom: 1.5rem;
        }
        
        .map-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .map-title {
            margin: 0;
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .leaflet-map {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }
        
        .leaflet-map:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }
        
        .map-controls {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        /* Responsive map */
        @media (max-width: 768px) {
            .leaflet-map {
                height: 300px !important;
            }
            
            .map-controls .btn {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Global map instances storage
        window.leafletMaps = window.leafletMaps || {};
        
        // Initialize map when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeMap('{{ $id }}', {{ $latitude }}, {{ $longitude }}, {{ $zoom }}, @json($markers));
        });
        
        function initializeMap(mapId, lat, lng, zoom, markers) {
            try {
                // Check if Leaflet is available
                if (typeof L === 'undefined') {
                    console.error('Leaflet is not loaded');
                    return;
                }
                
                // Create map instance
                const map = L.map(mapId).setView([lat, lng], zoom);
                
                // Add OpenStreetMap tiles
                // Satellite imagery
                var satellite = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', 
                    {
                        attribution: 'Tiles © Esri — Source: Esri, Garmin, GEBCO, NOAA NGDC, and the GIS User Community',
                        maxZoom: 20
                    }
                );

                // Labels (overlay)
                var labels = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', 
                    {
                        attribution: 'Labels © Esri',
                        maxZoom: 20,
                        pane: 'overlayPane' // ensures labels are on top
                    }
                );

                // Add both to map
                satellite.addTo(map);
                labels.addTo(map);


                
                // Store map instance globally
                window.leafletMaps[mapId] = map;
                
                // Add markers if provided
                if (markers && markers.length > 0) {
                    markers.forEach(marker => {
                        if (marker.lat && marker.lng) {
                            const leafletMarker = L.marker([marker.lat, marker.lng])
                                .addTo(map)
                                .bindPopup(marker.popup || 'Marqueur');
                            
                            // Store marker reference
                            if (!map.markers) map.markers = [];
                            map.markers.push(leafletMarker);
                        }
                    });
                }
                
                // Add click event for editable maps
                if ({{ $editable ? 'true' : 'false' }}) {
                    map.on('click', function(e) {
                        addMarkerAtPosition(mapId, e.latlng.lat, e.latlng.lng);
                    });
                }
                
                console.log(`Map ${mapId} initialized successfully`);
                
            } catch (error) {
                console.error(`Error initializing map ${mapId}:`, error);
            }
        }
        
        // Add marker at specific position
        function addMarkerAtPosition(mapId, lat, lng, popup = 'Nouveau marqueur') {
            const map = window.leafletMaps[mapId];
            if (!map) return;
            
            const marker = L.marker([lat, lng])
                .addTo(map)
                .bindPopup(popup);
            
            if (!map.markers) map.markers = [];
            map.markers.push(marker);
            
            // Trigger custom event
            const event = new CustomEvent('markerAdded', {
                detail: { mapId, lat, lng, popup }
            });
            document.dispatchEvent(event);
        }
        
        // Add marker to map (for button click)
        function addMarkerToMap(mapId) {
            const map = window.leafletMaps[mapId];
            if (!map) return;
            
            const center = map.getCenter();
            addMarkerAtPosition(mapId, center.lat, center.lng, 'Marqueur ajouté');
        }
        
        // Clear all markers from map
        function clearMapMarkers(mapId) {
            const map = window.leafletMaps[mapId];
            if (!map || !map.markers) return;
            
            map.markers.forEach(marker => {
                map.removeLayer(marker);
            });
            map.markers = [];
            
            console.log(`All markers cleared from map ${mapId}`);
        }
        
        // Get map center coordinates
        function getMapCenter(mapId) {
            const map = window.leafletMaps[mapId];
            if (!map) return;
            
            const center = map.getCenter();
            const coords = `${center.lat.toFixed(6)}, ${center.lng.toFixed(6)}`;
            
            // Show coordinates in alert (you can modify this to show in a modal or input field)
            alert(`Centre de la carte: ${coords}`);
            
            // Copy to clipboard
            navigator.clipboard.writeText(coords).then(() => {
                console.log('Coordinates copied to clipboard');
            });
        }
        
        // Public function to add custom markers
        window.addMapMarker = function(mapId, lat, lng, popup) {
            addMarkerAtPosition(mapId, lat, lng, popup);
        };
        
        // Public function to remove specific marker
        window.removeMapMarker = function(mapId, markerIndex) {
            const map = window.leafletMaps[mapId];
            if (!map || !map.markers || !map.markers[markerIndex]) return;
            
            map.removeLayer(map.markers[markerIndex]);
            map.markers.splice(markerIndex, 1);
        };
        
        // Public function to get map instance
        window.getMapInstance = function(mapId) {
            return window.leafletMaps[mapId];
        };
    </script>
@endpush