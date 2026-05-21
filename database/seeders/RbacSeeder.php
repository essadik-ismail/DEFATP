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
 *   admin | central | dranef | dpanef | cpf_zdtf | brigade_dfp
 *
 * zdtfdpanef is computed as the union of: dpanef ∪ cpf_zdtf
 * Legacy roles cpf, zdtf, brigade, dfp share permissions with their replacements.
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
     * National supervision: read-only + upload on all document types.
     */
    private function centralPermissions(): array
    {
        return [
            P::AUTH_LOGIN,
            P::CESSION_VIEW,
            P::ARTICLE_VIEW,
            P::CONTRACT_SALE_VIEW,
            P::ADJUDICATAIRE_LETTER_VIEW,
            P::ADJUDICATAIRE_LETTER_DOWNLOAD,
            P::CAUTION_PAYMENT_VIEW,
            P::TAX_PAYMENT_VIEW,
            P::INSTALLMENT_PAYMENT_VIEW,
            P::INSTALLATION_REPORT_VIEW,
            P::INSTALLATION_REPORT_DOWNLOAD_FOR_SIGNATURE,
            P::INSTALLATION_REPORT_UPLOAD_SIGNED,
            P::OPERATING_PERMIT_VIEW,
            P::OPERATING_PERMIT_DOWNLOAD_FOR_SIGNATURE,
            P::OPERATING_PERMIT_UPLOAD_SIGNED,
            P::VEHICLE_VIEW,
            P::REMOVAL_PERMIT_VIEW,
            P::REMOVAL_PERMIT_DOWNLOAD_FOR_SIGNATURE,
            P::REMOVAL_PERMIT_UPLOAD_SIGNED,
            P::HAWKING_PERMIT_VIEW,
            P::HAWKING_PERMIT_UPLOAD_SIGNED,
            P::RECOLEMENT_REPORT_VIEW,
            P::RECOLEMENT_REPORT_DOWNLOAD_FOR_SIGNATURE,
            P::RECOLEMENT_REPORT_UPLOAD_SIGNED,
            P::RELEASE_VIEW,
            P::ALERTS_VIEW,
        ];
    }

    /**
     * Permissions assigned to `dranef`.
     * Regional management: full create/edit on cessions, articles, contracts,
     * exploitants, payments; view/download/upload on installation and permits.
     */
    private function dranefPermissions(): array
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
            P::CONTRACT_SALE_VIEW,
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
            P::INSTALLMENT_PAYMENT_CREATE,
            P::INSTALLMENT_PAYMENT_UPDATE,
            P::INSTALLMENT_PAYMENT_VIEW,
            P::INSTALLATION_REPORT_VIEW,
            P::INSTALLATION_REPORT_DOWNLOAD_FOR_SIGNATURE,
            P::INSTALLATION_REPORT_UPLOAD_SIGNED,
            P::OPERATING_PERMIT_VIEW,
            P::OPERATING_PERMIT_DOWNLOAD_FOR_SIGNATURE,
            P::OPERATING_PERMIT_UPLOAD_SIGNED,
            P::VEHICLE_CREATE,
            P::VEHICLE_UPDATE,
            P::VEHICLE_VIEW,
            P::REMOVAL_PERMIT_VIEW,
            P::REMOVAL_PERMIT_DOWNLOAD_FOR_SIGNATURE,
            P::REMOVAL_PERMIT_UPLOAD_SIGNED,
            P::HAWKING_PERMIT_VIEW,
            P::HAWKING_PERMIT_UPLOAD_SIGNED,
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
     * Permissions assigned to `dpanef`.
     * Provincial document saisie: creates PV installation, permis exploiter,
     * véhicules, permis enlever/colportage, récolement, prorogation, mainlevée.
     */
    private function dpanefPermissions(): array
    {
        return [
            P::AUTH_LOGIN,
            P::CESSION_VIEW,
            P::ARTICLE_VIEW,
            P::CONTRACT_SALE_VIEW,
            P::ADJUDICATAIRE_LETTER_VIEW,
            P::ADJUDICATAIRE_LETTER_DOWNLOAD,
            P::CAUTION_PAYMENT_VIEW,
            P::TAX_PAYMENT_VIEW,
            P::INSTALLMENT_PAYMENT_VIEW,
            P::INSTALLATION_REPORT_CREATE,
            P::INSTALLATION_REPORT_UPDATE,
            P::INSTALLATION_REPORT_VIEW,
            P::INSTALLATION_REPORT_DOWNLOAD_FOR_SIGNATURE,
            P::INSTALLATION_REPORT_UPLOAD_SIGNED,
            P::OPERATING_PERMIT_CREATE,
            P::OPERATING_PERMIT_UPDATE,
            P::OPERATING_PERMIT_VIEW,
            P::OPERATING_PERMIT_DOWNLOAD_FOR_SIGNATURE,
            P::OPERATING_PERMIT_UPLOAD_SIGNED,
            P::VEHICLE_CREATE,
            P::VEHICLE_UPDATE,
            P::VEHICLE_VIEW,
            P::REMOVAL_PERMIT_CREATE,
            P::REMOVAL_PERMIT_UPDATE,
            P::REMOVAL_PERMIT_VIEW,
            P::REMOVAL_PERMIT_DOWNLOAD_FOR_SIGNATURE,
            P::REMOVAL_PERMIT_UPLOAD_SIGNED,
            P::HAWKING_PERMIT_CREATE,
            P::HAWKING_PERMIT_UPDATE,
            P::HAWKING_PERMIT_VIEW,
            P::HAWKING_PERMIT_UPLOAD_SIGNED,
            P::HAWKING_BOOK_CREATE,
            P::HAWKING_PERMIT_LOSS_DECLARE,
            P::RECOLEMENT_REPORT_CREATE,
            P::RECOLEMENT_REPORT_UPDATE,
            P::RECOLEMENT_REPORT_VIEW,
            P::RECOLEMENT_REPORT_DOWNLOAD_FOR_SIGNATURE,
            P::RECOLEMENT_REPORT_UPLOAD_SIGNED,
            P::EXTENSION_CREATE,
            P::RELEASE_CREATE,
            P::RELEASE_VIEW,
            P::ALERTS_VIEW,
        ];
    }

    /**
     * Permissions assigned to `cpf_zdtf` (and legacy `cpf` / `zdtf`).
     * Field post: creates/manages permis enlever and colportage; view-only elsewhere.
     */
    private function cpfZdtfPermissions(): array
    {
        return [
            P::AUTH_LOGIN,
            P::CESSION_VIEW,
            P::ARTICLE_VIEW,
            P::CONTRACT_SALE_VIEW,
            P::ADJUDICATAIRE_LETTER_VIEW,
            P::CAUTION_PAYMENT_VIEW,
            P::TAX_PAYMENT_VIEW,
            P::INSTALLMENT_PAYMENT_VIEW,
            P::INSTALLATION_REPORT_VIEW,
            P::OPERATING_PERMIT_VIEW,
            P::VEHICLE_VIEW,
            P::REMOVAL_PERMIT_CREATE,
            P::REMOVAL_PERMIT_UPDATE,
            P::REMOVAL_PERMIT_VIEW,
            P::REMOVAL_PERMIT_DOWNLOAD_FOR_SIGNATURE,
            P::REMOVAL_PERMIT_UPLOAD_SIGNED,
            P::HAWKING_PERMIT_CREATE,
            P::HAWKING_PERMIT_UPDATE,
            P::HAWKING_PERMIT_VIEW,
            P::HAWKING_PERMIT_UPLOAD_SIGNED,
            P::HAWKING_BOOK_CREATE,
            P::HAWKING_PERMIT_LOSS_DECLARE,
            P::RECOLEMENT_REPORT_VIEW,
            P::ALERTS_VIEW,
        ];
    }

    /**
     * Permissions assigned to `brigade_dfp` (and legacy `brigade` / `dfp`).
     * Field control: read-only + limited download on permits.
     */
    private function brigadeDfpPermissions(): array
    {
        return [
            P::AUTH_LOGIN,
            P::CESSION_VIEW,
            P::ARTICLE_VIEW,
            P::CONTRACT_SALE_VIEW,
            P::ADJUDICATAIRE_LETTER_VIEW,
            P::ADJUDICATAIRE_LETTER_DOWNLOAD,
            P::CAUTION_PAYMENT_VIEW,
            P::TAX_PAYMENT_VIEW,
            P::INSTALLMENT_PAYMENT_VIEW,
            P::INSTALLATION_REPORT_VIEW,
            P::OPERATING_PERMIT_VIEW,
            P::VEHICLE_VIEW,
            P::REMOVAL_PERMIT_VIEW,
            P::REMOVAL_PERMIT_DOWNLOAD_FOR_SIGNATURE,
            P::HAWKING_PERMIT_VIEW,
            P::RECOLEMENT_REPORT_VIEW,
            P::ALERTS_VIEW,
        ];
    }

    /**
     * zdtfdpanef = dpanef ∪ cpf_zdtf (union, no duplicates).
     *
     * Superset role — computed at runtime so individual methods stay in sync.
     */
    private function zdtfDpanefPermissions(): array
    {
        return array_values(array_unique(array_merge(
            $this->dpanefPermissions(),
            $this->cpfZdtfPermissions(),
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
        $cpfZdtf    = $this->cpfZdtfPermissions();
        $brigadeDfp = $this->brigadeDfpPermissions();

        $matrix = [
            UserRole::Admin->value      => $this->adminPermissions(),
            UserRole::Central->value    => $this->centralPermissions(),
            UserRole::Dranef->value     => $this->dranefPermissions(),
            UserRole::Dpanef->value     => $this->dpanefPermissions(),
            UserRole::ZdtfDpanef->value => $this->zdtfDpanefPermissions(),
            UserRole::CpfZdtf->value    => $cpfZdtf,
            UserRole::BrigadeDfp->value => $brigadeDfp,
            // Legacy roles — same permissions as their replacements
            UserRole::Cpf->value        => $cpfZdtf,
            UserRole::Zdtf->value       => $cpfZdtf,
            UserRole::Brigade->value    => $brigadeDfp,
            UserRole::Dfp->value        => $brigadeDfp,
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
