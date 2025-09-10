<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SimpleTest extends DuskTestCase
{
    /**
     * Simple test that demonstrates basic browser functionality.
     */
    public function test_basic_browser_functionality(): void
    {
        $this->browse(function (Browser $browser) {
            // Test basic browser operations
            $browser->visit('https://www.google.com')
                    ->pause(2000)
                    ->assertTitle('Google')
                    ->screenshot('google_homepage')
                    ->pause(1000);
        });
    }

    /**
     * Test that demonstrates browser interaction with a simple form.
     */
    public function test_basic_form_interaction(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://www.google.com')
                    ->pause(2000)
                    ->type('input[name="q"]', 'Laravel Dusk Testing')
                    ->pause(1000)
                    ->screenshot('google_search_typed')
                    ->press('input[name="btnK"]')
                    ->pause(3000)
                    ->screenshot('google_search_results');
        });
    }

    /**
     * Test responsive design with different screen sizes.
     */
    public function test_responsive_design(): void
    {
        $this->browse(function (Browser $browser) {
            // Desktop view
            $browser->resize(1920, 1080)
                    ->visit('https://www.google.com')
                    ->pause(1000)
                    ->screenshot('responsive_desktop')
                    
                    // Tablet view
                    ->resize(768, 1024)
                    ->pause(1000)
                    ->screenshot('responsive_tablet')
                    
                    // Mobile view
                    ->resize(375, 667)
                    ->pause(1000)
                    ->screenshot('responsive_mobile');
        });
    }
}