<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Article;
use App\Models\User;
use App\Services\ArticleWorkflowService;

/**
 * Record-level authorization for Articles.
 *
 * All methods delegate to a named permission so the policy stays
 * decoupled from role names. To restrict a specific record
 * (e.g. "only the creator can edit"), add the condition here —
 * the permission check remains the base requirement.
 */
class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ARTICLE_VIEW);
    }

    public function view(User $user, Article $article): bool
    {
        return $user->can(Permission::ARTICLE_VIEW);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::ARTICLE_CREATE);
    }

    public function update(User $user, Article $article): bool
    {
        if (!$user->can(Permission::ARTICLE_UPDATE)) {
            return false;
        }

        // Article is locked once it leaves the DRAFT_ARTICLE state
        $state = $article->workflow_state ?? ArticleWorkflowService::DRAFT_ARTICLE;
        return $state === ArticleWorkflowService::DRAFT_ARTICLE;
    }

    /**
     * Example of a record-level restriction layered on top of a permission.
     * Only users who can create articles AND belong to the article's zdtf
     * may delete it — or admins who bypass all restrictions via the before hook.
     */
    public function delete(User $user, Article $article): bool
    {
        if (!$user->can(Permission::ARTICLE_DELETE)) {
            return false;
        }

        // Record-level: full org hierarchy must match, not just zdtf
        return $user->zdtf_id !== null
            && $user->zdtf_id === $article->zdtf_id
            && $user->dranef_id === $article->dranef_id;
    }

    public function generateContract(User $user, Article $article): bool
    {
        return $user->can(Permission::CONTRACT_SALE_GENERATE);
    }

    public function managePayments(User $user, Article $article): bool
    {
        return $user->can(Permission::CAUTION_PAYMENT_CREATE)
            || $user->can(Permission::INSTALLMENT_PAYMENT_CREATE);
    }
}
