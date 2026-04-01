<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SmokeTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_login_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Se connecter')
                ->assertSee('PPR')
                ->assertSee('Mot de passe');
        });
    }

    public function test_authenticated_user_can_open_dashboard(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/')
                ->assertSee('Tableau de bord');
        });
    }
}
