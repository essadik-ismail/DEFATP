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
            'Numéro d\'Article*',
            'Date d\'Adjudication*',
            'Numéro Juridique',
            'Exploitant',
            'Lot',
            'Nature Juridique',
            'Parcelle',
            'Latitude',
            'Longitude',
            'Superficie',
            'Prix de retrait',
            'Prix de vente',
            'Invendu',
            'BO (m³)',
            'BI (m³)',
            'BF (st)',
            'Tanin (t)',
            'Fleur Acacia (t)',
            'Caroube (t)',
            'Romarin (t)',
            'PS (t)',
            'Liège (st)',
            'Charbon Bois (ox)',
            'Fourniture mise en charge',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // Année
            'B' => 18,  // Numéro d'Article
            'C' => 18,  // Date d'Adjudication
            'D' => 20,  // Numéro Juridique
            'E' => 25,  // Exploitant
            'F' => 8,   // Lot
            'G' => 20,  // Nature Juridique
            'H' => 12,  // Parcelle
            'I' => 12,  // Latitude
            'J' => 12,  // Longitude
            'K' => 12,  // Superficie
            'L' => 15,  // Prix de retrait
            'M' => 15,  // Prix de vente
            'N' => 12,  // Invendu
            'O' => 10,  // BO (m³)
            'P' => 10,  // BI (m³)
            'Q' => 10,  // BF (st)
            'R' => 10,  // Tanin (t)
            'S' => 15,  // Fleur Acacia (t)
            'T' => 12,  // Caroube (t)
            'U' => 12,  // Romarin (t)
            'V' => 10,  // PS (t)
            'W' => 12,  // Liège (st)
            'X' => 15,  // Charbon Bois (ox)
            'Y' => 20,  // Fourniture mise en charge
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:Y1')->applyFromArray([
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

        // Template intentionally left without sample data rows

        // Add instructions sheet
        $instructionsSheet = $sheet->getParent()->createSheet();
        $instructionsSheet->setTitle('Instructions');
        
        $instructions = [
            ['Champ', 'Description', 'Format', 'Obligatoire'],
            ['Année', 'Année de l\'article', 'Nombre entier (ex: 2025)', 'Oui'],
            ['Numéro d\'Article', 'Numéro unique de l\'article', 'Texte (ex: ART001)', 'Oui'],
            ['Date d\'Adjudication', 'Date de l\'adjudication', 'Date (YYYY-MM-DD)', 'Oui'],
            ['Numéro Juridique', 'Numéro juridique', 'Texte', 'Non'],
            ['Exploitant', 'Nom complet de l\'exploitant', 'Texte (doit exister dans la base)', 'Non'],
            ['Lot', 'Numéro du lot', 'Texte ou nombre', 'Non'],
            ['Nature Juridique', 'Nature juridique', 'Texte', 'Non'],
            ['Parcelle', 'Numéro de parcelle', 'Texte', 'Non'],
            ['Latitude', 'Coordonnée latitude', 'Nombre décimal', 'Non'],
            ['Longitude', 'Coordonnée longitude', 'Nombre décimal', 'Non'],
            ['Superficie', 'Superficie en hectares', 'Nombre décimal', 'Non'],
            ['Prix de retrait', 'Prix de retrait en DH', 'Nombre décimal', 'Non'],
            ['Prix de vente', 'Prix de vente en DH', 'Nombre décimal', 'Non'],
            ['Invendu', 'Article invendu', 'Oui/Non, True/False, 1/0', 'Non'],
            ['BO (m³)', 'Bois d\'œuvre en m³', 'Nombre décimal', 'Non'],
            ['BI (m³)', 'Bois d\'industrie en m³', 'Nombre décimal', 'Non'],
            ['BF (st)', 'Bois de feu en stères', 'Nombre décimal', 'Non'],
            ['Tanin (t)', 'Tanin en tonnes', 'Nombre décimal', 'Non'],
            ['Fleur Acacia (t)', 'Fleur d\'acacia en tonnes', 'Nombre décimal', 'Non'],
            ['Caroube (t)', 'Caroube en tonnes', 'Nombre décimal', 'Non'],
            ['Romarin (t)', 'Romarin en tonnes', 'Nombre décimal', 'Non'],
            ['PS (t)', 'PS en tonnes', 'Nombre décimal', 'Non'],
            ['Liège (st)', 'Liège en stères', 'Nombre décimal', 'Non'],
            ['Charbon Bois (ox)', 'Charbon de bois en ox', 'Nombre décimal', 'Non'],
            ['Fourniture mise en charge', 'Fourniture mise en charge', 'Nombre décimal', 'Non'],
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
