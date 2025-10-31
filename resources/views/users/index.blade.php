@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Content -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg" style="background: linear-gradient(to bottom right, #059669, #047857);">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-clip-text text-transparent" style="background: linear-gradient(to right, #059669, #047857); -webkit-background-clip: text; background-clip: text;">Gestion des Utilisateurs</h1>
                        <p class="text-gray-600 text-lg mt-2">Administration des utilisateurs et des rôles</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    @can('users.create')
                    <a href="{{ route('users.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Nouvel Utilisateur
                    </a>
                    @endcan
                    @can('users.view')
                    <a href="{{ route('users.export') }}" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fas fa-download"></i>
                        Exporter
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-card 
            title="Total Utilisateurs" 
            subtitle="{{ $users->total() }} utilisateurs enregistrés"
            variant="gradient"
            color="blue"
            icon="fas fa-users"
            padding="compact"
        >
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $users->total() }}</div>
                <div class="text-sm text-gray-600 mt-1">Utilisateurs actifs</div>
            </div>
        </x-card>

        <x-card 
            title="Utilisateurs Actifs" 
            subtitle="{{ $users->where('is_deleted', false)->count() }} utilisateurs actifs"
            variant="colored"
            color="green"
            icon="fas fa-user-check"
            padding="compact"
        >
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600">{{ $users->where('is_deleted', false)->count() }}</div>
                <div class="text-sm text-gray-600 mt-1">Actuellement actifs</div>
            </div>
        </x-card>

        <x-card 
            title="Rôles Créés" 
            subtitle="{{ $roles->count() }} rôles définis"
            variant="gradient"
            color="purple"
            icon="fas fa-shield-alt"
            padding="compact"
        >
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $roles->count() }}</div>
                <div class="text-sm text-gray-600 mt-1">Rôles disponibles</div>
            </div>
        </x-card>

        <x-card 
            title="Nouveaux (30j)" 
            subtitle="{{ $users->where('created_at', '>=', now()->subDays(30))->count() }} nouveaux utilisateurs"
            variant="colored"
            color="blue"
            icon="fas fa-calendar"
            padding="compact"
        >
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</div>
                <div class="text-sm text-gray-600 mt-1">Derniers 30 jours</div>
            </div>
        </x-card>
    </div>

    <!-- Search and Filters -->
    <x-card 
        title="Recherche et Filtres" 
        subtitle="Filtrez et recherchez parmi les utilisateurs"
        variant="colored"
        color="blue"
        icon="fas fa-search"
        padding="normal"
    >
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search text-blue-500 mr-2"></i>
                    Recherche
                </label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Nom, email ou PPR..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                    Rôle
                </label>
                <select 
                    id="role" 
                    name="role"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on text-blue-500 mr-2"></i>
                    Statut
                </label>
                <select 
                    id="status" 
                    name="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="flex items-end">
                <button 
                    type="submit" 
                    class="w-full px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center justify-center gap-2"
                >
                    <i class="fas fa-search"></i>
                    Filtrer
                </button>
            </div>
        </form>
    </x-card>

    <!-- Users Table -->
    <x-card 
        title="Liste des Utilisateurs" 
        subtitle="{{ $users->total() }} utilisateurs trouvés"
        variant="gradient"
        color="blue"
        icon="fas fa-table"
        padding="normal"
    >
        @php
            $headers = ['ID', 'Nom', 'Email', 'PPR', 'Rôles', 'Statut', 'Date de création', 'Actions'];
            $rows = [];
        @endphp
        @foreach($users as $user)
            @php
                $rows[] = [
                    '<span class="badge bg-secondary">' . e($user->id) . '</span>',
                    view('components.users.partials.name-cell', compact('user'))->render(),
                    '<span class="text-muted">' . e($user->email) . '</span>',
                    '<span class="badge bg-info">' . e($user->ppr) . '</span>',
                    view('components.users.partials.roles-cell', compact('user'))->render(),
                    view('components.users.partials.status-cell', compact('user'))->render(),
                    '<small class="text-muted">' . e($user->created_at->format('d/m/Y H:i')) . '</small>',
                    view('components.users.partials.actions-cell', compact('user'))->render(),
                ];
            @endphp
        @endforeach

        <x-data-table :headers="$headers" :rows="$rows" :pagination="$users->appends(request()->query())->links()" />
    </x-card>
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
