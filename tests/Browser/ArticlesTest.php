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

class ArticlesTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create necessary related models
        $this->exploitant = Exploitant::factory()->create();
        $this->essence = Essence::factory()->create();
        $this->foret = Foret::factory()->create();
        $this->localisation = Localisation::factory()->create();
        $this->natureDeCoupe = NatureDeCoupe::factory()->create();
        $this->situationAdministrative = SituationAdministrative::factory()->create();
    }

    /**
     * Test articles index page displays correctly.
     */
    public function test_articles_index_displays_correctly(): void
    {
        $user = User::factory()->create();
        Article::factory()->count(5)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->assertSee('Articles')
                    ->assertSee('Nouvel article')
                    ->assertSee('Rechercher')
                    ->assertSee('Filtres');
        });
    }

    /**
     * Test user can create a new article.
     */
    public function test_user_can_create_new_article(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->clickLink('Nouvel article')
                    ->assertPathIs('/articles/create')
                    ->assertSee('Créer un nouvel article')
                    ->type('numero_article', 'ART-001')
                    ->type('prix_vente', '1000')
                    ->type('volume', '50')
                    ->select('exploitant_id', $this->exploitant->id)
                    ->select('essence_id', $this->essence->id)
                    ->select('foret_id', $this->foret->id)
                    ->select('localisation_id', $this->localisation->id)
                    ->select('nature_de_coupe_id', $this->natureDeCoupe->id)
                    ->select('situation_administrative_id', $this->situationAdministrative->id)
                    ->type('date_adjudication', '2024-01-15')
                    ->press('Créer l\'article')
                    ->assertPathIs('/articles')
                    ->assertSee('Article créé avec succès')
                    ->assertSee('ART-001');
        });
    }

    /**
     * Test user can view article details.
     */
    public function test_user_can_view_article_details(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'numero_article' => 'ART-002',
            'prix_vente' => 1500,
        ]);

        $this->browse(function (Browser $browser) use ($user, $article) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->clickLink('ART-002')
                    ->assertPathIs('/articles/' . $article->id)
                    ->assertSee('ART-002')
                    ->assertSee('1500')
                    ->assertSee('Détails de l\'article');
        });
    }

    /**
     * Test user can edit an article.
     */
    public function test_user_can_edit_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'numero_article' => 'ART-003',
            'prix_vente' => 2000,
        ]);

        $this->browse(function (Browser $browser) use ($user, $article) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->click('@edit-button-' . $article->id)
                    ->assertPathIs('/articles/' . $article->id . '/edit')
                    ->assertSee('Modifier l\'article')
                    ->clear('prix_vente')
                    ->type('prix_vente', '2500')
                    ->press('Mettre à jour')
                    ->assertPathIs('/articles')
                    ->assertSee('Article mis à jour avec succès')
                    ->assertSee('2500');
        });
    }

    /**
     * Test user can delete an article.
     */
    public function test_user_can_delete_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'numero_article' => 'ART-004',
        ]);

        $this->browse(function (Browser $browser) use ($user, $article) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->click('@delete-button-' . $article->id)
                    ->whenAvailable('.modal', function ($modal) {
                        $modal->press('Confirmer la suppression');
                    })
                    ->assertSee('Article supprimé avec succès')
                    ->assertDontSee('ART-004');
        });
    }

    /**
     * Test article search functionality.
     */
    public function test_article_search_functionality(): void
    {
        $user = User::factory()->create();
        Article::factory()->create(['numero_article' => 'ART-SEARCH-001']);
        Article::factory()->create(['numero_article' => 'ART-OTHER-002']);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->type('@search-input', 'SEARCH')
                    ->press('@search-button')
                    ->assertSee('ART-SEARCH-001')
                    ->assertDontSee('ART-OTHER-002');
        });
    }

    /**
     * Test article filtering by status.
     */
    public function test_article_filtering_by_status(): void
    {
        $user = User::factory()->create();
        Article::factory()->create(['is_validated' => true]);
        Article::factory()->create(['is_validated' => false]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->select('@status-filter', 'validated')
                    ->press('@apply-filters')
                    ->assertSee('Articles validés');
        });
    }

    /**
     * Test article filtering by year.
     */
    public function test_article_filtering_by_year(): void
    {
        $user = User::factory()->create();
        Article::factory()->create(['annee' => 2024]);
        Article::factory()->create(['annee' => 2023]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->select('@year-filter', '2024')
                    ->press('@apply-filters')
                    ->assertSee('Articles de 2024');
        });
    }

    /**
     * Test article export functionality.
     */
    public function test_article_export_functionality(): void
    {
        $user = User::factory()->create();
        Article::factory()->count(3)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->click('@export-button')
                    ->assertSee('Exporter les articles')
                    ->click('@export-excel')
                    ->waitForText('Export en cours...')
                    ->assertSee('Export terminé');
        });
    }

    /**
     * Test article import functionality.
     */
    public function test_article_import_functionality(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->click('@import-button')
                    ->assertSee('Importer des articles')
                    ->attach('@file-input', __DIR__ . '/fixtures/articles.xlsx')
                    ->press('@import-submit')
                    ->waitForText('Import en cours...')
                    ->assertSee('Import terminé');
        });
    }

    /**
     * Test article form validation.
     */
    public function test_article_form_validation(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/articles/create')
                    ->press('Créer l\'article')
                    ->assertSee('Le champ numéro d\'article est obligatoire')
                    ->assertSee('Le champ prix de vente est obligatoire')
                    ->assertSee('Le champ volume est obligatoire');
        });
    }

    /**
     * Test article pagination.
     */
    public function test_article_pagination(): void
    {
        $user = User::factory()->create();
        Article::factory()->count(25)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->assertSee('Page 1')
                    ->click('@next-page')
                    ->assertSee('Page 2')
                    ->click('@previous-page')
                    ->assertSee('Page 1');
        });
    }

    /**
     * Test article sorting.
     */
    public function test_article_sorting(): void
    {
        $user = User::factory()->create();
        Article::factory()->create(['numero_article' => 'ART-Z']);
        Article::factory()->create(['numero_article' => 'ART-A']);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/articles')
                    ->click('@sort-numero-article')
                    ->assertSeeIn('@first-row', 'ART-A')
                    ->click('@sort-numero-article')
                    ->assertSeeIn('@first-row', 'ART-Z');
        });
    }
}
