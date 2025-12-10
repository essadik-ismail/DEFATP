@props(['show' => true, 'autoStart' => false])

@if($show)
<div id="dashboard-guide" 
     x-data="{
         currentStep: 0,
         totalSteps: 7,
         isActive: {{ $autoStart ? 'true' : 'false' }},
         steps: [
             {
                 element: '.guide-actions-header',
                 title: 'Actions Rapides',
                 description: 'Cette section vous donne un accès rapide aux fonctionnalités principales de l\'application. Utilisez ces raccourcis pour naviguer efficacement dans le système.',
                 position: 'bottom'
             },
             {
                 element: '.guide-action-nouvel-article',
                 title: 'Nouvel Article',
                 description: 'Créez un nouvel article forestier rapidement. Cette fonctionnalité vous permet d\'enregistrer de nouveaux articles avec toutes les informations nécessaires.',
                 position: 'bottom'
             },
             {
                 element: '.guide-action-import-export',
                 title: 'Import/Export',
                 description: 'Gérez vos données avec Excel. Importez des données en masse ou exportez vos informations pour un traitement externe. Parfait pour les opérations de grande envergure.',
                 position: 'bottom'
             },
             {
                 element: '.guide-action-rapports',
                 title: 'Rapports',
                 description: 'Générez des rapports détaillés sur vos activités forestières. Analysez les données, visualisez les statistiques et créez des rapports personnalisés.',
                 position: 'bottom'
             },
             {
                 element: '.guide-action-parametres',
                 title: 'Paramètres',
                 description: 'Configurez les données de base du système. Gérez les essences, forêts, localisations et autres paramètres essentiels pour votre gestion forestière.',
                 position: 'bottom'
             },
             {
                 element: '.guide-action-utilisateurs',
                 title: 'Utilisateurs',
                 description: 'Gérez les comptes utilisateurs et leurs permissions. Créez, modifiez ou supprimez des utilisateurs et assignez les rôles appropriés.',
                 position: 'bottom'
             },
             {
                 element: '.guide-action-rechercher',
                 title: 'Rechercher',
                 description: 'Trouvez rapidement vos articles. Utilisez la fonction de recherche pour filtrer et localiser les articles selon différents critères.',
                 position: 'bottom'
             }
         ],
         init() {
             if (this.isActive) {
                 this.$nextTick(() => this.startGuide());
             }
         },
         startGuide() {
             this.isActive = true;
             this.currentStep = 0;
             this.$nextTick(() => this.highlightStep());
         },
         nextStep() {
             if (this.currentStep < this.totalSteps - 1) {
                 this.currentStep++;
                 this.highlightStep();
             } else {
                 this.endGuide();
             }
         },
         prevStep() {
             if (this.currentStep > 0) {
                 this.currentStep--;
                 this.highlightStep();
             }
         },
         highlightStep() {
             const step = this.steps[this.currentStep];
             if (!step || !step.element) return;
             
             const element = document.querySelector(step.element);
             if (element) {
                 this.positionSpotlight(element);
                 this.positionTooltip(element, step);
                 element.scrollIntoView({ behavior: 'smooth', block: 'center' });
             }
         },
         positionSpotlight(element) {
             const rect = element.getBoundingClientRect();
             const spotlight = document.getElementById('guide-spotlight');
             if (spotlight) {
                 const padding = 10;
                 // Fixed positioning is relative to viewport, so use getBoundingClientRect directly
                 spotlight.style.left = (rect.left - padding) + 'px';
                 spotlight.style.top = (rect.top - padding) + 'px';
                 spotlight.style.width = (rect.width + padding * 2) + 'px';
                 spotlight.style.height = (rect.height + padding * 2) + 'px';
                 spotlight.style.display = 'block';
             }
         },
         positionTooltip(element, step) {
             const tooltip = document.getElementById('guide-tooltip');
             if (!tooltip) return;
             
             const rect = element.getBoundingClientRect();
             const position = step.position || 'right';
             
             tooltip.style.display = 'block';
             
             // Reset transforms and positions
             tooltip.style.transform = '';
             tooltip.style.top = '';
             tooltip.style.bottom = '';
             tooltip.style.left = '';
             tooltip.style.right = '';
             
             // Small delay to get accurate tooltip dimensions
             setTimeout(() => {
                 const tooltipRect = tooltip.getBoundingClientRect();
                 const viewportWidth = window.innerWidth;
                 const viewportHeight = window.innerHeight;
                 
                 // Determine best position based on available space
                 const spaceRight = viewportWidth - rect.right;
                 const spaceLeft = rect.left;
                 
                 // Prefer right side if there's enough space, otherwise use left or bottom
                 if (spaceRight >= 400 || (spaceRight >= 300 && spaceRight > spaceLeft)) {
                     // Position to the right
                     let left = rect.right + 20;
                     let top = rect.top;
                     
                     // Center vertically if possible
                     if (top + tooltipRect.height > viewportHeight - 20) {
                         top = viewportHeight - tooltipRect.height - 20;
                     }
                     if (top < 20) {
                         top = 20;
                     }
                     
                     tooltip.style.left = left + 'px';
                     tooltip.style.top = top + 'px';
                     tooltip.style.transform = '';
                     tooltip.classList.remove('tooltip-bottom', 'tooltip-left', 'tooltip-top');
                     tooltip.classList.add('tooltip-right');
                 } else if (spaceLeft >= 400) {
                     // Position to the left
                     let right = viewportWidth - rect.left + 20;
                     let top = rect.top;
                     
                     if (top + tooltipRect.height > viewportHeight - 20) {
                         top = viewportHeight - tooltipRect.height - 20;
                     }
                     if (top < 20) {
                         top = 20;
                     }
                     
                     tooltip.style.right = right + 'px';
                     tooltip.style.top = top + 'px';
                     tooltip.style.transform = '';
                     tooltip.classList.remove('tooltip-bottom', 'tooltip-right', 'tooltip-top');
                     tooltip.classList.add('tooltip-left');
                 } else {
                     // Position below
                     let top = rect.bottom + 20;
                     let left = rect.left + (rect.width / 2);
                     
                     // Adjust if tooltip goes off screen
                     if (left + (tooltipRect.width / 2) > viewportWidth - 20) {
                         left = viewportWidth - (tooltipRect.width / 2) - 20;
                     }
                     if (left - (tooltipRect.width / 2) < 20) {
                         left = (tooltipRect.width / 2) + 20;
                     }
                     
                     // Check if there's enough space below
                     if (top + tooltipRect.height > viewportHeight - 20) {
                         // Position above instead
                         top = rect.top - tooltipRect.height - 20;
                         tooltip.classList.remove('tooltip-bottom', 'tooltip-right', 'tooltip-left');
                         tooltip.classList.add('tooltip-top');
                     } else {
                         tooltip.classList.remove('tooltip-top', 'tooltip-right', 'tooltip-left');
                         tooltip.classList.add('tooltip-bottom');
                     }
                     
                     tooltip.style.top = top + 'px';
                     tooltip.style.left = left + 'px';
                     tooltip.style.transform = 'translateX(-50%)';
                 }
             }, 10);
         },
         endGuide() {
             this.isActive = false;
             document.getElementById('guide-spotlight').style.display = 'none';
             document.getElementById('guide-tooltip').style.display = 'none';
         },
         skipGuide() {
             this.endGuide();
         }
     }"
     class="dashboard-guide-wrapper"
     :class="{ 'guide-active': isActive }">
    
    <!-- Overlay -->
    <div x-show="isActive" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="guide-overlay fixed inset-0 bg-black/70 backdrop-blur-sm z-40"
         @click="skipGuide()"></div>
    
    <!-- Spotlight -->
    <div id="guide-spotlight" 
         class="guide-spotlight fixed z-50 pointer-events-none"
         style="display: none;"></div>
    
    <!-- Tooltip -->
    <div id="guide-tooltip" 
         x-show="isActive"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="guide-tooltip fixed z-[60] bg-white rounded-xl shadow-2xl p-6 w-80"
         style="display: none;">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1 pr-4">
                <h3 class="text-lg font-bold text-gray-900 mb-3" x-text="steps[currentStep]?.title"></h3>
                <p class="text-gray-600 text-sm leading-relaxed" x-text="steps[currentStep]?.description"></p>
            </div>
            <button @click="skipGuide()" 
                    class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors"
                    title="Fermer le guide">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
            <div class="text-sm font-medium text-gray-600">
                Étape <span x-text="currentStep + 1"></span> sur <span x-text="totalSteps"></span>
            </div>
            <div class="flex gap-2">
                <button @click="prevStep()" 
                        :disabled="currentStep === 0"
                        :class="currentStep === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200'"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Précédent</span>
                </button>
                <button @click="nextStep()" 
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-medium transition-all flex items-center gap-2 shadow-md">
                    <span x-text="currentStep === totalSteps - 1 ? 'Terminer' : 'Suivant'"></span>
                    <i class="fas fa-arrow-right" x-show="currentStep !== totalSteps - 1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Start Guide Button (floating) -->
    <div x-show="!isActive" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="fixed bottom-8 right-8 z-30">
        <button @click="startGuide()" 
                class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center gap-2">
            <i class="fas fa-play"></i>
            <span>Commencer le Guide</span>
        </button>
    </div>
