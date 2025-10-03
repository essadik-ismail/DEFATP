@extends('layouts.app')

@section('title', 'Test du Système de Visite Guidée Personnalisé')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-signs me-2"></i>Test du Système de Visite Guidée Personnalisé
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Cette page teste le système de visite guidée personnalisé créé from scratch.
                        Cliquez sur "Démarrer la Visite" pour tester les fonctionnalités.
                    </div>
                    
                    <!-- Demo Elements for Tour -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card demo-element" id="demo-sidebar">
                                <div class="card-body text-center">
                                    <i class="fas fa-bars fa-2x text-primary mb-2"></i>
                                    <h6>Barre Latérale</h6>
                                    <p class="text-muted small">Navigation principale de l'application</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card demo-element" id="demo-topbar">
                                <div class="card-body text-center">
                                    <i class="fas fa-toolbar fa-2x text-success mb-2"></i>
                                    <h6>Barre Supérieure</h6>
                                    <p class="text-muted small">Actions rapides et notifications</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card demo-element" id="demo-content">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-alt fa-2x text-warning mb-2"></i>
                                    <h6>Zone de Contenu</h6>
                                    <p class="text-muted small">Affichage des données et formulaires</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card demo-element" id="demo-filters">
                                <div class="card-body text-center">
                                    <i class="fas fa-filter fa-2x text-info mb-2"></i>
                                    <h6>Filtres</h6>
                                    <p class="text-muted small">Recherche et filtrage des données</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card demo-element" id="demo-table">
                                <div class="card-body text-center">
                                    <i class="fas fa-table fa-2x text-danger mb-2"></i>
                                    <h6>Tableau de Données</h6>
                                    <p class="text-muted small">Affichage et gestion des informations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tour Control Buttons -->
                    <div class="d-flex gap-3 flex-wrap justify-content-center">
                        <button type="button" class="btn btn-primary btn-lg" onclick="startCustomTour('demo-tour')">
                            <i class="fas fa-play me-2"></i>Démarrer la Visite
                        </button>
                        
                        <button type="button" class="btn btn-outline-primary" onclick="startCustomTour('forest-tour')">
                            <i class="fas fa-tree me-2"></i>Thème Forêt
                        </button>
                        
                        <button type="button" class="btn btn-outline-info" onclick="startCustomTour('modern-tour')">
                            <i class="fas fa-palette me-2"></i>Thème Moderne
                        </button>
                        
                        <button type="button" class="btn btn-outline-secondary" onclick="testTourFunctions()">
                            <i class="fas fa-cog me-2"></i>Tester les Fonctions
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Fonctionnalités du Système :</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Spotlight animé sur les éléments</li>
                            <li><i class="fas fa-check text-success me-2"></i>Navigation fluide avec clavier (flèches)</li>
                            <li><i class="fas fa-check text-success me-2"></i>Progress bar et indicateurs visuels</li>
                            <li><i class="fas fa-check text-success me-2"></i>Thèmes personnalisables (Forêt, Moderne)</li>
                            <li><i class="fas fa-check text-success me-2"></i>Positionnement intelligent des popovers</li>
                            <li><i class="fas fa-check text-success me-2"></i>Contrôles de pause, skip et aide</li>
                            <li><i class="fas fa-check text-success me-2"></i>Design responsive et animations fluides</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Tour System -->
<x-custom-tour 
    tourId="demo-tour"
    :steps="[
        [
            'element' => '#demo-sidebar',
            'title' => 'Bienvenue dans DEFATP! 🌳',
            'description' => 'Cette barre latérale vous permet de naviguer entre toutes les sections de l\'application. Commençons par explorer les fonctionnalités principales.',
            'popoverPosition' => 'right'
        ],
        [
            'element' => '#demo-topbar',
            'title' => 'Barre Supérieure 🎯',
            'description' => 'Accédez rapidement aux notifications, paramètres de profil et autres actions importantes depuis cette barre.',
            'popoverPosition' => 'bottom'
        ],
        [
            'element' => '#demo-content',
            'title' => 'Zone de Contenu 📄',
            'description' => 'C\'est ici que s\'affichent vos données, formulaires et informations. La zone principale de travail de l\'application.',
            'popoverPosition' => 'top'
        ],
        [
            'element' => '#demo-filters',
            'title' => 'Système de Filtres 🔍',
            'description' => 'Utilisez ces filtres pour rechercher et affiner vos données selon vos besoins spécifiques.',
            'popoverPosition' => 'top'
        ],
        [
            'element' => '#demo-table',
            'title' => 'Tableau de Données 📊',
            'description' => 'Visualisez et gérez toutes vos informations dans ce tableau interactif. Vous pouvez trier, filtrer et modifier les données.',
            'popoverPosition' => 'bottom'
        ]
    ]"
    theme="default"
    :autoStart="false"
/>

<!-- Forest Theme Tour -->
<x-custom-tour 
    tourId="forest-tour"
    :steps="[
        [
            'element' => '#demo-sidebar',
            'title' => '🌲 Navigation Forestière',
            'description' => 'Explorez la forêt numérique de DEFATP avec cette barre de navigation intuitive.',
            'popoverPosition' => 'right'
        ],
        [
            'element' => '#demo-topbar',
            'title' => '🌿 Outils de Gestion',
            'description' => 'Accédez aux outils essentiels pour la gestion de vos ressources forestières.',
            'popoverPosition' => 'bottom'
        ],
        [
            'element' => '#demo-content',
            'title' => '🌳 Centre de Contrôle',
            'description' => 'Le cœur de votre système de gestion forestière, où toutes les informations convergent.',
            'popoverPosition' => 'top'
        ]
    ]"
    theme="forest"
    :autoStart="false"
/>

<!-- Modern Theme Tour -->
<x-custom-tour 
    tourId="modern-tour"
    :steps="[
        [
            'element' => '#demo-sidebar',
            'title' => '🚀 Interface Moderne',
            'description' => 'Une navigation moderne et intuitive pour une expérience utilisateur optimale.',
            'popoverPosition' => 'right'
        ],
        [
            'element' => '#demo-topbar',
            'title' => '⚡ Actions Rapides',
            'description' => 'Accédez rapidement aux fonctionnalités les plus utilisées avec cette barre d\'outils moderne.',
            'popoverPosition' => 'bottom'
        ],
        [
            'element' => '#demo-content',
            'title' => '💎 Zone de Travail',
            'description' => 'Un espace de travail élégant et fonctionnel pour gérer vos données efficacement.',
            'popoverPosition' => 'top'
        ]
    ]"
    theme="modern"
    :autoStart="false"
/>
@endsection

@push('scripts')
<script>
    function testTourFunctions() {
        console.log('=== Test des Fonctions de Tour ===');
        console.log('Tours disponibles:', Object.keys(window.customTours || {}));
        
        if (window.customTours['demo-tour']) {
            console.log('Tour demo-tour:', window.customTours['demo-tour']);
            console.log('État actuel:', {
                isActive: window.customTours['demo-tour'].isActive,
                currentStep: window.customTours['demo-tour'].currentStep,
                totalSteps: window.customTours['demo-tour'].steps.length
            });
        }
        
        alert('Vérifiez la console pour les détails des fonctions de tour!');
    }
    
    // Listen for tour completion events
    document.addEventListener('tourCompleted', function(event) {
        console.log('Tour terminé:', event.detail);
        
        // Show completion message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            <strong>Visite terminée!</strong> Vous avez complété le tour "${event.detail.tourId}" avec ${event.detail.steps} étapes.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.alert-info'));
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    });
</script>
@endpush
