<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Seeds Spatie roles and permissions aligned with the workflow business rules.
 * Safe to re-run: uses firstOrCreate.
 */
class WorkflowRolesSeeder extends Seeder
{
    // Permissions grouped by domain
    const PERMISSIONS = [
        // Articles
        'article.create',
        'article.edit',
        'article.delete',
        'article.view',
        // Contract ventes
        'contract.create',
        'contract.edit',
        'contract.view',
        // Lettre adjudicataire
        'letter.generate',
        'letter.upload_signed',
        // Payments
        'payment.manage',
        // Permits
        'permit_exploiter.create',
        'permit_exploiter.view',
        // PV installation
        'pv_installation.create',
        // Vehicle declarations
        'vehicle.declare',
        // Colportage
        'colportage.create',
        'colportage.view',
        // Carnets
        'carnet.manage',
        // Prorogation
        'prorogation.request',
        'prorogation.approve',
        // Récolement / mainlevée
        'recolement.submit',
        'mainlevee.issue',
        // Alerts
        'alert.view',
        'alert.archive',
        // User management
        'user.manage',
        // Settings
        'settings.manage',
    ];

    const ROLE_PERMISSIONS = [
        UserRole::Admin->value => '*', // all
        UserRole::Agency->value => [
            'article.view', 'contract.view', 'permit_exploiter.view', 'colportage.view',
            'alert.view', 'payment.manage', 'letter.generate', 'letter.upload_signed',
        ],
        UserRole::Zdtf->value => [
            'article.create', 'article.edit', 'article.view',
            'contract.create', 'contract.edit', 'contract.view',
            'letter.generate', 'letter.upload_signed',
            'payment.manage',
            'permit_exploiter.create', 'permit_exploiter.view',
            'alert.view',
        ],
        UserRole::Brigade->value => [
            'article.view', 'contract.view',
            'vehicle.declare',
            'colportage.create', 'colportage.view',
            'carnet.manage',
            'pv_installation.create',
            'alert.view',
        ],
        UserRole::Dfp->value => [
            'article.view', 'contract.view',
            'vehicle.declare',
            'colportage.create', 'colportage.view',
            'carnet.manage',
            'pv_installation.create',
            'alert.view',
        ],
        UserRole::Dpanef->value => [
            'article.view', 'contract.view',
            'recolement.submit',
            'alert.view',
        ],
        UserRole::Dranef->value => [
            'article.view', 'contract.view',
            'prorogation.approve',
            'mainlevee.issue',
            'alert.view', 'alert.archive',
        ],
        UserRole::ProvinceCommission->value => [
            'article.view', 'contract.view',
            'recolement.submit',
            'alert.view',
        ],
        UserRole::Supervisor->value => [
            'article.view', 'contract.view', 'permit_exploiter.view',
            'colportage.view', 'alert.view',
        ],
    ];

    public function run(): void
    {
        // Ensure all permissions exist
        foreach (self::PERMISSIONS as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        foreach (self::ROLE_PERMISSIONS as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            if ($permissions === '*') {
                $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions($permissions);
            }
        }

        $this->command->info('Workflow roles and permissions seeded.');
    }
}
