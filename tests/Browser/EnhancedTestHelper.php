<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Exception;

class EnhancedTestHelper
{
    /**
     * Wait for page to be fully loaded with multiple checks.
     */
    public static function waitForPageLoad(Browser $browser, int $timeout = 10): void
    {
        $browser->waitUntilMissing('.loading', $timeout)
                ->waitUntilMissing('[data-loading="true"]', $timeout)
                ->waitUntilMissing('.spinner', $timeout)
                ->waitForText('Se connecter', $timeout);
    }

    /**
     * Take a screenshot with descriptive name and timestamp.
     */
    public static function takeScreenshot(Browser $browser, string $name, string $description = ''): void
    {
        $timestamp = now()->format('Y-m-d_H-i-s-u');
        $filename = $description ? "{$name}_{$description}_{$timestamp}" : "{$name}_{$timestamp}";
        $browser->screenshot($filename);
    }

    /**
     * Fill login form with visual feedback.
     */
    public static function fillLoginForm(Browser $browser, string $ppr = '12345678', string $password = 'password'): void
    {
        $browser->type('ppr', $ppr)
                ->pause(300) // Short pause to see typing
                ->type('password', $password)
                ->pause(300); // Short pause to see typing
    }

    /**
     * Verify login form is properly filled.
     */
    public static function verifyLoginForm(Browser $browser, string $ppr = '12345678', string $password = 'password'): void
    {
        $browser->assertInputValue('ppr', $ppr)
                ->assertInputValue('password', $password);
    }

    /**
     * Attempt login with error handling.
     */
    public static function attemptLogin(Browser $browser): array
    {
        $browser->press('Se connecter')
                ->pause(3000); // Wait for response

        // Check if login was successful
        if ($browser->element('@dashboard') || $browser->element('.dashboard') || $browser->element('[data-testid="dashboard"]')) {
            return ['success' => true, 'message' => 'Login successful'];
        } elseif ($browser->element('.alert-danger') || $browser->element('.error-message')) {
            return ['success' => false, 'message' => 'Login failed with error message'];
        } else {
            return ['success' => false, 'message' => 'Unknown login result'];
        }
    }

    /**
     * Test responsive design across different screen sizes.
     */
    public static function testResponsiveDesign(Browser $browser, string $url = 'http://127.0.0.1:8000/login'): void
    {
        $sizes = [
            'desktop' => [1920, 1080],
            'laptop' => [1366, 768],
            'tablet' => [768, 1024],
            'mobile' => [375, 667],
            'small_mobile' => [320, 568]
        ];

        foreach ($sizes as $name => $dimensions) {
            $browser->resize($dimensions[0], $dimensions[1])
                    ->visit($url)
                    ->waitForText('Se connecter', 10)
                    ->pause(1000)
                    ->screenshot("responsive_{$name}");
        }
    }

    /**
     * Test form validation with comprehensive checks.
     */
    public static function testFormValidation(Browser $browser): void
    {
        // Test empty form submission
        $browser->press('Se connecter')
                ->pause(2000)
                ->screenshot('validation_empty_form');

        // Test partial form submissions
        $browser->type('ppr', '12345678')
                ->press('Se connecter')
                ->pause(2000)
                ->screenshot('validation_ppr_only');

        $browser->clear('ppr')
                ->type('password', 'password')
                ->press('Se connecter')
                ->pause(2000)
                ->screenshot('validation_password_only');
    }

    /**
     * Wait for element to be visible with custom timeout.
     */
    public static function waitForElement(Browser $browser, string $selector, int $timeout = 10): void
    {
        $browser->waitFor($selector, $timeout)
                ->assertVisible($selector);
    }

    /**
     * Scroll to element and take screenshot.
     */
    public static function scrollToElement(Browser $browser, string $selector, string $screenshotName = ''): void
    {
        $browser->scrollTo($selector)
                ->pause(500);
        
        if ($screenshotName) {
            $browser->screenshot($screenshotName);
        }
    }

    /**
     * Test keyboard navigation.
     */
    public static function testKeyboardNavigation(Browser $browser): void
    {
        $browser->keys('input[name="ppr"]', ['{tab}'])
                ->pause(500)
                ->screenshot('keyboard_tab_navigation')
                ->keys('input[name="password"]', ['{tab}'])
                ->pause(500)
                ->screenshot('keyboard_tab_to_submit');
    }

    /**
     * Test form clearing functionality.
     */
    public static function testFormClearing(Browser $browser): void
    {
        $browser->type('ppr', '12345678')
                ->type('password', 'password')
                ->pause(500)
                ->screenshot('form_filled')
                ->clear('ppr')
                ->clear('password')
                ->pause(500)
                ->screenshot('form_cleared');
    }

    /**
     * Get current page information for debugging.
     */
    public static function getPageInfo(Browser $browser): array
    {
        return [
            'title' => $browser->driver->getTitle(),
            'url' => $browser->driver->getCurrentURL(),
            'source' => $browser->driver->getPageSource(),
        ];
    }

    /**
     * Log test step with timestamp.
     */
    public static function logStep(Browser $browser, string $step, string $screenshotName = ''): void
    {
        $timestamp = now()->format('H:i:s.u');
        echo "\n[{$timestamp}] Test Step: {$step}\n";
        
        if ($screenshotName) {
            $browser->screenshot($screenshotName);
        }
    }
}
