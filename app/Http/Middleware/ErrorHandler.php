<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
            
            // Check if there are any validation errors
            if ($response->getStatusCode() === 422) {
                $errors = session('errors');
                if ($errors) {
                    // Store errors in session for display
                    session()->flash('error', 'Veuillez corriger les erreurs dans le formulaire.');
                }
            }
            
            return $response;
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Application error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request->fullUrl(),
                'user_id' => auth()->id(),
            ]);
            
            // Show user-friendly error message
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Une erreur est survenue. Veuillez réessayer.',
                    'message' => config('app.debug') ? $e->getMessage() : 'Erreur interne du serveur'
                ], 500);
            }
            
            // For web requests, redirect with error message
            session()->flash('error', 'Une erreur inattendue est survenue. Veuillez réessayer.');
            
            if ($request->is('articles/*')) {
                return redirect()->route('articles.index');
            }
            
            if ($request->is('settings/*')) {
                return redirect()->route('settings.index');
            }
            
            return redirect()->route('dashboard');
        }
    }
}
