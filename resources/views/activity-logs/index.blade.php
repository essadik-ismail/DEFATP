@extends('layouts.app')

@section('title', 'Journal d\'Activités')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-history text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">Journal d'Activités</h1>
                        <p class="text-gray-600 text-lg mt-2">Suivi des actions des utilisateurs dans le système</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('activity-logs.export') }}" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-download"></i>
                        Exporter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <!-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-card 
            title="Total Activités" 
            subtitle="Toutes les activités enregistrées"
            variant="gradient"
            color="purple"
            icon="fas fa-history"
            padding="compact"
        >
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600" id="totalActivities">-</div>
                <div class="text-sm text-gray-600 mt-1">Activités totales</div>
            </div>
        </x-card>

        <x-card 
            title="Aujourd'hui" 
            subtitle="Activités d'aujourd'hui"
            variant="colored"
            color="green"
            icon="fas fa-calendar-day"
            padding="compact"
        >
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600" id="todayActivities">-</div>
                <div class="text-sm text-gray-600 mt-1">Activités du jour</div>
            </div>
        </x-card>

        <x-card 
            title="Cette Semaine" 
            subtitle="Activités de cette semaine"
            variant="gradient"
            color="blue"
            icon="fas fa-calendar-week"
            padding="compact"
        >
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600" id="weekActivities">-</div>
                <div class="text-sm text-gray-600 mt-1">Activités hebdomadaires</div>
            </div>
        </x-card>

        <x-card 
            title="Ce Mois" 
            subtitle="Activités de ce mois"
            variant="colored"
            color="orange"
            icon="fas fa-calendar-alt"
            padding="compact"
        >
            <div class="text-center">
                <div class="text-3xl font-bold text-orange-600" id="monthActivities">-</div>
                <div class="text-sm text-gray-600 mt-1">Activités mensuelles</div>
            </div>
        </x-card>
    </div> -->

    <!-- Search and Filters -->
    <x-card 
        title="Recherche et Filtres" 
        subtitle="Filtrez et recherchez parmi les activités"
        variant="colored"
        color="purple"
        icon="fas fa-search"
        padding="normal"
    >
        <form id="activityLogsFilter" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search text-purple-500 mr-2"></i>
                    Recherche
                </label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    placeholder="Description..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                >
            </div>
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user text-purple-500 mr-2"></i>
                    Utilisateur
                </label>
                <select 
                    id="user_id" 
                    name="user_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                >
                    <option value="">Tous les utilisateurs</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="action" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-cog text-purple-500 mr-2"></i>
                    Action
                </label>
                <select 
                    id="action" 
                    name="action"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                >
                    <option value="">Toutes les actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}">{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="model_type" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-database text-purple-500 mr-2"></i>
                    Type de modèle
                </label>
                <select 
                    id="model_type" 
                    name="model_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                >
                    <option value="">Tous les types</option>
                    @foreach($modelTypes as $modelType)
                        <option value="{{ $modelType }}">{{ class_basename($modelType) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_range" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar text-purple-500 mr-2"></i>
                    Période
                </label>
                <div class="flex gap-2">
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                    >
                    <span class="flex items-center text-gray-500">à</span>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                    >
                </div>
            </div>
            <div class="md:col-span-2 lg:col-span-5 flex gap-3">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                >
                    <i class="fas fa-search"></i>
                    Filtrer
                </button>
                <button 
                    type="button" 
                    onclick="resetFilters()"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center gap-2"
                >
                    <i class="fas fa-undo"></i>
                    Réinitialiser
                </button>
            </div>
        </form>
    </x-card>

    <!-- Activity Logs Table -->
    <x-card 
        title="Journal d'Activités" 
        subtitle="<span id='totalCount'>0 activités</span> trouvées"
        variant="gradient"
        color="purple"
        icon="fas fa-table"
        padding="normal"
    >
        <div id="activityLogsTable">
            @include('activity-logs.partials.activity-logs-table', ['activityLogs' => $activityLogs])
        </div>
        
        <div id="activityLogsPagination">
            @include('activity-logs.partials.pagination', ['activityLogs' => $activityLogs])
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
<script>
// Load statistics on page load
document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
});

// Filter form submission
document.getElementById('activityLogsFilter').addEventListener('submit', function(e) {
    e.preventDefault();
    loadActivityLogs();
});

// Load activity logs with filters
function loadActivityLogs() {
    const formData = new FormData(document.getElementById('activityLogsFilter'));
    const params = new URLSearchParams(formData);
    
    fetch(`{{ route('activity-logs.ajax-logs') }}?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('activityLogsTable').innerHTML = data.html;
            document.getElementById('activityLogsPagination').innerHTML = data.pagination;
            
            // Update total count
            const totalCount = document.querySelector('.pagination-info .text-muted');
            if (totalCount) {
                document.getElementById('totalCount').textContent = totalCount.textContent;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Load statistics
function loadStatistics() {
    fetch('{{ route("activity-logs.statistics") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalActivities').textContent = data.total_activities;
            document.getElementById('todayActivities').textContent = data.today_activities;
            document.getElementById('weekActivities').textContent = data.this_week_activities;
            document.getElementById('monthActivities').textContent = data.this_month_activities;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Reset filters
function resetFilters() {
    document.getElementById('activityLogsFilter').reset();
    loadActivityLogs();
}

// Auto-refresh every 30 seconds
setInterval(loadStatistics, 30000);
</script>
@endpush

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e3e6f0;
}

.border-left-primary {
    border-left: 4px solid #007bff !important;
}

.border-left-success {
    border-left: 4px solid #28a745 !important;
}

.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}

.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.btn {
    border-radius: 0.35rem;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-1px);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
    border: none;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #545b62 0%, #3d4449 100%);
    transform: translateY(-1px);
}
</style>
@endpush
