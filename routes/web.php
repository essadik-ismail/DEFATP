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
Route::get('/verify-exploitant/{exploitant}/image', [SettingsController::class, 'serveExploitantImagePublic'])->name('verify-exploitant.image');

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
    
    // ODF Routes
    Route::prefix('odfs')->name('odfs.')->group(function () {
        Route::get('/', [OdfController::class, 'index'])->name('index');

        // New multi-step creation wizard
        Route::get('/create', [OdfController::class, 'createStep1'])->name('create');
        Route::get('/create/step1', [OdfController::class, 'createStep1'])->name('create.step1');
        Route::post('/create/step1', [OdfController::class, 'storeStep1'])->name('store.step1');

        Route::get('/{odf}/create/step2', [OdfController::class, 'createStep2'])->name('create.step2');
        Route::post('/{odf}/create/step2', [OdfController::class, 'storeStep2'])->name('store.step2');

        Route::get('/{odf}/create/step3', [OdfController::class, 'createStep3'])->name('create.step3');
        Route::post('/{odf}/create/step3', [OdfController::class, 'storeStep3'])->name('store.step3');

        Route::get('/{odf}/create/step4', [OdfController::class, 'createStep4'])->name('create.step4');
        Route::post('/{odf}/create/step4', [OdfController::class, 'storeStep4'])->name('store.step4');

        Route::get('/{odf}/create/step5', [OdfController::class, 'createStep5'])->name('create.step5');
        Route::post('/{odf}/create/step5', [OdfController::class, 'storeStep5'])->name('store.step5');

        // Legacy single-step store route (kept for safety, but wizard uses the step routes)
        Route::post('/', [OdfController::class, 'store'])->name('store');
        Route::get('/{odf}', [OdfController::class, 'show'])->name('show');
        Route::get('/{odf}/edit', [OdfController::class, 'edit'])->name('edit');
        
        // Multi-step edit routes
        Route::get('/{odf}/edit/step1', [OdfController::class, 'editStep1'])->name('edit.step1');
        Route::put('/{odf}/edit/step1', [OdfController::class, 'updateStep1'])->name('update.step1');
        
        Route::get('/{odf}/edit/step2', [OdfController::class, 'editStep2'])->name('edit.step2');
        Route::put('/{odf}/edit/step2', [OdfController::class, 'updateStep2'])->name('update.step2');
        
        Route::get('/{odf}/edit/step3', [OdfController::class, 'editStep3'])->name('edit.step3');
        Route::put('/{odf}/edit/step3', [OdfController::class, 'updateStep3'])->name('update.step3');
        
        Route::get('/{odf}/edit/step4', [OdfController::class, 'editStep4'])->name('edit.step4');
        Route::put('/{odf}/edit/step4', [OdfController::class, 'updateStep4'])->name('update.step4');
        
        Route::get('/{odf}/edit/step5', [OdfController::class, 'editStep5'])->name('edit.step5');
        Route::put('/{odf}/edit/step5', [OdfController::class, 'updateStep5'])->name('update.step5');
        
        Route::put('/{odf}', [OdfController::class, 'update'])->name('update');
        Route::delete('/{odf}', [OdfController::class, 'destroy'])->name('destroy');
        
        // Members routes
        Route::post('/{odf}/members', [OdfController::class, 'storeMember'])->name('members.store');
        Route::put('/{odf}/members/{member}', [OdfController::class, 'updateMember'])->name('members.update');
        Route::delete('/{odf}/members/{member}', [OdfController::class, 'destroyMember'])->name('members.destroy');
        
        // Activities routes
        Route::post('/{odf}/activities', [OdfController::class, 'storeActivity'])->name('activities.store');
        Route::put('/{odf}/activities/{activity}', [OdfController::class, 'updateActivity'])->name('activities.update');
        Route::delete('/{odf}/activities/{activity}', [OdfController::class, 'destroyActivity'])->name('activities.destroy');
        
        // ODF Etaps routes
        Route::get('/{odf}/odf-etaps/{odfEtap}', [OdfController::class, 'getOdfEtap'])->name('odf-etaps.show');
        Route::post('/{odf}/odf-etaps', [OdfController::class, 'storeOdfEtap'])->name('odf-etaps.store');
        Route::put('/{odf}/odf-etaps/{odfEtap}', [OdfController::class, 'updateOdfEtap'])->name('odf-etaps.update');
        Route::delete('/{odf}/odf-etaps/{odfEtap}', [OdfController::class, 'destroyOdfEtap'])->name('odf-etaps.destroy');
        
        // Contract ODF routes
        Route::post('/{odf}/contract-odf', [OdfController::class, 'storeContractOdf'])->name('contract-odf.store');
        Route::put('/{odf}/contract-odf/{contractOdf}', [OdfController::class, 'updateContractOdf'])->name('contract-odf.update');
        Route::delete('/{odf}/contract-odf/{contractOdf}', [OdfController::class, 'destroyContractOdf'])->name('contract-odf.destroy');
        
        // ODF Modifications routes
        Route::post('/{odf}/odf-modifications', [OdfController::class, 'storeOdfModification'])->name('odf-modifications.store');
        Route::put('/{odf}/odf-modifications/{odfModification}', [OdfController::class, 'updateOdfModification'])->name('odf-modifications.update');
        Route::delete('/{odf}/odf-modifications/{odfModification}', [OdfController::class, 'destroyOdfModification'])->name('odf-modifications.destroy');
    });
    
    // ODF Entité API route
    Route::get('/api/odf-entites/{odfEntite}', [OdfController::class, 'getOdfEntite'])->name('api.odf-entites.show');
    
    // PDFC Routes
    Route::prefix('pdfcs')->name('pdfcs.')->group(function () {
        Route::get('/', [PdfcController::class, 'index'])->name('index');
        Route::get('/create', [PdfcController::class, 'create'])->name('create');
        Route::post('/', [PdfcController::class, 'store'])->name('store');
        Route::get('/{pdfc}', [PdfcController::class, 'show'])->name('show');
        Route::get('/{pdfc}/edit', [PdfcController::class, 'edit'])->name('edit');
        Route::put('/{pdfc}', [PdfcController::class, 'update'])->name('update');
        Route::post('/{pdfc}/transition-state', [PdfcController::class, 'transitionState'])->name('transition-state');
        Route::delete('/{pdfc}', [PdfcController::class, 'destroy'])->name('destroy');
        
        // Phase Routes
        Route::prefix('{pdfc}/phases')->name('phases.')->group(function () {
            Route::get('/create', [PhaseController::class, 'create'])->name('create');
            Route::post('/', [PhaseController::class, 'store'])->name('store');
            Route::get('/{phase}/edit', [PhaseController::class, 'edit'])->name('edit');
            Route::put('/{phase}', [PhaseController::class, 'update'])->name('update');
            Route::delete('/{phase}', [PhaseController::class, 'destroy'])->name('destroy');
            Route::post('/{phase}/validate', [PhaseController::class, 'validatePhase'])->name('validate');
        });
        
        // Etape Routes
        Route::prefix('{pdfc}/phases/{phase}/etapes')->name('etapes.')->group(function () {
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
    Route::get('/entity-data', [App\Http\Controllers\EntityDataController::class, 'index'])->name('entity-data.index');
    
    // Financial Data Management (Recette)
    Route::prefix('financial-data')->name('financial-data.')->group(function () {
        Route::get('/', [App\Http\Controllers\FinancialDataController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\FinancialDataController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\FinancialDataController::class, 'store'])->name('store');
        Route::get('/{nationalSummary}/edit', [App\Http\Controllers\FinancialDataController::class, 'edit'])->name('edit');
        Route::put('/{nationalSummary}', [App\Http\Controllers\FinancialDataController::class, 'update'])->name('update');
        Route::delete('/{nationalSummary}', [App\Http\Controllers\FinancialDataController::class, 'destroy'])->name('destroy');
    });

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
        
        // Localisations
        Route::prefix('localisations')->name('localisations.')->group(function () {
            Route::get('/', [SettingsController::class, 'localisations'])->name('index');
            Route::get('/create', [SettingsController::class, 'createLocalisation'])->name('create');
            Route::post('/', [SettingsController::class, 'storeLocalisation'])->name('store');
            Route::get('/{localisation}/edit', [SettingsController::class, 'editLocalisation'])->name('edit');
            Route::put('/{localisation}', [SettingsController::class, 'updateLocalisation'])->name('update');
            Route::delete('/{localisation}', [SettingsController::class, 'destroyLocalisation'])->name('destroy');
            Route::get('/export', [SettingsController::class, 'exportLocalisations'])->name('export');
            Route::post('/import', [SettingsController::class, 'importLocalisations'])->name('import');
        });
        
        // ODF Entités
        Route::prefix('odf-entites')->name('odf-entites.')->group(function () {
            Route::get('/', [SettingsController::class, 'odfEntites'])->name('index');
            Route::get('/create', [SettingsController::class, 'createOdfEntite'])->name('create');
            Route::post('/', [SettingsController::class, 'storeOdfEntite'])->name('store');
            Route::get('/{odfEntite}/edit', [SettingsController::class, 'editOdfEntite'])->name('edit');
            Route::put('/{odfEntite}', [SettingsController::class, 'updateOdfEntite'])->name('update');
            Route::delete('/{odfEntite}', [SettingsController::class, 'destroyOdfEntite'])->name('destroy');
        });
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

    // Articles Routes
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/create', [ArticleController::class, 'create'])->name('create');
        Route::post('/', [ArticleController::class, 'store'])->name('store');
        Route::get('/export', [ArticleController::class, 'export'])->name('export');
        Route::post('/import', [ArticleController::class, 'import'])->name('import');
        
        // Simple Article Creation Routes
        Route::get('/create/simple', [ArticleController::class, 'createSimple'])->name('create.simple');
        Route::post('/store/simple', [ArticleController::class, 'storeSimple'])->name('store.simple');
        Route::get('/template/download', [ArticleController::class, 'downloadTemplate'])->name('template.download');
        
        // Legacy Articles Route (must be before /{article} route)
        Route::get('/legacy-articles', [\App\Http\Controllers\ReportController::class, 'legacyArticles'])->name('legacy-articles');
        
        Route::get('/{article}', [ArticleController::class, 'show'])->name('show');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('destroy');
        Route::post('/{article}/import-locations', [ArticleController::class, 'importLocations'])->name('import-locations');
    });

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
        Route::get('/contracts', [ReportController::class, 'contractsReport'])->name('contracts');
        Route::get('/exploitants', [ReportController::class, 'exploitantsReport'])->name('exploitants');
        Route::get('/odfs', [ReportController::class, 'odfsReport'])->name('odfs');
        Route::get('/products', [ReportController::class, 'productsReport'])->name('products');
        Route::get('/products-development-chart', [ReportController::class, 'productsDevelopmentChart'])->name('products-development-chart');
    });

});
