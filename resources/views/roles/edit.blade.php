@extends('layouts.app')

@section('title', 'Modifier le Rôle')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Rôles</a></li>
<li class="breadcrumb-item"><a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a></li>
<li class="breadcrumb-item active">Modifier</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    <x-page-header
        title="Modifier le rôle"
        subtitle="{{ $role->name }}"
        icon="fas fa-pen"
        :backRoute="route('roles.show', $role)"
        backText="Retour"
    >
        <x-slot name="actions">
            <x-button href="{{ route('roles.show', $role) }}" variant="secondary" icon="fas fa-eye">Voir</x-button>
        </x-slot>
    </x-page-header>

    <form action="{{ route('roles.update', $role) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div style="max-width:1000px;">
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                        padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <h2 style="font-size:0.9375rem; font-weight:700; color:#1A2D22; margin:0 0 1.25rem;
                            padding-bottom:0.75rem; border-bottom:1px solid #EEF2EF;">
                    <i class="fas fa-shield-alt" style="color:#276749; margin-right:0.375rem;"></i>
                    Informations du rôle
                </h2>

                @include('roles._form', [
                    'role'            => $role,
                    'permissions'     => $permissions,
                    'rolePermissions' => $rolePermissions,
                    'isProtected'     => in_array($role->name, ['admin']),
                ])

                <div style="display:flex; gap:0.625rem; justify-content:flex-end; margin-top:1.5rem;
                            padding-top:1rem; border-top:1px solid #EEF2EF;">
                    <a href="{{ route('roles.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
function selectAll() {
    document.querySelectorAll('.perm-check').forEach(cb => { cb.checked = true; });
    document.querySelectorAll('input[type="checkbox"][id^="module_"]').forEach(cb => { cb.checked = true; });
    updateCounter();
}
function deselectAll() {
    document.querySelectorAll('.perm-check').forEach(cb => { cb.checked = false; });
    document.querySelectorAll('input[type="checkbox"][id^="module_"]').forEach(cb => { cb.checked = false; });
    updateCounter();
}
function toggleModule(key) {
    const body = document.getElementById('module_body_' + key);
    const chevron = document.getElementById('chevron_' + key);
    const isHidden = body.style.display === 'none';
    body.style.display = isHidden ? 'grid' : 'none';
    chevron.style.transform = isHidden ? '' : 'rotate(-90deg)';
}
function toggleModuleCheckbox(key, checked) {
    document.querySelectorAll('.module-' + key).forEach(cb => { cb.checked = checked; });
    updateCounter();
}
function updateCounter() {
    const count = document.querySelectorAll('.perm-check:checked').length;
    const el = document.getElementById('checkedCount');
    if (el) el.textContent = count;
}
updateCounter();
</script>
@endpush
