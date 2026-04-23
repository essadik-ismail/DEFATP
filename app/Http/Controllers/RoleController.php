<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /** Roles that cannot be deleted (system-protected). */
    private const PROTECTED_ROLES = ['admin'];

    public function index(Request $request): View
    {
        $this->authorize('roles.view');

        $query = Role::withCount('permissions');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $roles = $query->orderBy('name')->paginate(15)->withQueryString();

        ActivityLogger::log('view', 'Consultation de la liste des rôles', Role::class);

        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        $this->authorize('roles.create');

        $permissions = $this->groupedPermissions();

        return view('roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $this->authorize('roles.create');

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        ActivityLogger::log('create', "Rôle {$role->name} créé", Role::class, $role->id, $request);

        return redirect()->route('roles.show', $role)
            ->with('success', "Rôle « {$role->name} » créé avec succès.");
    }

    public function show(Role $role): View
    {
        $this->authorize('roles.view');

        $role->load('permissions');

        $groupedPermissions = $role->permissions
            ->groupBy(fn($p) => explode('.', $p->name)[0])
            ->sortKeys();

        $usersCount = $role->users()->count();

        ActivityLogger::log('view', "Consultation du rôle {$role->name}", Role::class, $role->id, request());

        return view('roles.show', compact('role', 'groupedPermissions', 'usersCount'));
    }

    public function edit(Role $role): View
    {
        $this->authorize('roles.update');

        $permissions        = $this->groupedPermissions();
        $rolePermissions    = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->authorize('roles.update');

        $oldName = $role->name;

        if (!in_array($role->name, self::PROTECTED_ROLES, true)) {
            $role->update(['name' => $request->name]);
        }

        if ($this->canAssignPermissions()) {
            $role->syncPermissions($request->permissions ?? []);
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        ActivityLogger::log(
            'update',
            "Rôle {$oldName} mis à jour" . ($oldName !== $role->name ? " → {$role->name}" : ''),
            Role::class,
            $role->id,
            $request
        );

        return redirect()->route('roles.show', $role)
            ->with('success', "Rôle « {$role->name} » mis à jour avec succès.");
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('roles.delete');

        if (in_array($role->name, self::PROTECTED_ROLES, true)) {
            return redirect()->route('roles.index')
                ->with('error', "Le rôle « {$role->name} » est protégé et ne peut pas être supprimé.");
        }

        $roleName = $role->name;

        ActivityLogger::log('delete', "Rôle {$roleName} supprimé", Role::class, $role->id, request());

        $role->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('roles.index')
            ->with('success', "Rôle « {$roleName} » supprimé avec succès.");
    }

    // ──────────────────────────────────────────────────────────────────────────

    private function groupedPermissions(): array
    {
        $labels = [
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

        $grouped = [];

        Permission::orderBy('name')->get()->each(function ($permission) use ($labels, &$grouped) {
            $module = explode('.', $permission->name)[0];
            $label  = $labels[$module] ?? ucfirst(str_replace('_', ' ', $module));

            $grouped[$label][] = $permission;
        });

        ksort($grouped);

        return $grouped;
    }

    private function canAssignPermissions(): bool
    {
        return auth()->user()?->can('roles.assign_permissions') ?? false;
    }
}
