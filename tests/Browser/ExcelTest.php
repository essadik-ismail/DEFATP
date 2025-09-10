<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Article;
use App\Models\Essence;
use App\Models\Foret;
use App\Models\Exploitant;
use App\Models\Localisation;
use App\Models\NatureDeCoupe;
use App\Models\SituationAdministrative;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExcelTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test Excel import/export page displays correctly.
     */
    public function test_excel_page_displays_correctly(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->assertSee('Import/Export Excel')
                    ->assertSee('Exporter tout')
                    ->assertSee('Importer tout')
                    ->assertSee('Articles')
                    ->assertSee('Essences')
                    ->assertSee('Forêts')
                    ->assertSee('Exploitants')
                    ->assertSee('Localisations')
                    ->assertSee('Nature de Coupes')
                    ->assertSee('Situations Administratives');
        });
    }

    /**
     * Test individual table export functionality.
     */
    public function test_individual_table_export(): void
    {
        $user = User::factory()->create();
        
        // Create test data
        Article::factory()->count(3)->create();
        Essence::factory()->count(2)->create();
        Foret::factory()->count(2)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@export-articles')
                    ->waitForText('Export en cours...')
                    ->assertSee('Export terminé')
                    ->click('@export-essences')
                    ->waitForText('Export en cours...')
                    ->assertSee('Export terminé')
                    ->click('@export-forets')
                    ->waitForText('Export en cours...')
                    ->assertSee('Export terminé');
        });
    }

    /**
     * Test complete export functionality.
     */
    public function test_complete_export(): void
    {
        $user = User::factory()->create();
        
        // Create test data for all tables
        Article::factory()->count(2)->create();
        Essence::factory()->count(2)->create();
        Foret::factory()->count(2)->create();
        Exploitant::factory()->count(2)->create();
        Localisation::factory()->count(2)->create();
        NatureDeCoupe::factory()->count(2)->create();
        SituationAdministrative::factory()->count(2)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@export-all')
                    ->waitForText('Export en cours...')
                    ->assertSee('Export terminé')
                    ->assertSee('Toutes les données ont été exportées');
        });
    }

    /**
     * Test individual table import functionality.
     */
    public function test_individual_table_import(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@import-articles')
                    ->assertSee('Importer des articles')
                    ->attach('@file-input', __DIR__ . '/fixtures/articles.xlsx')
                    ->press('@import-submit')
                    ->waitForText('Import en cours...')
                    ->assertSee('Import terminé')
                    ->assertSee('Articles importés avec succès');
        });
    }

    /**
     * Test bulk import functionality.
     */
    public function test_bulk_import(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@import-all')
                    ->assertSee('Importer toutes les données')
                    ->attach('@file-input', __DIR__ . '/fixtures/all_data.zip')
                    ->press('@import-submit')
                    ->waitForText('Import en cours...')
                    ->assertSee('Import terminé')
                    ->assertSee('Toutes les données ont été importées');
        });
    }

    /**
     * Test file validation for import.
     */
    public function test_file_validation_for_import(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@import-articles')
                    ->attach('@file-input', __DIR__ . '/fixtures/invalid_file.txt')
                    ->press('@import-submit')
                    ->assertSee('Format de fichier non supporté')
                    ->assertSee('Veuillez sélectionner un fichier Excel (.xlsx, .xls) ou CSV');
        });
    }

    /**
     * Test file size validation for import.
     */
    public function test_file_size_validation_for_import(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@import-articles')
                    ->attach('@file-input', __DIR__ . '/fixtures/large_file.xlsx')
                    ->press('@import-submit')
                    ->assertSee('Fichier trop volumineux')
                    ->assertSee('La taille du fichier ne doit pas dépasser 10MB');
        });
    }

    /**
     * Test import with validation errors.
     */
    public function test_import_with_validation_errors(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@import-articles')
                    ->attach('@file-input', __DIR__ . '/fixtures/invalid_data.xlsx')
                    ->press('@import-submit')
                    ->waitForText('Import terminé')
                    ->assertSee('Import terminé avec des erreurs')
                    ->assertSee('Veuillez vérifier les données et réessayer');
        });
    }

    /**
     * Test export with filters.
     */
    public function test_export_with_filters(): void
    {
        $user = User::factory()->create();
        
        // Create test data with different years
        Article::factory()->create(['annee' => 2024]);
        Article::factory()->create(['annee' => 2023]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@export-articles')
                    ->assertSee('Filtres d\'export')
                    ->select('@year-filter', '2024')
                    ->select('@status-filter', 'validated')
                    ->press('@export-with-filters')
                    ->waitForText('Export en cours...')
                    ->assertSee('Export terminé')
                    ->assertSee('Articles exportés avec filtres appliqués');
        });
    }

    /**
     * Test export progress indicator.
     */
    public function test_export_progress_indicator(): void
    {
        $user = User::factory()->create();
        Article::factory()->count(100)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@export-articles')
                    ->waitForText('Export en cours...')
                    ->assertSee('0%')
                    ->waitForText('50%')
                    ->assertSee('50%')
                    ->waitForText('100%')
                    ->assertSee('100%')
                    ->assertSee('Export terminé');
        });
    }

    /**
     * Test import progress indicator.
     */
    public function test_import_progress_indicator(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@import-articles')
                    ->attach('@file-input', __DIR__ . '/fixtures/large_articles.xlsx')
                    ->press('@import-submit')
                    ->waitForText('Import en cours...')
                    ->assertSee('0%')
                    ->waitForText('50%')
                    ->assertSee('50%')
                    ->waitForText('100%')
                    ->assertSee('100%')
                    ->assertSee('Import terminé');
        });
    }

    /**
     * Test export error handling.
     */
    public function test_export_error_handling(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@export-articles')
                    ->waitForText('Erreur lors de l\'export')
                    ->assertSee('Une erreur est survenue lors de l\'export')
                    ->assertSee('Veuillez réessayer plus tard');
        });
    }

    /**
     * Test import error handling.
     */
    public function test_import_error_handling(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->click('@import-articles')
                    ->attach('@file-input', __DIR__ . '/fixtures/corrupted_file.xlsx')
                    ->press('@import-submit')
                    ->waitForText('Erreur lors de l\'import')
                    ->assertSee('Une erreur est survenue lors de l\'import')
                    ->assertSee('Veuillez vérifier le fichier et réessayer');
        });
    }

    /**
     * Test Excel page responsive design.
     */
    public function test_excel_page_responsive_design(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->resize(375, 667) // Mobile size
                    ->assertSee('Import/Export Excel')
                    ->resize(1920, 1080) // Desktop size
                    ->assertSee('Import/Export Excel');
        });
    }

    /**
     * Test Excel page accessibility.
     */
    public function test_excel_page_accessibility(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/excel')
                    ->assertSee('Exporter tout')
                    ->assertSee('Importer tout')
                    ->assertSee('Articles')
                    ->assertSee('Essences')
                    ->assertSee('Forêts')
                    ->assertSee('Exploitants')
                    ->assertSee('Localisations')
                    ->assertSee('Nature de Coupes')
                    ->assertSee('Situations Administratives');
        });
    }
}
