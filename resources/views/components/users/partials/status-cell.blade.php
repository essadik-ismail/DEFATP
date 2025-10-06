@php /** @var \App\Models\User $user */ @endphp
@if($user->is_deleted)
    <span class="badge bg-danger">
        <i class="fas fa-times-circle me-1"></i>Inactif
    </span>
@else
    <span class="badge bg-success">
        <i class="fas fa-check-circle me-1"></i>Actif
    </span>
@endif

