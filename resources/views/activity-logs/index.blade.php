@extends('layouts.app')

@section('title', 'Journal d\'Activités')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-history text-primary me-2"></i>
                Journal d'Activités
            </h1>
            <p class="text-muted">Suivi des actions des utilisateurs dans le système</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('activity-logs.export') }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Exporter
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Activités
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalActivities">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-history fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Aujourd'hui
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="todayActivities">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Cette Semaine
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="weekActivities">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Ce Mois
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="monthActivities">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search me-2"></i>Recherche et Filtres
            </h6>
        </div>
        <div class="card-body">
            <form id="activityLogsFilter" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Description...">
                </div>
                <div class="col-md-2">
                    <label for="user_id" class="form-label">Utilisateur</label>
                    <select class="form-select" id="user_id" name="user_id">
                        <option value="">Tous les utilisateurs</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="action" class="form-label">Action</label>
                    <select class="form-select" id="action" name="action">
                        <option value="">Toutes les actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}">{{ ucfirst($action) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="model_type" class="form-label">Type de modèle</label>
                    <select class="form-select" id="model_type" name="model_type">
                        <option value="">Tous les types</option>
                        @foreach($modelTypes as $modelType)
                            <option value="{{ $modelType }}">{{ class_basename($modelType) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_range" class="form-label">Période</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="date_from" name="date_from">
                        <span class="input-group-text">à</span>
                        <input type="date" class="form-control" id="date_to" name="date_to">
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetFilters()">
                        <i class="fas fa-undo me-2"></i>Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Journal d'Activités
            </h6>
            <div class="d-flex gap-2">
                <span class="badge bg-primary" id="totalCount">0 activités</span>
            </div>
        </div>
        <div class="card-body">
            <div id="activityLogsTable">
                @include('activity-logs.partials.activity-logs-table', ['activityLogs' => $activityLogs])
            </div>
            
            <div id="activityLogsPagination">
                @include('activity-logs.partials.pagination', ['activityLogs' => $activityLogs])
            </div>
        </div>
    </div>
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
