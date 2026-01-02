<?php

namespace App\Imports;

use App\Models\Article;
use App\Models\Exploitant;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Database\Eloquent\Model;

class ArticlesImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError, WithEvents
{
    use Importable, SkipsErrors;

    protected $rowCount = 0;
    protected $exploitantId;
    protected $foretIds;
    protected $essenceIds;
    protected $situationAdministrativeIds;
    protected $natureDeCoupeIds;
    protected $modeExploitationIds;
    protected $zdtfId;
    protected $productsData;
    protected $createdArticleKeys = [];

    /**
     * Constructor to accept relationship IDs and products
     */
    public function __construct(
        $exploitantId = null,
        $foretIds = [],
        $essenceIds = [],
        $situationAdministrativeIds = [],
        $natureDeCoupeIds = [],
        $modeExploitationIds = [],
        $zdtfId = null,
        $productsData = []
    ) {
        $this->exploitantId = $exploitantId;
        $this->foretIds = $foretIds;
        $this->essenceIds = $essenceIds;
        $this->situationAdministrativeIds = $situationAdministrativeIds;
        $this->natureDeCoupeIds = $natureDeCoupeIds;
        $this->modeExploitationIds = $modeExploitationIds;
        $this->zdtfId = $zdtfId;
        $this->productsData = $productsData;
    }

    public function model(array $row)
    {
        $this->rowCount++;

        // Use exploitant_id from form if provided, otherwise try to resolve from Excel
        $exploitantId = $this->exploitantId;
        if (is_null($exploitantId)) {
            $exploitantId = $row['exploitant_id'] ?? $row['Exploitant ID'] ?? $row['exploitant'] ?? $row['Exploitant'] ?? null;
            if (!is_null($exploitantId) && !is_numeric($exploitantId)) {
                $byName = Exploitant::where('nom_complet', trim($exploitantId))->first();
                $exploitantId = $byName?->id;
            }
        }

        $numero = $row['numero'] ?? $row["Numéro"] ?? $row["Numéro d'Article"] ?? null;
        $annee = $row['annee'] ?? $row['Année'] ?? null;

        // Store article key for relationship syncing
        if ($numero && $annee) {
            $this->createdArticleKeys[] = ['numero' => $numero, 'annee' => $annee];
        }

        $article = new Article([
            'type' => $row['type'] ?? $row['Type'] ?? null,
            'annee' => $annee,
            'numero' => $numero,
            'date_adjudication' => $row['date_adjudication'] ?? $row['date'] ?? $row['Date'] ?? $row["Date d'adjudication"] ?? $row["Date d'Adjudication"] ?? null,
            'numero_adjudication' => $row['numero_adjudication'] ?? $row["Numéro d'adjudication"] ?? $row['Numéro Juridique'] ?? null,
            'exploitant_id' => $exploitantId,
            'nature_juridique' => $row['nature_juridique'] ?? $row['Nature Juridique'] ?? null,
            'parcelle' => $row['parcelle'] ?? $row['Parcelle'] ?? null,
            'lat' => $row['lat'] ?? $row['Latitude'] ?? null,
            'log' => $row['log'] ?? $row['Longitude'] ?? null,
            'lot' => $row['lot'] ?? $row['Lot'] ?? null,
            'superficie' => $row['superficie'] ?? $row['Superficie'] ?? null,
            'prix_de_retrait' => $row['prix_de_retrait'] ?? $row['Prix de retrait'] ?? null,
            'prix_vente' => $row['prix_vente'] ?? $row['Prix de vente'] ?? null,
            'invendu' => $this->parseBoolean($row['invendu'] ?? $row['Invendu'] ?? false),
            'fourniture_mise_charge' => $row['fourniture_mise_charge'] ?? $row['Fourniture mise en charge'] ?? null,
            'taxe_refection_chemins' => $row['taxe_refection_chemins'] ?? $row['Taxe refection chemins'] ?? null,
            'service_rendu_anef' => $row['service_rendu_anef'] ?? $row['Service rendu ANEF'] ?? null,
            'bois_chauffage_volume' => $row['bois_chauffage_volume'] ?? $row['Bois chauffage volume'] ?? null,
            'bois_chauffage_destination' => $row['bois_chauffage_destination'] ?? $row['Bois chauffage destination'] ?? null,
            'date_payement_service_anef' => $row['date_payement_service_anef'] ?? $row['Date payement service ANEF'] ?? null,
            'date_livaison_mise_en_charge_bf' => $row['date_livaison_mise_en_charge_bf'] ?? $row['Date livraison mise en charge BF'] ?? null,
            'zdtf_id' => $this->zdtfId ?? ($row['zdtf_id'] ?? $row['ZDTF ID'] ?? null),
            'bo_m3' => $row['bo_m3'] ?? $row['BO (m³)'] ?? null,
            'bi_m3' => $row['bi_m3'] ?? $row['BI (m³)'] ?? null,
            'bf_st' => $row['bf_st'] ?? $row['BF (st)'] ?? null,
            'tanin_t' => $row['tanin_t'] ?? $row['Tanin (t)'] ?? null,
            'fleur_acacia_t' => $row['fleur_acacia_t'] ?? $row['Fleur Acacia (t)'] ?? null,
            'caroube_t' => $row['caroube_t'] ?? $row['Caroube (t)'] ?? null,
            'romarin_t' => $row['romarin_t'] ?? $row['Romarin (t)'] ?? null,
            'ps_t' => $row['ps_t'] ?? $row['PS (t)'] ?? null,
            'liége_st' => $row['liége_st'] ?? $row['liege_st'] ?? $row['Liège (st)'] ?? null,
            'charbon_bois_ox' => $row['charbon_bois_ox'] ?? $row['Charbon Bois (ox)'] ?? null,
        ]);

        return $article;
    }

