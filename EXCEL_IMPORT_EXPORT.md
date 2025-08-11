# Excel Import/Export Functionality

This Laravel application now includes comprehensive Excel import and export functionality for all database tables using the Maatwebsite Excel package.

## Features

### Export Features
- **Individual Table Export**: Export each table separately to Excel format
- **Complete Export**: Export all tables in a single ZIP file
- **Filtered Export**: Export articles with filters (year, forest, essence, etc.)
- **Formatted Output**: Excel files with proper headers, styling, and data formatting

### Import Features
- **Individual Table Import**: Import data into specific tables
- **Bulk Import**: Import multiple files at once with automatic table detection
- **Data Validation**: Comprehensive validation rules for all imported data
- **Error Handling**: Graceful error handling with detailed feedback
- **Batch Processing**: Efficient processing of large datasets

## Supported Tables

1. **Articles** - Main data table with complex relationships
2. **Essences** - Tree species data
3. **Forets** - Forest data with coordinates
4. **NatureDeCoupes** - Cutting nature types
5. **SituationAdministratives** - Administrative situations
6. **Exploitants** - Operator/contractor data
7. **SessionAdjudications** - Bidding session data
8. **Localisations** - Location codes

## File Formats Supported

- **Excel**: .xlsx, .xls
- **CSV**: .csv
- **Maximum file size**: 10MB per file

## Usage

### Accessing the Interface

Navigate to **Import/Export Excel** in the main navigation menu to access the comprehensive interface.

### Exporting Data

#### Individual Export
1. Go to the Excel Import/Export page
2. Click on the specific table export link
3. Download the Excel file

#### Complete Export
1. Click "Exporter tout" button
2. Download the ZIP file containing all tables

### Importing Data

#### Individual Import
1. Select a file for the specific table
2. Click the import button
3. Review success/error messages

#### Bulk Import
1. Select multiple files
2. Ensure files are named with table names (e.g., `articles.xlsx`, `essences.xlsx`)
3. Click "Importer tous les fichiers"
4. Review the results summary

## File Format Requirements

### Articles Import
Required columns:
- `annee` (Year) - Required, integer 1900-2100
- `numero` (Number) - Required, string
- `date` (Date) - Required, valid date
- `invendu` (Unsold) - Optional, boolean (Oui/Non, Yes/No, True/False, 1/0)
- `prix_de_retrait` (Withdrawal price) - Optional, numeric
- `lot` (Lot) - Optional, string
- `parcelle` (Parcel) - Optional, string
- `superficie` (Area) - Optional, numeric
- `prix_vente` (Sale price) - Optional, numeric
- `fourniture_mise_charge` (Supply charge) - Optional, numeric
- `date_dr` (DR date) - Optional, valid date
- `observations` (Observations) - Optional, text
- `bo_m3` (BO m³) - Optional, numeric
- `bi_m3` (BI m³) - Optional, numeric
- `bf_st` (BF st) - Optional, numeric
- `tanin_t` (Tannin t) - Optional, numeric
- `fleur_acacia_t` (Acacia flower t) - Optional, numeric
- `caroube_t` (Carob t) - Optional, numeric
- `romarin_t` (Rosemary t) - Optional, numeric
- `ps_t` (PS t) - Optional, numeric
- `liege_st` (Cork st) - Optional, numeric
- `charbon_bois_ox` (Wood charcoal ox) - Optional, numeric

Related data columns (will be matched by name/code):
- `commune` (Commune) - Matches SituationAdministrative
- `foret` (Forest) - Matches Foret
- `essence` (Essence) - Matches Essence
- `nature_de_coupe` (Cutting nature) - Matches NatureDeCoupe
- `localisation` (Location) - Matches Localisation

### Other Tables Import

#### Essences
- `essence` - Required, string, max 255 characters

#### Forets
- `foret` - Required, string, max 255 characters
- `lat` - Optional, numeric (latitude)
- `log` - Optional, numeric (longitude)
- `province` - Optional, string, max 255 characters

#### NatureDeCoupes
- `nature_de_coupe` - Required, string, max 255 characters

#### SituationAdministratives
- `commune` - Required, string, max 255 characters
- `province` - Optional, string, max 255 characters

#### Exploitants
- `nom_complet` - Required, string, max 255 characters
- `cin` - Optional, string, max 255 characters
- `adresse` - Optional, string, max 500 characters
- `telephone` - Optional, string, max 20 characters
- `email` - Optional, valid email, max 255 characters

#### Localisations
- `code` - Required, string, max 255 characters

## Error Handling

The system provides comprehensive error handling:

- **Validation Errors**: Detailed validation messages for each field
- **File Format Errors**: Clear messages for unsupported file types
- **Import Errors**: Specific error messages for failed imports
- **Success Messages**: Confirmation messages for successful operations

## Performance Features

- **Batch Processing**: Large datasets are processed in batches of 100 records
- **Chunk Reading**: Excel files are read in chunks to manage memory usage
- **Progress Feedback**: Real-time feedback during import operations

## Security Features

- **File Validation**: Strict file type and size validation
- **CSRF Protection**: All import forms include CSRF tokens
- **Authentication Required**: All import/export operations require authentication

## Technical Implementation

### Export Classes
Located in `app/Exports/`:
- `ArticlesExport.php`
- `EssencesExport.php`
- `ForetsExport.php`
- `NatureDeCoupesExport.php`
- `SituationAdministrativesExport.php`
- `ExploitantsExport.php`
- `SessionAdjudicationsExport.php`
- `LocalisationsExport.php`

### Import Classes
Located in `app/Imports/`:
- `ArticlesImport.php`
- `EssencesImport.php`
- `ForetsImport.php`
- `NatureDeCoupesImport.php`
- `SituationAdministrativesImport.php`
- `ExploitantsImport.php`
- `LocalisationsImport.php`

### Controllers
- `ExcelController.php` - Main controller for import/export operations
- Updated `ArticleController.php` - Added Excel export/import methods
- Updated `SettingsController.php` - Added Excel export/import methods for settings tables

### Routes
All routes are prefixed with `/excel` and include:
- Individual export routes for each table
- Individual import routes for each table
- Bulk export route for all tables
- Bulk import route for multiple files

## Troubleshooting

### Common Issues

1. **File too large**: Ensure files are under 10MB
2. **Invalid file format**: Use only .xlsx, .xls, or .csv files
3. **Missing required columns**: Ensure all required columns are present
4. **Invalid data types**: Check that data matches the expected format
5. **Related data not found**: Ensure referenced data exists in the database

### Best Practices

1. **Backup data** before large imports
2. **Test imports** with small files first
3. **Validate data** in Excel before importing
4. **Use consistent naming** for related data
5. **Check file encoding** (UTF-8 recommended)

## Support

For issues or questions regarding the Excel import/export functionality, please check:
1. The error messages provided by the system
2. This documentation
3. The Laravel logs for detailed error information
