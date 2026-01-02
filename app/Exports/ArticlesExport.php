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
            'locations',
            'zdtf',
            'modeExploitations'
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

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Année',
            'Numéro',
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
            'Taxe refection chemins',
            'Service rendu ANEF',
            'Bois chauffage volume',
            'Bois chauffage destination',
            'Date payement service ANEF',
            'Date livraison mise en charge BF',
            'ZDTF',
            'Mode d\'Exploitation',
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
            $article->taxe_refection_chemins ?? 'N/A',
            $article->service_rendu_anef ?? 'N/A',
            $article->bois_chauffage_volume ?? 'N/A',
            $article->bois_chauffage_destination ?? 'N/A',
            $article->date_payement_service_anef ? $article->date_payement_service_anef->format('d/m/Y') : 'N/A',
            $article->date_livaison_mise_en_charge_bf ? $article->date_livaison_mise_en_charge_bf->format('d/m/Y') : 'N/A',
            $article->zdtf?->name ?? 'N/A',
            $article->modeExploitations->pluck('name')->join(', ') ?: 'N/A',
            $article->products->map(function($product) {
                return $product->name . ' (x' . ($product->pivot->quantity ?? $product->quantity ?? 0) . ')';
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
