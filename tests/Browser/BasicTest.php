<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BasicTest extends DuskTestCase
{
    /**
     * Test that the application loads without errors.
     */
    public function test_application_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->assertTitle('Connexion - SylvaNet')
                    ->assertSee('Se connecter');
        });
    }

    /**
     * Test that the login page is accessible.
     */
    public function test_login_page_accessible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->assertSee('PPR')
                    ->assertSee('Mot de passe')
                    ->assertSee('Se connecter');
        });
    }

    /**
     * Test that guest users are redirected to login.
     */
    public function test_guest_redirected_to_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/')
                    ->assertPathIs('/login');
        });
    }
}
