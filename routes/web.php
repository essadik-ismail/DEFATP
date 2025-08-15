<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExcelController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('auth.profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('auth.profile.update');
    
    // User Management Routes
    Route::prefix('users')->name('auth.users.')->group(function () {
        Route::get('/', [AuthController::class, 'showUsers'])->name('index');
        Route::get('/create', [AuthController::class, 'showCreateUser'])->name('create');
        Route::post('/', [AuthController::class, 'storeUser'])->name('store');
        Route::get('/{user}/edit', [AuthController::class, 'showEditUser'])->name('edit');
        Route::put('/{user}', [AuthController::class, 'updateUser'])->name('update');
        Route::delete('/{user}', [AuthController::class, 'destroyUser'])->name('destroy');
    });

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Settings Routes
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    

    
    // Essences
    Route::get('/essences', [SettingsController::class, 'essences'])->name('essences');
    Route::post('/essences', [SettingsController::class, 'storeEssence'])->name('essences.store');
    Route::get('/essences/{essence}/edit', [SettingsController::class, 'editEssence'])->name('essences.edit');
    Route::put('/essences/{essence}', [SettingsController::class, 'updateEssence'])->name('essences.update');
    Route::delete('/essences/{essence}', [SettingsController::class, 'destroyEssence'])->name('essences.destroy');
    Route::get('/essences/export', [SettingsController::class, 'exportEssences'])->name('essences.export');
    Route::post('/essences/import', [SettingsController::class, 'importEssences'])->name('essences.import');
    
    // Forets
    Route::get('/forets', [SettingsController::class, 'forets'])->name('forets');
    Route::post('/forets', [SettingsController::class, 'storeForet'])->name('forets.store');
    Route::get('/forets/{foret}/edit', [SettingsController::class, 'editForet'])->name('forets.edit');
    Route::put('/forets/{foret}', [SettingsController::class, 'updateForet'])->name('forets.update');
    Route::delete('/forets/{foret}', [SettingsController::class, 'destroyForet'])->name('forets.destroy');
    Route::get('/forets/export', [SettingsController::class, 'exportForets'])->name('forets.export');
    Route::post('/forets/import', [SettingsController::class, 'importForets'])->name('forets.import');
    
    // Nature de Coupes
    Route::get('/nature-de-coupes', [SettingsController::class, 'natureDeCoupes'])->name('nature-de-coupes');
    Route::post('/nature-de-coupes', [SettingsController::class, 'storeNatureDeCoupe'])->name('nature-de-coupes.store');
    Route::get('/nature-de-coupes/{natureDeCoupe}/edit', [SettingsController::class, 'editNatureDeCoupe'])->name('nature-de-coupes.edit');
    Route::put('/nature-de-coupes/{natureDeCoupe}', [SettingsController::class, 'updateNatureDeCoupe'])->name('nature-de-coupes.update');
    Route::delete('/nature-de-coupes/{natureDeCoupe}', [SettingsController::class, 'destroyNatureDeCoupe'])->name('nature-de-coupes.destroy');
    Route::get('/nature-de-coupes/export', [SettingsController::class, 'exportNatureDeCoupes'])->name('nature-de-coupes.export');
    Route::post('/nature-de-coupes/import', [SettingsController::class, 'importNatureDeCoupes'])->name('nature-de-coupes.import');
    
    // Situation Administratives
    Route::get('/situation-administratives', [SettingsController::class, 'situationAdministratives'])->name('situation-administratives');
    Route::post('/situation-administratives', [SettingsController::class, 'storeSituationAdministrative'])->name('situation-administratives.store');
    Route::get('/situation-administratives/{situationAdministrative}/edit', [SettingsController::class, 'editSituationAdministrative'])->name('situation-administratives.edit');
    Route::put('/situation-administratives/{situationAdministrative}', [SettingsController::class, 'updateSituationAdministrative'])->name('situation-administratives.update');
    Route::delete('/situation-administratives/{situationAdministrative}', [SettingsController::class, 'destroySituationAdministrative'])->name('situation-administratives.destroy');
    Route::get('/situation-administratives/export', [SettingsController::class, 'exportSituationAdministratives'])->name('situation-administratives.export');
    Route::post('/situation-administratives/import', [SettingsController::class, 'importSituationAdministratives'])->name('situation-administratives.import');
    
    // Exploitants
    Route::get('/exploitants', [SettingsController::class, 'exploitants'])->name('exploitants');
    Route::post('/exploitants', [SettingsController::class, 'storeExploitant'])->name('exploitants.store');
    Route::get('/exploitants/{exploitant}/edit', [SettingsController::class, 'editExploitant'])->name('exploitants.edit');
    Route::put('/exploitants/{exploitant}', [SettingsController::class, 'updateExploitant'])->name('exploitants.update');
    Route::delete('/exploitants/{exploitant}', [SettingsController::class, 'destroyExploitant'])->name('exploitants.destroy');
    Route::get('/exploitants/export', [SettingsController::class, 'exportExploitants'])->name('exploitants.export');
    Route::post('/exploitants/import', [SettingsController::class, 'importExploitants'])->name('exploitants.import');
    

    
    // Localisations
    Route::get('/localisations', [SettingsController::class, 'localisations'])->name('localisations');
    Route::get('/localisations/export', [SettingsController::class, 'exportLocalisations'])->name('localisations.export');
    Route::post('/localisations/import', [SettingsController::class, 'importLocalisations'])->name('localisations.import');
    
    // ZDTFs
    Route::get('/zdtfs', [SettingsController::class, 'zdtfs'])->name('zdtfs');
    Route::post('/zdtfs', [SettingsController::class, 'storeZdtf'])->name('zdtfs.store');
    Route::put('/zdtfs/{zdtf}', [SettingsController::class, 'updateZdtf'])->name('zdtfs.update');
    Route::delete('/zdtfs/{zdtf}', [SettingsController::class, 'destroyZdtf'])->name('zdtfs.destroy');
    
    // DPANEFs
    Route::get('/dpanefs', [SettingsController::class, 'dpanefs'])->name('dpanefs');
    Route::post('/dpanefs', [SettingsController::class, 'storeDpanef'])->name('dpanefs.store');
    Route::put('/dpanefs/{dpanef}', [SettingsController::class, 'updateDpanef'])->name('dpanefs.update');
    Route::delete('/dpanefs/{dpanef}', [SettingsController::class, 'destroyDpanef'])->name('dpanefs.destroy');
    
    // DRANEFs
    Route::get('/dranefs', [SettingsController::class, 'dranefs'])->name('dranefs');
    Route::post('/dranefs', [SettingsController::class, 'storeDranef'])->name('dranefs.store');
    Route::put('/dranefs/{dranef}', [SettingsController::class, 'updateDranef'])->name('dranefs.update');
    Route::delete('/dranefs/{dranef}', [SettingsController::class, 'destroyDranef'])->name('dranefs.destroy');
    
    // Situation Forestieres
    Route::get('/situation-forestieres', [SettingsController::class, 'situationForestieres'])->name('situation-forestieres');
    Route::post('/situation-forestieres', [SettingsController::class, 'storeSituationForestiere'])->name('situation-forestieres.store');
    Route::put('/situation-forestieres/{situationForestiere}', [SettingsController::class, 'updateSituationForestiere'])->name('situation-forestieres.update');
    Route::delete('/situation-forestieres/{situationForestiere}', [SettingsController::class, 'destroySituationForestiere'])->name('situation-forestieres.destroy');
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

    // Articles Routes
    Route::resource('articles', ArticleController::class);
    Route::get('/articles/export', [ArticleController::class, 'export'])->name('articles.export');
    Route::post('/articles/import', [ArticleController::class, 'import'])->name('articles.import');

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/summary', [ReportController::class, 'summary'])->name('summary');
        Route::get('/summary/export', [ReportController::class, 'exportSummary'])->name('summary.export');
        Route::get('/articles-by-year', [ReportController::class, 'articlesByYear'])->name('articles-by-year');
        Route::get('/articles-by-foret', [ReportController::class, 'articlesByForet'])->name('articles-by-foret');
        Route::get('/articles-by-essence', [ReportController::class, 'articlesByEssence'])->name('articles-by-essence');
        Route::get('/articles-by-exploitant', [ReportController::class, 'articlesByExploitant'])->name('articles-by-exploitant');
        Route::get('/invendus', [ReportController::class, 'invendus'])->name('invendus');
        Route::get('/vendus', [ReportController::class, 'vendus'])->name('vendus');
    });

    // Simple Tour Test Route
    Route::get('/simple-tour-test', function () {
        return view('simple-tour-test');
    })->name('simple.tour.test');

    // Functionality Tour Demo Route
    Route::get('/functionality-tour-demo', function () {
        return view('functionality-tour-demo');
    })->name('functionality.tour.demo');

    // Select Search Demo Route
    Route::get('/select-search-demo', function () {
        return view('select-search-demo');
    })->name('select.search.demo');
});
