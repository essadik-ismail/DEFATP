<?php

namespace App\Exports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArticlesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(private array $filters = [])
    {
    }

    public function collection()
    {
        $query = Article::with(['cession', 'forets', 'essences', 'provinces', 'communes'])
            ->orderBy('numero');

        if (! empty($this->filters['foret_id'])) {
            $query->whereHas('forets', function ($builder) {
                $builder->where('forets.id', $this->filters['foret_id']);
            });
        }

        if (! empty($this->filters['essence_id'])) {
            $query->whereHas('essences', function ($builder) {
                $builder->where('essences.id', $this->filters['essence_id']);
            });
        }

        if (array_key_exists('invandu', $this->filters) && $this->filters['invandu'] !== null && $this->filters['invandu'] !== '') {
            $query->where('invandu', (bool) $this->filters['invandu']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Numero',
            'Cession',
            'Lot',
            'Parcelle',
            'Superficie',
            'Nature juridique',
            'Forets',
            'Essences',
            'Provinces',
            'Communes',
            'Etape',
            'Cree le',
        ];
    }

    public function map($article): array
    {
        return [
            $article->numero,
            $article->cession?->Code_catalographique,
            $article->lot,
            $article->parcelle,
            $article->superficie,
            $article->nature_juridique,
            $article->forets->pluck('foret')->implode(', '),
            $article->essences->pluck('essence')->implode(', '),
            $article->provinces->pluck('nom')->implode(', '),
            $article->communes->pluck('nom')->implode(', '),
            $article->current_step,
            optional($article->created_at)?->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
