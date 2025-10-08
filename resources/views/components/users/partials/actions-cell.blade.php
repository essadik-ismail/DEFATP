@php /** @var \App\Models\User $user */ @endphp
<div class="flex items-center gap-2">
    @can('users.view')
    <a href="{{ route('users.show', $user) }}" 
       class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors duration-200" 
       title="Voir les détails">
        <i class="fas fa-eye text-sm"></i>
    </a>
    @endcan

    @can('users.edit')
    <a href="{{ route('users.edit', $user) }}" 
       class="inline-flex items-center justify-center w-8 h-8 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-lg transition-colors duration-200" 
       title="Modifier l'utilisateur">
        <i class="fas fa-edit text-sm"></i>
    </a>
    @endcan

    @can('users.edit')
    <button type="button" 
            onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_deleted ? 'false' : 'true' }})"
            class="inline-flex items-center justify-center w-8 h-8 {{ $user->is_deleted ? 'bg-green-100 hover:bg-green-200 text-green-600' : 'bg-yellow-100 hover:bg-yellow-200 text-yellow-600' }} rounded-lg transition-colors duration-200" 
            title="{{ $user->is_deleted ? 'Activer l\'utilisateur' : 'Désactiver l\'utilisateur' }}">
        @if($user->is_deleted)
            <i class="fas fa-user-check text-sm"></i>
        @else
            <i class="fas fa-user-times text-sm"></i>
        @endif
    </button>
    @endcan

    @can('users.delete')
    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" 
                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"
                class="inline-flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition-colors duration-200" 
                title="Supprimer l'utilisateur">
            <i class="fas fa-trash text-sm"></i>
        </button>
    </form>
    @endcan
</div>

