# Laravel Dusk Testing Guide

This guide provides comprehensive information about the Laravel Dusk tests implemented for the Laravel application.

## 📋 Overview

Laravel Dusk provides an expressive, easy-to-use browser automation and testing API. The tests in this application cover all major functionality including authentication, CRUD operations, Excel import/export, reports, and user management.

## 🚀 Getting Started

### Prerequisites

- Laravel Dusk is already installed and configured
- ChromeDriver is automatically managed by Dusk
- Test database is configured (SQLite in-memory by default)

### Running Tests

```bash
# Run all Dusk tests
php artisan dusk

# Run specific test file
php artisan dusk tests/Browser/AuthenticationTest.php

# Run tests with specific filter
php artisan dusk --filter=test_user_can_login

# Run tests in headless mode (default)
php artisan dusk

# Run tests with browser visible
DUSK_HEADLESS_DISABLED=true php artisan dusk

# Run tests with browser maximized
DUSK_START_MAXIMIZED=true php artisan dusk
```

## 📁 Test Structure

### Test Files

1. **`AuthenticationTest.php`** - Authentication and user profile tests
2. **`DashboardTest.php`** - Dashboard functionality and statistics tests
3. **`ArticlesTest.php`** - Articles CRUD operations and management tests
4. **`SettingsTest.php`** - Settings management (essences, forests, exploitants, etc.) tests
5. **`ExcelTest.php`** - Excel import/export functionality tests
6. **`ReportsTest.php`** - Reports and analytics tests
7. **`UserManagementTest.php`** - User management and administration tests
8. **`TestRunner.php`** - Test runner for executing all tests

### Components

- **`AppComponent.php`** - Reusable component for common application interactions

### Fixtures

- **`fixtures/`** - Test data files for import/export testing
- **`screenshots/`** - Screenshots captured during test failures
- **`source/`** - Source code captured during test failures
- **`console/`** - Console logs captured during test failures

## 🧪 Test Categories

### 1. Authentication Tests (`AuthenticationTest.php`)

Tests user authentication functionality:

- ✅ User login with valid credentials
- ✅ User login with invalid credentials
- ✅ User logout
- ✅ User profile viewing
- ✅ User profile updating
- ✅ Guest user redirection
- ✅ Form validation

**Key Test Methods:**
- `test_user_can_login_with_valid_credentials()`
- `test_user_cannot_login_with_invalid_credentials()`
- `test_user_can_logout()`
- `test_user_can_view_profile()`
- `test_user_can_update_profile()`

### 2. Dashboard Tests (`DashboardTest.php`)

Tests dashboard functionality and statistics:

- ✅ Dashboard display
- ✅ Statistics display
- ✅ Navigation links
- ✅ Recent articles section
- ✅ Quick actions
- ✅ Responsive design
- ✅ Search functionality
- ✅ Filters
- ✅ Export functionality
- ✅ Refresh functionality

**Key Test Methods:**
- `test_dashboard_displays_correctly()`
- `test_dashboard_statistics_are_displayed()`
- `test_dashboard_navigation_links_work()`
- `test_dashboard_recent_articles_section()`

### 3. Articles Tests (`ArticlesTest.php`)

Tests articles CRUD operations:

- ✅ Articles index display
- ✅ Create new article
- ✅ View article details
- ✅ Edit article
- ✅ Delete article
- ✅ Search functionality
- ✅ Filtering by status
- ✅ Filtering by year
- ✅ Export functionality
- ✅ Import functionality
- ✅ Form validation
- ✅ Pagination
- ✅ Sorting

**Key Test Methods:**
- `test_user_can_create_new_article()`
- `test_user_can_view_article_details()`
- `test_user_can_edit_article()`
- `test_user_can_delete_article()`
- `test_article_search_functionality()`

### 4. Settings Tests (`SettingsTest.php`)

Tests settings management for all entities:

