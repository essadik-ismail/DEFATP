@php /** @var \App\Models\User $user */ @endphp
<div class="btn-group" role="group">
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

