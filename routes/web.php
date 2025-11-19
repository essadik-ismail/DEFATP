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
use App\Http\Controllers\OdfController;
use App\Http\Controllers\PdfcController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\EtapeController;

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
Route::prefix('notifications')->name('notifications.')->middleware(['auth', 'permission:notifications.view'])->group(function () {
    Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::get('/get', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('get');
    Route::patch('/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::patch('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->middleware('permission:notifications.delete')->name('destroy');
    Route::delete('/delete-read', [App\Http\Controllers\NotificationController::class, 'deleteRead'])->middleware('permission:notifications.delete')->name('delete-read');
    Route::get('/settings', [App\Http\Controllers\NotificationController::class, 'settings'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\NotificationController::class, 'updateSettings'])->middleware('permission:notifications.update')->name('update-settings');
    Route::get('/statistics', [App\Http\Controllers\NotificationController::class, 'statistics'])->name('statistics');
    Route::post('/send-test', [App\Http\Controllers\NotificationController::class, 'sendTest'])->middleware('permission:notifications.create')->name('send-test');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('auth.profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('auth.profile.update');
    
    // Activity Journals Routes
    Route::resource('activity-journals', \App\Http\Controllers\ActivityJournalController::class);
    
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
        Route::post('/{user}/assign-roles-permissions', [UserController::class, 'assignRolesPermissions'])->middleware('permission:users.edit')->name('assign-roles-permissions');
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
    Route::get('/', [DashboardController::class, 'index'])->middleware('permission:dashboard.view')->name('dashboard');

    Route::prefix('exploitants')->name('exploitants.')->middleware('permission:exploitants.view')->group(function () {
        Route::get('/', [SettingsController::class, 'exploitants'])->name('index');
        Route::get('/create', [SettingsController::class, 'createExploitant'])->middleware('permission:exploitants.create')->name('create');
        Route::post('/', [SettingsController::class, 'storeExploitant'])->middleware('permission:exploitants.create')->name('store');
        Route::get('/{exploitant}', [SettingsController::class, 'showExploitant'])->name('show');
        Route::get('/{exploitant}/carte-professionnelle', [SettingsController::class, 'carteProfessionnelle'])->name('carte-professionnelle');
        Route::get('/{exploitant}/edit', [SettingsController::class, 'editExploitant'])->middleware('permission:exploitants.update')->name('edit');
        Route::put('/{exploitant}', [SettingsController::class, 'updateExploitant'])->middleware('permission:exploitants.update')->name('update');
        Route::delete('/{exploitant}', [SettingsController::class, 'destroyExploitant'])->middleware('permission:exploitants.delete')->name('destroy');
        Route::get('/export', [SettingsController::class, 'exportExploitants'])->middleware('permission:exploitants.export')->name('export');
        Route::post('/import', [SettingsController::class, 'importExploitants'])->middleware('permission:exploitants.import')->name('import');
    });
    
    Route::prefix('coperatives')->name('coperatives.')->middleware('permission:coperatives.view')->group(function () {
        Route::get('/', [SettingsController::class, 'coperatives'])->name('index');
    });
    
    // ODF Routes
    Route::prefix('odfs')->name('odfs.')->middleware('permission:odfs.view')->group(function () {
        Route::get('/', [OdfController::class, 'index'])->name('index');
        Route::get('/create', [OdfController::class, 'create'])->middleware('permission:odfs.create')->name('create');
        Route::post('/', [OdfController::class, 'store'])->middleware('permission:odfs.create')->name('store');
        Route::get('/{odf}', [OdfController::class, 'show'])->name('show');
        Route::get('/{odf}/edit', [OdfController::class, 'edit'])->middleware('permission:odfs.update')->name('edit');
        Route::put('/{odf}', [OdfController::class, 'update'])->middleware('permission:odfs.update')->name('update');
        Route::delete('/{odf}', [OdfController::class, 'destroy'])->middleware('permission:odfs.delete')->name('destroy');
    });
    
    // PDFC Routes
    Route::prefix('pdfcs')->name('pdfcs.')->middleware('permission:pdfcs.view')->group(function () {
        Route::get('/', [PdfcController::class, 'index'])->name('index');
        Route::get('/create', [PdfcController::class, 'create'])->middleware('permission:pdfcs.create')->name('create');
        Route::post('/', [PdfcController::class, 'store'])->middleware('permission:pdfcs.create')->name('store');
        Route::get('/{pdfc}', [PdfcController::class, 'show'])->name('show');
        Route::get('/{pdfc}/edit', [PdfcController::class, 'edit'])->middleware('permission:pdfcs.update')->name('edit');
        Route::put('/{pdfc}', [PdfcController::class, 'update'])->middleware('permission:pdfcs.update')->name('update');
        Route::post('/{pdfc}/transition-state', [PdfcController::class, 'transitionState'])->middleware('permission:pdfcs.update')->name('transition-state');
        Route::delete('/{pdfc}', [PdfcController::class, 'destroy'])->middleware('permission:pdfcs.delete')->name('destroy');
        
        // Phase Routes
        Route::prefix('{pdfc}/phases')->name('phases.')->middleware('permission:pdfcs.update')->group(function () {
            Route::get('/create', [PhaseController::class, 'create'])->name('create');
            Route::post('/', [PhaseController::class, 'store'])->name('store');
            Route::get('/{phase}/edit', [PhaseController::class, 'edit'])->name('edit');
            Route::put('/{phase}', [PhaseController::class, 'update'])->name('update');
            Route::delete('/{phase}', [PhaseController::class, 'destroy'])->name('destroy');
            Route::post('/{phase}/validate', [PhaseController::class, 'validatePhase'])->name('validate');
        });
        
        // Etape Routes
        Route::prefix('{pdfc}/phases/{phase}/etapes')->name('etapes.')->middleware('permission:pdfcs.update')->group(function () {
            Route::get('/create', [EtapeController::class, 'create'])->name('create');
            Route::post('/', [EtapeController::class, 'store'])->name('store');
            Route::get('/{etape}/edit', [EtapeController::class, 'edit'])->name('edit');
            Route::put('/{etape}', [EtapeController::class, 'update'])->name('update');
            Route::delete('/{etape}', [EtapeController::class, 'destroy'])->name('destroy');
            Route::post('/{etape}/validate', [EtapeController::class, 'validateEtape'])->name('validate');
            Route::post('/{etape}/reject', [EtapeController::class, 'rejectEtape'])->name('reject');
        });
    });
    
    // Settings Routes
    // Unified Entity Data Management
    Route::get('/entity-data', [App\Http\Controllers\EntityDataController::class, 'index'])->middleware('permission:entity-data.view')->name('entity-data.index');

    Route::prefix('settings')->name('settings.')->middleware('permission:settings.view')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        
        // Essences
        Route::prefix('essences')->name('essences.')->middleware('permission:essences.view')->group(function () {
            Route::get('/', [SettingsController::class, 'essences'])->name('index');
            Route::get('/create', [SettingsController::class, 'createEssence'])->middleware('permission:essences.create')->name('create');
            Route::post('/', [SettingsController::class, 'storeEssence'])->middleware('permission:essences.create')->name('store');
            Route::get('/{essence}/edit', [SettingsController::class, 'editEssence'])->middleware('permission:essences.update')->name('edit');
            Route::put('/{essence}', [SettingsController::class, 'updateEssence'])->middleware('permission:essences.update')->name('update');
            Route::delete('/{essence}', [SettingsController::class, 'destroyEssence'])->middleware('permission:essences.delete')->name('destroy');
            Route::get('/export', [SettingsController::class, 'exportEssences'])->middleware('permission:essences.export')->name('export');
            Route::post('/import', [SettingsController::class, 'importEssences'])->middleware('permission:essences.import')->name('import');
        });
        
        // Forêts
        Route::prefix('forets')->name('forets.')->middleware('permission:forets.view')->group(function () {
            Route::get('/', [SettingsController::class, 'forets'])->name('index');
            Route::get('/map', [SettingsController::class, 'foretsMap'])->name('map');
            Route::get('/create', [SettingsController::class, 'createForet'])->middleware('permission:forets.create')->name('create');
            Route::post('/', [SettingsController::class, 'storeForet'])->middleware('permission:forets.create')->name('store');
            Route::get('/{foret}/edit', [SettingsController::class, 'editForet'])->middleware('permission:forets.update')->name('edit');
            Route::put('/{foret}', [SettingsController::class, 'updateForet'])->middleware('permission:forets.update')->name('update');
            Route::delete('/{foret}', [SettingsController::class, 'destroyForet'])->middleware('permission:forets.delete')->name('destroy');
            Route::get('/export', [SettingsController::class, 'exportForets'])->middleware('permission:forets.export')->name('export');
            Route::post('/import', [SettingsController::class, 'importForets'])->middleware('permission:forets.import')->name('import');
        });
        
        // Nature de Coupes
        Route::prefix('nature-de-coupes')->name('nature-de-coupes.')->middleware('permission:nature-de-coupes.view')->group(function () {
            Route::get('/', [SettingsController::class, 'natureDeCoupes'])->name('index');
            Route::get('/create', [SettingsController::class, 'createNatureDeCoupe'])->middleware('permission:nature-de-coupes.create')->name('create');
            Route::post('/', [SettingsController::class, 'storeNatureDeCoupe'])->middleware('permission:nature-de-coupes.create')->name('store');
            Route::get('/{natureDeCoupe}/edit', [SettingsController::class, 'editNatureDeCoupe'])->middleware('permission:nature-de-coupes.update')->name('edit');
            Route::put('/{natureDeCoupe}', [SettingsController::class, 'updateNatureDeCoupe'])->middleware('permission:nature-de-coupes.update')->name('update');
            Route::delete('/{natureDeCoupe}', [SettingsController::class, 'destroyNatureDeCoupe'])->middleware('permission:nature-de-coupes.delete')->name('destroy');
            Route::get('/export', [SettingsController::class, 'exportNatureDeCoupes'])->middleware('permission:nature-de-coupes.export')->name('export');
            Route::post('/import', [SettingsController::class, 'importNatureDeCoupes'])->middleware('permission:nature-de-coupes.import')->name('import');
        });
        
        // Situations Administratives
        Route::prefix('situation-administratives')->name('situation-administratives.')->middleware('permission:situation-administratives.view')->group(function () {
            Route::get('/', [SettingsController::class, 'situationAdministratives'])->name('index');
            Route::get('/create', [SettingsController::class, 'createSituationAdministrative'])->middleware('permission:situation-administratives.create')->name('create');
            Route::post('/', [SettingsController::class, 'storeSituationAdministrative'])->middleware('permission:situation-administratives.create')->name('store');
            Route::get('/{situationAdministrative}/edit', [SettingsController::class, 'editSituationAdministrative'])->middleware('permission:situation-administratives.update')->name('edit');
            Route::put('/{situationAdministrative}', [SettingsController::class, 'updateSituationAdministrative'])->middleware('permission:situation-administratives.update')->name('update');
            Route::delete('/{situationAdministrative}', [SettingsController::class, 'destroySituationAdministrative'])->middleware('permission:situation-administratives.delete')->name('destroy');
            Route::get('/export', [SettingsController::class, 'exportSituationAdministratives'])->middleware('permission:situation-administratives.export')->name('export');
            Route::post('/import', [SettingsController::class, 'importSituationAdministratives'])->middleware('permission:situation-administratives.import')->name('import');
        });
        
        // Localisations
        Route::prefix('localisations')->name('localisations.')->middleware('permission:localisations.view')->group(function () {
            Route::get('/', [SettingsController::class, 'localisations'])->name('index');
            Route::get('/create', [SettingsController::class, 'createLocalisation'])->middleware('permission:localisations.create')->name('create');
            Route::post('/', [SettingsController::class, 'storeLocalisation'])->middleware('permission:localisations.create')->name('store');
            Route::get('/{localisation}/edit', [SettingsController::class, 'editLocalisation'])->middleware('permission:localisations.update')->name('edit');
            Route::put('/{localisation}', [SettingsController::class, 'updateLocalisation'])->middleware('permission:localisations.update')->name('update');
            Route::delete('/{localisation}', [SettingsController::class, 'destroyLocalisation'])->middleware('permission:localisations.delete')->name('destroy');
            Route::get('/export', [SettingsController::class, 'exportLocalisations'])->middleware('permission:localisations.export')->name('export');
            Route::post('/import', [SettingsController::class, 'importLocalisations'])->middleware('permission:localisations.import')->name('import');
        });
    });

    // Excel Import/Export Routes
    Route::prefix('excel')->name('excel.')->middleware('permission:excel.view')->group(function () {
        Route::get('/', [ExcelController::class, 'index'])->name('index');
        Route::get('/export-all', [ExcelController::class, 'exportAll'])->middleware('permission:excel.export')->name('export-all');
        Route::post('/import-all', [ExcelController::class, 'importAll'])->middleware('permission:excel.import')->name('import-all');
        
        // Individual exports
        Route::get('/export/articles', [ExcelController::class, 'exportArticles'])->middleware('permission:excel.export')->name('export.articles');
        Route::get('/export/essences', [ExcelController::class, 'exportEssences'])->middleware('permission:excel.export')->name('export.essences');
        Route::get('/export/forets', [ExcelController::class, 'exportForets'])->middleware('permission:excel.export')->name('export.forets');
        Route::get('/export/nature-de-coupes', [ExcelController::class, 'exportNatureDeCoupes'])->middleware('permission:excel.export')->name('export.nature-de-coupes');
        Route::get('/export/situation-administratives', [ExcelController::class, 'exportSituationAdministratives'])->middleware('permission:excel.export')->name('export.situation-administratives');
        Route::get('/export/exploitants', [ExcelController::class, 'exportExploitants'])->middleware('permission:excel.export')->name('export.exploitants');
        Route::get('/export/localisations', [ExcelController::class, 'exportLocalisations'])->middleware('permission:excel.export')->name('export.localisations');
        
        // Individual imports
        Route::post('/import/articles', [ExcelController::class, 'importArticles'])->middleware('permission:excel.import')->name('import.articles');
        Route::post('/import/essences', [ExcelController::class, 'importEssences'])->middleware('permission:excel.import')->name('import.essences');
        Route::post('/import/forets', [ExcelController::class, 'importForets'])->middleware('permission:excel.import')->name('import.forets');
        Route::post('/import/nature-de-coupes', [ExcelController::class, 'importNatureDeCoupes'])->middleware('permission:excel.import')->name('import.nature-de-coupes');
        Route::post('/import/situation-administratives', [ExcelController::class, 'importSituationAdministratives'])->middleware('permission:excel.import')->name('import.situation-administratives');
        Route::post('/import/exploitants', [ExcelController::class, 'importExploitants'])->middleware('permission:excel.import')->name('import.exploitants');
        Route::post('/import/localisations', [ExcelController::class, 'importLocalisations'])->middleware('permission:excel.import')->name('import.localisations');
    });

    // Contracts Routes
    Route::prefix('contracts')->name('contracts.')->middleware('permission:contracts.view')->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('index');
        Route::get('/create', [ContractController::class, 'create'])->middleware('permission:contracts.create')->name('create');
        Route::post('/', [ContractController::class, 'store'])->middleware('permission:contracts.create')->name('store');
        Route::get('/{contract}', [ContractController::class, 'show'])->name('show');
        Route::get('/{contract}/edit', [ContractController::class, 'edit'])->middleware('permission:contracts.update')->name('edit');
        Route::put('/{contract}', [ContractController::class, 'update'])->middleware('permission:contracts.update')->name('update');
        Route::delete('/{contract}', [ContractController::class, 'destroy'])->middleware('permission:contracts.delete')->name('destroy');
        
        // Contract Especes Routes
        Route::prefix('especes')->name('especes.')->middleware('permission:especes.view')->group(function () {
            Route::get('/create', [ContractController::class, 'createEspece'])->middleware('permission:especes.create')->name('create');
            Route::post('/', [ContractController::class, 'storeEspece'])->middleware('permission:especes.create')->name('store');
            Route::get('/{espece}/edit', [ContractController::class, 'editEspece'])->middleware('permission:especes.update')->name('edit');
            Route::put('/{espece}', [ContractController::class, 'updateEspece'])->middleware('permission:especes.update')->name('update');
            Route::delete('/{espece}', [ContractController::class, 'destroyEspece'])->middleware('permission:especes.delete')->name('destroy');
        });
        
        // Contract Avenants Routes
        Route::prefix('avenants')->name('avenants.')->middleware('permission:avenants.view')->group(function () {
            Route::get('/create', [ContractController::class, 'createAvenant'])->middleware('permission:avenants.create')->name('create');
            Route::post('/', [ContractController::class, 'storeAvenant'])->middleware('permission:avenants.create')->name('store');
            Route::get('/{avenant}/edit', [ContractController::class, 'editAvenant'])->middleware('permission:avenants.update')->name('edit');
            Route::put('/{avenant}', [ContractController::class, 'updateAvenant'])->middleware('permission:avenants.update')->name('update');
            Route::delete('/{avenant}', [ContractController::class, 'destroyAvenant'])->middleware('permission:avenants.delete')->name('destroy');
        });
        
        // Contract Coperatives Routes
        Route::prefix('coperatives')->name('coperatives.')->middleware('permission:coperatives.view')->group(function () {
            Route::get('/create', [ContractController::class, 'createCoperative'])->middleware('permission:coperatives.create')->name('create');
            Route::post('/', [ContractController::class, 'storeCoperative'])->middleware('permission:coperatives.create')->name('store');
            Route::get('/{coperative}/edit', [ContractController::class, 'editCoperative'])->middleware('permission:coperatives.update')->name('edit');
            Route::put('/{coperative}', [ContractController::class, 'updateCoperative'])->middleware('permission:coperatives.update')->name('update');
            Route::delete('/{coperative}', [ContractController::class, 'destroyCoperative'])->middleware('permission:coperatives.delete')->name('destroy');
        });
        
        // Contract Vocations Routes
        Route::prefix('vocations')->name('vocations.')->middleware('permission:vocations.view')->group(function () {
            Route::get('/create', [ContractController::class, 'createVocation'])->middleware('permission:vocations.create')->name('create');
            Route::post('/', [ContractController::class, 'storeVocation'])->middleware('permission:vocations.create')->name('store');
            Route::get('/{vocation}/edit', [ContractController::class, 'editVocation'])->middleware('permission:vocations.update')->name('edit');
            Route::put('/{vocation}', [ContractController::class, 'updateVocation'])->middleware('permission:vocations.update')->name('update');
            Route::delete('/{vocation}', [ContractController::class, 'destroyVocation'])->middleware('permission:vocations.delete')->name('destroy');
        });
    });

    // Articles Routes
    Route::prefix('articles')->name('articles.')->middleware('permission:articles.view')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->middleware('permission:articles.create')->name('create');
        Route::post('/', [ArticleController::class, 'store'])->middleware('permission:articles.create')->name('store');
        Route::get('/export', [ArticleController::class, 'export'])->middleware('permission:articles.export')->name('export');
        Route::post('/import', [ArticleController::class, 'import'])->middleware('permission:articles.import')->name('import');
        
        // Simple Article Creation Routes
        Route::get('/create/simple', [ArticleController::class, 'createSimple'])->middleware('permission:articles.create')->name('create.simple');
        Route::post('/store/simple', [ArticleController::class, 'storeSimple'])->middleware('permission:articles.create')->name('store.simple');
        Route::get('/template/download', [ArticleController::class, 'downloadTemplate'])->name('template.download');
        
        // Legacy Articles Route (must be before /{article} route)
        Route::get('/legacy-articles', [\App\Http\Controllers\ReportController::class, 'legacyArticles'])->name('legacy-articles');
        
        Route::get('/{article}', [ArticleController::class, 'show'])->name('show');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->middleware('permission:articles.update')->name('edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->middleware('permission:articles.update')->name('update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->middleware('permission:articles.delete')->name('destroy');
        Route::post('/{article}/import-locations', [ArticleController::class, 'importLocations'])->middleware('permission:articles.import')->name('import-locations');
    });

    // Reports Routes
    Route::prefix('reports')->name('reports.')->middleware('permission:reports.view')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
        Route::get('/summary/export', [ReportController::class, 'exportSummary'])->middleware('permission:reports.export')->name('summary.export');
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
        Route::get('/contracts', [ReportController::class, 'contractsReport'])->name('contracts');
        Route::get('/exploitants', [ReportController::class, 'exploitantsReport'])->name('exploitants');
    });

});
