@extends('layouts.app')

@section('title', 'Tableau de Bord - DEFATP')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Welcome Guide for New Users -->
    <x-welcome-guide :show="true" />
    
    <!-- Actions Required Section -->
    @if(isset($actionsRequired) && count($actionsRequired) > 0)
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-red-500 to-pink-600">
                <i class="fas fa-exclamation-circle text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-pink-600">
                    Actions Requises
                </h2>
                <p class="text-gray-600">Éléments nécessitant votre attention</p>
            </div>
        </div>
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Priorité</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($actionsRequired as $action)
                        <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location.href='{{ $action['route'] }}'">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br {{ $action['color'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas {{ $action['icon'] }} text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $action['title'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ $action['description'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-lg font-bold bg-gradient-to-r {{ $action['color'] }} bg-clip-text text-transparent">{{ $action['count'] }}</span>
                                <span class="text-xs text-gray-500 ml-1">élément(s)</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($action['priority'] === 'high')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Urgent</span>
                                @elseif($action['priority'] === 'medium')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Moyen</span>
                                @else
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Faible</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2 text-sm font-semibold" style="color: #059669;">
                                    <span>Voir</span>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Welcome Card -->
    <!-- <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 text-center border border-white/20">
            <div class="mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto">
                    <i class="fas fa-tree text-white text-3xl"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-4">
                Bienvenue sur DEFATP
            </h1>
            <p class="text-xl text-gray-600 mb-8">Votre plateforme de gestion forestière intelligente</p>
        </div>
    </div> -->

    <!-- Date Filter Section -->
    <!-- <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">Filtre par Période</h2>
                    <p class="text-gray-600">Sélectionnez une période pour afficher les statistiques correspondantes</p>
                </div>
            </div>
            
            <form method="GET" action="{{ route('dashboard') }}" id="dateFilterForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-group">
                        <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus text-purple-500 mr-2"></i>
                            Date de Début
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                               placeholder="jj/mm/aaaa">
                    </div>
                    
                    <div class="form-group">
                        <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-minus text-purple-500 mr-2"></i>
                            Date de Fin
                            <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 31/12/2024)"></i>
                        </label>
                        <input type="date" 
                               class="form-input w-full px-4 py-3 border border-gray-300 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 hover:border-gray-400" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ request('end_date', now()->format('Y-m-d')) }}"
                               placeholder="jj/mm/aaaa">
                    </div>
                    
                    <div class="form-group flex items-end">
                        <div class="flex gap-3 w-full">
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-filter"></i>
                                <span>Filtrer</span>
                            </button>
                            <button type="button" 
                                    onclick="resetDateFilter()"
                                    class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300"
                                    title="Réinitialiser">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                @if(request('start_date') || request('end_date'))
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                        <div class="flex items-center gap-2 text-blue-700">
                            <i class="fas fa-info-circle"></i>
                            <span class="font-semibold">Période sélectionnée :</span>
                            <span>{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Début' }}</span>
                            <span>→</span>
                            <span>{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Fin' }}</span>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div> -->

    <!-- Quick Actions -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(to bottom right, #059669, #047857);">
                <i class="fas fa-bolt text-white text-xl"></i>
            </div>
            <h2 class="text-3xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">
                Actions Rapides
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('articles.create') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-plus-circle text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Nouvel Article</h3>
                <p class="text-gray-600">Créez un nouvel article forestier en quelques étapes simples</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('excel.index') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-excel text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Import/Export</h3>
                <p class="text-gray-600">Gérez vos données avec Excel pour une manipulation en masse</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('reports.index') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rapports</h3>
                <p class="text-gray-600">Générez des rapports détaillés sur vos activités forestières</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('settings.index') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cog text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Paramètres</h3>
                <p class="text-gray-600">Configurez les données de base : forêts, essences, localisations</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('auth.users.index') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users-cog text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Utilisateurs</h3>
                <p class="text-gray-600">Gérez les comptes utilisateurs et les permissions</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 text-center border border-white/20 hover:shadow-xl transition-all duration-300 transform hover:scale-105 cursor-pointer" onclick="window.location.href='{{ route('articles.index') }}'">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rechercher</h3>
                <p class="text-gray-600">Trouvez rapidement vos articles avec des filtres avancés</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers for quick action cards
    document.querySelectorAll('.quick-action-icon').forEach(icon => {
        icon.parentElement.addEventListener('click', function() {
            const action = this.querySelector('h3').textContent;
            console.log('Quick action clicked:', action);
        });
    });
    
    // Add hover effects for cards
    document.querySelectorAll('.glassmorphism-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Function to reset date filter
function resetDateFilter() {
    // Set dates to current month
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    
    document.getElementById('start_date').value = firstDay.toISOString().split('T')[0];
    document.getElementById('end_date').value = today.toISOString().split('T')[0];
    
    // Submit the form
    document.getElementById('dateFilterForm').submit();
}

// Auto-submit form when dates change
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = new Date(this.value);
    const endDate = new Date(document.getElementById('end_date').value);
    
    if (startDate > endDate) {
        document.getElementById('end_date').value = this.value;
    }
});

document.getElementById('end_date').addEventListener('change', function() {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(this.value);
    
    if (endDate < startDate) {
        document.getElementById('start_date').value = this.value;
    }
});
</script>
@endpush 