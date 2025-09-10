<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class AppComponent extends BaseComponent
{
    /**
     * Get the root selector for the component.
     */
    public function selector(): string
    {
        return '#app';
    }

    /**
     * Assert that the browser page contains the component.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     */
    public function elements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }

    /**
     * Navigate to the login page.
     */
    public function navigateToLogin(Browser $browser): void
    {
        $browser->visit('/login');
    }

    /**
     * Login with credentials.
     */
    public function login(Browser $browser, string $email, string $password): void
    {
        $browser->type('email', $email)
                ->type('password', $password)
                ->press('Se connecter');
    }

    /**
     * Logout from the application.
     */
    public function logout(Browser $browser): void
    {
        $browser->click('@logout-button')
                ->whenAvailable('.modal', function ($modal) {
                    $modal->press('Confirmer');
                });
    }

    /**
     * Navigate to dashboard.
     */
    public function navigateToDashboard(Browser $browser): void
    {
        $browser->visit('/');
    }

    /**
     * Navigate to articles page.
     */
    public function navigateToArticles(Browser $browser): void
    {
        $browser->visit('/articles');
    }

    /**
     * Navigate to settings page.
     */
    public function navigateToSettings(Browser $browser): void
    {
        $browser->visit('/settings');
    }

    /**
     * Navigate to reports page.
     */
    public function navigateToReports(Browser $browser): void
    {
        $browser->visit('/reports');
    }

    /**
     * Navigate to Excel import/export page.
     */
    public function navigateToExcel(Browser $browser): void
    {
        $browser->visit('/excel');
    }

    /**
     * Wait for page to load.
     */
    public function waitForPageLoad(Browser $browser): void
    {
        $browser->waitForText('Tableau de bord', 10);
    }

    /**
     * Assert success message is displayed.
     */
    public function assertSuccessMessage(Browser $browser, string $message): void
    {
        $browser->assertSee($message);
    }

    /**
     * Assert error message is displayed.
     */
    public function assertErrorMessage(Browser $browser, string $message): void
    {
        $browser->assertSee($message);
    }

    /**
     * Assert validation error is displayed.
     */
    public function assertValidationError(Browser $browser, string $field, string $message): void
    {
        $browser->assertSee($message);
    }

    /**
     * Fill form field.
     */
    public function fillField(Browser $browser, string $field, string $value): void
    {
        $browser->type($field, $value);
    }

    /**
     * Select option from dropdown.
     */
    public function selectOption(Browser $browser, string $field, string $value): void
    {
        $browser->select($field, $value);
    }

    /**
     * Click button.
     */
    public function clickButton(Browser $browser, string $button): void
    {
        $browser->press($button);
    }

    /**
     * Click link.
     */
    public function clickLink(Browser $browser, string $link): void
    {
        $browser->clickLink($link);
    }

    /**
     * Assert text is visible.
     */
    public function assertTextVisible(Browser $browser, string $text): void
    {
        $browser->assertSee($text);
    }

    /**
     * Assert text is not visible.
     */
    public function assertTextNotVisible(Browser $browser, string $text): void
    {
        $browser->assertDontSee($text);
    }

    /**
     * Assert path is correct.
     */
    public function assertPath(Browser $browser, string $path): void
    {
        $browser->assertPathIs($path);
    }

    /**
     * Wait for element to be visible.
     */
    public function waitForElement(Browser $browser, string $selector, int $seconds = 10): void
    {
        $browser->waitFor($selector, $seconds);
    }

    /**
     * Wait for text to be visible.
     */
    public function waitForText(Browser $browser, string $text, int $seconds = 10): void
    {
        $browser->waitForText($text, $seconds);
    }

    /**
     * Take screenshot.
     */
    public function takeScreenshot(Browser $browser, string $name): void
    {
        $browser->screenshot($name);
    }

    /**
     * Scroll to element.
     */
    public function scrollToElement(Browser $browser, string $selector): void
    {
        $browser->script("document.querySelector('{$selector}').scrollIntoView();");
    }

    /**
     * Resize browser window.
     */
    public function resizeWindow(Browser $browser, int $width, int $height): void
    {
        $browser->resize($width, $height);
    }
}
