<?php

namespace App\Exports;

use App\Models\SituationAdministrative;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SituationAdministrativesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return SituationAdministrative::where('is_deleted', false)->orderBy('commune')->get();
    }

    public function headings(): array
    {
        return [
            'Commune',
            'Province',
        ];
    }

    public function map($situationAdministrative): array
    {
        return [
            $situationAdministrative->commune,
            $situationAdministrative->province
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
