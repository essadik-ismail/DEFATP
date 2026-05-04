@extends('layouts.app')

@section('title', 'Gestion des Rôles')

@section('breadcrumb')
<li class="breadcrumb-item active">Rôles</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    <x-page-header
        title="Rôles"
        subtitle="Administration des rôles et permissions"
        icon="fas fa-shield-alt"
    >
        <x-slot name="actions">
            @can('roles.create')
                <x-button href="{{ route('roles.create') }}" icon="fas fa-plus">
                    Nouveau rôle
                </x-button>
            @endcan
        </x-slot>
    </x-page-header>

    {{-- ── Filters ──────────────────────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('roles.index') }}" id="filterForm"
          style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                 padding:1rem 1.25rem; margin-bottom:1rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <div style="display:flex; gap:0.75rem; align-items:flex-end; flex-wrap:wrap;">
            <div style="flex:1; min-width:200px;">
                <label class="form-label">Rechercher</label>
                <div style="position:relative;">
                    <i class="fas fa-search" style="position:absolute; left:0.625rem; top:50%; transform:translateY(-50%); color:#9AB3A3; font-size:0.8125rem; pointer-events:none;"></i>
                    <input type="text" name="search" class="form-input" style="padding-left:2rem;"
                           placeholder="Nom du rôle…" value="{{ request('search') }}"
                           onkeyup="debounceFilter()">
                </div>
            </div>
            <div style="display:flex; gap:0.5rem;">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                @if(request('search'))
                    <a href="{{ route('roles.index') }}" class="btn-secondary" title="Effacer">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- ── Table card ───────────────────────────────────────────────────────── --}}
    <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04);">

        <div style="padding:0.75rem 1.25rem; border-bottom:1px solid #EEF2EF; background:#FAFCFB;">
            <p style="font-size:0.8125rem; color:#5F7A6B; margin:0;">
                {{ $roles->total() }} rôle(s)
            </p>
        </div>

        <div style="overflow-x:auto;">
            <table class="table-anef">
                <thead>
                    <tr>
                        <th>Rôle</th>
                        <th class="text-center">Permissions</th>
                        <th>Protégé</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        @php $isProtected = in_array($role->name, ['admin']); @endphp
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:0.625rem;">
                                    <div style="width:32px; height:32px; border-radius:0.5rem; background:#E4F2EB;
                                                display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <i class="fas fa-shield-alt" style="color:#276749; font-size:0.8rem;"></i>
                                    </div>
                                    <div>
                                        <span style="font-weight:600; color:#1A2D22;">{{ $role->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge-info">{{ $role->permissions_count }}</span>
                            </td>
                            <td>
                                @if($isProtected)
                                    <span class="badge-warning"><i class="fas fa-lock" style="font-size:0.6rem;"></i> Système</span>
                                @else
                                    <span style="color:#C6D9CE; font-size:0.8125rem;">—</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; justify-content:center; gap:0.375rem;">
                                    <a href="{{ route('roles.show', $role) }}"
                                       class="tbl-action bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200"
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('roles.update')
                                        <a href="{{ route('roles.edit', $role) }}"
                                           class="tbl-action bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200"
                                           title="Modifier">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('roles.delete')
                                        @if(!$isProtected)
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Supprimer le rôle « {{ $role->name }} » ? Cette action est irréversible.')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="tbl-action bg-red-50 hover:bg-red-100 text-red-600 border border-red-200"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="tbl-action" style="opacity:0.3; cursor:not-allowed;" title="Rôle protégé">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:2.5rem 1rem; text-align:center;">
                                <x-empty-state
                                    icon="fas fa-shield-alt"
                                    title="Aucun rôle trouvé"
                                    message="Commencez par créer un rôle."
                                    color="green"
                                >
                                    @can('roles.create')
                                        <x-button href="{{ route('roles.create') }}" icon="fas fa-plus" size="sm">
                                            Créer un rôle
                                        </x-button>
                                    @endcan
                                </x-empty-state>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($roles->hasPages())
            <div style="padding:0.75rem 1.25rem; border-top:1px solid #EEF2EF;">
                {{ $roles->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
let searchTimeout;
function debounceFilter() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => document.getElementById('filterForm').submit(), 500);
}
</script>
@endpush
