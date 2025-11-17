@props(['show' => true])

@if($show)
<div class="welcome-guide bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 border-2 border-blue-300 rounded-2xl p-8 mb-8 shadow-xl relative overflow-hidden" 
     x-data="{ show: true }" 
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95">
    
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-200/30 to-purple-200/30 rounded-full blur-3xl -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-green-200/30 to-blue-200/30 rounded-full blur-2xl -ml-24 -mb-24"></div>
    
    <div class="relative z-10">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            Bienvenue sur DEFATP !
                        </h3>
                        <p class="text-gray-600 text-sm mt-1">Votre plateforme de gestion forestière intelligente</p>
                    </div>
                </div>
                <p class="text-gray-700 mb-6 text-base leading-relaxed">
                    Découvrez les fonctionnalités essentielles pour gérer efficacement vos articles forestiers et optimiser vos opérations.
                </p>
            </div>
            
            <button @click="show = false" 
                    class="text-gray-400 hover:text-gray-600 hover:bg-white/50 rounded-lg p-2 transition-all duration-200 ml-4 flex-shrink-0">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border-2 border-amber-200 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-md">
                <i class="fas fa-lightbulb text-white"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-amber-900">
                    <span class="font-semibold">Astuce :</span> Besoin d'aide ? Cliquez sur l'icône <i class="fas fa-question-circle text-amber-600 mx-1 text-base"></i> à côté de chaque champ pour obtenir des informations détaillées.
                </p>
            </div>
        </div>
    </div>
</div>
@endif
