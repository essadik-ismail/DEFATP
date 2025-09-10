<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Article;
use App\Models\Exploitant;
use App\Models\Essence;
use App\Models\Foret;
use App\Models\Localisation;
use App\Models\NatureDeCoupe;
use App\Models\SituationAdministrative;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ReportsTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->exploitant = Exploitant::factory()->create(['nom_complet' => 'Test Exploitant']);
        $this->essence = Essence::factory()->create(['essence' => 'Test Essence']);
        $this->foret = Foret::factory()->create(['nom_foret' => 'Test Forêt']);
        $this->localisation = Localisation::factory()->create(['code' => 'LOC-001']);
        $this->natureDeCoupe = NatureDeCoupe::factory()->create(['nature_de_coupe' => 'Test Coupe']);
        $this->situationAdministrative = SituationAdministrative::factory()->create(['commune' => 'Test Commune']);
    }

    /**
     * Test reports index page displays correctly.
     */
    public function test_reports_index_displays_correctly(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->assertSee('Rapports')
                    ->assertSee('Résumé général')
                    ->assertSee('Articles par année')
                    ->assertSee('Articles par forêt')
                    ->assertSee('Articles par essence')
                    ->assertSee('Articles par exploitant')
                    ->assertSee('Articles par nature de coupe')
                    ->assertSee('Articles par localisation')
                    ->assertSee('Articles par statut de validation')
                    ->assertSee('Articles invendus')
                    ->assertSee('Articles vendus');
        });
    }

    /**
     * Test summary report functionality.
     */
    public function test_summary_report(): void
    {
        $user = User::factory()->create();
        
        // Create test articles
        Article::factory()->create([
            'annee' => 2024,
            'prix_vente' => 1000,
            'is_validated' => true,
            'is_sold' => false,
        ]);
        Article::factory()->create([
            'annee' => 2024,
            'prix_vente' => 2000,
            'is_validated' => true,
            'is_sold' => true,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->clickLink('Résumé général')
                    ->assertPathIs('/reports/summary')
                    ->assertSee('Résumé général')
                    ->assertSee('Total des articles')
                    ->assertSee('Articles validés')
                    ->assertSee('Articles vendus')
                    ->assertSee('Articles invendus')
                    ->assertSee('Chiffre d\'affaires total')
                    ->assertSee('2') // Total articles
                    ->assertSee('2') // Validated articles
                    ->assertSee('1') // Sold articles
                    ->assertSee('1') // Unsold articles
                    ->assertSee('3000'); // Total revenue
        });
    }

    /**
     * Test articles by year report.
     */
    public function test_articles_by_year_report(): void
    {
        $user = User::factory()->create();
        
        // Create test articles for different years
        Article::factory()->create(['annee' => 2024, 'prix_vente' => 1000]);
        Article::factory()->create(['annee' => 2024, 'prix_vente' => 2000]);
        Article::factory()->create(['annee' => 2023, 'prix_vente' => 1500]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->clickLink('Articles par année')
                    ->assertPathIs('/reports/articles-by-year')
                    ->assertSee('Articles par année')
                    ->assertSee('2024')
                    ->assertSee('2023')
                    ->assertSee('2') // 2024 count
                    ->assertSee('1') // 2023 count
                    ->assertSee('3000') // 2024 total
                    ->assertSee('1500'); // 2023 total
        });
    }

    /**
     * Test articles by forest report.
     */
    public function test_articles_by_forest_report(): void
    {
        $user = User::factory()->create();
        
        // Create test articles for different forests
        Article::factory()->create([
            'foret_id' => $this->foret->id,
            'prix_vente' => 1000,
        ]);
        Article::factory()->create([
            'foret_id' => $this->foret->id,
            'prix_vente' => 2000,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->clickLink('Articles par forêt')
                    ->assertPathIs('/reports/articles-by-foret')
                    ->assertSee('Articles par forêt')
                    ->assertSee('Test Forêt')
                    ->assertSee('2') // Count
                    ->assertSee('3000'); // Total
        });
    }

    /**
     * Test articles by essence report.
     */
    public function test_articles_by_essence_report(): void
    {
        $user = User::factory()->create();
        
        // Create test articles for different essences
        Article::factory()->create([
            'essence_id' => $this->essence->id,
            'prix_vente' => 1000,
        ]);
        Article::factory()->create([
            'essence_id' => $this->essence->id,
            'prix_vente' => 2000,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->clickLink('Articles par essence')
                    ->assertPathIs('/reports/articles-by-essence')
                    ->assertSee('Articles par essence')
                    ->assertSee('Test Essence')
                    ->assertSee('2') // Count
                    ->assertSee('3000'); // Total
        });
    }

    /**
     * Test articles by exploitant report.
     */
    public function test_articles_by_exploitant_report(): void
    {
        $user = User::factory()->create();
        
        // Create test articles for different exploitants
        Article::factory()->create([
            'exploitant_id' => $this->exploitant->id,
            'prix_vente' => 1000,
        ]);
        Article::factory()->create([
            'exploitant_id' => $this->exploitant->id,
            'prix_vente' => 2000,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->clickLink('Articles par exploitant')
                    ->assertPathIs('/reports/articles-by-exploitant')
                    ->assertSee('Articles par exploitant')
                    ->assertSee('Test Exploitant')
                    ->assertSee('2') // Count
                    ->assertSee('3000'); // Total
        });
    }

    /**
     * Test articles by validation status report.
     */
    public function test_articles_by_validation_status_report(): void
    {
        $user = User::factory()->create();
        
        // Create test articles with different validation statuses
        Article::factory()->create(['is_validated' => true]);
        Article::factory()->create(['is_validated' => true]);
        Article::factory()->create(['is_validated' => false]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->clickLink('Articles par statut de validation')
                    ->assertPathIs('/reports/articles-by-validation-status')
                    ->assertSee('Articles par statut de validation')
                    ->assertSee('Validés')
                    ->assertSee('En attente')
                    ->assertSee('2') // Validated count
                    ->assertSee('1'); // Pending count
        });
    }

    /**
     * Test unsold articles report.
     */
    public function test_unsold_articles_report(): void
    {
        $user = User::factory()->create();
        
        // Create test articles
        Article::factory()->create(['is_sold' => false]);
        Article::factory()->create(['is_sold' => false]);
        Article::factory()->create(['is_sold' => true]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->clickLink('Articles invendus')
                    ->assertPathIs('/reports/invendus')
                    ->assertSee('Articles invendus')
                    ->assertSee('2') // Unsold count
                    ->assertDontSee('Articles vendus');
        });
    }

    /**
     * Test sold articles report.
     */
    public function test_sold_articles_report(): void
    {
        $user = User::factory()->create();
        
        // Create test articles
        Article::factory()->create(['is_sold' => true]);
        Article::factory()->create(['is_sold' => true]);
        Article::factory()->create(['is_sold' => false]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->clickLink('Articles vendus')
                    ->assertPathIs('/reports/vendus')
                    ->assertSee('Articles vendus')
                    ->assertSee('2') // Sold count
                    ->assertDontSee('Articles invendus');
        });
    }

    /**
     * Test report export functionality.
     */
    public function test_report_export_functionality(): void
    {
        $user = User::factory()->create();
        Article::factory()->count(5)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports/summary')
                    ->click('@export-button')
                    ->assertSee('Exporter le rapport')
                    ->click('@export-excel')
                    ->waitForText('Export en cours...')
                    ->assertSee('Export terminé');
        });
    }

    /**
     * Test report filtering functionality.
     */
    public function test_report_filtering_functionality(): void
    {
        $user = User::factory()->create();
        
        // Create test articles for different years
        Article::factory()->create(['annee' => 2024]);
        Article::factory()->create(['annee' => 2023]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports/articles-by-year')
                    ->select('@year-filter', '2024')
                    ->press('@apply-filters')
                    ->assertSee('Filtres appliqués')
                    ->assertSee('2024')
                    ->assertDontSee('2023');
        });
    }

    /**
     * Test report date range filtering.
     */
    public function test_report_date_range_filtering(): void
    {
        $user = User::factory()->create();
        
        // Create test articles with different dates
        Article::factory()->create(['created_at' => '2024-01-01']);
        Article::factory()->create(['created_at' => '2024-02-01']);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports/summary')
                    ->type('@date-from', '2024-01-01')
                    ->type('@date-to', '2024-01-31')
                    ->press('@apply-filters')
                    ->assertSee('Filtres appliqués')
                    ->assertSee('1'); // Only one article in date range
        });
    }

    /**
     * Test report search functionality.
     */
    public function test_report_search_functionality(): void
    {
        $user = User::factory()->create();
        
        // Create test articles
        Article::factory()->create(['numero_article' => 'ART-001']);
        Article::factory()->create(['numero_article' => 'ART-002']);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports/summary')
                    ->type('@search-input', 'ART-001')
                    ->press('@search-button')
                    ->assertSee('ART-001')
                    ->assertDontSee('ART-002');
        });
    }

    /**
     * Test report responsive design.
     */
    public function test_report_responsive_design(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->resize(375, 667) // Mobile size
                    ->assertSee('Rapports')
                    ->resize(1920, 1080) // Desktop size
                    ->assertSee('Rapports');
        });
    }

    /**
     * Test report accessibility.
     */
    public function test_report_accessibility(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->assertSee('Rapports')
                    ->assertSee('Résumé général')
                    ->assertSee('Articles par année')
                    ->assertSee('Articles par forêt')
                    ->assertSee('Articles par essence')
                    ->assertSee('Articles par exploitant')
                    ->assertSee('Articles par nature de coupe')
                    ->assertSee('Articles par localisation')
                    ->assertSee('Articles par statut de validation')
                    ->assertSee('Articles invendus')
                    ->assertSee('Articles vendus');
        });
    }

    /**
     * Test report navigation.
     */
    public function test_report_navigation(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports')
                    ->clickLink('Résumé général')
                    ->assertPathIs('/reports/summary')
                    ->back()
                    ->assertPathIs('/reports')
                    ->clickLink('Articles par année')
                    ->assertPathIs('/reports/articles-by-year')
                    ->back()
                    ->assertPathIs('/reports');
        });
    }
}
