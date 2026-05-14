<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Exploitant;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class QueryOptimizer
{
    /**
     * Get optimized dashboard statistics
     */
    public static function getDashboardStats(): array
    {
        return Cache::remember('dashboard_stats_optimized', 300, function () {
            // Use raw SQL for better performance
            // Articles use SoftDeletes (deleted_at); invendu = sold when 0, unsold when 1
            $stats = DB::select("
                SELECT
                    (SELECT COUNT(*) FROM articles WHERE deleted_at IS NULL) as total_articles,
                    (SELECT COUNT(*) FROM articles WHERE deleted_at IS NULL AND invendu = 0) as sold_articles,
                    (SELECT COUNT(*) FROM articles WHERE deleted_at IS NULL AND invendu = 1) as unsold_articles,
                    (SELECT COUNT(*) FROM exploitants WHERE is_deleted = 0) as total_exploitants,
                    (SELECT COUNT(*) FROM users WHERE is_deleted = 0) as total_users
            ")[0];

            return [
                'total_articles' => $stats->total_articles,
                'sold_articles' => $stats->sold_articles,
                'unsold_articles' => $stats->unsold_articles,
                'total_sales' => 0,
                'total_exploitants' => $stats->total_exploitants,
                'total_users' => $stats->total_users,
                'sold_percentage' => $stats->total_articles > 0 ? 
                    round(($stats->sold_articles / $stats->total_articles) * 100, 2) : 0,
                'unsold_percentage' => $stats->total_articles > 0 ? 
                    round(($stats->unsold_articles / $stats->total_articles) * 100, 2) : 0,
            ];
        });
    }

    /**
     * Get optimized articles with relationships
     */
    public static function getArticlesWithRelations(array $filters = [], int $perPage = 15)
    {
        $cacheKey = 'articles_optimized_' . md5(serialize($filters)) . '_' . $perPage;
        
        return Cache::remember($cacheKey, 120, function () use ($filters, $perPage) {
            $query = Article::select(['id', 'numero', 'lot', 'parcelle', 'superficie', 'invendu', 'created_at', 'updated_at'])
                ->with(['forets:id,foret', 'essences:id,essence', 'provinces:id,nom', 'communes:id,nom']);

            if (isset($filters['search']) && $filters['search']) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('numero', 'like', "%{$search}%")
                      ->orWhere('lot', 'like', "%{$search}%")
                      ->orWhere('parcelle', 'like', "%{$search}%")
                      ->orWhereHas('forets', fn ($q) => $q->where('foret', 'like', "%{$search}%"))
                      ->orWhereHas('essences', fn ($q) => $q->where('essence', 'like', "%{$search}%"));
                });
            }

            if (isset($filters['year'])) {
                $query->whereYear('created_at', $filters['year']);
            }

            if (isset($filters['foret_id'])) {
                $query->whereHas('forets', fn ($q) => $q->where('forets.id', $filters['foret_id']));
            }

            if (isset($filters['essence_id'])) {
                $query->whereHas('essences', fn ($q) => $q->where('essences.id', $filters['essence_id']));
            }

            return $query->orderBy('created_at', 'desc')->paginate($perPage);
        });
    }

    /**
     * Get optimized exploitants with filters
     */
    public static function getExploitantsWithFilters(array $filters = [], int $perPage = 15)
    {
        $cacheKey = 'exploitants_optimized_' . md5(serialize($filters)) . '_' . $perPage;
        
        return Cache::remember($cacheKey, 120, function () use ($filters, $perPage) {
            $query = Exploitant::select([
                'id', 'numero', 'nom_complet', 'raison_sociale', 'n_cin', 'categorie', 
                'activite', 'adjudicataire', 'adresse', 'qualification_rc', 
                'date_obtention', 'duree_validite', 'exclusion', 'duree_exclusion',
                'created_at', 'updated_at', 'is_deleted'
            ]);

            // Apply filters using optimized scopes
            if (isset($filters['search']) && $filters['search']) {
                $search = $filters['search'];
                $query->where(function($q) use ($search) {
                    $q->where('nom_complet', 'like', "%{$search}%")
                      ->orWhere('raison_sociale', 'like', "%{$search}%")
                      ->orWhere('n_cin', 'like', "%{$search}%")
                      ->orWhere('numero', 'like', "%{$search}%");
                });
            }

            if (isset($filters['categorie'])) {
                if ($filters['categorie'] === 'societe') {
                    $query->companies();
                } elseif ($filters['categorie'] === 'personne_physique') {
                    $query->individuals();
                }
            }

            if (isset($filters['activite'])) {
                if ($filters['activite'] === 'BI') {
                    $query->BI();
                } elseif ($filters['activite'] === 'BO') {
                    $query->BO();
                } elseif ($filters['activite'] === 'PAM') {
                    $query->PAM();
                }
            }

            if (isset($filters['exclusion'])) {
                if ($filters['exclusion'] === 'active') {
                    $query->active();
                } elseif ($filters['exclusion'] === 'excluded') {
                    $query->excluded();
                }
            }

            if (isset($filters['adjudicataire'])) {
                if ($filters['adjudicataire'] === 'true') {
                    $query->adjudicataires();
                } elseif ($filters['adjudicataire'] === 'false') {
                    $query->nonAdjudicataires();
                }
            }

            if (isset($filters['qualification'])) {
                $query->byQualification($filters['qualification']);
            }

            if (isset($filters['permit_status'])) {
                if ($filters['permit_status'] === 'valid') {
                    $query->validPermits();
                } elseif ($filters['permit_status'] === 'expired') {
                    $query->expiredPermits();
                }
            }

            if (isset($filters['start_date']) || isset($filters['end_date'])) {
                $query->dateRange($filters['start_date'] ?? null, $filters['end_date'] ?? null);
            }

            if (isset($filters['recent_days'])) {
                $query->recent($filters['recent_days']);
            }

            return $query->orderBy('created_at', 'desc')->paginate($perPage);
        });
    }

    /**
     * Get recent activity with optimized queries
     */
    public static function getRecentActivity(int $limit = 10)
    {
        return Cache::remember("recent_activity_{$limit}", 60, function () use ($limit) {
            return DB::table('activity_logs')
                ->select([
                    'activity_logs.id',
                    'activity_logs.action',
                    'activity_logs.description',
                    'activity_logs.created_at',
                    'users.name as user_name',
                    'users.ppr as user_ppr'
                ])
                ->join('users', 'activity_logs.user_id', '=', 'users.id')
                ->where('users.is_deleted', false)
                ->orderBy('activity_logs.created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get statistics for reports
     */
    public static function getReportStatistics(array $filters = []): array
    {
        $cacheKey = 'report_stats_' . md5(serialize($filters));
        
        return Cache::remember($cacheKey, 600, function () use ($filters) {
            $year = (int) ($filters['year'] ?? date('Y'));

            $stats = DB::select("
                SELECT 
                    COUNT(*) as total_articles,
                    SUM(CASE WHEN invendu = 0 THEN 1 ELSE 0 END) as sold_count,
                    SUM(CASE WHEN invendu = 1 THEN 1 ELSE 0 END) as unsold_count
                FROM articles 
                WHERE YEAR(created_at) = ? AND deleted_at IS NULL
            ", [$year])[0];

            return [
                'year' => $year,
                'total_articles' => $stats->total_articles,
                'sold_count' => $stats->sold_count,
                'unsold_count' => $stats->unsold_count,
                'total_sales' => 0,
                'total_retrait' => 0,
                'avg_price' => 0,
                'total_volume' => 0,
                'sold_percentage' => $stats->total_articles > 0
                    ? round(($stats->sold_count / $stats->total_articles) * 100, 2) : 0,
            ];
        });
    }

    /**
     * Clear all caches
     */
    public static function clearAllCaches(): void
    {
        Cache::flush();
    }

    /**
     * Clear specific cache patterns
     */
    public static function clearCachePattern(string $pattern): void
    {
        // For file cache, we'll clear all cache since we can't pattern match
        // In production with Redis, this would be more sophisticated
        Cache::flush();
    }
}
