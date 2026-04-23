<?php

namespace App\Enums;

/**
 * Single source of truth for every permission slug in the app.
 *
 * Usage:
 *   $user->can(Permission::CESSION_CREATE)
 *   @can(Permission::ARTICLE_VIEW)
 *   ->middleware('can:' . Permission::ALERTS_VIEW)
 */
final class Permission
{
    // ── Authentication & accounts ─────────────────────────────────────────
    const AUTH_LOGIN       = 'auth.login';
    const ACCOUNTS_CREATE  = 'accounts.create';

    // ── Cessions ──────────────────────────────────────────────────────────
    const CESSION_CREATE   = 'cession.create';
    const CESSION_UPDATE   = 'cession.update';
    const CESSION_CLOSE    = 'cession.close';
    const CESSION_VIEW     = 'cession.view';

    // ── Articles ──────────────────────────────────────────────────────────
    const ARTICLE_CREATE   = 'article.create';
    const ARTICLE_UPDATE   = 'article.update';
    const ARTICLE_VIEW     = 'article.view';

    // ── Contrats de vente ─────────────────────────────────────────────────
    const CONTRACT_SALE_CREATE   = 'contract.sale.create';
    const CONTRACT_SALE_UPDATE   = 'contract.sale.update';
    const CONTRACT_SALE_GENERATE = 'contract.sale.generate';

    // ── Exploitants ───────────────────────────────────────────────────────
    const EXPLOITANT_CREATE = 'exploitant.create';
    const EXPLOITANT_UPDATE = 'exploitant.update';

    // ── Lettre adjudicataire ──────────────────────────────────────────────
    const ADJUDICATAIRE_LETTER_VIEW         = 'adjudicataire_letter.view';
    const ADJUDICATAIRE_LETTER_DOWNLOAD     = 'adjudicataire_letter.download';
    const ADJUDICATAIRE_LETTER_UPLOAD_SIGNED = 'adjudicataire_letter.upload_signed';

    // ── Paiement de caution ───────────────────────────────────────────────
    const CAUTION_PAYMENT_CREATE = 'caution_payment.create';
    const CAUTION_PAYMENT_UPDATE = 'caution_payment.update';
    const CAUTION_PAYMENT_VIEW   = 'caution_payment.view';

    // ── Paiement des taxes ────────────────────────────────────────────────
    const TAX_PAYMENT_CREATE = 'tax_payment.create';
    const TAX_PAYMENT_UPDATE = 'tax_payment.update';
    const TAX_PAYMENT_VIEW   = 'tax_payment.view';

    // ── PV d'installation ─────────────────────────────────────────────────
    const INSTALLATION_REPORT_CREATE               = 'installation_report.create';
    const INSTALLATION_REPORT_UPDATE               = 'installation_report.update';
    const INSTALLATION_REPORT_VIEW                 = 'installation_report.view';
    const INSTALLATION_REPORT_DOWNLOAD_FOR_SIGNATURE = 'installation_report.download_for_signature';
    const INSTALLATION_REPORT_UPLOAD_SIGNED        = 'installation_report.upload_signed';

    // ── Permis d'exploiter ────────────────────────────────────────────────
    const OPERATING_PERMIT_CREATE               = 'operating_permit.create';
    const OPERATING_PERMIT_UPDATE               = 'operating_permit.update';
    const OPERATING_PERMIT_VIEW                 = 'operating_permit.view';
    const OPERATING_PERMIT_DOWNLOAD_FOR_SIGNATURE = 'operating_permit.download_for_signature';
    const OPERATING_PERMIT_UPLOAD_SIGNED        = 'operating_permit.upload_signed';

    // ── Véhicules ─────────────────────────────────────────────────────────
    const VEHICLE_CREATE = 'vehicle.create';
    const VEHICLE_VIEW   = 'vehicle.view';
    const VEHICLE_UPDATE = 'vehicle.update';

