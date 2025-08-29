@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users text-primary me-2"></i>
                Gestion des Utilisateurs
            </h1>
            <p class="text-muted">Administration des utilisateurs et des rôles</p>
        </div>
        <div class="d-flex gap-2">
            @can('create users')
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouvel Utilisateur
            </a>
            @endcan
            @can('view users')
            <a href="{{ route('users.export') }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Exporter
            </a>
            @endcan
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
                                Total Utilisateurs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Utilisateurs Actifs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('is_deleted', false)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                Rôles Créés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $roles->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
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
                                Nouveaux (30j)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('created_at', '>=', now()->subDays(30))->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('users.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nom, email ou PPR...">
                </div>
                <div class="col-md-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">Tous les rôles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Liste des Utilisateurs
            </h6>
            <div class="d-flex gap-2">
                <span class="badge bg-primary">{{ $users->total() }} utilisateurs</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive position-relative">
                <div class="table-scroll-indicator"></div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>PPR</th>
                            <th>Rôles</th>
                            <th>Statut</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $user->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($user->image)
                                        <img src="{{ asset('storage/' . $user->image) }}" 
                                             alt="{{ $user->name }}" 
                                             class="rounded-circle me-2" 
                                             width="32" height="32">
                                    @else
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 32px; height: 32px;">
                                            <span class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $user->email }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $user->ppr }}</span>
                            </td>
                            <td>
                                @forelse($user->roles as $role)
                                    <span class="badge bg-success me-1">{{ $role->name }}</span>
                                @empty
                                    <span class="badge bg-light text-dark">Aucun rôle</span>
                                @endforelse
                            </td>
                            <td>
                                @if($user->is_deleted)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>Inactif
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Actif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $user->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Quick Actions -->
                                    @can('view users')
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('edit users')
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan

                                    <!-- Dropdown for Additional Actions -->
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @can('edit users')
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_deleted ? 'false' : 'true' }})">
                                                    @if($user->is_deleted)
                                                        <i class="fas fa-user-check text-success me-2"></i>Activer
                                                    @else
                                                        <i class="fas fa-user-times text-warning me-2"></i>Désactiver
                                                    @endif
                                                </a>
                                            </li>
                                            @endcan
                                            
                                            @can('delete users')
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                        <i class="fas fa-trash me-2"></i>Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>Aucun utilisateur trouvé</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} 
                    sur {{ $users->total() }} utilisateurs
                </div>
                <div>
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
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

// Table scroll indicator
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.table-responsive');
    const indicator = document.querySelector('.table-scroll-indicator');
    
    if (table && indicator) {
        table.addEventListener('scroll', function() {
            const scrollLeft = table.scrollLeft;
            const maxScrollLeft = table.scrollWidth - table.clientWidth;
            const scrollPercentage = (scrollLeft / maxScrollLeft) * 100;
            
            indicator.style.width = scrollPercentage + '%';
            indicator.style.opacity = scrollLeft > 0 ? '1' : '0';
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.table-scroll-indicator {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #007bff, #28a745);
    transition: width 0.3s ease, opacity 0.3s ease;
    opacity: 0;
    z-index: 1000;
}

.table-responsive {
    border-radius: 0.5rem;
    overflow: auto;
}

.table th {
    position: sticky;
    top: 0;
    background: white;
    z-index: 10;
    border-top: none;
}

.table th:first-child {
    border-left: none;
}

.table th:last-child {
    border-right: none;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    border-radius: 0.375rem;
}

.btn-group .btn:not(:last-child) {
    margin-right: 0.25rem;
}
</style>
@endpush
