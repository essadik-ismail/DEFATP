@extends('layouts.app')

@section('title', 'Rôle — ' . $role->name)

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('roles.index') }}">Rôles</a></li>
<li class="bc-item active">{{ $role->name }}</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    <x-page-header
        title="{{ $role->name }}"
        subtitle="Détail du rôle et permissions associées"
        icon="fas fa-shield-alt"
        :backRoute="route('roles.index')"
        backText="Retour"
    >
        <x-slot name="actions">
            @can('roles.update')
                <x-button href="{{ route('roles.edit', $role) }}" variant="secondary" icon="fas fa-pen">Modifier</x-button>
            @endcan
            @can('roles.delete')
                @if(!in_array($role->name, ['admin']))
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline"
                          onsubmit="return confirm('Supprimer le rôle « {{ $role->name }} » ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                @endif
            @endcan
        </x-slot>
    </x-page-header>

    <div style="display:grid; grid-template-columns:1fr 300px; gap:1rem; align-items:start;">

        {{-- ── Left: Permissions ───────────────────────────────────────────── --}}
        <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                    padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">

            <h2 style="font-size:0.9375rem; font-weight:700; color:#1A2D22; margin:0 0 1.25rem;
                        padding-bottom:0.75rem; border-bottom:1px solid #EEF2EF;">
                <i class="fas fa-key" style="color:#276749; margin-right:0.375rem;"></i>
                Permissions
                <span class="badge-info" style="margin-left:0.5rem;">{{ $role->permissions->count() }}</span>
            </h2>

            @if($role->permissions->isEmpty())
                <div style="text-align:center; padding:2rem 1rem; color:#9AB3A3;">
                    <i class="fas fa-key" style="font-size:2rem; margin-bottom:0.75rem; display:block; opacity:0.4;"></i>
                    <p style="margin:0;">Aucune permission assignée à ce rôle.</p>
                </div>
            @else
                @foreach($groupedPermissions as $module => $perms)
                    @php
                        $moduleLabels = [
                            'auth'                => 'Authentification',
                            'accounts'            => 'Comptes',
                            'roles'               => 'Gestion des rôles',
                            'users'               => 'Gestion des utilisateurs',
                            'activity_logs'       => "Journal d'activité",
                            'cession'             => 'Cessions',
                            'article'             => 'Articles',
                            'contract'            => 'Contrats de vente',
                            'exploitant'          => 'Exploitants',
                            'adjudicataire_letter'=> 'Lettre adjudicataire',
                            'caution_payment'     => 'Paiement de caution',
                            'tax_payment'         => 'Taxes forestières',
                            'installation_report' => "PV d'installation",
                            'operating_permit'    => "Permis d'exploiter",
                            'vehicle'             => 'Véhicules',
                            'installment_payment' => 'Paiement de tranches',
                            'removal_permit'      => "Permis d'enlever",
                            'hawking_permit'      => 'Permis de colportage',
                            'recolement_report'   => 'PV de récolement',
                            'extension'           => 'Prorogation',
                            'release'             => 'Main levée',
                            'termination'         => 'Résiliation',
                            'forfeiture'          => 'Déchéance',
                            'hawking_book'        => 'Carnet de colportage',
                            'alerts'              => 'Alertes',
                        ];
                        $label = $moduleLabels[$module] ?? ucfirst(str_replace('_', ' ', $module));
                    @endphp
                    <div style="margin-bottom:1rem;">
                        <p style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em;
                                   color:#5F7A6B; margin:0 0 0.5rem;">
                            {{ $label }}
                        </p>
                        <div style="display:flex; flex-wrap:wrap; gap:0.375rem;">
                            @foreach($perms as $perm)
                                <span style="background:#E4F2EB; color:#276749; border:1px solid #B2D8C2;
                                             border-radius:0.375rem; padding:0.2rem 0.5rem; font-size:0.75rem;
                                             font-family:monospace;">
                                    {{ $perm->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- ── Right: Meta info ─────────────────────────────────────────────── --}}
        <div style="display:flex; flex-direction:column; gap:1rem;">

            {{-- Summary card --}}
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                        padding:1.25rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <h3 style="font-size:0.875rem; font-weight:700; color:#1A2D22; margin:0 0 1rem;
                            padding-bottom:0.625rem; border-bottom:1px solid #EEF2EF;">
                    <i class="fas fa-info-circle" style="color:#276749; margin-right:0.375rem;"></i>
                    Informations
                </h3>

                <div style="display:grid; gap:0.75rem;">
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.125rem;">Nom</p>
                        <p style="font-size:0.9rem; font-weight:600; color:#1A2D22; margin:0;">{{ $role->name }}</p>
                    </div>
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.125rem;">Permissions</p>
                        <p style="font-size:0.9rem; font-weight:600; color:#1A2D22; margin:0;">{{ $role->permissions->count() }}</p>
                    </div>
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.125rem;">Utilisateurs</p>
                        <p style="font-size:0.9rem; font-weight:600; color:#1A2D22; margin:0;">{{ $usersCount }}</p>
                    </div>
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.125rem;">Statut</p>
                        @if(in_array($role->name, ['admin']))
                            <span class="badge-warning"><i class="fas fa-lock" style="font-size:0.6rem;"></i> Protégé</span>
                        @else
                            <span class="badge-success">Actif</span>
                        @endif
                    </div>
                    <div>
                        <p style="font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#9AB3A3; margin:0 0 0.125rem;">Créé le</p>
                        <p style="font-size:0.8rem; color:#5F7A6B; margin:0;">{{ $role->created_at?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Actions card --}}
            @canany(['roles.update', 'roles.delete'])
            <div style="background:#fff; border:1px solid #DDE5E1; border-radius:0.75rem;
                        padding:1.25rem; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                <h3 style="font-size:0.875rem; font-weight:700; color:#1A2D22; margin:0 0 0.875rem;">
                    <i class="fas fa-bolt" style="color:#276749; margin-right:0.375rem;"></i>
                    Actions
                </h3>
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    @can('roles.update')
                        <a href="{{ route('roles.edit', $role) }}"
                           style="display:flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem;
                                  border-radius:0.5rem; background:#FFF9E6; color:#92400E; font-size:0.8125rem;
                                  font-weight:500; border:1px solid #FDE68A; text-decoration:none;
                                  transition:background 0.15s;"
                           onmouseover="this.style.background='#FEF3C7'" onmouseout="this.style.background='#FFF9E6'">
                            <i class="fas fa-pen" style="font-size:0.75rem;"></i> Modifier le rôle
                        </a>
                    @endcan
                    @can('roles.delete')
                        @if(!in_array($role->name, ['admin']))
                            <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                  onsubmit="return confirm('Supprimer le rôle « {{ $role->name }} » ?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="width:100%; display:flex; align-items:center; gap:0.5rem;
                                               padding:0.5rem 0.75rem; border-radius:0.5rem; background:#FEF2F2;
                                               color:#991B1B; font-size:0.8125rem; font-weight:500;
                                               border:1px solid #FCA5A5; cursor:pointer; transition:background 0.15s;"
                                        onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='#FEF2F2'">
                                    <i class="fas fa-trash" style="font-size:0.75rem;"></i> Supprimer le rôle
                                </button>
                            </form>
                        @else
                            <p style="font-size:0.75rem; color:#9AB3A3; text-align:center; margin:0;">
                                <i class="fas fa-lock" style="font-size:0.65rem;"></i> Rôle système protégé
                            </p>
                        @endif
                    @endcan
                </div>
            </div>
            @endcanany

        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
@media (max-width: 768px) {
    .roles-show-grid { grid-template-columns: 1fr !important; }
}
</style>
@endpush
