<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    /**
     * Cache keys constants
     */
    const DASHBOARD_STATS = 'dashboard_stats';
    const DASHBOARD_RECENT_ARTICLES = 'dashboard_recent_articles';
    const EXPLOITANTS_STATS = 'exploitants_stats_';
    const ARTICLES_STATS = 'articles_stats_';
    const USER_PERMISSIONS = 'user_permissions_';

    /**
     * Cache TTL constants (in seconds)
     */
    const TTL_SHORT = 60;      // 1 minute
    const TTL_MEDIUM = 300;    // 5 minutes
    const TTL_LONG = 1800;     // 30 minutes
    const TTL_VERY_LONG = 3600; // 1 hour

    /**
     * Get cached data or store it if not exists
     */
    public static function remember(string $key, int $ttl, callable $callback, array $tags = [])
    {
        try {
            if (!empty($tags)) {
                return Cache::tags($tags)->remember($key, $ttl, $callback);
            }
            
            return Cache::remember($key, $ttl, $callback);
        } catch (\Exception $e) {
            Log::warning("Cache error for key {$key}: " . $e->getMessage());
            return $callback();
        }
    }

    /**
     * Store data in cache
     */
    public static function put(string $key, $value, int $ttl = self::TTL_MEDIUM, array $tags = [])
    {
        try {
            if (!empty($tags)) {
                Cache::tags($tags)->put($key, $value, $ttl);
            } else {
                Cache::put($key, $value, $ttl);
            }
        } catch (\Exception $e) {
            Log::warning("Cache put error for key {$key}: " . $e->getMessage());
        }
    }

    /**
     * Get data from cache
     */
    public static function get(string $key, $default = null, array $tags = [])
    {
        try {
            if (!empty($tags)) {
                return Cache::tags($tags)->get($key, $default);
            }
            
            return Cache::get($key, $default);
        } catch (\Exception $e) {
            Log::warning("Cache get error for key {$key}: " . $e->getMessage());
            return $default;
        }
    }

    /**
     * Forget cached data
     */
    public static function forget(string $key, array $tags = [])
    {
        try {
            if (!empty($tags)) {
                Cache::tags($tags)->forget($key);
            } else {
                Cache::forget($key);
            }
        } catch (\Exception $e) {
            Log::warning("Cache forget error for key {$key}: " . $e->getMessage());
        }
    }

    /**
     * Clear cache by tags
     */
    public static function clearByTags(array $tags)
    {
        try {
            Cache::tags($tags)->flush();
        } catch (\Exception $e) {
            Log::warning("Cache clear by tags error: " . $e->getMessage());
        }
    }

    /**
     * Clear all cache
     */
    public static function clearAll()
    {
        try {
            Cache::flush();
        } catch (\Exception $e) {
            Log::warning("Cache clear all error: " . $e->getMessage());
        }
    }

    /**
     * Generate cache key with parameters
     */
    public static function generateKey(string $prefix, array $params = []): string
    {
        if (empty($params)) {
            return $prefix;
        }

        return $prefix . '_' . md5(serialize($params));
    }

    /**
     * Cache dashboard statistics
     */
    public static function cacheDashboardStats(callable $callback)
    {
        return self::remember(self::DASHBOARD_STATS, self::TTL_MEDIUM, $callback);
    }

    /**
     * Cache recent articles
     */
    public static function cacheRecentArticles(callable $callback)
    {
        return self::remember(self::DASHBOARD_RECENT_ARTICLES, self::TTL_SHORT, $callback);
    }

    /**
     * Cache exploitants statistics
     */
    public static function cacheExploitantsStats(array $filters, callable $callback)
    {
        $key = self::generateKey(self::EXPLOITANTS_STATS, $filters);
        return self::remember($key, self::TTL_SHORT, $callback);
    }

    /**
     * Cache user permissions
     */
    public static function cacheUserPermissions(int $userId, callable $callback)
    {
        $key = self::USER_PERMISSIONS . $userId;
        return self::remember($key, self::TTL_LONG, $callback);
    }

    /**
     * Clear dashboard cache
     */
    public static function clearDashboardCache()
    {
        self::forget(self::DASHBOARD_STATS);
        self::forget(self::DASHBOARD_RECENT_ARTICLES);
    }

    /**
     * Clear articles cache
     */
    public static function clearArticlesCache()
    {
        self::forget(self::DASHBOARD_RECENT_ARTICLES);
        // Clear all cache since we can't pattern match with file cache
        self::clearAll();
    }

    /**
     * Clear exploitants cache
     */
    public static function clearExploitantsCache()
    {
        // Clear all cache since we can't pattern match with file cache
        self::clearAll();
    }

    /**
     * Clear user cache
     */
    public static function clearUserCache(int $userId = null)
    {
        if ($userId) {
            self::forget(self::USER_PERMISSIONS . $userId);
        } else {
            // Clear all cache since we can't pattern match with file cache
            self::clearAll();
        }
    }
}
