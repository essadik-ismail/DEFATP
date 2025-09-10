<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Exception;

class EnhancedVisibleTest extends DuskTestCase
{
    /**
     * Enhanced test that demonstrates browser visibility with comprehensive UX improvements.
     */
    public function test_enhanced_browser_visibility(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                EnhancedTestHelper::logStep($browser, 'Starting browser visibility test');
                
                $browser->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10)
                        ->pause(2000);

                EnhancedTestHelper::logStep($browser, 'Page loaded, taking initial screenshot', 'initial_page_load');
                
                // Comprehensive page validation
                $browser->assertTitle('MAKHZON')
                        ->assertVisible('form')
                        ->assertPresent('input[name="ppr"]')
                        ->assertPresent('input[name="password"]')
                        ->assertSee('Se connecter')
                        ->assertSee('PPR')
                        ->assertSee('Mot de passe');

                EnhancedTestHelper::logStep($browser, 'Page validation completed', 'page_validation_complete');
                
                // Test responsive design
                EnhancedTestHelper::testResponsiveDesign($browser);
                
                EnhancedTestHelper::logStep($browser, 'Responsive design test completed', 'responsive_test_complete');
                
            } catch (Exception $e) {
                EnhancedTestHelper::takeScreenshot($browser, 'error', 'browser_visibility_failed');
                throw $e;
            }
        });
    }

    /**
     * Enhanced test that demonstrates comprehensive form interaction.
     */
    public function test_enhanced_form_interaction(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                EnhancedTestHelper::logStep($browser, 'Starting enhanced form interaction test');
                
                $browser->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10)
                        ->pause(1000);

                EnhancedTestHelper::logStep($browser, 'Page loaded, testing form interaction', 'form_interaction_start');
                
                // Fill form with visual feedback
                EnhancedTestHelper::fillLoginForm($browser);
                EnhancedTestHelper::logStep($browser, 'Form filled', 'form_filled');
                
                // Verify form is properly filled
                EnhancedTestHelper::verifyLoginForm($browser);
                EnhancedTestHelper::logStep($browser, 'Form verification completed', 'form_verified');
                
                // Test form clearing
                EnhancedTestHelper::testFormClearing($browser);
                EnhancedTestHelper::logStep($browser, 'Form clearing test completed', 'form_clearing_complete');
                
                // Refill form for further testing
                EnhancedTestHelper::fillLoginForm($browser);
                
                // Test keyboard navigation
                EnhancedTestHelper::testKeyboardNavigation($browser);
                EnhancedTestHelper::logStep($browser, 'Keyboard navigation test completed', 'keyboard_navigation_complete');
                
            } catch (Exception $e) {
                EnhancedTestHelper::takeScreenshot($browser, 'error', 'form_interaction_failed');
                throw $e;
            }
        });
    }

    /**
     * Comprehensive login attempt test with detailed feedback.
     */
    public function test_comprehensive_login_attempt(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                EnhancedTestHelper::logStep($browser, 'Starting comprehensive login attempt test');
                
                $browser->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10)
                        ->pause(1000);

                EnhancedTestHelper::logStep($browser, 'Page loaded, preparing login attempt', 'login_attempt_preparation');
                
                // Fill login form
                EnhancedTestHelper::fillLoginForm($browser);
                EnhancedTestHelper::logStep($browser, 'Form filled, attempting login', 'login_form_filled');
                
                // Attempt login
                $result = EnhancedTestHelper::attemptLogin($browser);
                EnhancedTestHelper::logStep($browser, "Login attempt result: {$result['message']}", 'login_attempt_result');
                
                // Take appropriate screenshot based on result
                if ($result['success']) {
                    $browser->assertSee('Tableau de bord')
                           ->screenshot('login_success_dashboard');
                } else {
                    $browser->screenshot('login_failed_error');
                }
                
            } catch (Exception $e) {
                EnhancedTestHelper::takeScreenshot($browser, 'error', 'login_attempt_failed');
                throw $e;
            }
        });
    }

    /**
     * Comprehensive form validation test.
     */
    public function test_comprehensive_form_validation(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                EnhancedTestHelper::logStep($browser, 'Starting comprehensive form validation test');
                
                $browser->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10)
                        ->pause(1000);

                EnhancedTestHelper::logStep($browser, 'Page loaded, testing form validation', 'validation_test_start');
                
                // Test form validation
                EnhancedTestHelper::testFormValidation($browser);
                EnhancedTestHelper::logStep($browser, 'Form validation test completed', 'validation_test_complete');
                
            } catch (Exception $e) {
                EnhancedTestHelper::takeScreenshot($browser, 'error', 'validation_test_failed');
                throw $e;
            }
        });
    }

    /**
     * Performance and loading test.
     */
    public function test_performance_and_loading(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                EnhancedTestHelper::logStep($browser, 'Starting performance and loading test');
                
                $startTime = microtime(true);
                
                $browser->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10);
                
                $loadTime = microtime(true) - $startTime;
                EnhancedTestHelper::logStep($browser, "Page load time: {$loadTime} seconds", 'performance_test');
                
                // Test page responsiveness
                $browser->pause(1000)
                        ->screenshot('performance_loaded_page');
                
                // Test form interaction performance
                $formStartTime = microtime(true);
                EnhancedTestHelper::fillLoginForm($browser);
                $formTime = microtime(true) - $formStartTime;
                
                EnhancedTestHelper::logStep($browser, "Form interaction time: {$formTime} seconds", 'form_performance_test');
                
            } catch (Exception $e) {
                EnhancedTestHelper::takeScreenshot($browser, 'error', 'performance_test_failed');
                throw $e;
            }
        });
    }

    /**
     * Accessibility and usability test.
     */
    public function test_accessibility_and_usability(): void
    {
        $this->browse(function (Browser $browser) {
            try {
                EnhancedTestHelper::logStep($browser, 'Starting accessibility and usability test');
                
                $browser->visit('http://127.0.0.1:8000/login')
                        ->waitForText('Se connecter', 10)
                        ->pause(1000);

                // Test form labels and accessibility
                $browser->assertSee('PPR')
                        ->assertSee('Mot de passe')
                        ->assertSee('Se connecter');

                EnhancedTestHelper::logStep($browser, 'Accessibility checks completed', 'accessibility_checks');
                
                // Test form focus and tab navigation
                $browser->click('input[name="ppr"]')
                        ->pause(500)
                        ->screenshot('focus_ppr_field')
                        ->keys('input[name="ppr"]', ['{tab}'])
                        ->pause(500)
                        ->screenshot('tab_to_password_field')
                        ->keys('input[name="password"]', ['{tab}'])
                        ->pause(500)
                        ->screenshot('tab_to_submit_button');

                EnhancedTestHelper::logStep($browser, 'Usability tests completed', 'usability_tests_complete');
                
            } catch (Exception $e) {
                EnhancedTestHelper::takeScreenshot($browser, 'error', 'accessibility_test_failed');
                throw $e;
            }
        });
    }
}
