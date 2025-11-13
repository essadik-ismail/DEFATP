<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\ContractController;

// Health Check Routes
Route::get('/health', [HealthController::class, 'index'])->name('health');
Route::get('/health/detailed', [HealthController::class, 'detailed'])->name('health.detailed');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/captcha/refresh', [AuthController::class, 'refreshCaptcha'])->name('captcha.refresh');
});

// Guest verification route (no authentication required)
Route::get('/verify-exploitant/{exploitant}', [SettingsController::class, 'verifyExploitant'])->name('verify-exploitant');

// Notification routes
Route::prefix('notifications')->name('notifications.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::get('/get', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('get');
    Route::patch('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::patch('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
    Route::delete('/delete-read', [App\Http\Controllers\NotificationController::class, 'deleteRead'])->name('delete-read');
    Route::get('/settings', [App\Http\Controllers\NotificationController::class, 'settings'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\NotificationController::class, 'updateSettings'])->name('update-settings');
    Route::get('/statistics', [App\Http\Controllers\NotificationController::class, 'statistics'])->name('statistics');
    Route::post('/send-test', [App\Http\Controllers\NotificationController::class, 'sendTest'])->name('send-test');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('auth.profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('auth.profile.update');
    
    // User Management Routes (Legacy - AuthController)
    Route::prefix('users')->name('auth.users.')->group(function () {
        Route::get('/', [AuthController::class, 'showUsers'])->name('index');
        Route::get('/create', [AuthController::class, 'showCreateUser'])->name('create');
        Route::post('/', [AuthController::class, 'storeUser'])->name('store');
        Route::get('/{user}/edit', [AuthController::class, 'showEditUser'])->name('edit');
        Route::put('/{user}', [AuthController::class, 'updateUser'])->name('update');
        Route::delete('/{user}', [AuthController::class, 'destroyUser'])->name('destroy');
    });

    // New User Management Routes (UserController with Spatie)
    Route::prefix('admin/users')->name('users.')->middleware('permission:users.view')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->middleware('permission:users.create')->name('create');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:users.create')->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->middleware('permission:users.edit')->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->middleware('permission:users.edit')->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('permission:users.delete')->name('destroy');
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->middleware('permission:users.edit')->name('toggle-status');
        Route::get('/export', [UserController::class, 'export'])->middleware('permission:users.view')->name('export');
    });

    // Activity Logs Routes
    Route::prefix('admin/activity-logs')->name('activity-logs.')->middleware('permission:activity-logs.view')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        Route::get('/user/{user}', [ActivityLogController::class, 'userActivity'])->name('user-activity');
        Route::get('/ajax/logs', [ActivityLogController::class, 'getActivityLogs'])->name('ajax-logs');
        Route::get('/export', [ActivityLogController::class, 'export'])->middleware('permission:activity-logs.export')->name('export');
        Route::get('/statistics', [ActivityLogController::class, 'getStatistics'])->name('statistics');
    });

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/exploitants', [SettingsController::class, 'exploitants'])->name('exploitants.index');
    Route::get('/exploitants/create', [SettingsController::class, 'createExploitant'])->name('exploitants.create');
    Route::post('/exploitants', [SettingsController::class, 'storeExploitant'])->name('exploitants.store');
    Route::get('/exploitants/{exploitant}', [SettingsController::class, 'showExploitant'])->name('exploitants.show');
    Route::get('/exploitants/{exploitant}/carte-professionnelle', [SettingsController::class, 'carteProfessionnelle'])->name('exploitants.carte-professionnelle');
    Route::get('/exploitants/{exploitant}/edit', [SettingsController::class, 'editExploitant'])->name('exploitants.edit');
    Route::put('/exploitants/{exploitant}', [SettingsController::class, 'updateExploitant'])->name('exploitants.update');
    Route::delete('/exploitants/{exploitant}', [SettingsController::class, 'destroyExploitant'])->name('exploitants.destroy');
    Route::get('/exploitants/export', [SettingsController::class, 'exportExploitants'])->name('exploitants.export');
    Route::post('/exploitants/import', [SettingsController::class, 'importExploitants'])->name('exploitants.import');
    
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    

    
    // Essences
    Route::get('/essences', [SettingsController::class, 'essences'])->name('essences');
    Route::get('/essences/create', [SettingsController::class, 'createEssence'])->name('essences.create');
    Route::post('/essences', [SettingsController::class, 'storeEssence'])->name('essences.store');
    Route::get('/essences/{essence}/edit', [SettingsController::class, 'editEssence'])->name('essences.edit');
    Route::put('/essences/{essence}', [SettingsController::class, 'updateEssence'])->name('essences.update');
    Route::delete('/essences/{essence}', [SettingsController::class, 'destroyEssence'])->name('essences.destroy');
    Route::get('/essences/export', [SettingsController::class, 'exportEssences'])->name('essences.export');
    Route::post('/essences/import', [SettingsController::class, 'importEssences'])->name('essences.import');
    
    // Forêts
    Route::get('/forets', [SettingsController::class, 'forets'])->name('forets');
    Route::get('/forets/map', [SettingsController::class, 'foretsMap'])->name('forets.map');
    Route::get('/forets/create', [SettingsController::class, 'createForet'])->name('forets.create');
    Route::post('/forets', [SettingsController::class, 'storeForet'])->name('forets.store');
    Route::get('/forets/{foret}/edit', [SettingsController::class, 'editForet'])->name('forets.edit');
    Route::put('/forets/{foret}', [SettingsController::class, 'updateForet'])->name('forets.update');
    Route::delete('/forets/{foret}', [SettingsController::class, 'destroyForet'])->name('forets.destroy');
    Route::get('/forets/export', [SettingsController::class, 'exportForets'])->name('forets.export');
    Route::post('/forets/import', [SettingsController::class, 'importForets'])->name('forets.import');
    
    // Nature de Coupes
    Route::get('/nature-de-coupes', [SettingsController::class, 'natureDeCoupes'])->name('nature-de-coupes');
    Route::get('/nature-de-coupes/create', [SettingsController::class, 'createNatureDeCoupe'])->name('nature-de-coupes.create');
    Route::post('/nature-de-coupes', [SettingsController::class, 'storeNatureDeCoupe'])->name('nature-de-coupes.store');
    Route::get('/nature-de-coupes/{natureDeCoupe}/edit', [SettingsController::class, 'editNatureDeCoupe'])->name('nature-de-coupes.edit');
    Route::put('/nature-de-coupes/{natureDeCoupe}', [SettingsController::class, 'updateNatureDeCoupe'])->name('nature-de-coupes.update');
    Route::delete('/nature-de-coupes/{natureDeCoupe}', [SettingsController::class, 'destroyNatureDeCoupe'])->name('nature-de-coupes.destroy');
    Route::get('/nature-de-coupes/export', [SettingsController::class, 'exportNatureDeCoupes'])->name('nature-de-coupes.export');
    Route::post('/nature-de-coupes/import', [SettingsController::class, 'importNatureDeCoupes'])->name('nature-de-coupes.import');
    
    // Situations Administratives
    Route::get('/situation-administratives', [SettingsController::class, 'situationAdministratives'])->name('situation-administratives');
    Route::get('/situation-administratives/create', [SettingsController::class, 'createSituationAdministrative'])->name('situation-administratives.create');
    Route::post('/situation-administratives', [SettingsController::class, 'storeSituationAdministrative'])->name('situation-administratives.store');
    Route::get('/situation-administratives/{situationAdministrative}/edit', [SettingsController::class, 'editSituationAdministrative'])->name('situation-administratives.edit');
    Route::put('/situation-administratives/{situationAdministrative}', [SettingsController::class, 'updateSituationAdministrative'])->name('situation-administratives.update');
    Route::delete('/situation-administratives/{situationAdministrative}', [SettingsController::class, 'destroySituationAdministrative'])->name('situation-administratives.destroy');
    Route::get('/situation-administratives/export', [SettingsController::class, 'exportSituationAdministratives'])->name('situation-administratives.export');
    Route::post('/situation-administratives/import', [SettingsController::class, 'importSituationAdministratives'])->name('situation-administratives.import');
    
    // Localisations
    Route::get('/localisations', [SettingsController::class, 'localisations'])->name('localisations');
    Route::get('/localisations/create', [SettingsController::class, 'createLocalisation'])->name('localisations.create');
    Route::post('/localisations', [SettingsController::class, 'storeLocalisation'])->name('localisations.store');
    Route::get('/localisations/{localisation}/edit', [SettingsController::class, 'editLocalisation'])->name('localisations.edit');
    Route::put('/localisations/{localisation}', [SettingsController::class, 'updateLocalisation'])->name('localisations.update');
    Route::delete('/localisations/{localisation}', [SettingsController::class, 'destroyLocalisation'])->name('localisations.destroy');
    Route::get('/localisations/export', [SettingsController::class, 'exportLocalisations'])->name('localisations.export');
    Route::post('/localisations/import', [SettingsController::class, 'importLocalisations'])->name('localisations.import');
    
});

    // Excel Import/Export Routes
    Route::prefix('excel')->name('excel.')->group(function () {
        Route::get('/', [ExcelController::class, 'index'])->name('index');
        Route::get('/export-all', [ExcelController::class, 'exportAll'])->name('export-all');
        Route::post('/import-all', [ExcelController::class, 'importAll'])->name('import-all');
        
        // Individual exports
        Route::get('/export/articles', [ExcelController::class, 'exportArticles'])->name('export.articles');
        Route::get('/export/essences', [ExcelController::class, 'exportEssences'])->name('export.essences');
        Route::get('/export/forets', [ExcelController::class, 'exportForets'])->name('export.forets');
        Route::get('/export/nature-de-coupes', [ExcelController::class, 'exportNatureDeCoupes'])->name('export.nature-de-coupes');
        Route::get('/export/situation-administratives', [ExcelController::class, 'exportSituationAdministratives'])->name('export.situation-administratives');
        Route::get('/export/exploitants', [ExcelController::class, 'exportExploitants'])->name('export.exploitants');

        Route::get('/export/localisations', [ExcelController::class, 'exportLocalisations'])->name('export.localisations');
        
        // Individual imports
        Route::post('/import/articles', [ExcelController::class, 'importArticles'])->name('import.articles');
        Route::post('/import/essences', [ExcelController::class, 'importEssences'])->name('import.essences');
        Route::post('/import/forets', [ExcelController::class, 'importForets'])->name('import.forets');
        Route::post('/import/nature-de-coupes', [ExcelController::class, 'importNatureDeCoupes'])->name('import.nature-de-coupes');
        Route::post('/import/situation-administratives', [ExcelController::class, 'importSituationAdministratives'])->name('import.situation-administratives');
        Route::post('/import/exploitants', [ExcelController::class, 'importExploitants'])->name('import.exploitants');

        Route::post('/import/localisations', [ExcelController::class, 'importLocalisations'])->name('import.localisations');
    });

    // Contracts Routes
    Route::resource('contracts', ContractController::class);
    
    // Contract Especes Routes
    Route::get('/contracts/especes/create', [ContractController::class, 'createEspece'])->name('contracts.especes.create');
    Route::post('/contracts/especes', [ContractController::class, 'storeEspece'])->name('contracts.especes.store');
    Route::get('/contracts/especes/{espece}/edit', [ContractController::class, 'editEspece'])->name('contracts.especes.edit');
    Route::put('/contracts/especes/{espece}', [ContractController::class, 'updateEspece'])->name('contracts.especes.update');
    Route::delete('/contracts/especes/{espece}', [ContractController::class, 'destroyEspece'])->name('contracts.especes.destroy');
    
    // Contract Avenants Routes
    Route::get('/contracts/avenants/create', [ContractController::class, 'createAvenant'])->name('contracts.avenants.create');
    Route::post('/contracts/avenants', [ContractController::class, 'storeAvenant'])->name('contracts.avenants.store');
    Route::get('/contracts/avenants/{avenant}/edit', [ContractController::class, 'editAvenant'])->name('contracts.avenants.edit');
    Route::put('/contracts/avenants/{avenant}', [ContractController::class, 'updateAvenant'])->name('contracts.avenants.update');
    Route::delete('/contracts/avenants/{avenant}', [ContractController::class, 'destroyAvenant'])->name('contracts.avenants.destroy');
    
    // Contract Coperatives Routes
    Route::get('/contracts/coperatives/create', [ContractController::class, 'createCoperative'])->name('contracts.coperatives.create');
    Route::post('/contracts/coperatives', [ContractController::class, 'storeCoperative'])->name('contracts.coperatives.store');
    Route::get('/contracts/coperatives/{coperative}/edit', [ContractController::class, 'editCoperative'])->name('contracts.coperatives.edit');
    Route::put('/contracts/coperatives/{coperative}', [ContractController::class, 'updateCoperative'])->name('contracts.coperatives.update');
    Route::delete('/contracts/coperatives/{coperative}', [ContractController::class, 'destroyCoperative'])->name('contracts.coperatives.destroy');
    
    // Contract Vocations Routes
    Route::get('/contracts/vocations/create', [ContractController::class, 'createVocation'])->name('contracts.vocations.create');
    Route::post('/contracts/vocations', [ContractController::class, 'storeVocation'])->name('contracts.vocations.store');
    Route::get('/contracts/vocations/{vocation}/edit', [ContractController::class, 'editVocation'])->name('contracts.vocations.edit');
    Route::put('/contracts/vocations/{vocation}', [ContractController::class, 'updateVocation'])->name('contracts.vocations.update');
    Route::delete('/contracts/vocations/{vocation}', [ContractController::class, 'destroyVocation'])->name('contracts.vocations.destroy');

    // Articles Routes
    Route::resource('articles', ArticleController::class);
    Route::get('/articles/export', [ArticleController::class, 'export'])->name('articles.export');
    Route::post('/articles/import', [ArticleController::class, 'import'])->name('articles.import');
    Route::post('/articles/{article}/import-locations', [ArticleController::class, 'importLocations'])->name('articles.import-locations');
    
    // Simple Article Creation Routes
    Route::get('/articles/create/simple', [ArticleController::class, 'createSimple'])->name('articles.create.simple');
    Route::post('/articles/store/simple', [ArticleController::class, 'storeSimple'])->name('articles.store.simple');
    Route::get('/articles/template/download', [ArticleController::class, 'downloadTemplate'])->name('articles.template.download');

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
        Route::get('/summary/export', [ReportController::class, 'exportSummary'])->name('summary.export');
        Route::get('/articles-by-year', [ReportController::class, 'articlesByYear'])->name('articles-by-year');
        Route::get('/articles-by-foret', [ReportController::class, 'articlesByForet'])->name('articles-by-foret');
        Route::get('/articles-by-essence', [ReportController::class, 'articlesByEssence'])->name('articles-by-essence');
        Route::get('/articles-by-exploitant', [ReportController::class, 'articlesByExploitant'])->name('articles-by-exploitant');
        Route::get('/articles-by-nature-de-coupe', [ReportController::class, 'articlesByNatureDeCoupe'])->name('articles-by-nature-de-coupe');
        Route::get('/articles-by-localisation', [ReportController::class, 'articlesByLocalisation'])->name('articles-by-localisation');
        Route::get('/articles-by-validation-status', [ReportController::class, 'articlesByValidationStatus'])->name('articles-by-validation-status');
        Route::get('/invendus', [ReportController::class, 'invendus'])->name('invendus');
        Route::get('/vendus', [ReportController::class, 'vendus'])->name('vendus');
        Route::get('/legacy-articles', [ReportController::class, 'legacyArticles'])->name('legacy-articles');
        Route::get('/legacy-articles-table', [ReportController::class, 'legacyArticlesTable'])->name('legacy-articles-table');
        Route::get('/legacy-articles-by-year', [ReportController::class, 'legacyArticlesByYear'])->name('legacy-articles-by-year');
        Route::get('/legacy-articles-by-province', [ReportController::class, 'legacyArticlesByProvince'])->name('legacy-articles-by-province');
        Route::get('/legacy-articles-by-essence', [ReportController::class, 'legacyArticlesByEssence'])->name('legacy-articles-by-essence');
        Route::get('/product-quantities-charts', [ReportController::class, 'productQuantitiesCharts'])->name('product-quantities-charts');
        Route::get('/legacy-quantities-charts', [ReportController::class, 'legacyQuantitiesCharts'])->name('legacy-quantities-charts');
        Route::get('/article-quantities-charts', [ReportController::class, 'articleQuantitiesCharts'])->name('article-quantities-charts');
        Route::get('/unified', [ReportController::class, 'unifiedReports'])->name('unified');
        Route::get('/unified-table', [ReportController::class, 'unifiedTable'])->name('unified-table');
    });

    
    
    // Test AJAX Route
    Route::post('/test-ajax', function () {
        return response()->json([
            'success' => true,
            'message' => 'AJAX test successful',
            'timestamp' => now()
        ]);
    })->name('test.ajax');
});
