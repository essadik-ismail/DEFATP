<?php

namespace App\Exports;

use App\Models\Foret;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ForetsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Foret::where('is_deleted', false)->orderBy('foret')->get();
    }

    public function headings(): array
    {
        return [
            'Forêt',
            'Latitude',
            'Longitude',
            'Province',
        ];
    }

    public function map($foret): array
    {
        return [
            $foret->foret,
            $foret->lat,
            $foret->log,
            $foret->province
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
