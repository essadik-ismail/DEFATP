@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('breadcrumb')
<li class="breadcrumb-item active">Utilisateurs</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    <x-page-header
        title="Utilisateurs"
        subtitle="Administration des comptes et des rôles"
        icon="fas fa-users"
    >
        <x-slot name="actions">
            @can('users.create')
                <x-button href="{{ route('users.create') }}" icon="fas fa-plus">
                    Nouvel utilisateur
                </x-button>
            @endcan
            @can('users.view')
                <x-button href="{{ route('users.export') }}" variant="secondary" icon="fas fa-download">
                    Exporter
                </x-button>
            @endcan
        </x-slot>
    </x-page-header>

    {{-- ── KPI strip ──────────────────────────────────────────────────── --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px,1fr)); gap:0.875rem; margin-bottom:1.5rem;">
        @foreach([
            ['label'=>'Total',   'value'=>$stats['total'],    'icon'=>'fas fa-users',        'color'=>'#1A5276','bg'=>'#EBF5FB','border'=>'#AED6F1'],
            ['label'=>'Actifs',  'value'=>$stats['active'],   'icon'=>'fas fa-check-circle', 'color'=>'#276749','bg'=>'#ECFDF5','border'=>'#A7F3D0'],
            ['label'=>'Inactifs','value'=>$stats['inactive'], 'icon'=>'fas fa-ban',          'color'=>'#9B2C2C','bg'=>'#FEF2F2','border'=>'#FCA5A5'],
        ] as $kpi)
        <div style="background:#fff; border:1px solid {{ $kpi['border'] }}; border-radius:0.75rem;
                    padding:1rem 1.25rem; display:flex; align-items:center; gap:0.875rem;
                    box-shadow:0 1px 3px rgba(0,0,0,0.04);">
            <div style="width:36px; height:36px; border-radius:0.5rem; flex-shrink:0; display:flex;
                        align-items:center; justify-content:center; background:{{ $kpi['bg'] }};">
                <i class="{{ $kpi['icon'] }}" style="color:{{ $kpi['color'] }}; font-size:0.9rem;"></i>
            </div>
            <div>
                <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#5F7A6B; margin:0 0 0.125rem;">{{ $kpi['label'] }}</p>
                <p style="font-size:1.5rem; font-weight:700; color:#1A2D22; margin:0; line-height:1;">{{ number_format($kpi['value']) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Filters ─────────────────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('users.index') }}" id="filterForm"
          style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                 padding:1rem 1.25rem; margin-bottom:1rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:0.75rem; align-items:flex-end;">
            <div>
                <label class="form-label">Rechercher</label>
                <div style="position:relative;">
                    <i class="fas fa-search" style="position:absolute; left:0.625rem; top:50%; transform:translateY(-50%); color:#9AB3A3; font-size:0.8125rem; pointer-events:none;"></i>
                    <input type="text" name="search" class="form-input" style="padding-left:2rem;"
                           placeholder="Nom, email, PPR…" value="{{ request('search') }}"
                           onkeyup="debounceFilter()">
                </div>
            </div>
            <div>
                <label class="form-label">Rôle</label>
                <select name="role" class="form-select" onchange="submitFilter()">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Statut</label>
                <select name="status" class="form-select" onchange="submitFilter()">
                    <option value="">Tous les statuts</option>
                    <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <button type="submit" class="btn-primary" style="flex:1;">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <button type="button" class="btn-secondary" onclick="clearFilters()" title="Effacer les filtres">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @if(request('per_page'))
            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
        @endif
    </form>

    {{-- ── Table card ──────────────────────────────────────────────────── --}}
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">

        {{-- Toolbar --}}
        <div style="padding:0.75rem 1.25rem; border-bottom:1px solid #EEF2EF; background:#FAFCFB;
                    display:flex; align-items:center; justify-content:space-between; gap:0.75rem; flex-wrap:wrap;">
            <p style="font-size:0.8125rem; color:#5F7A6B; margin:0;">
                {{ $users->total() }} utilisateur(s)
                @if(request()->hasAny(['search','role','status']))
                    <span style="display:inline-flex; align-items:center; gap:0.3rem; margin-left:0.5rem;
                                  padding:0.2rem 0.5rem; border-radius:1rem; font-size:0.6875rem; font-weight:600;
                                  background:#EBF5FB; color:#1A5276;">
                        <i class="fas fa-filter"></i> Filtrés
                    </span>
                @endif
            </p>
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <label style="font-size:0.75rem; color:#5F7A6B;">Par page:</label>
                <select class="form-select" style="width:auto; padding:0.25rem 0.5rem; font-size:0.8125rem;"
                        onchange="changePerPage(this.value)">
                    @foreach([10,15,25,50,100] as $n)
                        <option value="{{ $n }}" {{ request('per_page', 15) == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table class="table-anef">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>PPR</th>
                        <th>Rôle org.</th>
                        <th>Rôles</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td style="font-size:0.75rem; color:#8FA89B;">{{ $user->id }}</td>
                            <td>
                                <div style="display:flex; align-items:center; gap:0.625rem;">
                                    <div style="width:30px; height:30px; border-radius:50%; background:#E4F2EB;
                                                display:flex; align-items:center; justify-content:center;
                                                font-weight:700; font-size:0.75rem; color:#276749; flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span style="font-weight:600; color:#1A2D22;">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td style="color:#5F7A6B;">{{ $user->email }}</td>
                            <td>
                                @if($user->ppr)
                                    <span class="badge-info">{{ $user->ppr }}</span>
                                @else
                                    <span style="color:#C6D9CE;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($user->role)
                                    <span class="badge-neutral">{{ $user->role->label() }}</span>
                                @else
                                    <span style="color:#C6D9CE;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($user->roles && $user->roles->count())
                                    <div style="display:flex; flex-wrap:wrap; gap:0.25rem;">
                                        @foreach($user->roles as $role)
                                            <span class="badge-neutral">{{ $role->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span style="color:#C6D9CE;">Aucun</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_deleted)
                                    <span class="badge-danger"><span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span> Inactif</span>
                                @else
                                    <span class="badge-success"><span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Actif</span>
                                @endif
                            </td>
                            <td style="color:#5F7A6B; font-size:0.75rem;">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; justify-content:center; gap:0.375rem;">
                                    <a href="{{ route('users.show', $user) }}"
                                       class="tbl-action bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200"
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="tbl-action bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200"
                                       title="Modifier">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button"
                                            onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_deleted ? 'false' : 'true' }})"
                                            class="tbl-action {{ $user->is_deleted ? 'bg-green-50 hover:bg-green-100 text-green-700 border border-green-200' : 'bg-yellow-50 hover:bg-yellow-100 text-yellow-700 border border-yellow-200' }}"
                                            title="{{ $user->is_deleted ? 'Activer' : 'Désactiver' }}">
                                        <i class="fas fa-{{ $user->is_deleted ? 'check' : 'ban' }}"></i>
                                    </button>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="tbl-action bg-red-50 hover:bg-red-100 text-red-600 border border-red-200"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding:2.5rem 1rem; text-align:center;">
                                <x-empty-state
                                    icon="fas fa-users"
                                    title="Aucun utilisateur trouvé"
                                    message="Commencez par créer le premier utilisateur."
                                    color="green"
                                >
                                    @can('users.create')
                                        <x-button href="{{ route('users.create') }}" icon="fas fa-plus" size="sm">
                                            Créer un utilisateur
                                        </x-button>
                                    @endcan
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div style="padding:0.75rem 1.25rem; border-top:1px solid #EEF2EF;">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Toggle status confirmation --}}
<div id="toggleStatusModal" style="display:none; position:fixed; inset:0; z-index:9000;
     background:rgba(0,0,0,0.4); align-items:center; justify-content:center;" x-data>
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

@endsection

@push('scripts')
<script>
let searchTimeout;
function debounceFilter() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => submitFilter(), 500);
}
function submitFilter() { document.getElementById('filterForm').submit(); }
function clearFilters() {
    ['search','role','status'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    submitFilter();
}
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

let pendingToggleUrl = null;
function toggleUserStatus(userId, newStatus) {
    pendingToggleUrl = `/admin/users/${userId}/toggle-status`;
    const modal = document.getElementById('toggleStatusModal');
    modal.style.display = 'flex';
}
function closeToggleModal() {
    document.getElementById('toggleStatusModal').style.display = 'none';
    pendingToggleUrl = null;
}
document.getElementById('confirmToggleStatus').onclick = function() {
    if (!pendingToggleUrl) return;
    fetch(pendingToggleUrl, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
