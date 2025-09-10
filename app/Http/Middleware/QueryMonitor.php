<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class QueryMonitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.debug')) {
            DB::enableQueryLog();
        }

        $response = $next($request);

        if (config('app.debug')) {
            $queries = DB::getQueryLog();
            $queryCount = count($queries);
            $slowQueries = collect($queries)->filter(function ($query) {
                return $query['time'] > 100; // Queries slower than 100ms
            });

            if ($queryCount > 10) {
                Log::warning("High query count detected", [
                    'url' => $request->url(),
                    'method' => $request->method(),
                    'query_count' => $queryCount,
                    'queries' => $queries
                ]);
            }

            if ($slowQueries->isNotEmpty()) {
                Log::warning("Slow queries detected", [
                    'url' => $request->url(),
                    'method' => $request->method(),
                    'slow_queries' => $slowQueries->toArray()
                ]);
            }
        }

        return $response;
    }
}
