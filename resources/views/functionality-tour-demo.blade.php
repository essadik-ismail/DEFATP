@extends('layouts.app')

@section('title', 'Démo du Système de Tour des Fonctionnalités')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Démo du Système de Tour des Fonctionnalités
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Cette page démontre le système de tour des fonctionnalités avec des flèches et des popups descriptifs.
                        Cliquez sur le bouton "Aide" en bas à droite pour démarrer le tour.
                    </div>
                    
                    <!-- Demo Functionalities -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card" id="sidebar-functionality">
                                <div class="card-body text-center">
                                    <i class="fas fa-bars fa-2x text-primary mb-2"></i>
                                    <h6>Barre de Navigation</h6>
                                    <p class="text-muted small">Accès aux différentes sections de l'application</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card" id="search-functionality">
                                <div class="card-body text-center">
                                    <i class="fas fa-search fa-2x text-success mb-2"></i>
                                    <h6>Recherche Avancée</h6>
                                    <p class="text-muted small">Trouvez rapidement vos informations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card" id="export-functionality">
                                <div class="card-body text-center">
                                    <i class="fas fa-download fa-2x text-warning mb-2"></i>
                                    <h6>Export de Données</h6>
                                    <p class="text-muted small">Téléchargez vos données en différents formats</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card" id="map-functionality">
                                <div class="card-body text-center">
                                    <i class="fas fa-map-marked-alt fa-2x text-info mb-2"></i>
                                    <h6>Cartographie</h6>
                                    <p class="text-muted small">Visualisez vos données sur une carte interactive</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card" id="reports-functionality">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-bar fa-2x text-danger mb-2"></i>
                                    <h6>Rapports et Statistiques</h6>
                                    <p class="text-muted small">Analysez vos données avec des graphiques</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="card" id="filters-functionality">
                                <div class="card-body text-center">
                                    <i class="fas fa-filter fa-2x text-secondary mb-2"></i>
                                    <h6>Système de Filtres</h6>
                                    <p class="text-muted small">Affinez vos recherches selon vos besoins</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card" id="notifications-functionality">
                                <div class="card-body text-center">
                                    <i class="fas fa-bell fa-2x text-primary mb-2"></i>
                                    <h6>Notifications</h6>
                                    <p class="text-muted small">Restez informé des mises à jour importantes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Manual Start Button -->
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-lg" onclick="startFunctionalityTour('main-demo')">
                            <i class="fas fa-play me-2"></i>Démarrer le Tour Manuellement
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Fonctionnalités du Système :</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Flèches animées pointant vers les éléments</li>
                            <li><i class="fas fa-check text-success me-2"></i>Popups descriptifs avec icônes et détails</li>
                            <li><i class="fas fa-check text-success me-2"></i>Instructions d'utilisation étape par étape</li>
                            <li><i class="fas fa-check text-success me-2"></i>Navigation au clavier (flèches gauche/droite)</li>
                            <li><i class="fas fa-check text-success me-2"></i>Bouton d'aide flottant en bas à droite</li>
                            <li><i class="fas fa-check text-success me-2"></i>Spotlight avec animations et effets de lueur</li>
                            <li><i class="fas fa-check text-success me-2"></i>Design responsive et thèmes personnalisables</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Functionality Tour System -->
