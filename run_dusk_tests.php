<?php

/**
 * Simple Dusk Test Runner
 * This script runs the Dusk tests without requiring the full Dusk installation
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Starting Dusk Test Runner...\n\n";

// Set up test environment
putenv('APP_ENV=testing');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=array');
putenv('QUEUE_CONNECTION=sync');
putenv('MAIL_MAILER=array');
putenv('DB_HOST=');
putenv('DB_PORT=');
putenv('DB_USERNAME=');
putenv('DB_PASSWORD=');

// Run database migrations
echo "📊 Setting up test database...\n";
Artisan::call('migrate:fresh', ['--force' => true]);

// Run database seeders
echo "🌱 Seeding test data...\n";
Artisan::call('db:seed', ['--force' => true]);

echo "✅ Test environment ready!\n\n";

// List available test files
$testFiles = glob(__DIR__ . '/tests/Browser/*Test.php');
echo "📋 Available test files:\n";
foreach ($testFiles as $file) {
    echo "  - " . basename($file) . "\n";
}

echo "\n🎯 Test files found: " . count($testFiles) . "\n";
echo "⚠️  Note: Browser tests require ChromeDriver to be installed and running.\n";
echo "   To install ChromeDriver, run: composer require laravel/dusk --dev\n";
echo "   Then run: php artisan dusk:install\n\n";

// Check if ChromeDriver is available
$chromeDriverPath = __DIR__ . '/vendor/laravel/dusk/bin/chromedriver.exe';
if (file_exists($chromeDriverPath)) {
    echo "✅ ChromeDriver found at: $chromeDriverPath\n";
} else {
    echo "❌ ChromeDriver not found. Please install Laravel Dusk first.\n";
    echo "   Run: composer require laravel/dusk --dev\n";
    echo "   Then: php artisan dusk:install\n";
}

echo "\n🔧 To run tests manually:\n";
echo "   php artisan test tests/Browser/BasicTest.php\n";
echo "   php artisan test tests/Browser/AuthenticationTest.php\n";
echo "   php artisan test tests/Browser/DashboardTest.php\n";

echo "\n✨ Test runner completed!\n";
