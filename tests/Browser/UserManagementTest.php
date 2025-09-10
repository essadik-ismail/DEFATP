<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test user management index displays correctly.
     */
    public function test_user_management_index_displays_correctly(): void
    {
        $user = User::factory()->create();
        User::factory()->count(3)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->assertSee('Gestion des utilisateurs')
                    ->assertSee('Nouvel utilisateur')
                    ->assertSee('Rechercher')
                    ->assertSee('Filtres');
        });
    }

    /**
     * Test user can create a new user.
     */
    public function test_user_can_create_new_user(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->clickLink('Nouvel utilisateur')
                    ->assertPathIs('/admin/users/create')
                    ->assertSee('Créer un nouvel utilisateur')
                    ->type('name', 'John Doe')
                    ->type('email', 'john@example.com')
                    ->type('password', 'password')
                    ->type('password_confirmation', 'password')
                    ->press('Créer l\'utilisateur')
                    ->assertPathIs('/admin/users')
                    ->assertSee('Utilisateur créé avec succès')
                    ->assertSee('John Doe');
        });
    }

    /**
     * Test user can view user details.
     */
    public function test_user_can_view_user_details(): void
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $this->browse(function (Browser $browser) use ($user, $targetUser) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->clickLink('Jane Doe')
                    ->assertPathIs('/admin/users/' . $targetUser->id)
                    ->assertSee('Détails de l\'utilisateur')
                    ->assertSee('Jane Doe')
                    ->assertSee('jane@example.com');
        });
    }

    /**
     * Test user can edit a user.
     */
    public function test_user_can_edit_user(): void
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $this->browse(function (Browser $browser) use ($user, $targetUser) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->click('@edit-user-' . $targetUser->id)
                    ->assertPathIs('/admin/users/' . $targetUser->id . '/edit')
                    ->assertSee('Modifier l\'utilisateur')
                    ->clear('name')
                    ->type('name', 'Updated Name')
                    ->clear('email')
                    ->type('email', 'updated@example.com')
                    ->press('Mettre à jour')
                    ->assertPathIs('/admin/users')
                    ->assertSee('Utilisateur mis à jour avec succès')
                    ->assertSee('Updated Name');
        });
    }

    /**
     * Test user can delete a user.
     */
    public function test_user_can_delete_user(): void
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create([
            'name' => 'User To Delete',
        ]);

        $this->browse(function (Browser $browser) use ($user, $targetUser) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->click('@delete-user-' . $targetUser->id)
                    ->whenAvailable('.modal', function ($modal) {
                        $modal->press('Confirmer la suppression');
                    })
                    ->assertSee('Utilisateur supprimé avec succès')
                    ->assertDontSee('User To Delete');
        });
    }

    /**
     * Test user can toggle user status.
     */
    public function test_user_can_toggle_user_status(): void
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create([
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($user, $targetUser) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->click('@toggle-status-' . $targetUser->id)
                    ->whenAvailable('.modal', function ($modal) {
                        $modal->press('Confirmer');
                    })
                    ->assertSee('Statut de l\'utilisateur mis à jour')
                    ->assertSee('Inactif');
        });
    }

    /**
     * Test user search functionality.
     */
    public function test_user_search_functionality(): void
    {
        $user = User::factory()->create();
        User::factory()->create(['name' => 'John Search']);
        User::factory()->create(['name' => 'Jane Other']);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->type('@search-input', 'Search')
                    ->press('@search-button')
                    ->assertSee('John Search')
                    ->assertDontSee('Jane Other');
        });
    }

    /**
     * Test user filtering by status.
     */
    public function test_user_filtering_by_status(): void
    {
        $user = User::factory()->create();
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->select('@status-filter', 'active')
                    ->press('@apply-filters')
                    ->assertSee('Utilisateurs actifs');
        });
    }

    /**
     * Test user export functionality.
     */
    public function test_user_export_functionality(): void
    {
        $user = User::factory()->create();
        User::factory()->count(3)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->click('@export-button')
                    ->assertSee('Exporter les utilisateurs')
                    ->click('@export-excel')
                    ->waitForText('Export en cours...')
                    ->assertSee('Export terminé');
        });
    }

    /**
     * Test user form validation.
     */
    public function test_user_form_validation(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/users/create')
                    ->press('Créer l\'utilisateur')
                    ->assertSee('Le champ nom est obligatoire')
                    ->assertSee('Le champ email est obligatoire')
                    ->assertSee('Le champ mot de passe est obligatoire');
        });
    }

    /**
     * Test user pagination.
     */
    public function test_user_pagination(): void
    {
        $user = User::factory()->create();
        User::factory()->count(25)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->assertSee('Page 1')
                    ->click('@next-page')
                    ->assertSee('Page 2')
                    ->click('@previous-page')
                    ->assertSee('Page 1');
        });
    }

    /**
     * Test user sorting.
     */
    public function test_user_sorting(): void
    {
        $user = User::factory()->create();
        User::factory()->create(['name' => 'Zoe User']);
        User::factory()->create(['name' => 'Alice User']);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/admin/users')
                    ->click('@sort-name')
                    ->assertSeeIn('@first-row', 'Alice User')
                    ->click('@sort-name')
                    ->assertSeeIn('@first-row', 'Zoe User');
        });
    }
}