</div>

@push('styles')
<style>
    .dashboard-guide-wrapper {
        position: relative;
    }
    
    .guide-overlay {
        pointer-events: auto;
    }
    
    .guide-spotlight {
        border-radius: 12px;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.7);
        border: 3px solid #10b981;
        animation: guidePulse 2s infinite;
        pointer-events: none;
        z-index: 50;
    }
    
    .guide-spotlight::before {
        content: '';
        position: absolute;
        top: -6px;
        left: -6px;
        right: -6px;
        bottom: -6px;
        border: 2px solid rgba(16, 185, 129, 0.5);
        border-radius: 16px;
        animation: guideGlow 1.5s infinite alternate;
    }
    
    @keyframes guidePulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.8), 0 0 0 9999px rgba(0, 0, 0, 0.7);
            transform: scale(1);
        }
        50% {
            box-shadow: 0 0 0 10px rgba(16, 185, 129, 0.2), 0 0 0 9999px rgba(0, 0, 0, 0.7);
            transform: scale(1.01);
        }
    }
    
    @keyframes guideGlow {
        0% {
            opacity: 0.5;
        }
        100% {
            opacity: 1;
        }
    }
    
    .guide-tooltip {
        pointer-events: auto;
        max-width: 400px;
    }
    
    /* Arrow for tooltip positioned at bottom */
    .guide-tooltip.tooltip-bottom::before {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        border: 10px solid transparent;
        border-bottom-color: white;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-bottom: -1px;
    }
    
    /* Arrow for tooltip positioned at top */
    .guide-tooltip.tooltip-top::before {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        border: 10px solid transparent;
        border-top-color: white;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-top: -1px;
    }
    
    /* Arrow for tooltip positioned at right */
    .guide-tooltip.tooltip-right::before {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        border: 10px solid transparent;
        border-right-color: white;
        right: 100%;
        top: 24px;
        margin-right: -1px;
    }
    
    /* Arrow for tooltip positioned at left */
    .guide-tooltip.tooltip-left::before {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        border: 10px solid transparent;
        border-left-color: white;
        left: 100%;
        top: 24px;
        margin-left: -1px;
    }
</style>
@endpush
@endif
