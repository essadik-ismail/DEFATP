<?php

/**
 * Enhanced Test Runner for Laravel Dusk Tests
 * 
 * This script provides an easy way to run different types of tests
 * with enhanced UX and debugging capabilities.
 */

echo "🚀 Laravel Dusk Enhanced Test Runner\n";
echo "=====================================\n\n";

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "❌ Error: Please run this script from the Laravel project root directory.\n";
    exit(1);
}

// Get command line arguments
$args = $argv ?? [];
$testType = $args[1] ?? 'help';

switch ($testType) {
    case 'visibility':
        echo "🔍 Running Browser Visibility Tests...\n";
        runCommand('php artisan dusk tests/Browser/VisibleTest.php');
        break;
        
    case 'simple':
        echo "🧪 Running Simple Browser Tests...\n";
        runCommand('php artisan dusk tests/Browser/SimpleTest.php');
        break;
        
    case 'enhanced':
        echo "✨ Running Enhanced Visibility Tests...\n";
        runCommand('php artisan dusk tests/Browser/EnhancedVisibleTest.php');
        break;
        
    case 'all':
        echo "🎯 Running All Enhanced Tests...\n";
        runCommand('php artisan dusk tests/Browser/TestRunner.php');
        break;
        
    case 'interaction':
        echo "🖱️ Running Form Interaction Tests...\n";
        runCommand('php artisan dusk tests/Browser/VisibleTest.php --filter=test_browser_interaction');
        break;
        
    case 'login':
        echo "🔐 Running Login Tests...\n";
        runCommand('php artisan dusk tests/Browser/AuthenticationTest.php');
        break;
        
    case 'responsive':
        echo "📱 Running Responsive Design Tests...\n";
        runCommand('php artisan dusk tests/Browser/EnhancedVisibleTest.php --filter=test_responsive_design');
        break;
        
    case 'performance':
        echo "⚡ Running Performance Tests...\n";
        runCommand('php artisan dusk tests/Browser/EnhancedVisibleTest.php --filter=test_performance_and_loading');
        break;
        
    case 'validation':
        echo "✅ Running Form Validation Tests...\n";
        runCommand('php artisan dusk tests/Browser/EnhancedVisibleTest.php --filter=test_comprehensive_form_validation');
        break;
        
    case 'accessibility':
        echo "♿ Running Accessibility Tests...\n";
        runCommand('php artisan dusk tests/Browser/EnhancedVisibleTest.php --filter=test_accessibility_and_usability');
        break;
        
    case 'screenshots':
        echo "📸 Opening Screenshots Directory...\n";
        $screenshotDir = 'tests/Browser/screenshots';
        if (is_dir($screenshotDir)) {
            if (PHP_OS_FAMILY === 'Windows') {
                runCommand("explorer \"$screenshotDir\"");
            } else {
                runCommand("open \"$screenshotDir\"");
            }
        } else {
            echo "❌ Screenshots directory not found. Run some tests first.\n";
        }
        break;
        
    case 'clean':
        echo "🧹 Cleaning Test Artifacts...\n";
        cleanTestArtifacts();
        break;
        
    case 'help':
    default:
        showHelp();
        break;
}

function runCommand($command) {
    echo "Executing: $command\n";
    echo str_repeat("-", 50) . "\n";
    
    $output = [];
    $returnCode = 0;
    exec($command, $output, $returnCode);
    
    foreach ($output as $line) {
        echo $line . "\n";
    }
    
    echo str_repeat("-", 50) . "\n";
    
    if ($returnCode === 0) {
        echo "✅ Test completed successfully!\n";
    } else {
        echo "❌ Test failed with exit code: $returnCode\n";
    }
    
    echo "\n";
}

function cleanTestArtifacts() {
    $directories = [
        'tests/Browser/screenshots',
        'tests/Browser/console',
        'tests/Browser/source'
    ];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $files = glob("$dir/*");
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                    echo "Deleted: $file\n";
                }
            }
            echo "Cleaned: $dir\n";
        }
    }
    
    echo "✅ Test artifacts cleaned!\n";
}

function showHelp() {
    echo "Available Commands:\n";
    echo "==================\n\n";
    
    $commands = [
        'simple' => 'Run simple browser tests (no app required)',
        'visibility' => 'Run basic browser visibility tests',
        'enhanced' => 'Run enhanced visibility tests with better UX',
        'all' => 'Run all enhanced tests with comprehensive reporting',
        'interaction' => 'Run form interaction tests only',
        'login' => 'Run authentication/login tests',
        'responsive' => 'Run responsive design tests',
        'performance' => 'Run performance and loading tests',
        'validation' => 'Run form validation tests',
        'accessibility' => 'Run accessibility and usability tests',
        'screenshots' => 'Open screenshots directory',
        'clean' => 'Clean test artifacts (screenshots, logs)',
        'help' => 'Show this help message'
    ];
    
    foreach ($commands as $command => $description) {
        echo sprintf("  %-15s %s\n", $command, $description);
    }
    
    echo "\nUsage Examples:\n";
    echo "===============\n";
    echo "  php run_tests.php enhanced    # Run enhanced tests\n";
    echo "  php run_tests.php all         # Run all tests with reporting\n";
    echo "  php run_tests.php responsive  # Test responsive design\n";
    echo "  php run_tests.php screenshots # View test screenshots\n";
    echo "  php run_tests.php clean       # Clean test artifacts\n\n";
    
    echo "💡 Tips:\n";
    echo "  - Make sure your Laravel app is running on http://127.0.0.1:8000\n";
    echo "  - Check the screenshots directory for visual debugging\n";
    echo "  - Use 'all' command for comprehensive testing\n";
    echo "  - Use 'clean' to remove old test artifacts\n\n";
}
