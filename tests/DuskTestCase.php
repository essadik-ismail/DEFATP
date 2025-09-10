<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class DuskTestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver(['--port=9515']);
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
            '--disable-web-security',
            '--disable-features=VizDisplayCompositor',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--disable-extensions',
            '--disable-plugins',
            '--disable-default-apps',
            '--disable-sync',
            '--disable-translate',
            '--mute-audio',
            '--no-first-run',
            '--disable-background-timer-throttling',
            '--disable-backgrounding-occluded-windows',
            '--disable-renderer-backgrounding',
            '--disable-field-trial-config',
            '--disable-back-forward-cache',
            '--disable-ipc-flooding-protection',
            '--disable-blink-features=AutomationControlled',
            '--disable-infobars',
            '--disable-notifications',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Determine if the browser should start maximized.
     */
    protected function shouldStartMaximized(): bool
    {
        return env('DUSK_START_MAXIMIZED', false);
    }

    /**
     * Determine if headless mode should be disabled.
     */
    protected function hasHeadlessDisabled(): bool
    {
        return env('DUSK_HEADLESS_DISABLED', true);
    }

    /**
     * Get the base URL for the application.
     */
    protected function baseUrl(): string
    {
        return env('APP_URL', 'http://127.0.0.1:8000');
    }

    /**
     * Get the database connection for testing.
     */
    protected function databaseConnection(): string
    {
        return 'sqlite';
    }

    /**
     * Get the database name for testing.
     */
    protected function databaseName(): string
    {
        return ':memory:';
    }

    /**
     * Get the test user credentials.
     */
    protected function getTestUserCredentials(): array
    {
        return [
            'ppr' => env('TEST_USER_PPR', '12345678'),
            'password' => env('TEST_USER_PASSWORD', 'password'),
        ];
    }

    /**
     * Get the admin user credentials.
     */
    protected function getAdminUserCredentials(): array
    {
        return [
            'ppr' => env('ADMIN_USER_PPR', '87654321'),
            'password' => env('ADMIN_USER_PASSWORD', 'password'),
        ];
    }

    /**
     * Get the test data directory.
     */
    protected function getTestDataDirectory(): string
    {
        return __DIR__ . '/Browser/fixtures';
    }

    /**
     * Get the screenshots directory.
     */
    protected function getScreenshotsDirectory(): string
    {
        return __DIR__ . '/Browser/screenshots';
    }

    /**
     * Get the source directory.
     */
    protected function getSourceDirectory(): string
    {
        return __DIR__ . '/Browser/source';
    }

    /**
     * Get the console directory.
     */
    protected function getConsoleDirectory(): string
    {
        return __DIR__ . '/Browser/console';
    }
}
