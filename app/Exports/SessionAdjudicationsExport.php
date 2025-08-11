<?php

namespace App\Exports;

use App\Models\SessionAdjudication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SessionAdjudicationsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return SessionAdjudication::with('exploitant')
            ->where('is_deleted', false)
            ->orderBy('date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Type',
            'Date',
            'Numéro',
            'Nature juridique',
            'Adjudicataire',
            'DC',
            'RC',
            'Date de résiliation',
            'Date de déchéance',
            'Exploitant',
            'Validé',
        ];
    }

    public function map($sessionAdjudication): array
    {
        return [
            $sessionAdjudication->type,
            $sessionAdjudication->date,
            $sessionAdjudication->numero,
            $sessionAdjudication->nature_juridique,
            $sessionAdjudication->adjudicatire,
            $sessionAdjudication->dc ? 'Oui' : 'Non',
            $sessionAdjudication->rc ? 'Oui' : 'Non',
            $sessionAdjudication->date_de_resiliation,
            $sessionAdjudication->date_de_decheance,
            $sessionAdjudication->exploitant?->nom_complet,
            $sessionAdjudication->is_validated ? 'Oui' : 'Non'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
