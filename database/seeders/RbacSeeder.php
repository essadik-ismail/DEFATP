<?php

namespace Database\Seeders;

use App\Enums\Permission as P;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Single source of truth for all roles and permissions.
 *
 * Permission matrix (columns = role groups):
 *   admin | central | dranef | dpanef | cpf | zdtf | brigade/dfp
 *
 * zdtfdpanef is computed as the union of: dpanef ∪ zdtf ∪ cpf
 *
 * Safe to re-run: firstOrCreate + syncPermissions are idempotent.
 */
class RbacSeeder extends Seeder
{
    // ─────────────────────────────────────────────────────────────────────────
    // Role → permission matrix
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Permissions assigned to the `admin` role.
     * Admin gets every permission — defined explicitly so it's auditable.
     */
    private function adminPermissions(): array
    {
        return P::all();
    }

    /**
     * Permissions assigned to `central`.
     * Read-only oversight at national level + alerts.
     */
    private function centralPermissions(): array
    {
        return [
            P::AUTH_LOGIN,
            P::CESSION_VIEW,
            P::ARTICLE_VIEW,
            P::ADJUDICATAIRE_LETTER_VIEW,
            P::CAUTION_PAYMENT_VIEW,
            P::TAX_PAYMENT_VIEW,
            P::INSTALLATION_REPORT_VIEW,
            P::OPERATING_PERMIT_VIEW,
            P::VEHICLE_VIEW,
            P::INSTALLMENT_PAYMENT_VIEW,
            P::REMOVAL_PERMIT_VIEW,
            P::HAWKING_PERMIT_VIEW,
            P::RECOLEMENT_REPORT_VIEW,
            P::RELEASE_VIEW,
            P::ALERTS_VIEW,
        ];
    }

    /**
     * Permissions assigned to `dranef`.
     * Regional directorate: approves prorogations, issues mainlevée,
     * closes cessions, creates résiliations and déchéances.
     */
    private function dranefPermissions(): array
    {
        return [
            P::AUTH_LOGIN,
            P::CESSION_CLOSE,
            P::CESSION_VIEW,
            P::ARTICLE_VIEW,
            P::ADJUDICATAIRE_LETTER_VIEW,
            P::CAUTION_PAYMENT_VIEW,
            P::TAX_PAYMENT_VIEW,
            P::INSTALLATION_REPORT_VIEW,
            P::OPERATING_PERMIT_VIEW,
            P::VEHICLE_VIEW,
            P::INSTALLMENT_PAYMENT_VIEW,
            P::REMOVAL_PERMIT_VIEW,
            P::HAWKING_PERMIT_VIEW,
            P::RECOLEMENT_REPORT_VIEW,
            P::RELEASE_CREATE,
            P::RELEASE_VIEW,
            P::TERMINATION_CREATE,
            P::FORFEITURE_CREATE,
            P::ALERTS_VIEW,
        ];
    }

    /**
     * Permissions assigned to `dpanef`.
     * Provincial directorate: supervises, submits PV de récolement.
     */
    private function dpanefPermissions(): array
    {
        return [
            P::AUTH_LOGIN,
            P::CESSION_VIEW,
            P::ARTICLE_VIEW,
            P::ADJUDICATAIRE_LETTER_VIEW,
            P::CAUTION_PAYMENT_VIEW,
            P::TAX_PAYMENT_VIEW,
            P::INSTALLATION_REPORT_VIEW,
            P::OPERATING_PERMIT_VIEW,
            P::VEHICLE_VIEW,
            P::INSTALLMENT_PAYMENT_VIEW,
            P::REMOVAL_PERMIT_VIEW,
            P::HAWKING_PERMIT_VIEW,
            P::RECOLEMENT_REPORT_CREATE,
            P::RECOLEMENT_REPORT_UPDATE,
            P::RECOLEMENT_REPORT_VIEW,
            P::RECOLEMENT_REPORT_DOWNLOAD_FOR_SIGNATURE,
            P::RECOLEMENT_REPORT_UPLOAD_SIGNED,
            P::RELEASE_VIEW,
            P::ALERTS_VIEW,
        ];
    }

    /**
     * Permissions assigned to `cpf`.
     * Commission Provinciale des Forêts: submits PV de récolement.
     * Kept separate from zdtf even though identical for now,
     * because they may diverge in the future.
     */
    private function cpfPermissions(): array
    {
        return [
            P::AUTH_LOGIN,
            P::CESSION_VIEW,
            P::ARTICLE_VIEW,
            P::INSTALLATION_REPORT_VIEW,
            P::OPERATING_PERMIT_VIEW,
            P::VEHICLE_VIEW,
            P::REMOVAL_PERMIT_VIEW,
            P::HAWKING_PERMIT_VIEW,
            P::RECOLEMENT_REPORT_CREATE,
            P::RECOLEMENT_REPORT_UPDATE,
            P::RECOLEMENT_REPORT_VIEW,
            P::RECOLEMENT_REPORT_DOWNLOAD_FOR_SIGNATURE,
            P::RECOLEMENT_REPORT_UPLOAD_SIGNED,
            P::ALERTS_VIEW,
        ];
    }

