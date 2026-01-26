@extends('layouts.app')

@section('title', 'Activités de ' . $user->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('activity-logs.index') }}">Journal d'activités</a></li>
<li class="breadcrumb-item active">Activités utilisateur</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-clock text-primary me-2"></i>
                Activités de {{ $user->name }}
            </h1>
            <p class="text-muted">Historique des actions de cet utilisateur</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour au journal
            </a>
            <a href="{{ route('users.show', $user) }}" class="btn btn-outline-primary">
                <i class="fas fa-user me-2"></i>Voir le profil
            </a>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <div class="text-center">
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle" 
                                 width="80" height="80">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-1">{{ $user->email }}</p>
                    <p class="text-muted mb-0">PPR: {{ $user->ppr }}</p>
                </div>
                <div class="col-md-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4 mb-0 text-primary">{{ $user->activityLogs()->count() }}</div>
                            <small class="text-muted">Total Activités</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 text-success">{{ $user->activityLogs()->whereDate('created_at', today())->count() }}</div>
                            <small class="text-muted">Aujourd'hui</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 text-info">{{ $user->activityLogs()->whereDate('created_at', '>=', now()->subDays(7))->count() }}</div>
                            <small class="text-muted">Cette Semaine</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filtres
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="action" class="form-label">Action</label>
                    <select class="form-select" id="action" name="action">
                        <option value="">Toutes les actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">
                        Date de début
                        <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 01/01/2024)"></i>
                    </label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}"
                           placeholder="jj/mm/aaaa">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">
                        Date de fin
                        <i class="fas fa-question-circle mx-1 text-gray-400" title="Format: jj/mm/aaaa (ex: 31/12/2024)"></i>
                    </label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}"
                           placeholder="jj/mm/aaaa">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('activity-logs.user-activity', $user) }}" class="btn btn-secondary">
                        <i class="fas fa-undo me-2"></i>Réinitialiser
                    </a>
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
                <span class="badge bg-primary">{{ $activityLogs->total() }} activités</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive position-relative">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Modèle</th>
                            <th>Adresse IP</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activityLogs as $log)
                            <tr>
                                <td>
                                    <span class="badge bg-{{ $log->action_color }} rounded-pill">
                                        <i class="{{ $log->action_icon }} me-1"></i>
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-wrap" style="max-width: 300px;">
                                        {{ $log->description }}
                                    </div>
                                </td>
                                <td>
                                    @if($log->model_type && $log->model_id)
                                        <span class="badge bg-info rounded-pill">
                                            <i class="fas fa-cube me-1"></i>
                                            {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <code class="small">{{ $log->ip_address ?? '-' }}</code>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $log->formatted_date }}</span>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('activity-logs.show', $log) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p class="mb-0">Aucune activité trouvée</p>
                                        <small>Aucune activité ne correspond aux critères de recherche</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Table scroll indicator -->
                <div class="table-scroll-indicator"></div>
            </div>
            
            <!-- Pagination -->
            @if($activityLogs->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="pagination-info">
                        <p class="text-muted mb-0">
                            Affichage de {{ $activityLogs->firstItem() ?? 0 }} à {{ $activityLogs->lastItem() ?? 0 }} 
                            sur {{ $activityLogs->total() }} activités
                        </p>
                    </div>
                    
                    <nav aria-label="Navigation des pages d'activités">
                        {{ $activityLogs->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

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

.badge {
    font-size: 0.75em;
}
</style>
@endpush