- ✅ Essences management
- ✅ Forêts management
- ✅ Exploitants management
- ✅ Nature de coupes management
- ✅ Localisations management
- ✅ Situation administratives management
- ✅ Search functionality
- ✅ Export functionality
- ✅ Import functionality
- ✅ Form validation
- ✅ Pagination
- ✅ Sorting

**Key Test Methods:**
- `test_essences_management()`
- `test_forets_management()`
- `test_exploitants_management()`
- `test_nature_de_coupes_management()`

### 5. Excel Tests (`ExcelTest.php`)

Tests Excel import/export functionality:

- ✅ Excel page display
- ✅ Individual table export
- ✅ Complete export
- ✅ Individual table import
- ✅ Bulk import
- ✅ File validation
- ✅ File size validation
- ✅ Import with validation errors
- ✅ Export with filters
- ✅ Progress indicators
- ✅ Error handling
- ✅ Responsive design
- ✅ Accessibility

**Key Test Methods:**
- `test_individual_table_export()`
- `test_complete_export()`
- `test_individual_table_import()`
- `test_bulk_import()`
- `test_file_validation_for_import()`

### 6. Reports Tests (`ReportsTest.php`)

Tests reports and analytics functionality:

- ✅ Reports index display
- ✅ Summary report
- ✅ Articles by year report
- ✅ Articles by forest report
- ✅ Articles by essence report
- ✅ Articles by exploitant report
- ✅ Articles by validation status report
- ✅ Unsold articles report
- ✅ Sold articles report
- ✅ Export functionality
- ✅ Filtering functionality
- ✅ Date range filtering
- ✅ Search functionality
- ✅ Responsive design
- ✅ Accessibility
- ✅ Navigation

**Key Test Methods:**
- `test_summary_report()`
- `test_articles_by_year_report()`
- `test_articles_by_forest_report()`
- `test_articles_by_essence_report()`

### 7. User Management Tests (`UserManagementTest.php`)

Tests user management and administration:

- ✅ User management index display
- ✅ Create new user
- ✅ View user details
- ✅ Edit user
- ✅ Delete user
- ✅ Toggle user status
- ✅ Search functionality
- ✅ Filtering by status
- ✅ Export functionality
- ✅ Form validation
- ✅ Pagination
- ✅ Sorting

**Key Test Methods:**
- `test_user_can_create_new_user()`
- `test_user_can_view_user_details()`
- `test_user_can_edit_user()`
- `test_user_can_delete_user()`

## 🔧 Configuration

### Environment Variables

Add these to your `.env` file for testing:

```env
# Dusk Configuration
DUSK_DRIVER_URL=http://localhost:9515
DUSK_HEADLESS_DISABLED=false
DUSK_START_MAXIMIZED=false

# Test User Credentials
TEST_USER_EMAIL=test@example.com
TEST_USER_PASSWORD=password

# Admin User Credentials
ADMIN_USER_EMAIL=admin@example.com
ADMIN_USER_PASSWORD=password

# Test Database
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### Test Database

The tests use an in-memory SQLite database by default. This ensures:
- Fast test execution
- Isolated test data
- No database cleanup required

### ChromeDriver Configuration

ChromeDriver is automatically managed by Dusk with optimized settings:
- Headless mode by default
- Disabled images and JavaScript for faster execution
- Optimized Chrome flags for testing
- Automatic port management

## 📊 Test Data

### Factories

The tests use Laravel factories to create test data:

```php
// Create test users
$user = User::factory()->create();

// Create test articles
$article = Article::factory()->create();

