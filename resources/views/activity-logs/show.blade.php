@extends('layouts.app')

@section('title', 'Détails de l\'Activité')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('activity-logs.index') }}">Journal d'activités</a></li>
<li class="breadcrumb-item active">Détail</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye text-primary me-2"></i>
                Détails de l'Activité
            </h1>
            <p class="text-muted">Informations détaillées sur cette action</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Activity Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informations de l'Activité
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Action</label>
                                <div>
                                    <span class="badge bg-{{ $activityLog->action_color }} rounded-pill fs-6">
                                        <i class="{{ $activityLog->action_icon }} me-2"></i>
                                        {{ ucfirst($activityLog->action) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Date et Heure</label>
                                <div class="fs-6">
                                    {{ $activityLog->formatted_date }}
                                    <br>
                                    <small class="text-muted">{{ $activityLog->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Description</label>
                        <div class="p-3 bg-light rounded">
                            {{ $activityLog->description }}
                        </div>
                    </div>

                    @if($activityLog->model_type && $activityLog->model_id)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Modèle Affecté</label>
                            <div>
                                <span class="badge bg-info rounded-pill fs-6">
                                    <i class="fas fa-cube me-2"></i>
                                    {{ class_basename($activityLog->model_type) }} #{{ $activityLog->model_id }}
                                </span>
                            </div>
                        </div>
                    @endif

                    @if($activityLog->properties)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Propriétés</label>
                            <div class="p-3 bg-light rounded">
                                <pre class="mb-0"><code>{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User and Technical Details -->
        <div class="col-lg-4">
            <!-- User Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Utilisateur
                    </h6>
                </div>
                <div class="card-body">
                    @if($activityLog->user)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-lg me-3">
                                @if($activityLog->user->image)
                                    <img src="{{ asset('storage/' . $activityLog->user->image) }}" 
                                         alt="{{ $activityLog->user->name }}" 
                                         class="rounded-circle" 
                                         width="64" height="64">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 64px; height: 64px;">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $activityLog->user->name }}</h6>
                                <p class="text-muted mb-0">{{ $activityLog->user->email }}</p>
                                <small class="text-muted">PPR: {{ $activityLog->user->ppr }}</small>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <a href="{{ route('users.show', $activityLog->user) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-user me-2"></i>Voir le profil
                            </a>
                            <a href="{{ route('activity-logs.user-activity', $activityLog->user) }}" 
                               class="btn btn-outline-info btn-sm mt-2">
                                <i class="fas fa-history me-2"></i>Voir les activités
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                            <p class="mb-0">Utilisateur supprimé</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Technical Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>Détails Techniques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Adresse IP</label>
                        <div>
                            <code class="bg-light p-2 rounded">{{ $activityLog->ip_address ?? 'N/A' }}</code>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Méthode HTTP</label>
                        <div>
                            <span class="badge bg-secondary">{{ $activityLog->method ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">URL</label>
                        <div class="text-break">
                            <code class="bg-light p-2 rounded d-block">{{ $activityLog->url ?? 'N/A' }}</code>
                        </div>
                    </div>

                    @if($activityLog->user_agent)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">User Agent</label>
                            <div class="text-break">
                                <code class="bg-light p-2 rounded d-block small">{{ $activityLog->user_agent }}</code>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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

.avatar-lg {
    flex-shrink: 0;
}

pre {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 1rem;
    font-size: 0.875rem;
    line-height: 1.5;
    overflow-x: auto;
}

code {
    font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    font-size: 0.875em;
}

.btn {
    border-radius: 0.35rem;
    font-weight: 500;
}

.btn-outline-primary:hover,
.btn-outline-info:hover {
    transform: translateY(-1px);
}
</style>
@endpush
