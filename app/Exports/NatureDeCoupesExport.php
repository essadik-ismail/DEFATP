<?php

namespace App\Exports;

use App\Models\NatureDeCoupe;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class NatureDeCoupesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties, WithTitle, WithEvents
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = NatureDeCoupe::query();

        // Apply filters if provided
        if (!empty($this->filters['search'])) {
            $query->where('nature_de_coupe', 'like', '%' . $this->filters['search'] . '%');
        }

        if (!empty($this->filters['status'])) {
            switch ($this->filters['status']) {
                case 'active':
                    $query->where('is_deleted', false);
                    break;
                case 'deleted':
                    $query->where('is_deleted', true);
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        if (!empty($this->filters['date_from'])) {
            $query->where('created_at', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->where('created_at', '<=', $this->filters['date_to'] . ' 23:59:59');
        }

        // Apply sorting
        $sortField = $this->filters['sort'] ?? 'nature_de_coupe';
        $sortDirection = $this->filters['direction'] ?? 'asc';
        
        $allowedSortFields = ['id', 'nature_de_coupe', 'created_at', 'updated_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nature de Coupe',
            'Statut',
            'Date de Création',
            'Date de Modification',
            'Créé par',
            'Modifié par',
        ];
    }

    public function map($natureDeCoupe): array
    {
        return [
            $natureDeCoupe->id,
            $natureDeCoupe->nature_de_coupe,
            $natureDeCoupe->is_deleted ? 'Supprimée' : 'Active',
            $natureDeCoupe->created_at ? $natureDeCoupe->created_at->format('d/m/Y H:i:s') : '-',
            $natureDeCoupe->updated_at ? $natureDeCoupe->updated_at->format('d/m/Y H:i:s') : '-',
            $natureDeCoupe->created_by ?? 'Système',
            $natureDeCoupe->updated_by ?? 'Système',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F2937'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            
            // Data rows styling
            'A2:' . $lastColumn . $lastRow => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                ],
            ],
            
            // ID column center alignment
            'A2:A' . $lastRow => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            
            // Date columns center alignment
            'D2:E' . $lastRow => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            
            // Status column center alignment
            'C2:C' . $lastRow => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // ID
            'B' => 25,  // Nature de Coupe
            'C' => 15,  // Statut
            'D' => 20,  // Date de Création
            'E' => 20,  // Date de Modification
            'F' => 20,  // Créé par
            'G' => 20,  // Modifié par
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Système de Gestion Forestière',
            'lastModifiedBy' => 'Système de Gestion Forestière',
            'title'          => 'Export des Natures de Coupe',
            'description'    => 'Export des natures de coupe avec filtres appliqués',
            'subject'        => 'Natures de Coupe',
            'keywords'       => 'nature, coupe, export, excel',
            'category'       => 'Rapports',
            'manager'        => 'Administrateur',
            'company'        => 'Ministère des Forêts',
        ];
    }

    public function title(): string
    {
        return 'Natures de Coupe';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Add summary information
                $summaryRow = $lastRow + 2;
                $sheet->setCellValue('A' . $summaryRow, 'Résumé de l\'export:');
                $sheet->setCellValue('A' . ($summaryRow + 1), 'Total des enregistrements: ' . ($lastRow - 1));
                $sheet->setCellValue('A' . ($summaryRow + 2), 'Date d\'export: ' . now()->format('d/m/Y H:i:s'));
                
                // Style summary section
                $sheet->getStyle('A' . $summaryRow . ':A' . ($summaryRow + 2))->getFont()->setBold(true);
                $sheet->getStyle('A' . $summaryRow . ':A' . ($summaryRow + 2))->getFont()->setSize(12);

                // Add filters information if any
                if (!empty($this->filters)) {
                    $filterRow = $summaryRow + 4;
                    $sheet->setCellValue('A' . $filterRow, 'Filtres appliqués:');
                    
                    $row = $filterRow + 1;
                    foreach ($this->filters as $key => $value) {
                        if (!empty($value)) {
                            $sheet->setCellValue('A' . $row, ucfirst($key) . ': ' . $value);
                            $row++;
                        }
                    }
                    
                    // Style filters section
                    $sheet->getStyle('A' . $filterRow . ':A' . ($row - 1))->getFont()->setItalic(true);
                    $sheet->getStyle('A' . $filterRow . ':A' . ($row - 1))->getFont()->setSize(10);
                }

                // Freeze the header row
                $sheet->freezePane('A2');
            },
        ];
    }
}
