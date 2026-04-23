<?php

namespace App\Providers;

use App\Models\Alert;
use App\Models\Article;
use App\Policies\ArticlePolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Admins bypass all Gate/Policy checks
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        Gate::policy(Article::class, ArticlePolicy::class);

        Paginator::useBootstrapFive();

        // Share the active alert count with every view so the sidebar badge
        // stays up-to-date without a query in each controller.
        View::composer('layouts.app', function ($view) {
            $view->with('sidebarAlertCount', Alert::active()->warningOrAbove()->count());
        });
    }
}
