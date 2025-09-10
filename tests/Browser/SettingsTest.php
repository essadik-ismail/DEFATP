<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Essence;
use App\Models\Foret;
use App\Models\Exploitant;
use App\Models\Localisation;
use App\Models\NatureDeCoupe;
use App\Models\SituationAdministrative;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SettingsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test essences management.
     */
    public function test_essences_management(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            // Test essences index
            $browser->loginAs($user)
                    ->visit('/settings/essences')
                    ->assertSee('Essences')
                    ->assertSee('Nouvelle essence');

            // Test create essence
            $browser->clickLink('Nouvelle essence')
                    ->assertPathIs('/settings/essences/create')
                    ->type('essence', 'Chêne')
                    ->type('description', 'Essence de chêne')
                    ->press('Créer')
                    ->assertPathIs('/settings/essences')
                    ->assertSee('Essence créée avec succès')
                    ->assertSee('Chêne');

            // Test edit essence
            $browser->click('@edit-essence-1')
                    ->assertPathIs('/settings/essences/1/edit')
                    ->clear('essence')
                    ->type('essence', 'Chêne Modifié')
                    ->press('Mettre à jour')
                    ->assertSee('Essence mise à jour avec succès')
                    ->assertSee('Chêne Modifié');

            // Test delete essence
            $browser->click('@delete-essence-1')
                    ->whenAvailable('.modal', function ($modal) {
                        $modal->press('Confirmer');
                    })
                    ->assertSee('Essence supprimée avec succès')
                    ->assertDontSee('Chêne Modifié');
        });
    }

    /**
     * Test forêts management.
     */
    public function test_forets_management(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            // Test forêts index
            $browser->loginAs($user)
                    ->visit('/settings/forets')
                    ->assertSee('Forêts')
                    ->assertSee('Nouvelle forêt');

            // Test create forêt
            $browser->clickLink('Nouvelle forêt')
                    ->assertPathIs('/settings/forets/create')
                    ->type('nom_foret', 'Forêt de Test')
                    ->type('superficie', '1000')
                    ->type('latitude', '45.123456')
                    ->type('longitude', '2.654321')
                    ->press('Créer')
                    ->assertPathIs('/settings/forets')
                    ->assertSee('Forêt créée avec succès')
                    ->assertSee('Forêt de Test');

            // Test edit forêt
            $browser->click('@edit-foret-1')
                    ->assertPathIs('/settings/forets/1/edit')
                    ->clear('nom_foret')
                    ->type('nom_foret', 'Forêt Modifiée')
                    ->press('Mettre à jour')
                    ->assertSee('Forêt mise à jour avec succès')
                    ->assertSee('Forêt Modifiée');
        });
    }

    /**
     * Test exploitants management.
     */
    public function test_exploitants_management(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            // Test exploitants index
            $browser->loginAs($user)
                    ->visit('/settings/exploitants')
                    ->assertSee('Exploitants')
                    ->assertSee('Nouvel exploitant');

            // Test create exploitant
            $browser->clickLink('Nouvel exploitant')
                    ->assertPathIs('/settings/exploitants/create')
                    ->type('nom_complet', 'Jean Dupont')
                    ->type('cin', 'A123456')
                    ->type('adresse', '123 Rue Test')
                    ->type('telephone', '0123456789')
                    ->type('email', 'jean@example.com')
                    ->press('Créer')
                    ->assertPathIs('/settings/exploitants')
                    ->assertSee('Exploitant créé avec succès')
                    ->assertSee('Jean Dupont');

            // Test view exploitant
            $browser->click('@view-exploitant-1')
                    ->assertPathIs('/settings/exploitants/1')
                    ->assertSee('Détails de l\'exploitant')
                    ->assertSee('Jean Dupont')
                    ->assertSee('A123456');

            // Test edit exploitant
            $browser->click('@edit-exploitant-1')
                    ->assertPathIs('/settings/exploitants/1/edit')
                    ->clear('nom_complet')
                    ->type('nom_complet', 'Jean Dupont Modifié')
                    ->press('Mettre à jour')
                    ->assertSee('Exploitant mis à jour avec succès')
                    ->assertSee('Jean Dupont Modifié');
        });
    }

    /**
     * Test nature de coupes management.
     */
    public function test_nature_de_coupes_management(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            // Test nature de coupes index
            $browser->loginAs($user)
                    ->visit('/settings/nature-de-coupes')
                    ->assertSee('Nature de Coupes')
                    ->assertSee('Nouvelle nature de coupe');

            // Test create nature de coupe
            $browser->clickLink('Nouvelle nature de coupe')
                    ->assertPathIs('/settings/nature-de-coupes/create')
                    ->type('nature_de_coupe', 'Coupe rase')
                    ->type('description', 'Coupe rase complète')
                    ->press('Créer')
                    ->assertPathIs('/settings/nature-de-coupes')
                    ->assertSee('Nature de coupe créée avec succès')
                    ->assertSee('Coupe rase');

            // Test edit nature de coupe
            $browser->click('@edit-nature-de-coupe-1')
                    ->assertPathIs('/settings/nature-de-coupes/1/edit')
                    ->clear('nature_de_coupe')
                    ->type('nature_de_coupe', 'Coupe rase modifiée')
                    ->press('Mettre à jour')
                    ->assertSee('Nature de coupe mise à jour avec succès')
                    ->assertSee('Coupe rase modifiée');
        });
    }

    /**
     * Test localisations management.
     */
    public function test_localisations_management(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            // Test localisations index
            $browser->loginAs($user)
                    ->visit('/settings/localisations')
                    ->assertSee('Localisations')
                    ->assertSee('Nouvelle localisation');

            // Test create localisation
            $browser->clickLink('Nouvelle localisation')
                    ->assertPathIs('/settings/localisations/create')
                    ->type('code', 'LOC-001')
                    ->type('dranef', 'DRANEF-001')
                    ->type('entite', 'Entité Test')
                    ->press('Créer')
                    ->assertPathIs('/settings/localisations')
                    ->assertSee('Localisation créée avec succès')
                    ->assertSee('LOC-001');

            // Test edit localisation
            $browser->click('@edit-localisation-1')
                    ->assertPathIs('/settings/localisations/1/edit')
                    ->clear('code')
                    ->type('code', 'LOC-001-MOD')
                    ->press('Mettre à jour')
                    ->assertSee('Localisation mise à jour avec succès')
                    ->assertSee('LOC-001-MOD');
        });
    }

    /**
     * Test situation administratives management.
     */
    public function test_situation_administratives_management(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            // Test situation administratives index
            $browser->loginAs($user)
                    ->visit('/settings/situation-administratives')
                    ->assertSee('Situations Administratives')
                    ->assertSee('Nouvelle situation administrative');

            // Test create situation administrative
            $browser->clickLink('Nouvelle situation administrative')
                    ->assertPathIs('/settings/situation-administratives/create')
                    ->type('commune', 'Commune Test')
                    ->type('province', 'Province Test')
                    ->press('Créer')
                    ->assertPathIs('/settings/situation-administratives')
                    ->assertSee('Situation administrative créée avec succès')
                    ->assertSee('Commune Test');

            // Test edit situation administrative
            $browser->click('@edit-situation-administrative-1')
                    ->assertPathIs('/settings/situation-administratives/1/edit')
                    ->clear('commune')
                    ->type('commune', 'Commune Modifiée')
                    ->press('Mettre à jour')
                    ->assertSee('Situation administrative mise à jour avec succès')
                    ->assertSee('Commune Modifiée');
        });
    }

    /**
     * Test settings search functionality.
     */
    public function test_settings_search_functionality(): void
    {
        $user = User::factory()->create();
        Essence::factory()->create(['essence' => 'Chêne']);
        Essence::factory()->create(['essence' => 'Pin']);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/settings/essences')
                    ->type('@search-input', 'Chêne')
                    ->press('@search-button')
                    ->assertSee('Chêne')
                    ->assertDontSee('Pin');
        });
    }

    /**
     * Test settings export functionality.
     */
    public function test_settings_export_functionality(): void
    {
        $user = User::factory()->create();
        Essence::factory()->count(3)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/settings/essences')
                    ->click('@export-button')
                    ->assertSee('Exporter les essences')
                    ->click('@export-excel')
                    ->waitForText('Export en cours...')
                    ->assertSee('Export terminé');
        });
    }

    /**
     * Test settings import functionality.
     */
    public function test_settings_import_functionality(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/settings/essences')
                    ->click('@import-button')
                    ->assertSee('Importer des essences')
                    ->attach('@file-input', __DIR__ . '/fixtures/essences.xlsx')
                    ->press('@import-submit')
                    ->waitForText('Import en cours...')
                    ->assertSee('Import terminé');
        });
    }

    /**
     * Test settings form validation.
     */
    public function test_settings_form_validation(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/settings/essences/create')
                    ->press('Créer')
                    ->assertSee('Le champ essence est obligatoire');
        });
    }

    /**
     * Test settings pagination.
     */
    public function test_settings_pagination(): void
    {
        $user = User::factory()->create();
        Essence::factory()->count(25)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/settings/essences')
                    ->assertSee('Page 1')
                    ->click('@next-page')
                    ->assertSee('Page 2')
                    ->click('@previous-page')
                    ->assertSee('Page 1');
        });
    }

    /**
     * Test settings sorting.
     */
    public function test_settings_sorting(): void
    {
        $user = User::factory()->create();
        Essence::factory()->create(['essence' => 'Zèbre']);
        Essence::factory()->create(['essence' => 'Araucaria']);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/settings/essences')
                    ->click('@sort-essence')
                    ->assertSeeIn('@first-row', 'Araucaria')
                    ->click('@sort-essence')
                    ->assertSeeIn('@first-row', 'Zèbre');
        });
    }
}
