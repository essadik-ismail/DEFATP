<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Exception;

class VisibleTest extends DuskTestCase
{
    /**
     * Test that shows browser window for demonstration with enhanced UX.
     */
    public function test_browser_visibility(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                $browser->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10) // Wait for specific text to appear
                        ->pause(2000) // Pause for 2 seconds to see the page
                        ->assertTitle('MAKHZON')
                        ->assertVisible('form') // Ensure form is visible
                        ->assertPresent('input[name="ppr"]') // Check if PPR input exists
                        ->assertPresent('input[name="password"]') // Check if password input exists
                        ->screenshot('login_page_loaded') // Take screenshot for debugging
                        ->pause(1000); // Another pause to see the result
            } catch (Exception $e) {
                $browser->screenshot('error_browser_visibility');
                throw $e;
            }
        });
    }

    /**
     * Test that demonstrates browser interaction with enhanced UX.
     */
    public function test_browser_interaction(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                $browser->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10) // Wait for page to fully load
                        ->pause(2000) // Longer pause to see the page load
                        ->screenshot('login_page_initial') // Take initial screenshot
                        
                        // Enhanced assertions with better error messages
                        ->assertSee('Se connecter')
                        ->assertSee('PPR')
                        ->assertSee('Mot de passe')
                        ->assertVisible('form')
                        
                        // Fill form with visual feedback
                        ->type('ppr', '12345678')
                        ->pause(500) // Short pause to see typing
                        ->screenshot('form_ppr_filled')
                        
                        ->type('password', 'password')
                        ->pause(500) // Short pause to see typing
                        ->screenshot('form_password_filled')
                        
                        // Verify form is properly filled
                        ->assertInputValue('ppr', '12345678')
                        ->assertInputValue('password', 'password')
                        
                        ->pause(2000) // Pause to see the form filled
                        ->screenshot('form_complete');
                        
            } catch (Exception $e) {
                $browser->screenshot('error_browser_interaction');
                throw $e;
            }
        });
    }

    /**
     * Test login attempt with visual feedback.
     */
    public function test_login_attempt(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                $browser->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10)
                        ->pause(1000)
                        ->screenshot('before_login')
                        
                        // Fill login form
                        ->type('ppr', '12345678')
                        ->type('password', 'password')
                        ->pause(1000)
                        
                        // Attempt login
                        ->press('Se connecter')
                        ->pause(3000) // Wait for response
                        ->screenshot('after_login_attempt');
                        
                        // Check if login was successful or failed
                        if ($browser->element('@dashboard') || $browser->element('.dashboard')) {
                            $browser->assertSee('Tableau de bord')
                                   ->screenshot('login_success');
                        } else {
                            $browser->assertSee('Ces identifiants ne correspondent pas')
                                   ->screenshot('login_failed');
                        }
                        
            } catch (Exception $e) {
                $browser->screenshot('error_login_attempt');
                throw $e;
            }
        });
    }

    /**
     * Test form validation with visual feedback.
     */
    public function test_form_validation(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                $browser->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10)
                        ->pause(1000)
                        ->screenshot('before_validation_test')
                        
                        // Try to submit empty form
                        ->press('Se connecter')
                        ->pause(2000)
                        ->screenshot('empty_form_submission')
                        
                        // Check for validation messages
                        ->assertSee('Le champ PPR est obligatoire')
                        ->assertSee('Le champ mot de passe est obligatoire')
                        
                        // Fill only PPR field
                        ->type('ppr', '12345678')
                        ->press('Se connecter')
                        ->pause(2000)
                        ->screenshot('partial_form_submission')
                        
                        // Fill only password field
                        ->clear('ppr')
                        ->type('password', 'password')
                        ->press('Se connecter')
                        ->pause(2000)
                        ->screenshot('password_only_submission');
                        
            } catch (Exception $e) {
                $browser->screenshot('error_form_validation');
                throw $e;
            }
        });
    }

    /**
     * Test responsive design and mobile view.
     */
    public function test_responsive_design(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                // Test desktop view
                $browser->resize(1920, 1080)
                        ->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10)
                        ->pause(1000)
                        ->screenshot('desktop_view')
                        
                        // Test tablet view
                        ->resize(768, 1024)
                        ->pause(1000)
                        ->screenshot('tablet_view')
                        
                        // Test mobile view
                        ->resize(375, 667)
                        ->pause(1000)
                        ->screenshot('mobile_view')
                        
                        // Verify form is still functional on mobile
                        ->type('ppr', '12345678')
                        ->type('password', 'password')
                        ->pause(1000)
                        ->screenshot('mobile_form_filled');
                        
            } catch (Exception $e) {
                $browser->screenshot('error_responsive_design');
                throw $e;
            }
        });
    }

    /**
     * Helper method to wait for page to be fully loaded.
     */
    private function waitForPageLoad(Browser $browser, int $timeout = 10): void
    {
        $browser->waitUntilMissing('.loading', $timeout)
                ->waitUntilMissing('[data-loading="true"]', $timeout)
                ->waitForText('Se connecter', $timeout);
    }

    /**
     * Helper method to take screenshot with timestamp.
     */
    private function takeTimestampedScreenshot(Browser $browser, string $name): void
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $browser->screenshot("{$name}_{$timestamp}");
    }
}
