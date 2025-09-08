<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log suspicious activity
        $this->logSuspiciousActivity($request);

        // Check for common attack patterns
        if ($this->detectAttackPatterns($request)) {
            Log::warning('Potential attack detected', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'headers' => $request->headers->all()
            ]);

            return response()->json([
                'error' => 'Request blocked for security reasons'
            ], 403);
        }

        // Rate limiting for API endpoints
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = $this->getMaxAttempts($request);
        $decayMinutes = $this->getDecayMinutes($request);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            Log::warning('Rate limit exceeded', [
                'ip' => $request->ip(),
                'key' => $key,
                'attempts' => RateLimiter::attempts($key)
            ]);

            return response()->json([
                'error' => 'Too many requests. Please try again later.',
                'retry_after' => RateLimiter::availableIn($key)
            ], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        // Add security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }

    /**
     * Detect common attack patterns
     */
    private function detectAttackPatterns(Request $request): bool
    {
        $patterns = [
            // SQL Injection patterns
            '/(\bunion\b.*\bselect\b)/i',
            '/(\bselect\b.*\bfrom\b)/i',
            '/(\binsert\b.*\binto\b)/i',
            '/(\bupdate\b.*\bset\b)/i',
            '/(\bdelete\b.*\bfrom\b)/i',
            '/(\bdrop\b.*\btable\b)/i',
            '/(\balter\b.*\btable\b)/i',
            '/(\bexec\b|\bexecute\b)/i',
            '/(\bscript\b.*\b>)/i',
            '/(<script)/i',
            '/(javascript:)/i',
            '/(vbscript:)/i',
            '/(onload\s*=)/i',
            '/(onerror\s*=)/i',
            '/(onclick\s*=)/i',
            // Path traversal
            '/(\.\.\/|\.\.\\\\)/',
            '/(\/etc\/passwd|\/etc\/shadow)/i',
            '/(\/windows\/system32)/i',
            // Command injection
            '/(\||&|;|\$\(|\`)/',
            '/(cmd\.exe|powershell|bash|sh)/i',
        ];

        $input = $request->getContent() . ' ' . $request->getQueryString();
        $input .= ' ' . implode(' ', $request->all());

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log suspicious activity
     */
    private function logSuspiciousActivity(Request $request): void
    {
        $suspiciousHeaders = [
            'X-Forwarded-For' => $request->header('X-Forwarded-For'),
            'X-Real-IP' => $request->header('X-Real-IP'),
            'User-Agent' => $request->userAgent(),
        ];

        // Check for suspicious user agents
        $suspiciousUserAgents = [
            'sqlmap', 'nikto', 'nmap', 'masscan', 'zap', 'burp', 'w3af',
            'havij', 'sqlninja', 'pangolin', 'sqlsus', 'marathon',
            'absinthe', 'bsqlbf', 'jsql', 'sqlpowerinjector'
        ];

        $userAgent = strtolower($request->userAgent());
        foreach ($suspiciousUserAgents as $suspicious) {
            if (strpos($userAgent, $suspicious) !== false) {
                Log::warning('Suspicious user agent detected', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl()
                ]);
                break;
            }
        }

        // Check for multiple IPs in forwarded headers (potential proxy abuse)
        if ($request->header('X-Forwarded-For') && 
            count(explode(',', $request->header('X-Forwarded-For'))) > 3) {
            Log::warning('Multiple IPs in X-Forwarded-For header', [
                'ip' => $request->ip(),
                'forwarded_for' => $request->header('X-Forwarded-For'),
                'url' => $request->fullUrl()
            ]);
        }
    }

    /**
     * Resolve request signature for rate limiting
     */
    private function resolveRequestSignature(Request $request): string
    {
        if ($user = $request->user()) {
            return 'api:' . $user->id;
        }

        return 'api:' . $request->ip();
    }

    /**
     * Get max attempts based on request type
     */
    private function getMaxAttempts(Request $request): int
    {
        if ($request->is('api/auth/*')) {
            return 5; // Stricter for auth endpoints
        }

        if ($request->is('api/export/*')) {
            return 3; // Very strict for export endpoints
        }

        return 60; // Default for other API endpoints
    }

    /**
     * Get decay minutes based on request type
     */
    private function getDecayMinutes(Request $request): int
    {
        if ($request->is('api/auth/*')) {
            return 15; // 15 minutes for auth endpoints
        }

        if ($request->is('api/export/*')) {
            return 5; // 5 minutes for export endpoints
        }

        return 1; // 1 minute for other endpoints
    }
}
