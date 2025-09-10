<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Article;
use App\Models\Exploitant;
use App\Models\Essence;
use App\Models\Foret;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test dashboard displays correctly for authenticated user.
     */
    public function test_dashboard_displays_correctly(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->assertSee('Tableau de bord')
                    ->assertSee('Articles')
                    ->assertSee('Exploitants')
                    ->assertSee('Forêts')
                    ->assertSee('Essences');
        });
    }

    /**
     * Test dashboard statistics are displayed.
     */
    public function test_dashboard_statistics_are_displayed(): void
    {
        $user = User::factory()->create();
        
        // Create some test data
        Article::factory()->count(5)->create();
        Exploitant::factory()->count(3)->create();
        Essence::factory()->count(4)->create();
        Foret::factory()->count(2)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->assertSee('5')
                    ->assertSee('3')
                    ->assertSee('4')
                    ->assertSee('2');
        });
    }

    /**
     * Test dashboard navigation links work.
     */
    public function test_dashboard_navigation_links_work(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->clickLink('Articles')
                    ->assertPathIs('/articles')
                    ->back()
                    ->clickLink('Exploitants')
                    ->assertPathIs('/settings/exploitants')
                    ->back()
                    ->clickLink('Forêts')
                    ->assertPathIs('/settings/forets')
                    ->back()
                    ->clickLink('Essences')
                    ->assertPathIs('/settings/essences');
        });
    }

    /**
     * Test dashboard recent articles section.
     */
    public function test_dashboard_recent_articles_section(): void
    {
        $user = User::factory()->create();
        
        // Create recent articles
        Article::factory()->count(3)->create([
            'created_at' => now()->subDays(1),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->assertSee('Articles récents')
                    ->assertSee('Voir tous les articles');
        });
    }

    /**
     * Test dashboard quick actions.
     */
    public function test_dashboard_quick_actions(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->assertSee('Actions rapides')
                    ->assertSee('Nouvel article')
                    ->assertSee('Nouvel exploitant')
                    ->assertSee('Nouvelle forêt')
                    ->assertSee('Nouvelle essence');
        });
    }

    /**
     * Test dashboard responsive design.
     */
    public function test_dashboard_responsive_design(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->resize(375, 667) // Mobile size
                    ->assertSee('Tableau de bord')
                    ->resize(1920, 1080) // Desktop size
                    ->assertSee('Tableau de bord');
        });
    }

    /**
     * Test dashboard search functionality.
     */
    public function test_dashboard_search_functionality(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->type('@search-input', 'test')
                    ->press('@search-button')
                    ->assertSee('Résultats de recherche');
        });
    }

    /**
     * Test dashboard filters.
     */
    public function test_dashboard_filters(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->click('@filter-button')
                    ->assertSee('Filtres')
                    ->select('@year-filter', '2024')
                    ->select('@status-filter', 'validated')
                    ->press('@apply-filters')
                    ->assertSee('Filtres appliqués');
        });
    }

    /**
     * Test dashboard export functionality.
     */
    public function test_dashboard_export_functionality(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->click('@export-button')
                    ->assertSee('Exporter les données')
                    ->click('@export-excel')
                    ->waitForText('Export en cours...')
                    ->assertSee('Export terminé');
        });
    }

    /**
     * Test dashboard refresh functionality.
     */
    public function test_dashboard_refresh_functionality(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->click('@refresh-button')
                    ->waitForText('Données mises à jour')
                    ->assertSee('Données mises à jour');
        });
    }
}
