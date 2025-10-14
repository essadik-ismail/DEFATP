<?php

namespace App\Imports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ArticlesImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError
{
    use Importable, SkipsErrors;

    public function model(array $row)
    {

        return new Article([
            'annee' => $row['annee'] ?? $row['Année'] ?? null,
            'numero' => $row['numero'] ?? $row['Numéro'] ?? null,
            'date_adjudication' => $row['date_adjudication'] ?? $row['date'] ?? $row['Date'] ?? $row["Date d'adjudication"] ?? null,
            'invendu' => $this->parseBoolean($row['invendu'] ?? $row['Invendu'] ?? false),
            'prix_de_retrait' => $row['prix_de_retrait'] ?? $row['Prix de retrait'] ?? null,
            'lot' => $row['lot'] ?? $row['Lot'] ?? null,
            'parcelle' => $row['parcelle'] ?? $row['Parcelle'] ?? null,
            'superficie' => $row['superficie'] ?? $row['Superficie'] ?? null,
            'prix_vente' => $row['prix_vente'] ?? $row['Prix de vente'] ?? null,
            'fourniture_mise_charge' => $row['fourniture_mise_charge'] ?? $row['Fourniture mise en charge'] ?? null,
            'date_dr' => $row['date_dr'] ?? $row['Date DR'] ?? null,
            'observations' => $row['observations'] ?? $row['Observations'] ?? null,
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
    }

    public function rules(): array
    {
        return [
            'annee' => 'required|integer|min:1900|max:2100',
            'numero' => 'required|string|max:255',
            'date_adjudication' => 'required|date',
            'invendu' => 'nullable|boolean',
            'prix_de_retrait' => 'nullable|numeric|min:0',
            'lot' => 'nullable|string|max:255',
            'parcelle' => 'nullable|string|max:255',
            'superficie' => 'nullable|numeric|min:0',
            'prix_vente' => 'nullable|numeric|min:0',
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
}