    /**
     * Register events to sync relationships after articles are created
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Sync relationships for all created articles
                foreach ($this->createdArticleKeys as $key) {
                    $article = Article::where('numero', $key['numero'])
                                     ->where('annee', $key['annee'])
                                     ->first();
                    
                    if ($article) {
                        // Sync relationships from form selections
                        if (!empty($this->foretIds)) {
                            $article->forets()->sync($this->foretIds);
                        }
                        if (!empty($this->essenceIds)) {
                            $article->essences()->sync($this->essenceIds);
                        }
                        if (!empty($this->situationAdministrativeIds)) {
                            $article->situationsAdministratives()->sync($this->situationAdministrativeIds);
                        }
                        if (!empty($this->natureDeCoupeIds)) {
                            $article->naturesDeCoupe()->sync($this->natureDeCoupeIds);
                        }
                        if (!empty($this->modeExploitationIds)) {
                            $article->modeExploitations()->sync($this->modeExploitationIds);
                        }

                        // Sync products from form selections
                        if (!empty($this->productsData)) {
                            $productSync = [];
                            foreach ($this->productsData as $productData) {
                                $product = Product::firstOrCreate(['name' => $productData['name']]);
                                $productSync[$product->id] = ['quantity' => $productData['quantity']];
                            }
                            if (!empty($productSync)) {
                                $article->products()->sync($productSync);
                            }
                        }
                    }
                }
            },
        ];
    }

    public function rules(): array
    {
        return [
            'type' => 'nullable|string|in:appel_doffre,adjudication,marche_negocié',
            'annee' => 'required|integer|min:1900|max:2100',
            'numero' => 'required|string|max:255',
            'date_adjudication' => 'required|date',
            'numero_adjudication' => 'nullable|string|max:255',
            'exploitant_id' => 'nullable|integer|exists:exploitants,id',
            'nature_juridique' => 'nullable|string|max:255',
            'parcelle' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'log' => 'nullable|numeric',
            'lot' => 'nullable|string|max:255',
            'superficie' => 'nullable|numeric|min:0',
            'prix_de_retrait' => 'nullable|numeric|min:0',
            'prix_vente' => 'nullable|numeric|min:0',
            'invendu' => 'nullable|boolean',
            'fourniture_mise_charge' => 'nullable|numeric|min:0',
            'date_dr' => 'nullable|date',
            'observations' => 'nullable|string',
            'bo_m3' => 'nullable|numeric|min:0',
            'bi_m3' => 'nullable|numeric|min:0',
            'bf_st' => 'nullable|numeric|min:0',
            'tanin_t' => 'nullable|numeric|min:0',
            'fleur_acacia_t' => 'nullable|numeric|min:0',
            'caroube_t' => 'nullable|numeric|min:0',
            'romarin_t' => 'nullable|numeric|min:0',
            'ps_t' => 'nullable|numeric|min:0',
            'liége_st' => 'nullable|numeric|min:0',
            'charbon_bois_ox' => 'nullable|numeric|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'type.required' => 'Le type est requis.',
            'type.in' => 'Le type doit être appel_doffre ou adjudication.',
            'annee.required' => 'Le champ année est requis.',
            'annee.integer' => 'Le champ année doit être un nombre entier.',
            'annee.min' => 'Le champ année doit être supérieur à 1900.',
            'annee.max' => 'Le champ année doit être inférieur à 2100.',
            'numero.required' => 'Le champ numéro est requis.',
            'numero.string' => 'Le champ numéro doit être une chaîne de caractères.',
            'numero.max' => 'Le champ numéro ne peut pas dépasser 255 caractères.',
            'date_adjudication.required' => "La date d'adjudication est requise.",
            'date_adjudication.date' => "La date d'adjudication doit être une date valide.",
            'invendu.boolean' => 'Le champ invendu doit être vrai ou faux.',
            'prix_de_retrait.numeric' => 'Le prix de retrait doit être un nombre.',
            'prix_de_retrait.min' => 'Le prix de retrait doit être positif.',
            'lot.string' => 'Le champ lot doit être une chaîne de caractères.',
            'lot.max' => 'Le champ lot ne peut pas dépasser 255 caractères.',
            'parcelle.string' => 'Le champ parcelle doit être une chaîne de caractères.',
            'parcelle.max' => 'Le champ parcelle ne peut pas dépasser 255 caractères.',
            'superficie.numeric' => 'La superficie doit être un nombre.',
            'superficie.min' => 'La superficie doit être positive.',
            'prix_vente.numeric' => 'Le prix de vente doit être un nombre.',
            'prix_vente.min' => 'Le prix de vente doit être positif.',
            'fourniture_mise_charge.numeric' => 'La fourniture mise en charge doit être un nombre.',
            'fourniture_mise_charge.min' => 'La fourniture mise en charge doit être positive.',
            'date_dr.date' => 'La date DR doit être une date valide.',
            'observations.string' => 'Les observations doivent être une chaîne de caractères.',
            'numero_adjudication.max' => 'Le numéro juridique ne peut pas dépasser 255 caractères.',
            'exploitant_id.integer' => "L'exploitant doit être un ID valide.",
            'exploitant_id.exists' => "L'exploitant spécifié est introuvable.",
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    private function parseBoolean($value)
    {
        if (is_bool($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['oui', 'yes', 'true', '1', 'vrai']);
        }
        
        return (bool) $value;
    }

    public function onError(\Throwable $e)
    {
        // Log the error or handle it as needed
        \Log::error('Article import error: ' . $e->getMessage());
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
