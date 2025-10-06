@php /** @var \App\Models\User $user */ @endphp
@forelse($user->roles as $role)
    <span class="badge bg-success me-1">{{ $role->name }}</span>
@empty
    <span class="badge bg-light text-dark">Aucun rôle</span>
@endforelse

