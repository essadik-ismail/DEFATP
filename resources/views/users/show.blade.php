@extends('layouts.app')

@section('title', 'Détails de l\'Utilisateur')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user text-primary me-2"></i>
                Détails de l'Utilisateur
            </h1>
            <p class="text-muted">Informations détaillées de {{ $user->name }}</p>
        </div>
        <div class="d-flex gap-2">
            @can('edit users')
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            @endcan
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- User Information Cards -->
            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-id-card me-2"></i>Informations Personnelles
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Nom complet</label>
                                <p class="h6 mb-0">{{ $user->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <p class="h6 mb-0">
                                    <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                        {{ $user->email }}
                                    </a>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">PPR</label>
                                <p class="h6 mb-0">
                                    <span class="badge bg-info">{{ $user->ppr }}</span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Statut</label>
                                <p class="h6 mb-0">
                                    @if($user->is_deleted)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Inactif
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Actif
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-success">
                                <i class="fas fa-user-shield me-2"></i>Informations du Compte
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">ID Utilisateur</label>
                                <p class="h6 mb-0">
                                    <span class="badge bg-secondary">{{ $user->id }}</span>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted">Membre depuis</label>
                                <p class="h6 mb-0">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            
                            @if($user->updated_at != $user->created_at)
                            <div class="mb-3">
                                <label class="form-label text-muted">Dernière modification</label>
                                <p class="h6 mb-0">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            @endif
                            
                            @if($user->email_verified_at)
                            <div class="mb-3">
                                <label class="form-label text-muted">Email vérifié</label>
                                <p class="h6 mb-0">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>{{ $user->email_verified_at->format('d/m/Y à H:i') }}
                                    </span>
                                </p>
                            </div>
                            @else
                            <div class="mb-3">
                                <label class="form-label text-muted">Email vérifié</label>
                                <p class="h6 mb-0">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Non vérifié
                                    </span>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles and Permissions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-shield-alt me-2"></i>Rôles et Permissions
                    </h6>
                    @can('users.edit')
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#assignRolesPermissionsModal">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </button>
                    @endcan
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        <div class="row">
                            @foreach($user->roles as $role)
                            <div class="col-md-6 mb-3">
                                <div class="card border-left-warning">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="card-title mb-0">
                                                <span class="badge bg-warning text-dark">{{ $role->name }}</span>
                                            </h6>
                                            <small class="text-muted">{{ $role->permissions->count() }} permissions</small>
                                        </div>
                                        
                                        @if($role->permissions->count() > 0)
                                        <div class="permissions-list">
                                            @foreach($role->permissions->take(5) as $permission)
                                                <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                                            @endforeach
                                            @if($role->permissions->count() > 5)
                                                <span class="badge bg-secondary">+{{ $role->permissions->count() - 5 }} autres</span>
                                            @endif
                                        </div>
                                        @else
                                        <p class="text-muted small mb-0">Aucune permission spécifique</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun rôle assigné à cet utilisateur</p>
                        </div>
                    @endif
                    
                    @php
                        $directPermissions = $user->getDirectPermissions();
                    @endphp
                    @if($directPermissions->count() > 0)
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="mb-3">
                                <i class="fas fa-key me-2"></i>Permissions Directes
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($directPermissions as $permission)
                                    <span class="badge bg-info">{{ $permission->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Log (if available) -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-history me-2"></i>Activité Récente
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Compte créé</h6>
                                <p class="timeline-text">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($user->updated_at != $user->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info">
                                <i class="fas fa-user-edit text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Dernière modification</h6>
                                <p class="timeline-text">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($user->is_deleted)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger">
                                <i class="fas fa-user-times text-white"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Compte désactivé</h6>
                                <p class="timeline-text">Statut actuel: Inactif</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Profile Image Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-image me-2"></i>Photo de Profil
                    </h6>
                </div>
                <div class="card-body text-center">
                    @if($user->image)
                        <img src="{{ asset('storage/' . $user->image) }}" 
                             alt="{{ $user->name }}" 
                             class="img-fluid rounded-circle mb-3" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                        <p class="text-muted small">{{ $user->image }}</p>
                    @else
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 150px; height: 150px;">
                            <span class="text-white fw-bold" style="font-size: 3rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <p class="text-muted small">Aucune image de profil</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-bolt me-2"></i>Actions Rapides
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('edit users')
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Modifier l'utilisateur
                        </a>
                        
                        <button type="button" class="btn btn-outline-{{ $user->is_deleted ? 'success' : 'warning' }}" 
                                onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_deleted ? 'false' : 'true' }})">
                            @if($user->is_deleted)
                                <i class="fas fa-user-check me-2"></i>Activer l'utilisateur
                            @else
                                <i class="fas fa-user-times me-2"></i>Désactiver l'utilisateur
                            @endif
                        </button>
                        @endcan
                        
                        @can('delete users')
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
                                <i class="fas fa-trash me-2"></i>Supprimer l'utilisateur
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- System Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-cog me-2"></i>Informations Système
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Type de compte</label>
                        <p class="h6 mb-0">
                            @if($user->hasRole('Super Admin'))
                                <span class="badge bg-danger">Super Administrateur</span>
                            @elseif($user->hasRole('Admin'))
                                <span class="badge bg-warning text-dark">Administrateur</span>
                            @elseif($user->hasRole('Manager'))
                                <span class="badge bg-info">Manager</span>
                            @elseif($user->hasRole('Operator'))
                                <span class="badge bg-primary">Opérateur</span>
                            @elseif($user->hasRole('Viewer'))
                                <span class="badge bg-secondary">Lecteur</span>
                            @else
                                <span class="badge bg-light text-dark">Utilisateur standard</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Permissions totales</label>
                        <p class="h6 mb-0">
                            @php
                                $totalPermissions = $user->getAllPermissions()->count();
                            @endphp
                            <span class="badge bg-success">{{ $totalPermissions }}</span>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Dernière connexion</label>
                        <p class="h6 mb-0">
                            @if($user->last_login_at)
                                {{ \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y à H:i') }}
                            @else
                                <span class="text-muted">Jamais connecté</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleStatusModalLabel">Confirmer le changement de statut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir changer le statut de cet utilisateur ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmToggleStatus">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<!-- Assign Roles and Permissions Modal -->
<div class="modal fade" id="assignRolesPermissionsModal" tabindex="-1" aria-labelledby="assignRolesPermissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('users.assign-roles-permissions', $user) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="assignRolesPermissionsModalLabel">
                        <i class="fas fa-shield-alt me-2"></i>Assigner Rôles et Permissions
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Roles Section -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-user-tag me-2 text-warning"></i>Rôles
                        </h6>
                        <div class="form-group">
                            <select class="form-select" id="roles" name="roles[]" multiple size="5">
                                @foreach($allRoles as $role)
                                    <option value="{{ $role->name }}" 
                                        {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ $role->name }} 
                                        <small class="text-muted">({{ $role->permissions->count() }} permissions)</small>
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs rôles</small>
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div>
                        <h6 class="mb-3">
                            <i class="fas fa-key me-2 text-info"></i>Permissions Directes
                        </h6>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Les permissions directes sont en plus des permissions des rôles assignés.
                        </div>
                        
                        <div class="accordion" id="permissionsAccordion">
                            @foreach($allPermissions as $module => $permissions)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" 
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                                aria-controls="collapse{{ $loop->index }}">
                                            <strong>{{ ucfirst(str_replace('-', ' ', $module)) }}</strong>
                                            <span class="badge bg-secondary ms-2">{{ $permissions->count() }}</span>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $loop->index }}" 
                                         class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                                         aria-labelledby="heading{{ $loop->index }}" 
                                         data-bs-parent="#permissionsAccordion">
                                        <div class="accordion-body">
                                            <div class="row">
                                                @foreach($permissions as $permission)
                                                    <div class="col-md-6 mb-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->name }}" 
                                                                   id="permission_{{ $permission->id }}"
                                                                   {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleUserStatus(userId, newStatus) {
    const modal = new bootstrap.Modal(document.getElementById('toggleStatusModal'));
    const confirmBtn = document.getElementById('confirmToggleStatus');
    
    confirmBtn.onclick = function() {
        fetch(`/admin/users/${userId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors du changement de statut');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors du changement de statut');
        });
        
        modal.hide();
    };
    
    modal.show();
}
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

.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5em 0.75em;
}

.btn {
    border-radius: 0.35rem;
    font-weight: 500;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-title {
    margin: 0 0 5px 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: #495057;
}

.timeline-text {
    margin: 0;
    font-size: 0.8rem;
    color: #6c757d;
}

.permissions-list {
    max-height: 100px;
    overflow-y: auto;
}

.permissions-list::-webkit-scrollbar {
    width: 4px;
}

.permissions-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.permissions-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.permissions-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endpush