    /**
     * Permissions assigned to `zdtf`.
     * Field zone: creates articles, contracts, exploitants,
     * manages permits, payments, and requests prorogations.
     */
    private function zdtfPermissions(): array
    {
        return [
            P::AUTH_LOGIN,
            P::CESSION_CREATE,
            P::CESSION_UPDATE,
            P::CESSION_VIEW,
            P::ARTICLE_CREATE,
            P::ARTICLE_UPDATE,
            P::ARTICLE_VIEW,
            P::CONTRACT_SALE_CREATE,
            P::CONTRACT_SALE_UPDATE,
            P::CONTRACT_SALE_GENERATE,
            P::EXPLOITANT_CREATE,
            P::EXPLOITANT_UPDATE,
            P::ADJUDICATAIRE_LETTER_VIEW,
            P::ADJUDICATAIRE_LETTER_DOWNLOAD,
            P::ADJUDICATAIRE_LETTER_UPLOAD_SIGNED,
            P::CAUTION_PAYMENT_CREATE,
            P::CAUTION_PAYMENT_UPDATE,
            P::CAUTION_PAYMENT_VIEW,
            P::TAX_PAYMENT_CREATE,
            P::TAX_PAYMENT_UPDATE,
            P::TAX_PAYMENT_VIEW,
            P::INSTALLATION_REPORT_VIEW,
            P::OPERATING_PERMIT_CREATE,
            P::OPERATING_PERMIT_UPDATE,
            P::OPERATING_PERMIT_VIEW,
            P::OPERATING_PERMIT_DOWNLOAD_FOR_SIGNATURE,
            P::OPERATING_PERMIT_UPLOAD_SIGNED,
            P::VEHICLE_VIEW,
            P::INSTALLMENT_PAYMENT_CREATE,
            P::INSTALLMENT_PAYMENT_UPDATE,
            P::INSTALLMENT_PAYMENT_VIEW,
            P::REMOVAL_PERMIT_VIEW,
            P::HAWKING_PERMIT_VIEW,
            P::RECOLEMENT_REPORT_VIEW,
            P::EXTENSION_CREATE,
            P::ALERTS_VIEW,
        ];
    }

    /**
     * Permissions assigned to `brigade` and `dfp`.
     * Field units: declare vehicles, issue colportage permits, manage carnets.
     */
    private function brigadeDfpPermissions(): array
    {
        return [
            P::AUTH_LOGIN,
            P::CESSION_VIEW,
            P::ARTICLE_VIEW,
            P::INSTALLATION_REPORT_CREATE,
            P::INSTALLATION_REPORT_UPDATE,
            P::INSTALLATION_REPORT_VIEW,
            P::INSTALLATION_REPORT_DOWNLOAD_FOR_SIGNATURE,
            P::INSTALLATION_REPORT_UPLOAD_SIGNED,
            P::OPERATING_PERMIT_VIEW,
            P::VEHICLE_CREATE,
            P::VEHICLE_VIEW,
            P::VEHICLE_UPDATE,
            P::REMOVAL_PERMIT_CREATE,
            P::REMOVAL_PERMIT_UPDATE,
            P::REMOVAL_PERMIT_VIEW,
            P::REMOVAL_PERMIT_DOWNLOAD_FOR_SIGNATURE,
            P::REMOVAL_PERMIT_UPLOAD_SIGNED,
            P::HAWKING_PERMIT_CREATE,
            P::HAWKING_PERMIT_UPDATE,
            P::HAWKING_PERMIT_VIEW,
            P::HAWKING_PERMIT_UPLOAD_SIGNED,
            P::HAWKING_PERMIT_LOSS_DECLARE,
            P::RECOLEMENT_REPORT_VIEW,
            P::HAWKING_BOOK_CREATE,
            P::ALERTS_VIEW,
        ];
    }

    /**
     * zdtfdpanef = dpanef ∪ zdtf ∪ cpf (union, no duplicates).
     *
     * This is a superset role. Its permissions are computed at runtime so
     * you only need to update the individual role methods — zdtfdpanef
     * always stays in sync automatically.
     */
    private function zdtfDpanefPermissions(): array
    {
        return array_values(array_unique(array_merge(
            $this->dpanefPermissions(),
            $this->zdtfPermissions(),
            $this->cpfPermissions(),
        )));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Seeder entry point
    // ─────────────────────────────────────────────────────────────────────────

    public function run(): void
    {
        // Clear Spatie's in-memory and cache-layer permission cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 1 ── Create every permission in the system (includes roles.*, users.*, activity_logs.*)
        foreach (P::all() as $slug) {
            Permission::firstOrCreate(['name' => $slug, 'guard_name' => 'web']);
        }

        // 2 ── Define role → permission method map
        $matrix = [
            UserRole::Admin->value      => $this->adminPermissions(),
            UserRole::Central->value    => $this->centralPermissions(),
            UserRole::Dranef->value     => $this->dranefPermissions(),
            UserRole::Dpanef->value     => $this->dpanefPermissions(),
            UserRole::Cpf->value        => $this->cpfPermissions(),
            UserRole::Zdtf->value       => $this->zdtfPermissions(),
            UserRole::Brigade->value    => $this->brigadeDfpPermissions(),
            UserRole::Dfp->value        => $this->brigadeDfpPermissions(),
            UserRole::ZdtfDpanef->value => $this->zdtfDpanefPermissions(),
        ];

        // 3 ── Create roles and sync permissions
        foreach ($matrix as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
        }

        // 4 ── Clear cache again so fresh permissions are used immediately
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('✓ RBAC seeded: ' . count($matrix) . ' roles, ' . count(P::all()) . ' permissions.');
    }
}
