@php /** @var \App\Models\User $user */ @endphp
<div class="d-flex align-items-center gap-2">
    @can('users.view')
    <a href="{{ route('users.show', $user) }}" 
       class="btn btn-sm btn-outline-primary" 
       title="Voir les détails">
        <i class="fas fa-eye"></i>
    </a>
    @endcan

    @can('users.edit')
    <a href="{{ route('users.edit', $user) }}" 
       class="btn btn-sm btn-outline-warning" 
       title="Modifier l'utilisateur">
        <i class="fas fa-edit"></i>
    </a>
    @endcan

    @can('users.edit')
    <button type="button" 
            onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_deleted ? 'false' : 'true' }})"
            class="btn btn-sm {{ $user->is_deleted ? 'btn-outline-success' : 'btn-outline-warning' }}" 
            title="{{ $user->is_deleted ? 'Activer l\'utilisateur' : 'Désactiver l\'utilisateur' }}">
        @if($user->is_deleted)
            <i class="fas fa-user-check"></i>
        @else
            <i class="fas fa-user-times"></i>
        @endif
    </button>
    @endcan

    @can('users.delete')
    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" 
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"
                class="btn btn-sm btn-outline-danger" 
                title="Supprimer l'utilisateur">
            <i class="fas fa-trash"></i>
        </button>
    </form>
    @endcan
</div>

