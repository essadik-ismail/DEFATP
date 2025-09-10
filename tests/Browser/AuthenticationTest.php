<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthenticationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test user can login with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Se connecter')
                    ->assertPathIs('/')
                    ->assertSee('Tableau de bord');
        });
    }

    /**
     * Test user cannot login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'invalid@example.com')
                    ->type('password', 'wrongpassword')
                    ->press('Se connecter')
                    ->assertPathIs('/login')
                    ->assertSee('Ces identifiants ne correspondent pas à nos enregistrements');
        });
    }

    /**
     * Test user can logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->click('@logout-button')
                    ->assertPathIs('/login')
                    ->assertSee('Connexion');
        });
    }

    /**
     * Test user can view profile.
     */
    public function test_user_can_view_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/profile')
                    ->assertSee('Profil')
                    ->assertSee($user->name)
                    ->assertSee($user->email);
        });
    }

    /**
     * Test user can update profile.
     */
    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/profile')
                    ->type('name', 'Updated Name')
                    ->type('email', 'updated@example.com')
                    ->press('Mettre à jour')
                    ->assertSee('Profil mis à jour avec succès')
                    ->assertSee('Updated Name')
                    ->assertSee('updated@example.com');
        });
    }

    /**
     * Test guest user is redirected to login.
     */
    public function test_guest_user_is_redirected_to_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertPathIs('/login')
                    ->assertSee('Connexion');
        });
    }

    /**
     * Test login form validation.
     */
    public function test_login_form_validation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->press('Se connecter')
                    ->assertSee('Le champ email est obligatoire')
                    ->assertSee('Le champ mot de passe est obligatoire');
        });
    }

    /**
     * Test profile form validation.
     */
    public function test_profile_form_validation(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/profile')
                    ->clear('name')
                    ->clear('email')
                    ->press('Mettre à jour')
                    ->assertSee('Le champ nom est obligatoire')
                    ->assertSee('Le champ email est obligatoire');
        });
    }
}
