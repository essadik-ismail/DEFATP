# Test Fixtures

This directory contains test fixture files for Laravel Dusk tests.

## Files

- `articles.xlsx` - Sample Excel file for testing article imports
- `essences.xlsx` - Sample Excel file for testing essence imports
- `forets.xlsx` - Sample Excel file for testing forest imports
- `exploitants.xlsx` - Sample Excel file for testing exploitant imports
- `localisations.xlsx` - Sample Excel file for testing localisation imports
- `nature_de_coupes.xlsx` - Sample Excel file for testing nature de coupe imports
- `situation_administratives.xlsx` - Sample Excel file for testing situation administrative imports
- `all_data.zip` - ZIP file containing all sample data for bulk import testing
- `invalid_file.txt` - Invalid file for testing file validation
- `large_file.xlsx` - Large file for testing file size validation
- `invalid_data.xlsx` - File with invalid data for testing validation errors
- `corrupted_file.xlsx` - Corrupted file for testing error handling
- `large_articles.xlsx` - Large articles file for testing import progress

## Usage

These files are used in Dusk tests to simulate real-world import/export scenarios and test error handling.

## Creating Test Files

To create new test files, use the Laravel Excel package to generate sample data:

```php
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArticlesExport;

// Create sample data
$articles = Article::factory()->count(10)->create();

// Export to Excel
Excel::store(new ArticlesExport($articles), 'tests/Browser/fixtures/articles.xlsx');
```

## File Formats

- **Excel files**: .xlsx format for testing Excel import/export functionality
- **CSV files**: .csv format for testing CSV import/export functionality
- **ZIP files**: .zip format for testing bulk import functionality
- **Invalid files**: Various formats for testing file validation
