@props(['show' => true])

@if($show)
<div class="welcome-guide rounded-2xl p-6 mb-8 relative overflow-hidden"
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95"
     style="background: linear-gradient(135deg, rgba(5, 150, 105, 0.06), rgba(4, 120, 87, 0.04)); border: 1px solid rgba(154, 179, 163, 0.4); box-shadow: var(--shadow-card);">
    <div class="absolute top-0 right-0 w-48 h-48 bg-emerald-200/20 rounded-full blur-3xl -mr-24 -mt-24"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-teal-200/20 rounded-full blur-2xl -ml-16 -mb-16"></div>

    <div class="relative z-10">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--primary-gradient); box-shadow: 0 2px 8px rgba(5, 150, 105, 0.25);">
                        <i class="fas fa-leaf text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Bienvenue sur DEFATP</h3>
                        <p class="text-sm text-gray-500">Votre plateforme de gestion forestière</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Gérez vos articles forestiers, cessions, contrats et exploitants de manière centralisée et efficace.
                </p>
            </div>
            <button @click="show = false"
                    class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-white/80 transition-all"
                    title="Fermer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="flex items-center gap-3 p-3 rounded-xl bg-white/80 border" style="border-color: rgba(154, 179, 163, 0.4);">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: var(--primary-gradient);">
                <i class="fas fa-lightbulb text-white text-sm"></i>
            </div>
            <p class="text-sm text-gray-700">
                <span class="font-semibold">Astuce :</span> Cliquez sur l'icône <i class="fas fa-question-circle text-emerald-600 mx-0.5"></i> à côté des champs pour obtenir de l'aide.
            </p>
        </div>
    </div>
</div>
@endif
