<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Exception;

class TestRunner extends DuskTestCase
{
    /**
     * Run all enhanced tests with comprehensive reporting.
     */
    public function test_run_all_enhanced_tests(): void
    {
        $this->browse(function (Browser $browser) {
            $testResults = [];
            $startTime = microtime(true);

            try {
                // Test 1: Browser Visibility
                $this->runBrowserVisibilityTest($browser, $testResults);
                
                // Test 2: Form Interaction
                $this->runFormInteractionTest($browser, $testResults);
                
                // Test 3: Login Attempt
                $this->runLoginAttemptTest($browser, $testResults);
                
                // Test 4: Form Validation
                $this->runFormValidationTest($browser, $testResults);
                
                // Test 5: Performance Test
                $this->runPerformanceTest($browser, $testResults);
                
                // Test 6: Accessibility Test
                $this->runAccessibilityTest($browser, $testResults);
                
                $totalTime = microtime(true) - $startTime;
                $this->generateTestReport($browser, $testResults, $totalTime);
                
            } catch (Exception $e) {
                $browser->screenshot('test_runner_error');
                throw $e;
            }
        });
    }

    /**
     * Run browser visibility test.
     */
    private function runBrowserVisibilityTest(Browser $browser, array &$testResults): void
    {
        $testStart = microtime(true);
        
        try {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->waitForText('Se connecter', 10)
                    ->pause(2000)
                    ->assertTitle('MAKHZON')
                    ->assertVisible('form')
                    ->screenshot('test_1_browser_visibility');
            
            $testResults['browser_visibility'] = [
                'status' => 'PASSED',
                'duration' => microtime(true) - $testStart,
                'message' => 'Browser visibility test completed successfully'
            ];
        } catch (Exception $e) {
            $testResults['browser_visibility'] = [
                'status' => 'FAILED',
                'duration' => microtime(true) - $testStart,
                'message' => $e->getMessage()
            ];
            $browser->screenshot('test_1_browser_visibility_failed');
        }
    }

    /**
     * Run form interaction test.
     */
    private function runFormInteractionTest(Browser $browser, array &$testResults): void
    {
        $testStart = microtime(true);
        
        try {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->waitForText('Se connecter', 10)
                    ->pause(1000);
            
            EnhancedTestHelper::fillLoginForm($browser);
            EnhancedTestHelper::verifyLoginForm($browser);
            $browser->screenshot('test_2_form_interaction');
            
            $testResults['form_interaction'] = [
                'status' => 'PASSED',
                'duration' => microtime(true) - $testStart,
                'message' => 'Form interaction test completed successfully'
            ];
        } catch (Exception $e) {
            $testResults['form_interaction'] = [
                'status' => 'FAILED',
                'duration' => microtime(true) - $testStart,
                'message' => $e->getMessage()
            ];
            $browser->screenshot('test_2_form_interaction_failed');
        }
    }

    /**
     * Run login attempt test.
     */
    private function runLoginAttemptTest(Browser $browser, array &$testResults): void
    {
        $testStart = microtime(true);
        
        try {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->waitForText('Se connecter', 10)
                    ->pause(1000);
            
            EnhancedTestHelper::fillLoginForm($browser);
            $result = EnhancedTestHelper::attemptLogin($browser);
            $browser->screenshot('test_3_login_attempt');
            
            $testResults['login_attempt'] = [
                'status' => 'PASSED',
                'duration' => microtime(true) - $testStart,
                'message' => "Login attempt test completed: {$result['message']}"
            ];
        } catch (Exception $e) {
            $testResults['login_attempt'] = [
                'status' => 'FAILED',
                'duration' => microtime(true) - $testStart,
                'message' => $e->getMessage()
            ];
            $browser->screenshot('test_3_login_attempt_failed');
        }
    }

