@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('page-actions')
    <x-button href="{{ route('auth.users.create') }}" variant="primary" icon="fas fa-plus">
        Nouvel Utilisateur
    </x-button>
@endsection

@section('content')
    {{-- Alert Messages --}}
    @if(session('success'))
        <x-alert type="success" title="Succès!" dismissible="true" autoHide="true">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error" title="Erreur!" dismissible="true" autoHide="true">
            {{ session('error') }}
        </x-alert>
    @endif

    {{-- Data Table --}}
    <x-card title="Gestion des Utilisateurs" subtitle="Gérez les comptes utilisateurs du système" collapsible="false">
        <x-data-table 
            :headers="['ID', 'Utilisateur', 'PPR', 'Date de création', 'Actions']"
            :total="$users->total()"
            :pagination="$users->appends(request()->query())->links()"
            emptyMessage="Aucun utilisateur trouvé"
            emptySubmessage="Commencez par ajouter votre premier utilisateur"
        >
            @foreach($users as $user)
                <tr class="table-row">
                    <td class="table-cell">
                        <span class="table-id">{{ $user->id }}</span>
                    </td>
                    <td class="table-cell">
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
                    <td class="table-cell">
                        <span class="badge bg-secondary">{{ $user->ppr }}</span>
                    </td>
                    <td class="table-cell">
                        <span class="table-date">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </td>
                    <td class="table-cell">
                        <div class="table-actions">
                            <x-button href="{{ route('auth.users.edit', $user) }}" variant="outline" size="sm" icon="fas fa-edit" title="Modifier">
                                Modifier
                            </x-button>
                            
                            @if($user->id !== auth()->id())
                                <x-button type="button" variant="outline" size="sm" icon="fas fa-trash" title="Supprimer" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                    Supprimer
                                </x-button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-data-table>
    </x-card>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="userName"></strong> ?</p>
                    <p class="text-danger">Cette action est irréversible.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function confirmDelete(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = '{{ route('auth.users.destroy', ':id') }}'.replace(':id', userId);
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush 