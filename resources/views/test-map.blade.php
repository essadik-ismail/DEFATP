@extends('layouts.app')

@section('title', 'Test de la Carte')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marked-alt me-2"></i>Test de la Carte Leaflet
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Cette page teste l'intégration de Leaflet.js. Vous devriez voir une carte interactive ci-dessous.
                    </div>
                    
                    <!-- Test Map -->
                    <x-map 
                        id="test-map"
                        latitude="31.7917"
                        longitude="-7.0926"
                        :zoom="6"
                        height="400px"
                        :markers="[
                            ['lat' => 31.7917, 'lng' => -7.0926, 'popup' => 'Centre du Maroc'],
                            ['lat' => 34.0209, 'lng' => -6.8416, 'popup' => 'Rabat'],
                            ['lat' => 33.5731, 'lng' => -7.5898, 'popup' => 'Casablanca']
                        ]"
                        :editable="true"
                        title="Carte de Test"
                    />
                    
                    <div class="mt-4">
                        <h6>Fonctionnalités testées :</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Chargement de la carte</li>
                            <li><i class="fas fa-check text-success me-2"></i>Affichage des marqueurs</li>
                            <li><i class="fas fa-check text-success me-2"></i>Navigation (zoom, déplacement)</li>
                            <li><i class="fas fa-check text-success me-2"></i>Ajout de marqueurs par clic</li>
                            <li><i class="fas fa-check text-success me-2"></i>Boutons de contrôle</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
