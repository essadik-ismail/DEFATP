<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ArticlesTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithColumnWidths
{
    public function array(): array
    {
        // Return empty array for template - just headers
        return [];
    }

    public function headings(): array
    {
        return [
            'Année*',
            'Numéro*',
            'Date d\'Adjudication*',
            'Numéro d\'Adjudication',
            'Lot',
            'Type*',
            'Exploitant ID',
            'Nature Juridique',
            'Parcelle',
            'Latitude',
            'Longitude',
            'Superficie',
            'Prix de retrait',
            'Prix de vente',
            'BO (m³)',
            'BI (m³)',
            'BF (st)',
            'Tanin (t)',
            'Fleur Acacia (t)',
            'Caroube (t)',
            'Romarin (t)',
            'Liège (st)',
            'Charbon Bois (ox)',
            'Observations',
            'Charges du lot',
            'Date DR',
            'Invendu',
            'DC',
            'Localisations (séparées par ;)',
            'Situations Administratives (séparées par ;)',
            'Forêts (séparées par ;)',
            'Essences (séparées par ;)',
            'Natures de Coupe (séparées par ;)'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // Année
            'B' => 15,  // Numéro
            'C' => 18,  // Date d'Adjudication
            'D' => 20,  // Numéro d'Adjudication
            'E' => 8,   // Lot
            'F' => 15,  // Type
            'G' => 12,  // Exploitant ID
            'H' => 20,  // Nature Juridique
            'I' => 12,  // Parcelle
            'J' => 12,  // Latitude
            'K' => 12,  // Longitude
            'L' => 12,  // Superficie
            'M' => 15,  // Prix de retrait
            'N' => 15,  // Prix de vente
            'O' => 10,  // BO (m³)
            'P' => 10,  // BI (m³)
            'Q' => 10,  // BF (st)
            'R' => 10,  // Tanin (t)
            'S' => 15,  // Fleur Acacia (t)
            'T' => 12,  // Caroube (t)
            'U' => 12,  // Romarin (t)
            'V' => 12,  // Liège (st)
            'W' => 15,  // Charbon Bois (ox)
            'X' => 20,  // Observations
            'Y' => 20,  // Charges du lot
            'Z' => 12,  // Date DR
            'AA' => 10, // Invendu
            'AB' => 8,  // DC
            'AC' => 30, // Localisations
            'AD' => 30, // Situations Administratives
            'AE' => 30, // Forêts
            'AF' => 30, // Essences
            'AG' => 30, // Natures de Coupe
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:AG1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E86AB']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        // Add realistic example data row
        $sheet->setCellValue('A2', '2024');
        $sheet->setCellValue('B2', 'ART-2024-001');
        $sheet->setCellValue('C2', '2024-03-15');
        $sheet->setCellValue('D2', 'ADJ-2024-001');
        $sheet->setCellValue('E2', '1');
        $sheet->setCellValue('F2', 'appel_doffre');
        $sheet->setCellValue('G2', '2');
        $sheet->setCellValue('H2', 'Public');
        $sheet->setCellValue('I2', 'P001');
        $sheet->setCellValue('J2', '33.5731');
        $sheet->setCellValue('K2', '-7.5898');
        $sheet->setCellValue('L2', '125.75');
        $sheet->setCellValue('M2', '45000.00');
        $sheet->setCellValue('N2', '68000.00');
        $sheet->setCellValue('O2', '180.50');
        $sheet->setCellValue('P2', '220.25');
        $sheet->setCellValue('Q2', '65.75');
        $sheet->setCellValue('R2', '12.50');
        $sheet->setCellValue('S2', '8.25');
        $sheet->setCellValue('T2', '15.00');
        $sheet->setCellValue('U2', '5.50');
        $sheet->setCellValue('V2', '30.00');
        $sheet->setCellValue('W2', '18.75');
        $sheet->setCellValue('X2', 'Coupe de régénération - Zone de protection');
        $sheet->setCellValue('Y2', 'Transport et manutention');
        $sheet->setCellValue('Z2', '2024-03-20');
        $sheet->setCellValue('AA2', 'false');
        $sheet->setCellValue('AB2', 'false');
        $sheet->setCellValue('AC2', '01-110;01-200CG1');
        $sheet->setCellValue('AD2', 'Agadir - Souss-Massa;Casablanca - Casablanca-Settat');
        $sheet->setCellValue('AE2', 'Ain Tafetacht;Arbayâ');
        $sheet->setCellValue('AF2', 'Acacia;Chène vert');
        $sheet->setCellValue('AG2', 'CBE;CCN');

        // Style the example row
        $sheet->getStyle('A2:AG2')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8F4FD']
            ],
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '2E86AB']
            ]
        ]);
        
        // Add a note about the example data
        $sheet->setCellValue('A3', 'Note: La ligne 2 contient des données d\'exemple. Vous pouvez les modifier ou les supprimer.');
        $sheet->mergeCells('A3:AG3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '6C757D'],
                'size' => 10
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Add instructions sheet
        $instructionsSheet = $sheet->getParent()->createSheet();
        $instructionsSheet->setTitle('Instructions');
        
        $instructions = [
            ['Champ', 'Description', 'Format', 'Obligatoire'],
            ['', '', '', ''],
            ['IMPORTANT:', 'La ligne 2 contient des données d\'exemple', 'Vous pouvez les modifier ou les supprimer', ''],
            ['', '', '', ''],
            ['Année', 'Année de l\'article', 'Nombre entier (ex: 2024)', 'Oui'],
            ['Numéro', 'Numéro unique de l\'article', 'Texte (ex: ART-001)', 'Oui'],
            ['Date d\'Adjudication', 'Date de l\'adjudication', 'Date (YYYY-MM-DD)', 'Oui'],
            ['Numéro d\'Adjudication', 'Numéro d\'adjudication', 'Texte', 'Non'],
            ['Lot', 'Numéro du lot', 'Texte ou nombre', 'Non'],
            ['Type', 'Type d\'article', 'appel_doffre, adjudication, marche_negocié', 'Oui'],
            ['Exploitant ID', 'ID de l\'exploitant', 'Nombre entier', 'Non'],
            ['Nature Juridique', 'Nature juridique', 'Texte', 'Non'],
            ['Parcelle', 'Numéro de parcelle', 'Texte', 'Non'],
            ['Latitude', 'Coordonnée latitude', 'Nombre décimal', 'Non'],
            ['Longitude', 'Coordonnée longitude', 'Nombre décimal', 'Non'],
            ['Superficie', 'Superficie en hectares', 'Nombre décimal', 'Non'],
            ['Prix de retrait', 'Prix de retrait en DH', 'Nombre décimal', 'Non'],
            ['Prix de vente', 'Prix de vente en DH', 'Nombre décimal', 'Non'],
            ['BO (m³)', 'Bois d\'œuvre en m³', 'Nombre décimal', 'Non'],
            ['BI (m³)', 'Bois d\'industrie en m³', 'Nombre décimal', 'Non'],
            ['BF (st)', 'Bois de feu en stères', 'Nombre décimal', 'Non'],
            ['Tanin (t)', 'Tanin en tonnes', 'Nombre décimal', 'Non'],
            ['Fleur Acacia (t)', 'Fleur d\'acacia en tonnes', 'Nombre décimal', 'Non'],
            ['Caroube (t)', 'Caroube en tonnes', 'Nombre décimal', 'Non'],
            ['Romarin (t)', 'Romarin en tonnes', 'Nombre décimal', 'Non'],
            ['Liège (st)', 'Liège en stères', 'Nombre décimal', 'Non'],
            ['Charbon Bois (ox)', 'Charbon de bois en ox', 'Nombre décimal', 'Non'],
            ['Observations', 'Observations générales', 'Texte libre', 'Non'],
            ['Charges du lot', 'Charges spécifiques au lot', 'Texte libre', 'Non'],
            ['Date DR', 'Date de DR', 'Date (YYYY-MM-DD)', 'Non'],
            ['Invendu', 'Article invendu', 'true/false', 'Non'],
            ['DC', 'DC', 'true/false', 'Non'],
            ['Localisations', 'Codes de localisation', 'Séparés par ; (ex: 01-110;01-200CG1)', 'Non'],
            ['Situations Administratives', 'Situations administratives', 'Séparées par ; (ex: Agadir - Souss-Massa;Casablanca - Casablanca-Settat)', 'Non'],
            ['Forêts', 'Noms des forêts', 'Séparés par ; (ex: Ain Tafetacht;Arbayâ)', 'Non'],
            ['Essences', 'Types d\'essences', 'Séparés par ; (ex: Acacia;Chène vert)', 'Non'],
            ['Natures de Coupe', 'Types de coupe', 'Séparés par ; (ex: CBE;CCN)', 'Non']
        ];

        $instructionsSheet->fromArray($instructions, null, 'A1');
        
        // Style instructions sheet
        $instructionsSheet->getStyle('A1:D1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E86AB']
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ]
        ]);

        $instructionsSheet->getColumnDimension('A')->setWidth(20);
        $instructionsSheet->getColumnDimension('B')->setWidth(40);
        $instructionsSheet->getColumnDimension('C')->setWidth(25);
        $instructionsSheet->getColumnDimension('D')->setWidth(15);

        return [];
    }
}
