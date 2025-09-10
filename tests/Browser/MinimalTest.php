<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MinimalTest extends DuskTestCase
{
    /**
     * Test that the application can be accessed.
     */
    public function test_application_accessible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Connexion');
        });
    }

    /**
     * Test that the login page has required elements.
     */
    public function test_login_page_elements(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Email')
                    ->assertSee('Mot de passe')
                    ->assertSee('Se connecter');
        });
    }

    /**
     * Test that guest users are redirected to login.
     */
    public function test_guest_redirect(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertPathIs('/login');
        });
    }
}
