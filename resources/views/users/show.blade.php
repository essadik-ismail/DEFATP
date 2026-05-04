@extends('layouts.app')

@section('title', 'Utilisateur — ' . $user->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
<li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    <x-page-header
        title="{{ $user->name }}"
        subtitle="Détail du compte utilisateur"
        icon="fas fa-user"
        :backRoute="route('users.index')"
        backText="Retour"
    >
        <x-slot name="actions">
            @can('users.update')
                <x-button href="{{ route('users.edit', $user) }}" variant="secondary" icon="fas fa-pen">Modifier</x-button>
            @endcan
        </x-slot>
    </x-page-header>

    <div style="display:grid; grid-template-columns:1fr 300px; gap:1rem; align-items:start;">

        {{-- ── Left column ─────────────────────────────────────────────────── --}}
        <div style="display:flex; flex-direction:column; gap:1rem;">

            {{-- Personal info --}}
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                        padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <h2 style="font-size:0.9375rem; font-weight:700; color:#1A2D22; margin:0 0 1.25rem;
                            padding-bottom:0.75rem; border-bottom:1px solid #EEF2EF;">
                    <i class="fas fa-id-card" style="color:#276749; margin-right:0.375rem;"></i>
                    Informations personnelles
                </h2>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.25rem;">Nom complet</p>
                        <p style="font-size:0.9rem; font-weight:600; color:#1A2D22; margin:0;">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.25rem;">Email</p>
                        <a href="mailto:{{ $user->email }}" style="font-size:0.875rem; color:#276749; text-decoration:none;">{{ $user->email }}</a>
                    </div>
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.25rem;">PPR</p>
                        @if($user->ppr)
                            <span class="badge-info">{{ $user->ppr }}</span>
                        @else
                            <span style="color:#C6D9CE;">—</span>
                        @endif
                    </div>
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.25rem;">Rôle organisationnel</p>
                        @if($user->role)
                            <span class="badge-neutral">{{ $user->role->label() }}</span>
                        @else
                            <span style="color:#C6D9CE;">—</span>
                        @endif
                    </div>
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.25rem;">Statut</p>
                        @if($user->is_deleted)
                            <span class="badge-danger">Inactif</span>
                        @else
                            <span class="badge-success">Actif</span>
                        @endif
                    </div>
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.25rem;">Email vérifié</p>
                        @if($user->email_verified_at)
                            <span class="badge-success">
                                <i class="fas fa-check" style="font-size:0.6rem;"></i>
                                {{ $user->email_verified_at->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="badge-warning">Non vérifié</span>
                        @endif
                    </div>
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.25rem;">Membre depuis</p>
                        <p style="font-size:0.875rem; color:#5F7A6B; margin:0;">{{ $user->created_at?->format('d/m/Y à H:i') ?? '—' }}</p>
                    </div>
                    @if($user->updated_at && $user->updated_at->ne($user->created_at))
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.25rem;">Dernière modification</p>
                        <p style="font-size:0.875rem; color:#5F7A6B; margin:0;">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Roles --}}
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                        padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;
                            padding-bottom:0.75rem; border-bottom:1px solid #EEF2EF;">
                    <h2 style="font-size:0.9375rem; font-weight:700; color:#1A2D22; margin:0;">
                        <i class="fas fa-user-tag" style="color:#276749; margin-right:0.375rem;"></i>
                        Rôles assignés
                        <span class="badge-info" style="margin-left:0.5rem;">{{ $user->roles->count() }}</span>
                    </h2>
                    @can('users.assign_roles')
                        <button type="button" onclick="document.getElementById('assignModal').style.display='flex'"
                                class="btn-secondary" style="font-size:0.8rem; padding:0.375rem 0.75rem;">
                            <i class="fas fa-edit"></i> Gérer les rôles
                        </button>
                    @endcan
                </div>

                @if($user->roles->isEmpty())
                    <div style="text-align:center; padding:1.5rem; color:#9AB3A3;">
                        <i class="fas fa-user-slash" style="font-size:1.75rem; opacity:0.4; display:block; margin-bottom:0.5rem;"></i>
                        <p style="margin:0; font-size:0.875rem;">Aucun rôle assigné</p>
                    </div>
                @else
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:0.75rem;">
                        @foreach($user->roles as $role)
                            <div style="border:1px solid #DDE5E1; border-radius:0.625rem; padding:0.875rem;">
                                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.5rem;">
                                    <div style="display:flex; align-items:center; gap:0.5rem;">
                                        <div style="width:28px; height:28px; border-radius:0.375rem; background:#E4F2EB;
                                                    display:flex; align-items:center; justify-content:center;">
                                            <i class="fas fa-shield-alt" style="color:#276749; font-size:0.7rem;"></i>
                                        </div>
                                        <span style="font-weight:600; color:#1A2D22; font-size:0.875rem;">{{ $role->name }}</span>
                                    </div>
                                    <span class="badge-neutral" style="font-size:0.6875rem;">{{ $role->permissions->count() }} perm.</span>
                                </div>
                                @if($role->permissions->isNotEmpty())
                                    <div style="display:flex; flex-wrap:wrap; gap:0.25rem; max-height:72px; overflow:hidden;">
                                        @foreach($role->permissions->take(6) as $perm)
                                            <span style="background:#F0F4F2; color:#5F7A6B; border-radius:0.25rem;
                                                         padding:0.1rem 0.35rem; font-size:0.6875rem; font-family:monospace;">
                                                {{ $perm->name }}
                                            </span>
                                        @endforeach
                                        @if($role->permissions->count() > 6)
                                            <span style="background:#EBF5FB; color:#1A5276; border-radius:0.25rem;
                                                         padding:0.1rem 0.35rem; font-size:0.6875rem;">
                                                +{{ $role->permissions->count() - 6 }} autres
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Direct permissions --}}
                @php $directPerms = $user->getDirectPermissions(); @endphp
                @if($directPerms->isNotEmpty())
                    <div style="margin-top:1.25rem; padding-top:1.25rem; border-top:1px solid #EEF2EF;">
                        <p style="font-size:0.8125rem; font-weight:600; color:#1A2D22; margin:0 0 0.625rem;">
                            <i class="fas fa-key" style="color:#276749; margin-right:0.25rem;"></i>
                            Permissions directes ({{ $directPerms->count() }})
                        </p>
                        <div style="display:flex; flex-wrap:wrap; gap:0.35rem;">
                            @foreach($directPerms as $perm)
                                <span style="background:#EBF5FB; color:#1A5276; border:1px solid #AED6F1;
                                             border-radius:0.375rem; padding:0.2rem 0.5rem;
                                             font-size:0.75rem; font-family:monospace;">
                                    {{ $perm->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Effective permissions summary --}}
            @php
                $allPerms = $user->getAllPermissions()->sortBy('name');
                $grouped  = $allPerms->groupBy(fn($p) => explode('.', $p->name)[0]);
            @endphp
            @if($allPerms->isNotEmpty())
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                        padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <h2 style="font-size:0.9375rem; font-weight:700; color:#1A2D22; margin:0 0 1.25rem;
                            padding-bottom:0.75rem; border-bottom:1px solid #EEF2EF;">
                    <i class="fas fa-list-check" style="color:#276749; margin-right:0.375rem;"></i>
                    Permissions effectives
                    <span class="badge-success" style="margin-left:0.5rem;">{{ $allPerms->count() }}</span>
                </h2>
                @foreach($grouped->sortKeys() as $module => $perms)
                    <div style="margin-bottom:0.875rem;">
                        <p style="font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;
                                   color:#9AB3A3; margin:0 0 0.375rem;">{{ $module }}</p>
                        <div style="display:flex; flex-wrap:wrap; gap:0.3rem;">
                            @foreach($perms as $perm)
                                <span style="background:#E4F2EB; color:#276749; border:1px solid #B2D8C2;
                                             border-radius:0.25rem; padding:0.15rem 0.4rem;
                                             font-size:0.7rem; font-family:monospace;">
                                    {{ $perm->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

        </div>

        {{-- ── Right sidebar ────────────────────────────────────────────────── --}}
        <div style="display:flex; flex-direction:column; gap:1rem;">

            {{-- Avatar card --}}
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                        padding:1.25rem; box-shadow:0 1px 3px rgba(0,0,0,0.04); text-align:center;">
                <div style="width:80px; height:80px; border-radius:50%; background:#E4F2EB;
                             display:flex; align-items:center; justify-content:center;
                             margin:0 auto 0.75rem; font-size:2rem; font-weight:700; color:#276749;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <p style="font-weight:700; color:#1A2D22; margin:0 0 0.25rem;">{{ $user->name }}</p>
                <p style="font-size:0.8rem; color:#9AB3A3; margin:0;">{{ $user->email }}</p>
            </div>

            {{-- Stats --}}
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                        padding:1.25rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <h3 style="font-size:0.875rem; font-weight:700; color:#1A2D22; margin:0 0 0.875rem;
                            padding-bottom:0.625rem; border-bottom:1px solid #EEF2EF;">Statistiques</h3>
                <div style="display:grid; gap:0.625rem;">
                    @foreach([
                        ['label' => 'Rôles', 'value' => $user->roles->count(), 'icon' => 'fas fa-shield-alt', 'color' => '#276749', 'bg' => '#E4F2EB'],
                        ['label' => 'Permissions effectives', 'value' => $user->getAllPermissions()->count(), 'icon' => 'fas fa-key', 'color' => '#1A5276', 'bg' => '#EBF5FB'],
                        ['label' => 'Permissions directes', 'value' => $user->getDirectPermissions()->count(), 'icon' => 'fas fa-user-shield', 'color' => '#7B341E', 'bg' => '#FEECE2'],
                    ] as $stat)
                    <div style="display:flex; align-items:center; gap:0.625rem; padding:0.5rem 0.625rem;
                                border-radius:0.5rem; background:#FAFCFB; border:1px solid #EEF2EF;">
                        <div style="width:28px; height:28px; border-radius:0.375rem; background:{{ $stat['bg'] }};
                                    display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i class="{{ $stat['icon'] }}" style="color:{{ $stat['color'] }}; font-size:0.75rem;"></i>
                        </div>
                        <div style="flex:1;">
                            <p style="font-size:0.6875rem; color:#9AB3A3; margin:0;">{{ $stat['label'] }}</p>
                        </div>
                        <span style="font-size:1rem; font-weight:700; color:#1A2D22;">{{ $stat['value'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Actions --}}
            @canany(['users.update', 'users.delete'])
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                        padding:1.25rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <h3 style="font-size:0.875rem; font-weight:700; color:#1A2D22; margin:0 0 0.875rem;">Actions rapides</h3>
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    @can('users.update')
                        <a href="{{ route('users.edit', $user) }}"
                           style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem;
                                  border-radius:0.5rem; background:#FFF9E6; color:#92400E; font-size:0.8125rem;
                                  font-weight:500; border:1px solid #FDE68A; text-decoration:none;
                                  transition:background 0.15s;"
                           onmouseover="this.style.background='#FEF3C7'" onmouseout="this.style.background='#FFF9E6'">
                            <i class="fas fa-pen" style="font-size:0.75rem;"></i> Modifier l'utilisateur
                        </a>
                        <button type="button"
                                onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_deleted ? 'false' : 'true' }})"
                                style="width:100%; display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem;
                                       border-radius:0.5rem; font-size:0.8125rem; font-weight:500; cursor:pointer;
                                       border:1px solid {{ $user->is_deleted ? '#A7F3D0' : '#FDE68A' }};
                                       background:{{ $user->is_deleted ? '#ECFDF5' : '#FFF9E6' }};
                                       color:{{ $user->is_deleted ? '#065F46' : '#92400E' }};
                                       transition:background 0.15s;">
                            <i class="fas fa-{{ $user->is_deleted ? 'check' : 'ban' }}" style="font-size:0.75rem;"></i>
                            {{ $user->is_deleted ? 'Activer le compte' : 'Désactiver le compte' }}
                        </button>
                    @endcan
                    @can('users.delete')
                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Supprimer définitivement cet utilisateur ?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="width:100%; display:flex; align-items:center; gap:0.5rem;
                                           padding:0.5rem 0.75rem; border-radius:0.5rem; background:#FEF2F2;
                                           color:#991B1B; font-size:0.8125rem; font-weight:500;
                                           border:1px solid #FCA5A5; cursor:pointer; transition:background 0.15s;"
                                    onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='#FEF2F2'">
                                <i class="fas fa-trash" style="font-size:0.75rem;"></i> Supprimer l'utilisateur
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
            @endcanany

        </div>
    </div>

