<?php

namespace App\Exports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArticlesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Article::with([
            'exploitant',
            'products',
            'locations'
        ]);

        // Apply filters
        if (!empty($this->filters['annee'])) {
            $query->where('annee', $this->filters['annee']);
        }

        if (!empty($this->filters['foret_id'])) {
            $query->where('foret_id', $this->filters['foret_id']);
        }

        if (!empty($this->filters['essence_id'])) {
            $query->where('essence_id', $this->filters['essence_id']);
        }

        if (isset($this->filters['invendu'])) {
            $query->where('invendu', $this->filters['invendu']);
        }

        return $query->orderBy('date_adjudication', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Année',
            'Numéro',
            'Date d\'Adjudication',
            'Numéro d\'Adjudication',
            'Lot',
            'Type',
            'Exploitant',
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
            'Produits',
            'Emplacements'
        ];
    }

    public function map($article): array
    {
        return [
            $article->id,
            $article->annee,
            $article->numero,
            $article->date_adjudication ? $article->date_adjudication->format('d/m/Y') : 'N/A',
            $article->numero_adjudication ?? 'N/A',
            $article->lot ?? 'N/A',
            $article->type == 'appel_doffre' ? 'Appel d\'Offre' : 'Adjudication',
            $article->exploitant?->nom_complet ?? 'N/A',
            $article->nature_juridique ?? 'N/A',
            $article->parcelle ?? 'N/A',
            $article->lat ?? 'N/A',
            $article->log ?? 'N/A',
            $article->superficie ?? 'N/A',
            $article->prix_de_retrait ?? 'N/A',
            $article->prix_vente ?? 'N/A',
            $article->bo_m3 ?? 'N/A',
            $article->bi_m3 ?? 'N/A',
            $article->bf_st ?? 'N/A',
            $article->tanin_t ?? 'N/A',
            $article->fleur_acacia_t ?? 'N/A',
            $article->caroube_t ?? 'N/A',
            $article->romarin_t ?? 'N/A',
            $article->liége_st ?? 'N/A',
            $article->charbon_bois_ox ?? 'N/A',
            $article->products->map(function($product) {
                return $product->name . ' (x' . $product->quantity . ')';
            })->join(', '),
            $article->locations->map(function($location) {
                $parts = [];
                if ($location->mat) $parts[] = 'Mat: ' . $location->mat;
                if ($location->x && $location->y) $parts[] = 'Pos: (' . $location->x . ',' . $location->y . ')';
                return implode(' | ', $parts);
            })->join('; ')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