// Create test data with relationships
$article = Article::factory()->create([
    'exploitant_id' => $exploitant->id,
    'essence_id' => $essence->id,
    'foret_id' => $foret->id,
]);
```

### Fixtures

Test fixtures are located in `tests/Browser/fixtures/`:
- Sample Excel files for import testing
- Invalid files for validation testing
- Large files for performance testing
- Corrupted files for error handling testing

## 🎯 Best Practices

### 1. Test Organization

- Each test file focuses on a specific feature
- Tests are organized by functionality
- Common setup is handled in `setUp()` methods
- Test data is created using factories

### 2. Test Naming

- Test methods use descriptive names
- Names indicate what is being tested
- Names follow the pattern: `test_[action]_[expected_result]`

### 3. Assertions

- Use specific assertions for better error messages
- Assert both positive and negative cases
- Test edge cases and error conditions
- Verify both UI and data changes

### 4. Performance

- Use headless mode for CI/CD
- Disable images and JavaScript when possible
- Use in-memory database for speed
- Clean up test data automatically

### 5. Reliability

- Wait for elements to be visible
- Use explicit waits instead of sleep
- Handle dynamic content properly
- Test on different screen sizes

## 🐛 Troubleshooting

### Common Issues

1. **ChromeDriver not found**
   ```bash
   php artisan dusk:install
   ```

2. **Tests failing in CI/CD**
   - Ensure headless mode is enabled
   - Check ChromeDriver version compatibility
   - Verify environment variables

3. **Slow test execution**
   - Use headless mode
   - Disable images and JavaScript
   - Use in-memory database
   - Optimize Chrome flags

4. **Flaky tests**
   - Add explicit waits
   - Use more specific selectors
   - Handle dynamic content properly
   - Test on stable data

### Debug Mode

Enable debug mode for troubleshooting:

```bash
# Run with browser visible
DUSK_HEADLESS_DISABLED=true php artisan dusk

# Run with browser maximized
DUSK_START_MAXIMIZED=true php artisan dusk

# Run specific test with debug
php artisan dusk --filter=test_user_can_login --debug
```

### Screenshots and Logs

When tests fail, Dusk automatically captures:
- Screenshots in `tests/Browser/screenshots/`
- Source code in `tests/Browser/source/`
- Console logs in `tests/Browser/console/`

## 📈 Continuous Integration

### GitHub Actions

Example workflow for running Dusk tests:

```yaml
name: Dusk Tests

on: [push, pull_request]

jobs:
  dusk:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: 8.2
        
    - name: Install dependencies
      run: composer install --no-dev --optimize-autoloader
      
    - name: Setup environment
      run: cp .env.example .env
      
    - name: Generate key
      run: php artisan key:generate
      
    - name: Run migrations
      run: php artisan migrate
      
    - name: Run Dusk tests
      run: php artisan dusk
```

### Docker

Example Dockerfile for Dusk testing:

```dockerfile
FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    wget \
    gnupg

# Install Chrome
RUN wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google-chrome.list \
    && apt-get update \
    && apt-get install -y google-chrome-stable

# Install ChromeDriver
RUN wget -O /tmp/chromedriver.zip http://chromedriver.storage.googleapis.com/`curl -sS chromedriver.storage.googleapis.com/LATEST_RELEASE`/chromedriver_linux64.zip \
    && unzip /tmp/chromedriver.zip chromedriver -d /usr/local/bin/ \
    && rm /tmp/chromedriver.zip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install

# Run tests
CMD php artisan dusk
```

## 📚 Additional Resources

### Documentation

- [Laravel Dusk Documentation](https://laravel.com/docs/dusk)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [ChromeDriver Documentation](https://chromedriver.chromium.org/)

### Tools

- [Laravel Dusk](https://github.com/laravel/dusk)
- [ChromeDriver](https://chromedriver.chromium.org/)
- [PHPUnit](https://phpunit.de/)

### Examples

- [Laravel Dusk Examples](https://github.com/laravel/dusk/tree/master/tests)
- [Browser Testing Best Practices](https://laravel.com/docs/dusk#browser-testing)

## 🎉 Conclusion

This comprehensive Dusk testing suite provides:

- **Complete Coverage**: All major application features are tested
- **Reliable Testing**: Robust test structure with proper error handling
- **Easy Maintenance**: Well-organized code with clear documentation
- **CI/CD Ready**: Optimized for continuous integration environments
- **Performance Optimized**: Fast execution with minimal resource usage

The tests ensure that your Laravel application works correctly across all browsers and provides a solid foundation for maintaining code quality and preventing regressions.