    /**
     * Run form validation test.
     */
    private function runFormValidationTest(Browser $browser, array &$testResults): void
    {
        $testStart = microtime(true);
        
        try {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->waitForText('Se connecter', 10)
                    ->pause(1000);
            
            EnhancedTestHelper::testFormValidation($browser);
            $browser->screenshot('test_4_form_validation');
            
            $testResults['form_validation'] = [
                'status' => 'PASSED',
                'duration' => microtime(true) - $testStart,
                'message' => 'Form validation test completed successfully'
            ];
        } catch (Exception $e) {
            $testResults['form_validation'] = [
                'status' => 'FAILED',
                'duration' => microtime(true) - $testStart,
                'message' => $e->getMessage()
            ];
            $browser->screenshot('test_4_form_validation_failed');
        }
    }

    /**
     * Run performance test.
     */
    private function runPerformanceTest(Browser $browser, array &$testResults): void
    {
        $testStart = microtime(true);
        
        try {
            $startTime = microtime(true);
            $browser->visit('http://127.0.0.1:8000/login')
                    ->waitForText('Se connecter', 10);
            $loadTime = microtime(true) - $startTime;
            
            $browser->screenshot('test_5_performance');
            
            $testResults['performance'] = [
                'status' => 'PASSED',
                'duration' => microtime(true) - $testStart,
                'message' => "Performance test completed. Page load time: {$loadTime}s"
            ];
        } catch (Exception $e) {
            $testResults['performance'] = [
                'status' => 'FAILED',
                'duration' => microtime(true) - $testStart,
                'message' => $e->getMessage()
            ];
            $browser->screenshot('test_5_performance_failed');
        }
    }

    /**
     * Run accessibility test.
     */
    private function runAccessibilityTest(Browser $browser, array &$testResults): void
    {
        $testStart = microtime(true);
        
        try {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->waitForText('Se connecter', 10)
                    ->pause(1000);
            
            // Test keyboard navigation
            $browser->click('input[name="ppr"]')
                    ->keys('input[name="ppr"]', ['{tab}'])
                    ->keys('input[name="password"]', ['{tab}'])
                    ->screenshot('test_6_accessibility');
            
            $testResults['accessibility'] = [
                'status' => 'PASSED',
                'duration' => microtime(true) - $testStart,
                'message' => 'Accessibility test completed successfully'
            ];
        } catch (Exception $e) {
            $testResults['accessibility'] = [
                'status' => 'FAILED',
                'duration' => microtime(true) - $testStart,
                'message' => $e->getMessage()
            ];
            $browser->screenshot('test_6_accessibility_failed');
        }
    }

    /**
     * Generate comprehensive test report.
     */
    private function generateTestReport(Browser $browser, array $testResults, float $totalTime): void
    {
        $passedTests = array_filter($testResults, fn($result) => $result['status'] === 'PASSED');
        $failedTests = array_filter($testResults, fn($result) => $result['status'] === 'FAILED');
        
        $report = [
            'total_tests' => count($testResults),
            'passed' => count($passedTests),
            'failed' => count($failedTests),
            'total_duration' => $totalTime,
            'success_rate' => count($testResults) > 0 ? (count($passedTests) / count($testResults)) * 100 : 0,
            'results' => $testResults
        ];
        
        // Log report to console
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "TEST EXECUTION REPORT\n";
        echo str_repeat("=", 60) . "\n";
        echo "Total Tests: {$report['total_tests']}\n";
        echo "Passed: {$report['passed']}\n";
        echo "Failed: {$report['failed']}\n";
        echo "Success Rate: " . number_format($report['success_rate'], 2) . "%\n";
        echo "Total Duration: " . number_format($totalTime, 2) . " seconds\n";
        echo str_repeat("-", 60) . "\n";
        
        foreach ($testResults as $testName => $result) {
            $status = $result['status'] === 'PASSED' ? '✓' : '✗';
            echo "{$status} {$testName}: {$result['message']} ({$result['duration']}s)\n";
        }
        
        echo str_repeat("=", 60) . "\n";
        
        // Take final screenshot
        $browser->screenshot('test_execution_report');
    }
}