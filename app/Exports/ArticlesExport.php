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
            'situationAdministrative',
            'foret',
            'essence',
            'natureDeCoupe',

            'localisation'
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

        return $query->orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Année',
            'Numéro',
            'Date',
            'Invendu',
            'Prix de retrait',
            'Commune',
            'Province',
            'Forêt',
            'Essence',
            'Nature de coupe',

            'Localisation',
            'Lot',
            'Parcelle',
            'Superficie',
            'Prix de vente',
            'Fourniture mise en charge',
            'Date DR',
            'Observations',
            'BO (m³)',
            'BI (m³)',
            'BF (st)',
            'Tanin (t)',
            'Fleur Acacia (t)',
            'Caroube (t)',
            'Romarin (t)',
            'PS (t)',
            'Liège (st)',
            'Charbon Bois (ox)'
        ];
    }

    public function map($article): array
    {
        return [
            $article->id,
            $article->annee,
            $article->numero,
            $article->date,
            $article->invendu ? 'Oui' : 'Non',
            $article->prix_de_retrait,
            $article->situationAdministrative?->commune,
            $article->situationAdministrative?->province,
            $article->foret?->foret,
            $article->essence?->essence,
            $article->natureDeCoupe?->nature_de_coupe,

            $article->localisation?->CODE,
            $article->lot,
            $article->parcelle,
            $article->superficie,
            $article->prix_vente,
            $article->fourniture_mise_charge,
            $article->date_dr,
            $article->observations,
            $article->bo_m3,
            $article->bi_m3,
            $article->bf_st,
            $article->tanin_t,
            $article->fleur_acacia_t,
            $article->caroube_t,
            $article->romarin_t,
            $article->ps_t,
            $article->liege_st,
            $article->charbon_bois_ox
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