<x-functionality-tour 
    tourId="main-demo"
    :functionalities="[
        [
            'element' => '#sidebar-functionality',
            'title' => 'Barre de Navigation',
            'description' => 'La barre latérale est votre point d\'entrée principal pour naviguer dans l\'application.',
            'popupPosition' => 'right',
            'details' => [
                [
                    'icon' => 'fa-bars',
                    'text' => 'Menu principal avec toutes les sections'
                ],
                [
                    'icon' => 'fa-folder',
                    'text' => 'Organisation logique par catégories'
                ],
                [
                    'icon' => 'fa-home',
                    'text' => 'Accès rapide au tableau de bord'
                ]
            ],
            'instructions' => [
                'Cliquez sur une section pour y accéder',
                'Utilisez les sous-menus pour plus de détails',
                'Le menu se réduit automatiquement sur mobile'
            ]
        ],
        [
            'element' => '#search-functionality',
            'title' => 'Recherche Avancée',
            'description' => 'Trouvez rapidement les informations dont vous avez besoin avec notre système de recherche intelligent.',
            'popupPosition' => 'bottom',
            'details' => [
                [
                    'icon' => 'fa-search',
                    'text' => 'Recherche en temps réel'
                ],
                [
                    'icon' => 'fa-filter',
                    'text' => 'Filtres multiples disponibles'
                ],
                [
                    'icon' => 'fa-history',
                    'text' => 'Historique des recherches'
                ]
            ],
            'instructions' => [
                'Tapez vos mots-clés dans la barre de recherche',
                'Utilisez les filtres pour affiner les résultats',
                'Sauvegardez vos recherches favorites'
            ]
        ],
        [
            'element' => '#export-functionality',
            'title' => 'Export de Données',
            'description' => 'Exportez vos données dans différents formats pour les utiliser dans d\'autres applications.',
            'popupPosition' => 'top',
            'details' => [
                [
                    'icon' => 'fa-file-excel',
                    'text' => 'Export Excel (.xlsx)'
                ],
                [
                    'icon' => 'fa-file-pdf',
                    'text' => 'Export PDF avec mise en forme'
                ],
                [
                    'icon' => 'fa-file-csv',
                    'text' => 'Export CSV pour analyse'
                ]
            ],
            'instructions' => [
                'Sélectionnez les données à exporter',
                'Choisissez le format d\'export souhaité',
                'Cliquez sur Exporter et téléchargez le fichier'
            ]
        ],
        [
            'element' => '#map-functionality',
            'title' => 'Cartographie Interactive',
            'description' => 'Visualisez vos données géographiques sur une carte interactive avec Leaflet.js.',
            'popupPosition' => 'bottom',
            'details' => [
                [
                    'icon' => 'fa-map-marker-alt',
                    'text' => 'Marqueurs personnalisables'
                ],
                [
                    'icon' => 'fa-layers',
                    'text' => 'Couches de cartes multiples'
                ],
                [
                    'icon' => 'fa-crosshairs',
                    'text' => 'Sélection de coordonnées'
                ]
            ],
            'instructions' => [
                'Cliquez sur la carte pour ajouter des marqueurs',
                'Utilisez les contrôles pour zoomer et naviguer',
                'Activez/désactivez les différentes couches'
            ]
        ],
        [
            'element' => '#reports-functionality',
            'title' => 'Rapports et Statistiques',
            'description' => 'Générez des rapports détaillés et des graphiques pour analyser vos données.',
            'popupPosition' => 'top',
            'details' => [
                [
                    'icon' => 'fa-chart-line',
                    'text' => 'Graphiques interactifs'
                ],
                [
                    'icon' => 'fa-table',
                    'text' => 'Tableaux de données'
                ],
                [
                    'icon' => 'fa-calendar',
                    'text' => 'Périodes personnalisables'
                ]
            ],
            'instructions' => [
                'Sélectionnez la période d\'analyse',
                'Choisissez les types de graphiques',
                'Personnalisez les paramètres d\'affichage'
            ]
        ],
        [
            'element' => '#filters-functionality',
            'title' => 'Système de Filtres',
            'description' => 'Affinez vos recherches avec notre système de filtres avancé et flexible.',
            'popupPosition' => 'left',
            'details' => [
                [
                    'icon' => 'fa-sliders-h',
                    'text' => 'Filtres multiples combinables'
                ],
                [
                    'icon' => 'fa-save',
                    'text' => 'Sauvegarde des filtres'
                ],
                [
                    'icon' => 'fa-undo',
                    'text' => 'Réinitialisation facile'
                ]
            ],
            'instructions' => [
                'Sélectionnez les critères de filtrage',
                'Combinez plusieurs filtres ensemble',
                'Sauvegardez vos filtres pour une utilisation future'
            ]
        ],
        [
            'element' => '#notifications-functionality',
            'title' => 'Système de Notifications',
            'description' => 'Restez informé des événements importants et des mises à jour de l\'application.',
            'popupPosition' => 'right',
            'details' => [
                [
                    'icon' => 'fa-bell',
                    'text' => 'Notifications en temps réel'
                ],
                [
                    'icon' => 'fa-envelope',
                    'text' => 'Notifications par email'
                ],
                [
                    'icon' => 'fa-cog',
                    'text' => 'Préférences personnalisables'
                ]
            ],
            'instructions' => [
                'Cliquez sur l\'icône de cloche pour voir les notifications',
                'Configurez vos préférences de notification',
                'Marquez les notifications comme lues'
            ]
        ]
    ]"
    theme="forest"
    :autoStart="false"
    :showHelpButton="true"
/>
@endsection

@push('scripts')
<script>
    // Listen for tour completion events
    document.addEventListener('functionalityTourCompleted', function(event) {
        console.log('Functionality tour completed:', event.detail);
        
        // Show completion message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            <strong>Tour terminé!</strong> Vous avez découvert ${event.detail.functionalities} fonctionnalités de l'application.
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
    
    // Debug info
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Functionality tour demo page loaded');
        console.log('Available tours:', Object.keys(window.functionalityTours || {}));
    });
</script>
@endpush