    // ── Paiement de tranche ───────────────────────────────────────────────
    const INSTALLMENT_PAYMENT_CREATE = 'installment_payment.create';
    const INSTALLMENT_PAYMENT_UPDATE = 'installment_payment.update';
    const INSTALLMENT_PAYMENT_VIEW   = 'installment_payment.view';

    // ── Permis d'enlever ──────────────────────────────────────────────────
    const REMOVAL_PERMIT_CREATE               = 'removal_permit.create';
    const REMOVAL_PERMIT_UPDATE               = 'removal_permit.update';
    const REMOVAL_PERMIT_VIEW                 = 'removal_permit.view';
    const REMOVAL_PERMIT_DOWNLOAD_FOR_SIGNATURE = 'removal_permit.download_for_signature';
    const REMOVAL_PERMIT_UPLOAD_SIGNED        = 'removal_permit.upload_signed';

    // ── Permis de colportage ──────────────────────────────────────────────
    const HAWKING_PERMIT_CREATE        = 'hawking_permit.create';
    const HAWKING_PERMIT_UPDATE        = 'hawking_permit.update';
    const HAWKING_PERMIT_VIEW          = 'hawking_permit.view';
    const HAWKING_PERMIT_UPLOAD_SIGNED = 'hawking_permit.upload_signed';
    const HAWKING_PERMIT_LOSS_DECLARE  = 'hawking_permit.loss_declare';

    // ── PV de récolement ──────────────────────────────────────────────────
    const RECOLEMENT_REPORT_CREATE               = 'recolement_report.create';
    const RECOLEMENT_REPORT_UPDATE               = 'recolement_report.update';
    const RECOLEMENT_REPORT_VIEW                 = 'recolement_report.view';
    const RECOLEMENT_REPORT_DOWNLOAD_FOR_SIGNATURE = 'recolement_report.download_for_signature';
    const RECOLEMENT_REPORT_UPLOAD_SIGNED        = 'recolement_report.upload_signed';

    // ── Prorogation ───────────────────────────────────────────────────────
    const EXTENSION_CREATE = 'extension.create';

    // ── Main levée ────────────────────────────────────────────────────────
    const RELEASE_CREATE = 'release.create';
    const RELEASE_VIEW   = 'release.view';

    // ── Résiliation & déchéance ───────────────────────────────────────────
    const TERMINATION_CREATE = 'termination.create';
    const FORFEITURE_CREATE  = 'forfeiture.create';

    // ── Carnet de colportage ──────────────────────────────────────────────
    const HAWKING_BOOK_CREATE = 'hawking_book.create';

    // ── Alertes ───────────────────────────────────────────────────────────
    const ALERTS_VIEW = 'alerts.view';

    // ── Journal d'activité ────────────────────────────────────────────────
    const ACTIVITY_LOGS_VIEW = 'activity_logs.view';

    // ── Gestion des rôles ─────────────────────────────────────────────────
    const ROLES_VIEW               = 'roles.view';
    const ROLES_CREATE             = 'roles.create';
    const ROLES_UPDATE             = 'roles.update';
    const ROLES_DELETE             = 'roles.delete';
    const ROLES_ASSIGN_PERMISSIONS = 'roles.assign_permissions';

    // ── Gestion des utilisateurs ──────────────────────────────────────────
    const USERS_VIEW         = 'users.view';
    const USERS_CREATE       = 'users.create';
    const USERS_UPDATE       = 'users.update';
    const USERS_DELETE       = 'users.delete';
    const USERS_ASSIGN_ROLES = 'users.assign_roles';

    // ─────────────────────────────────────────────────────────────────────
    // Convenience: return every permission slug as a flat array.
    // Used by the seeder to create all Permission records.
    // ─────────────────────────────────────────────────────────────────────
    public static function all(): array
    {
        $reflection = new \ReflectionClass(self::class);
        return array_values($reflection->getConstants());
    }
}
