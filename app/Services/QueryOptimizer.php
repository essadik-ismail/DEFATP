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
            $stats = DB::select("
                SELECT 
                    (SELECT COUNT(*) FROM articles WHERE is_deleted = 0) as total_articles,
                    (SELECT COUNT(*) FROM articles WHERE is_deleted = 0 AND invendu = 0) as sold_articles,
                    (SELECT COUNT(*) FROM articles WHERE is_deleted = 0 AND invendu = 1) as unsold_articles,
                    (SELECT COALESCE(SUM(prix_vente), 0) FROM articles WHERE is_deleted = 0) as total_sales,
                    (SELECT COUNT(*) FROM exploitants WHERE is_deleted = 0) as total_exploitants,
                    (SELECT COUNT(*) FROM users WHERE is_deleted = 0) as total_users
            ")[0];

            return [
                'total_articles' => $stats->total_articles,
                'sold_articles' => $stats->sold_articles,
                'unsold_articles' => $stats->unsold_articles,
                'total_sales' => $stats->total_sales,
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
            $query = Article::select([
                'id', 'numero', 'annee', 'date_adjudication', 'invendu', 'prix_vente', 
                'prix_de_retrait', 'foret_id', 'essence_id', 'localisation_id', 
                'situation_administrative_id', 'exploitant_id', 'nature_de_coupe_id',
                'created_at', 'updated_at', 'is_validated', 'type'
            ])
            ->with([
                'foret:id,foret',
                'essence:id,essence', 
                'localisation:id,code',
                'situationAdministrative:id,commune',
                'exploitant:id,nom_complet,raison_sociale',
                'natureDeCoupe:id,nature_de_coupe'
            ]);

            // Apply filters using optimized scopes
            if (isset($filters['search']) && $filters['search']) {
                $search = $filters['search'];
                $query->where(function($q) use ($search) {
                    $q->where('numero', 'like', "%{$search}%")
                      ->orWhere('annee', 'like', "%{$search}%")
                      ->orWhereHas('foret', function($foretQuery) use ($search) {
                          $foretQuery->where('foret', 'like', "%{$search}%");
                      })
                      ->orWhereHas('essence', function($essenceQuery) use ($search) {
                          $essenceQuery->where('essence', 'like', "%{$search}%");
                      });
                });
            }

            if (isset($filters['status'])) {
                if ($filters['status'] === 'validated') {
                    $query->validated();
                } elseif ($filters['status'] === 'pending') {
                    $query->pending();
                } elseif ($filters['status'] === 'sold') {
                    $query->sold();
                } elseif ($filters['status'] === 'unsold') {
                    $query->unsold();
                }
            }

            if (isset($filters['type'])) {
                $query->byType($filters['type']);
            }

            if (isset($filters['year'])) {
                $query->byYear($filters['year']);
            }

            if (isset($filters['foret_id'])) {
                $query->byForest($filters['foret_id']);
            }

            if (isset($filters['essence_id'])) {
                $query->byEssence($filters['essence_id']);
            }

            if (isset($filters['min_price']) || isset($filters['max_price'])) {
                $query->priceRange($filters['min_price'] ?? null, $filters['max_price'] ?? null);
            }

            if (isset($filters['start_date']) || isset($filters['end_date'])) {
                $query->dateRange($filters['start_date'] ?? null, $filters['end_date'] ?? null);
            }

            if (isset($filters['recent_days'])) {
                $query->recent($filters['recent_days']);
            }

            if (isset($filters['high_value'])) {
                $query->highValue($filters['high_value']);
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
                } elseif ($filters['activite'] === 'BP') {
                    $query->BP();
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
            $year = $filters['year'] ?? date('Y');
            
            $stats = DB::select("
                SELECT 
                    COUNT(*) as total_articles,
                    SUM(CASE WHEN invendu = 0 THEN 1 ELSE 0 END) as sold_count,
                    SUM(CASE WHEN invendu = 1 THEN 1 ELSE 0 END) as unsold_count,
                    COALESCE(SUM(prix_vente), 0) as total_sales,
                    COALESCE(SUM(prix_de_retrait), 0) as total_retrait,
                    COALESCE(AVG(prix_vente), 0) as avg_price,
                    COALESCE(SUM(bo_m3 + bi_m3), 0) as total_volume
                FROM articles 
                WHERE annee = ? AND is_deleted = 0
            ", [$year])[0];

            return [
                'year' => $year,
                'total_articles' => $stats->total_articles,
                'sold_count' => $stats->sold_count,
                'unsold_count' => $stats->unsold_count,
                'total_sales' => $stats->total_sales,
                'total_retrait' => $stats->total_retrait,
                'avg_price' => round($stats->avg_price, 2),
                'total_volume' => $stats->total_volume,
                'sold_percentage' => $stats->total_articles > 0 ? 
                    round(($stats->sold_count / $stats->total_articles) * 100, 2) : 0,
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
