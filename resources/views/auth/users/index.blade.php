@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('page-actions')
    <a href="{{ route('auth.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nouvel Utilisateur
    </a>
@endsection

@section('content')
<div class="content-card">
    <div class="card-header">
        <div class="header-content">
            <h4 class="card-title">
                <i class="fas fa-users me-2"></i>
                Gestion des Utilisateurs
            </h4>
            <p class="card-subtitle">Gérez les comptes utilisateurs du système</p>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>PPR</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <span class="table-id">{{ $user->id }}</span>
                            </td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="user-details">
                                        <h6 class="user-name">{{ $user->name }}</h6>
                                        @if($user->id === auth()->id())
                                            <span class="user-badge current">
                                                <i class="fas fa-circle me-1"></i>Vous
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $user->ppr }}</span>
                            </td>
                            <td>
                                <span class="table-date">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('auth.users.edit', $user) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Supprimer"
                                                onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h4>Aucun utilisateur trouvé</h4>
                                    <p>Aucun utilisateur n'a été créé dans le système.</p>
                                    <a href="{{ route('auth.users.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Créer un utilisateur
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="userName"></strong> ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content-card {
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .card-header {
        padding: 2rem;
        border-bottom: 1px solid var(--border-color);
        background: var(--card-header-bg);
    }

    .header-content {
        text-align: center;
    }

    .card-title {
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-subtitle {
        margin: 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .card-body {
        padding: 2rem;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
        border-left: 4px solid #22c55e;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-left: 4px solid #ef4444;
    }

    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--card-bg);
    }

    .data-table th {
        background: var(--bg-secondary);
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
        border-bottom: 1px solid var(--border-color);
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .data-table tr:hover {
        background: var(--hover-bg);
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .table-id {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.875rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e293b 0%, #4a7c59 50%, #e67e22 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .user-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .user-name {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .user-badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    .user-badge.current {
        color: #22c55e;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .table-date {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
    }

    .table-actions .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-icon i {
        font-size: 2rem;
        opacity: 0.5;
    }

    .empty-state h4 {
        margin: 0 0 0.5rem 0;
        color: var(--text-primary);
        font-weight: 600;
    }

    .empty-state p {
        margin: 0 0 1.5rem 0;
        font-size: 0.875rem;
    }

    .modal-content {
        border-radius: 16px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
    }

    .modal-header {
        border-bottom: 1px solid var(--border-color);
        background: var(--card-header-bg);
        border-radius: 16px 16px 0 0;
    }

    .modal-title {
        color: var(--text-primary);
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 1rem 1.5rem;
        background: var(--bg-secondary);
        border-radius: 0 0 16px 16px;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: var(--text-secondary);
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }

    .btn-close:hover {
        opacity: 1;
    }

    @media (max-width: 768px) {
        .card-header, .card-body {
            padding: 1.5rem;
        }
        
        .table-actions {
            flex-direction: column;
        }
        
        .user-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = `/auth/users/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush 