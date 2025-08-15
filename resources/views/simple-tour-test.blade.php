@extends('layouts.app')

@section('title', 'Test Simple Tour')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Test Simple Tour</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Test simple pour vérifier que le système de tour fonctionne.
                    </div>
                    
                    <!-- Test Elements -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card" id="test-element-1">
                                <div class="card-body text-center">
                                    <h6>Élément Test 1</h6>
                                    <p class="text-muted">Premier élément pour le tour</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card" id="test-element-2">
                                <div class="card-body text-center">
                                    <h6>Élément Test 2</h6>
                                    <p class="text-muted">Deuxième élément pour le tour</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Test Button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-lg" onclick="startSimpleTour()">
                            <i class="fas fa-play me-2"></i>Démarrer le Tour Simple
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Simple Tour -->
<x-custom-tour 
    tourId="simple-test"
    :steps="[
        [
            'element' => '#test-element-1',
            'title' => 'Premier Élément',
            'description' => 'Ceci est le premier élément du tour de test avec des explications détaillées.',
            'popoverPosition' => 'bottom',
            'explanations' => [
                [
                    'icon' => 'fa-mouse-pointer',
                    'text' => 'Cliquez ici pour sélectionner cet élément'
                ],
                [
                    'icon' => 'fa-info-circle',
                    'text' => 'Cet élément contient des informations importantes'
                ]
            ]
        ],
        [
            'element' => '#test-element-2',
            'title' => 'Deuxième Élément',
            'description' => 'Ceci est le deuxième élément du tour de test avec des explications détaillées.',
            'popoverPosition' => 'top',
            'explanations' => [
                [
                    'icon' => 'fa-cog',
                    'text' => 'Utilisez cet élément pour configurer les paramètres'
                ],
                [
                    'icon' => 'fa-save',
                    'text' => 'N\'oubliez pas de sauvegarder vos modifications'
                ]
            ]
        ]
    ]"
    theme="default"
    :autoStart="false"
/>
@endsection

@push('scripts')
<script>
    function startSimpleTour() {
        console.log('Starting simple tour...');
        if (typeof startCustomTour === 'function') {
            startCustomTour('simple-test');
        } else {
            console.error('startCustomTour function not found');
            alert('Erreur: Fonction startCustomTour non trouvée');
        }
    }
    
    // Debug info
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded');
        console.log('window.customTours:', window.customTours);
        console.log('typeof startCustomTour:', typeof startCustomTour);
    });
</script>
@endpush
