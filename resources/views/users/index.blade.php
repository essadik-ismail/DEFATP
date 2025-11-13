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

    <!-- Search and Filters -->
    <x-card 
        title="Recherche et Filtres" 
        subtitle="Filtrez et recherchez parmi les utilisateurs"
        variant="colored"
        color="blue"
        icon="fas fa-search"
        padding="normal"
        class="mb-6"
    >
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="filterRole" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                    Rôle
                </label>
                <select 
                    id="filterRole" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filterStatus" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on text-blue-500 mr-2"></i>
                    Statut
                </label>
                <select 
                    id="filterStatus" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
                    <option value="">Tous les statuts</option>
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                </select>
            </div>
            <div class="flex items-end">
                <button 
                    type="button" 
                    id="resetFilters"
                    class="w-full px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors flex items-center justify-center gap-2"
                >
                    <i class="fas fa-redo"></i>
                    Réinitialiser
                </button>
            </div>
        </div>
    </x-card>

    <!-- Users Table -->
    <x-card 
        title="Liste des Utilisateurs" 
        subtitle="Gestion complète des utilisateurs"
        variant="gradient"
        color="blue"
        icon="fas fa-table"
        padding="normal"
    >
        <div class="table-responsive">
            <table id="usersTable" class="table table-striped table-hover" style="width:100%">
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
                    <!-- DataTables will populate this via AJAX -->
                </tbody>
            </table>
        </div>
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

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<style>
    #usersTable_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }
    
    #usersTable_wrapper .dataTables_length {
        margin-bottom: 1rem;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    let table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('users.index') }}",
            data: function(d) {
                d.role = $('#filterRole').val();
                d.status = $('#filterStatus').val();
            }
        },
        columns: [
            { data: 0, name: 'id', orderable: true, searchable: false },
            { data: 1, name: 'name', orderable: true, searchable: true },
            { data: 2, name: 'email', orderable: true, searchable: true },
            { data: 3, name: 'ppr', orderable: true, searchable: true },
            { data: 4, name: 'roles', orderable: false, searchable: false },
            { data: 5, name: 'is_deleted', orderable: true, searchable: false },
            { data: 6, name: 'created_at', orderable: true, searchable: false },
            { data: 7, name: 'actions', orderable: false, searchable: false }
        ],
        order: [[6, 'desc']],
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, 100], [10, 15, 25, 50, 100]],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimer',
                className: 'btn btn-info btn-sm'
            }
        ],
        responsive: true
    });

    // Apply filters when changed
    $('#filterRole, #filterStatus').on('change', function() {
        table.ajax.reload();
    });

    // Reset filters
    $('#resetFilters').on('click', function() {
        $('#filterRole').val('');
        $('#filterStatus').val('');
        table.ajax.reload();
    });

    // Add search input hint
    $(document).on('focus', '.dataTables_filter input', function() {
        if (typeof UXUtils !== 'undefined') {
            UXUtils.showToast('Utilisez Ctrl+K pour rechercher rapidement', 'info', 3000);
        }
    });

    // Add keyboard shortcut Ctrl+K to focus search
    $(document).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k' && !$(e.target).is('input, textarea')) {
            e.preventDefault();
            $('.dataTables_filter input').focus();
        }
    });
});

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
                $('#usersTable').DataTable().ajax.reload(null, false);
                modal.hide();
            } else {
                alert('Erreur lors du changement de statut');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors du changement de statut');
        });
    };
    
    modal.show();
}
</script>
@endpush
