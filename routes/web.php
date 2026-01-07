<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ArticleController;

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
Route::get('/verify-exploitant/{exploitant}/image', [SettingsController::class, 'serveExploitantImagePublic'])->name('verify-exploitant.image');

// Test notification route (remove in production)
Route::get('/test-notification', function () {
    $user = auth()->user();
    
    // Create a test notification
    $notification = new \App\Models\AppNotification([
        'type' => 'test',
        'title' => 'Test Notification',
        'message' => 'This is a test notification.',
        'user_id' => $user->id,
        'notifiable_type' => get_class($user),
        'notifiable_id' => $user->id,
        'action_url' => '/notifications',
        'icon' => 'fas fa-bell',
        'color' => 'primary',
        'priority' => 'medium'
    ]);
    
    $notification->save();
    
    return redirect()->back()->with('success', 'Test notification created!');
})->middleware('auth')->name('test.notification');

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
    
    // Activity Journals Routes
    Route::resource('activity-journals', \App\Http\Controllers\ActivityJournalController::class);

    // Archives
    Route::resource('archives', ArchiveController::class);
    Route::post('archives/{archive}/documents', [DocumentController::class, 'store'])->name('archives.documents.store');
    Route::delete('archives/{archive}/documents/{document}', [DocumentController::class, 'destroy'])->name('archives.documents.destroy');
    
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
    Route::prefix('admin/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{user}/assign-roles-permissions', [UserController::class, 'assignRolesPermissions'])->name('assign-roles-permissions');
        Route::get('/export', [UserController::class, 'export'])->name('export');
    });

    // Activity Logs Routes
    Route::prefix('admin/activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        Route::get('/user/{user}', [ActivityLogController::class, 'userActivity'])->name('user-activity');
        Route::get('/ajax/logs', [ActivityLogController::class, 'getActivityLogs'])->name('ajax-logs');
        Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
        Route::get('/statistics', [ActivityLogController::class, 'getStatistics'])->name('statistics');
    });


    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Articles Routes
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/{article}', [ArticleController::class, 'show'])->name('show');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
    });

    // Contract Ventes Routes
    Route::prefix('articles/{article}/contract-ventes')->name('contract-ventes.')->group(function () {
        Route::get('/create', [App\Http\Controllers\ContractVenteController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ContractVenteController::class, 'store'])->name('store');
        Route::get('/{contractVente}/edit', [App\Http\Controllers\ContractVenteController::class, 'edit'])->name('edit');
        Route::put('/{contractVente}', [App\Http\Controllers\ContractVenteController::class, 'update'])->name('update');
    });

    // AJAX route for exploitant details
    Route::get('/api/exploitants/{exploitant}', [App\Http\Controllers\ContractVenteController::class, 'getExploitant'])->name('api.exploitants.show');

    Route::prefix('exploitants')->name('exploitants.')->group(function () {
        Route::get('/', [SettingsController::class, 'exploitants'])->name('index');
        Route::get('/create', [SettingsController::class, 'createExploitant'])->name('create');
        Route::post('/', [SettingsController::class, 'storeExploitant'])->name('store');
        Route::get('/export', [SettingsController::class, 'exportExploitants'])->name('export');
        Route::post('/import', [SettingsController::class, 'importExploitants'])->name('import');
        Route::get('/{exploitant}/image', [SettingsController::class, 'serveExploitantImage'])->name('image');
        Route::get('/{exploitant}', [SettingsController::class, 'showExploitant'])->name('show');
        Route::get('/{exploitant}/carte-professionnelle', [SettingsController::class, 'carteProfessionnelle'])->name('carte-professionnelle');
        Route::get('/{exploitant}/edit', [SettingsController::class, 'editExploitant'])->name('edit');
        Route::put('/{exploitant}', [SettingsController::class, 'updateExploitant'])->name('update');
        Route::delete('/{exploitant}', [SettingsController::class, 'destroyExploitant'])->name('destroy');
    });
    
    Route::prefix('coperatives')->name('coperatives.')->group(function () {
        Route::get('/', [SettingsController::class, 'coperatives'])->name('index');
    });
    
    // Unified Entity Data Management
    Route::get('/entity-data', [App\Http\Controllers\EntityDataController::class, 'index'])->name('entity-data.index');
    
    // Financial Data Management (Recette)
    Route::prefix('financial-data')->name('financial-data.')->group(function () {
        Route::get('/', [App\Http\Controllers\FinancialDataController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\FinancialDataController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\FinancialDataController::class, 'store'])->name('store');
        Route::get('/{nationalSummary}', [App\Http\Controllers\FinancialDataController::class, 'show'])->name('show');
        Route::get('/{nationalSummary}/edit', [App\Http\Controllers\FinancialDataController::class, 'edit'])->name('edit');
        Route::put('/{nationalSummary}', [App\Http\Controllers\FinancialDataController::class, 'update'])->name('update');
        Route::delete('/{nationalSummary}', [App\Http\Controllers\FinancialDataController::class, 'destroy'])->name('destroy');
    });

    // Suivi Contract Programmes Management
    Route::resource('suivi-contract-programmes', App\Http\Controllers\SuiviContractProgrammeController::class);

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        
        // Essences
        Route::prefix('essences')->name('essences.')->group(function () {
            Route::get('/', [SettingsController::class, 'essences'])->name('index');
            Route::get('/create', [SettingsController::class, 'createEssence'])->name('create');
            Route::post('/', [SettingsController::class, 'storeEssence'])->name('store');
            Route::get('/{essence}/edit', [SettingsController::class, 'editEssence'])->name('edit');
            Route::put('/{essence}', [SettingsController::class, 'updateEssence'])->name('update');
            Route::delete('/{essence}', [SettingsController::class, 'destroyEssence'])->name('destroy');
            Route::get('/export', [SettingsController::class, 'exportEssences'])->name('export');
            Route::post('/import', [SettingsController::class, 'importEssences'])->name('import');
        });
        
        // Forêts
        Route::prefix('forets')->name('forets.')->group(function () {
            Route::get('/', [SettingsController::class, 'forets'])->name('index');
            Route::get('/map', [SettingsController::class, 'foretsMap'])->name('map');
            Route::get('/create', [SettingsController::class, 'createForet'])->name('create');
            Route::post('/', [SettingsController::class, 'storeForet'])->name('store');
            Route::get('/{foret}/edit', [SettingsController::class, 'editForet'])->name('edit');
            Route::put('/{foret}', [SettingsController::class, 'updateForet'])->name('update');
            Route::delete('/{foret}', [SettingsController::class, 'destroyForet'])->name('destroy');
            Route::get('/export', [SettingsController::class, 'exportForets'])->name('export');
            Route::post('/import', [SettingsController::class, 'importForets'])->name('import');
        });
        
        // Nature de Coupes
        Route::prefix('nature-de-coupes')->name('nature-de-coupes.')->group(function () {
            Route::get('/', [SettingsController::class, 'natureDeCoupes'])->name('index');
            Route::get('/create', [SettingsController::class, 'createNatureDeCoupe'])->name('create');
            Route::post('/', [SettingsController::class, 'storeNatureDeCoupe'])->name('store');
            Route::get('/{natureDeCoupe}/edit', [SettingsController::class, 'editNatureDeCoupe'])->name('edit');
            Route::put('/{natureDeCoupe}', [SettingsController::class, 'updateNatureDeCoupe'])->name('update');
            Route::delete('/{natureDeCoupe}', [SettingsController::class, 'destroyNatureDeCoupe'])->name('destroy');
            Route::get('/export', [SettingsController::class, 'exportNatureDeCoupes'])->name('export');
            Route::post('/import', [SettingsController::class, 'importNatureDeCoupes'])->name('import');
        });
        
        // Situations Administratives
        Route::prefix('situation-administratives')->name('situation-administratives.')->group(function () {
            Route::get('/', [SettingsController::class, 'situationAdministratives'])->name('index');
            Route::get('/create', [SettingsController::class, 'createSituationAdministrative'])->name('create');
            Route::post('/', [SettingsController::class, 'storeSituationAdministrative'])->name('store');
            Route::get('/{situationAdministrative}/edit', [SettingsController::class, 'editSituationAdministrative'])->name('edit');
            Route::put('/{situationAdministrative}', [SettingsController::class, 'updateSituationAdministrative'])->name('update');
            Route::delete('/{situationAdministrative}', [SettingsController::class, 'destroySituationAdministrative'])->name('destroy');
            Route::get('/export', [SettingsController::class, 'exportSituationAdministratives'])->name('export');
            Route::post('/import', [SettingsController::class, 'importSituationAdministratives'])->name('import');
        });
        
        // Mode Exploitations
        Route::prefix('mode-exploitations')->name('mode-exploitations.')->group(function () {
            Route::get('/', [SettingsController::class, 'modeExploitations'])->name('index');
            Route::get('/create', [SettingsController::class, 'createModeExploitation'])->name('create');
            Route::post('/', [SettingsController::class, 'storeModeExploitation'])->name('store');
            Route::get('/{modeExploitation}/edit', [SettingsController::class, 'editModeExploitation'])->name('edit');
            Route::put('/{modeExploitation}', [SettingsController::class, 'updateModeExploitation'])->name('update');
            Route::delete('/{modeExploitation}', [SettingsController::class, 'destroyModeExploitation'])->name('destroy');
        });
        
        // DRANEFs
        Route::prefix('dranefs')->name('dranefs.')->group(function () {
            Route::get('/', [SettingsController::class, 'dranefs'])->name('index');
            Route::get('/create', [SettingsController::class, 'createDranef'])->name('create');
            Route::post('/', [SettingsController::class, 'storeDranef'])->name('store');
            Route::get('/{dranef}/edit', [SettingsController::class, 'editDranef'])->name('edit');
            Route::put('/{dranef}', [SettingsController::class, 'updateDranef'])->name('update');
            Route::delete('/{dranef}', [SettingsController::class, 'destroyDranef'])->name('destroy');
        });
        
        // DPANEFs
        Route::prefix('dpanefs')->name('dpanefs.')->group(function () {
            Route::get('/', [SettingsController::class, 'dpanefs'])->name('index');
            Route::get('/create', [SettingsController::class, 'createDpanef'])->name('create');
            Route::post('/', [SettingsController::class, 'storeDpanef'])->name('store');
            Route::get('/{dpanef}/edit', [SettingsController::class, 'editDpanef'])->name('edit');
            Route::put('/{dpanef}', [SettingsController::class, 'updateDpanef'])->name('update');
            Route::delete('/{dpanef}', [SettingsController::class, 'destroyDpanef'])->name('destroy');
        });
        
        // ZDTFs
        Route::prefix('zdtfs')->name('zdtfs.')->group(function () {
            Route::get('/', [SettingsController::class, 'zdtfs'])->name('index');
            Route::get('/create', [SettingsController::class, 'createZdtf'])->name('create');
            Route::post('/', [SettingsController::class, 'storeZdtf'])->name('store');
            Route::get('/{zdtf}/edit', [SettingsController::class, 'editZdtf'])->name('edit');
            Route::put('/{zdtf}', [SettingsController::class, 'updateZdtf'])->name('update');
            Route::delete('/{zdtf}', [SettingsController::class, 'destroyZdtf'])->name('destroy');
        });
        
        // Cantons
        Route::prefix('cantons')->name('cantons.')->group(function () {
            Route::get('/', [SettingsController::class, 'cantons'])->name('index');
            Route::get('/create', [SettingsController::class, 'createCanton'])->name('create');
            Route::post('/', [SettingsController::class, 'storeCanton'])->name('store');
            Route::get('/{canton}/edit', [SettingsController::class, 'editCanton'])->name('edit');
            Route::put('/{canton}', [SettingsController::class, 'updateCanton'])->name('update');
            Route::delete('/{canton}', [SettingsController::class, 'destroyCanton'])->name('destroy');
        });
        
        // Parcelles
        Route::prefix('parcelles')->name('parcelles.')->group(function () {
            Route::get('/', [SettingsController::class, 'parcelles'])->name('index');
            Route::get('/create', [SettingsController::class, 'createParcelle'])->name('create');
            Route::post('/', [SettingsController::class, 'storeParcelle'])->name('store');
            Route::get('/{parcelle}/edit', [SettingsController::class, 'editParcelle'])->name('edit');
            Route::put('/{parcelle}', [SettingsController::class, 'updateParcelle'])->name('update');
            Route::delete('/{parcelle}', [SettingsController::class, 'destroyParcelle'])->name('destroy');
        });
        
    });

    // Excel Import/Export Routes
    Route::prefix('excel')->name('excel.')->group(function () {
        Route::get('/', [ExcelController::class, 'index'])->name('index');
        Route::get('/export-all', [ExcelController::class, 'exportAll'])->name('export-all');
        Route::post('/import-all', [ExcelController::class, 'importAll'])->name('import-all');
        
        // Individual exports
        Route::get('/export/essences', [ExcelController::class, 'exportEssences'])->name('export.essences');
        Route::get('/export/forets', [ExcelController::class, 'exportForets'])->name('export.forets');
        Route::get('/export/nature-de-coupes', [ExcelController::class, 'exportNatureDeCoupes'])->name('export.nature-de-coupes');
        Route::get('/export/situation-administratives', [ExcelController::class, 'exportSituationAdministratives'])->name('export.situation-administratives');
        Route::get('/export/exploitants', [ExcelController::class, 'exportExploitants'])->name('export.exploitants');
        // Individual imports
        Route::post('/import/essences', [ExcelController::class, 'importEssences'])->name('import.essences');
        Route::post('/import/forets', [ExcelController::class, 'importForets'])->name('import.forets');
        Route::post('/import/nature-de-coupes', [ExcelController::class, 'importNatureDeCoupes'])->name('import.nature-de-coupes');
        Route::post('/import/situation-administratives', [ExcelController::class, 'importSituationAdministratives'])->name('import.situation-administratives');
        Route::post('/import/exploitants', [ExcelController::class, 'importExploitants'])->name('import.exploitants');
    });

    // Contracts Routes
    Route::prefix('contracts')->name('contracts.')->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('index');
        Route::get('/create', [ContractController::class, 'create'])->name('create');
        Route::post('/', [ContractController::class, 'store'])->name('store');
        Route::get('/{contract}', [ContractController::class, 'show'])->name('show');
        Route::get('/{contract}/edit', [ContractController::class, 'edit'])->name('edit');
        Route::put('/{contract}', [ContractController::class, 'update'])->name('update');
        Route::delete('/{contract}', [ContractController::class, 'destroy'])->name('destroy');
        
        
        // Contract Avenants Routes
        Route::prefix('avenants')->name('avenants.')->group(function () {
            Route::get('/create', [ContractController::class, 'createAvenant'])->name('create');
            Route::post('/', [ContractController::class, 'storeAvenant'])->name('store');
            Route::get('/{avenant}/edit', [ContractController::class, 'editAvenant'])->name('edit');
            Route::put('/{avenant}', [ContractController::class, 'updateAvenant'])->name('update');
            Route::delete('/{avenant}', [ContractController::class, 'destroyAvenant'])->name('destroy');
        });
        
        // Contract Coperatives Routes
        Route::prefix('coperatives')->name('coperatives.')->group(function () {
            Route::get('/create', [ContractController::class, 'createCoperative'])->name('create');
            Route::post('/', [ContractController::class, 'storeCoperative'])->name('store');
            Route::get('/{coperative}/edit', [ContractController::class, 'editCoperative'])->name('edit');
            Route::put('/{coperative}', [ContractController::class, 'updateCoperative'])->name('update');
            Route::delete('/{coperative}', [ContractController::class, 'destroyCoperative'])->name('destroy');
        });
        
        // Contract Vocations Routes
        Route::prefix('vocations')->name('vocations.')->group(function () {
            Route::get('/create', [ContractController::class, 'createVocation'])->name('create');
            Route::post('/', [ContractController::class, 'storeVocation'])->name('store');
            Route::get('/{vocation}/edit', [ContractController::class, 'editVocation'])->name('edit');
            Route::put('/{vocation}', [ContractController::class, 'updateVocation'])->name('update');
            Route::delete('/{vocation}', [ContractController::class, 'destroyVocation'])->name('destroy');
        });
    });

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
        Route::get('/summary/export', [ReportController::class, 'exportSummary'])->name('summary.export');
        Route::get('/product-quantities-charts', [ReportController::class, 'productQuantitiesCharts'])->name('product-quantities-charts');
        Route::get('/legacy-quantities-charts', [ReportController::class, 'legacyQuantitiesCharts'])->name('legacy-quantities-charts');
        Route::get('/unified', [ReportController::class, 'unifiedReports'])->name('unified');
        Route::get('/unified-table', [ReportController::class, 'unifiedTable'])->name('unified-table');
        Route::get('/contracts', [ReportController::class, 'contractsReport'])->name('contracts');
        Route::get('/exploitants', [ReportController::class, 'exploitantsReport'])->name('exploitants');
        Route::get('/products', [ReportController::class, 'productsReport'])->name('products');
        Route::get('/products-development-chart', [ReportController::class, 'productsDevelopmentChart'])->name('products-development-chart');
    });

});