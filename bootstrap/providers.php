<?php

return [
    App\Providers\AppServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    App\Providers\ActivityLogServiceProvider::class,
    
    // Dusk service provider for testing
    ...(app()->environment('local', 'testing') ? [
        Laravel\Dusk\DuskServiceProvider::class,
    ] : []),
];
