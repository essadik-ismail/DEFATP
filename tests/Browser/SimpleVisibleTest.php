<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SimpleVisibleTest extends DuskTestCase
{
    /**
     * Simple test that shows browser window without complex assertions.
     */
    public function test_show_browser(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->pause(3000) // 3 second pause to see the page
                    ->assertTitle('MAKHZON'); // Simple assertion that should work
        });
    }

    /**
     * Test that shows form interaction with visible browser.
     */
    public function test_form_interaction(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->pause(2000) // Wait for page to load
                    ->assertTitle('MAKHZON')
                    ->pause(1000)
                    ->type('ppr', '12345678')
                    ->pause(1000)
                    ->type('password', 'password')
                    ->pause(2000); // Pause to see the form filled
        });
    }
}

