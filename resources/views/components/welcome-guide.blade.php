@props(['show' => true])

@if($show)
<div class="welcome-guide bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6" x-data="{ show: true }" x-show="show">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <h3 class="text-lg font-medium text-blue-900 mb-2">
                <i class="fas fa-star me-2"></i>Bienvenue sur DEFATP !
            </h3>
            <p class="text-blue-700 mb-4">
                Voici un guide rapide pour vous aider à commencer avec la gestion de vos articles forestiers.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-plus-circle text-green-600 me-2"></i>
                        <span class="font-medium text-blue-900">Créer un Article</span>
                    </div>
                    <p class="text-sm text-blue-700">Ajoutez de nouveaux articles forestiers avec notre formulaire simplifié en 4 étapes.</p>
                </div>
                
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-search text-blue-600 me-2"></i>
                        <span class="font-medium text-blue-900">Rechercher</span>
                    </div>
                    <p class="text-sm text-blue-700">Trouvez rapidement vos articles avec notre système de recherche avancé.</p>
                </div>
                
                <div class="bg-white p-3 rounded-lg border border-blue-100">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-chart-bar text-purple-600 me-2"></i>
                        <span class="font-medium text-blue-900">Rapports</span>
                    </div>
                    <p class="text-sm text-blue-700">Générez des rapports détaillés sur vos activités forestières.</p>
                </div>
            </div>
            
            <div class="flex items-center text-sm text-blue-600">
                <i class="fas fa-lightbulb me-2"></i>
                <span>Besoin d'aide ? Cliquez sur l'icône <i class="fas fa-question-circle mx-1"></i> à côté de chaque champ.</span>
            </div>
        </div>
        
        <button @click="show = false" class="text-blue-400 hover:text-blue-600 ml-4">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
</div>
@endif