</div>

{{-- Toggle status confirmation modal --}}
<div id="toggleStatusModal" style="display:none; position:fixed; inset:0; z-index:9000;
     background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:0.75rem; padding:1.5rem; width:100%; max-width:400px;
                margin:1rem; box-shadow:0 20px 60px rgba(0,0,0,0.15);">
        <h3 style="font-size:1rem; font-weight:700; color:#1A2D22; margin:0 0 0.5rem;">Changer le statut</h3>
        <p style="font-size:0.875rem; color:#5F7A6B; margin:0 0 1.25rem;">
            Êtes-vous sûr de vouloir changer le statut de cet utilisateur ?
        </p>
        <div style="display:flex; gap:0.625rem; justify-content:flex-end;">
            <button type="button" class="btn-secondary" onclick="closeToggleModal()">Annuler</button>
            <button type="button" class="btn-primary" id="confirmToggleStatus">Confirmer</button>
        </div>
    </div>
</div>

{{-- Assign roles & permissions modal --}}
@can('users.assign_roles')
<div id="assignModal" style="display:none; position:fixed; inset:0; z-index:9000;
     background:rgba(0,0,0,0.4); align-items:center; justify-content:center; padding:1rem;">
    <div style="background:#fff; border-radius:0.75rem; width:100%; max-width:560px;
                max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.15);">

        <form action="{{ route('users.assign-roles-permissions', $user) }}" method="POST">
            @csrf

            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #EEF2EF;
                        display:flex; align-items:center; justify-content:space-between;">
                <h3 style="font-size:1rem; font-weight:700; color:#1A2D22; margin:0;">
                    <i class="fas fa-user-tag" style="color:#276749; margin-right:0.375rem;"></i>
                    Gérer les rôles
                </h3>
                <button type="button" onclick="document.getElementById('assignModal').style.display='none'"
                        style="background:none; border:none; cursor:pointer; color:#9AB3A3; font-size:1.125rem;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div style="padding:1.25rem 1.5rem;">
                <p style="font-size:0.8125rem; color:#5F7A6B; margin:0 0 1rem;">
                    Sélectionnez les rôles à assigner à <strong>{{ $user->name }}</strong>.
                </p>
                <div style="display:grid; gap:0.5rem;">
                    @foreach($allRoles as $role)
                        <label style="display:flex; align-items:center; gap:0.625rem; cursor:pointer;
                                      padding:0.625rem 0.75rem; border-radius:0.5rem; border:1px solid #DDE5E1;
                                      transition:background 0.15s;"
                               onmouseover="this.style.background='#F0F4F2'" onmouseout="this.style.background='transparent'">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                   style="width:14px; height:14px; accent-color:#276749;"
                                   {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                            <div style="flex:1;">
                                <span style="font-weight:600; color:#1A2D22; font-size:0.875rem;">{{ $role->name }}</span>
                                <span style="font-size:0.75rem; color:#9AB3A3; margin-left:0.5rem;">{{ $role->permissions->count() }} permissions</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="padding:1rem 1.5rem; border-top:1px solid #EEF2EF;
                        display:flex; gap:0.625rem; justify-content:flex-end;">
                <button type="button" class="btn-secondary"
                        onclick="document.getElementById('assignModal').style.display='none'">
                    Annuler
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endcan

@endsection

@push('scripts')
<script>
let pendingToggleUrl = null;

function toggleUserStatus(userId) {
    pendingToggleUrl = `/admin/users/${userId}/toggle-status`;
    document.getElementById('toggleStatusModal').style.display = 'flex';
}
function closeToggleModal() {
    document.getElementById('toggleStatusModal').style.display = 'none';
    pendingToggleUrl = null;
}
document.getElementById('confirmToggleStatus').onclick = function () {
    if (!pendingToggleUrl) return;
    fetch(pendingToggleUrl, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { closeToggleModal(); window.location.reload(); }
        else alert('Erreur lors du changement de statut');
    })
    .catch(() => alert('Erreur lors du changement de statut'));
};
</script>
@endpush
