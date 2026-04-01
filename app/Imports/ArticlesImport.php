<?php

namespace App\Imports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ArticlesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable;
    use SkipsErrors;

    protected int $rowCount = 0;

    public function model(array $row)
    {
        $this->rowCount++;

        return new Article([
            'numero' => $row['numero'] ?? $row['Numero'] ?? null,
            'lot' => $row['lot'] ?? $row['Lot'] ?? null,
            'parcelle' => $row['parcelle'] ?? $row['Parcelle'] ?? null,
            'superficie' => $row['superficie'] ?? $row['Superficie'] ?? null,
            'nature_juridique' => $row['nature_juridique'] ?? $row['Nature juridique'] ?? null,
            'current_step' => $row['etape'] ?? $row['Etape'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'numero' => 'nullable|string|max:255',
            'lot' => 'nullable|string|max:255',
            'parcelle' => 'nullable|string|max:255',
            'superficie' => 'nullable|numeric|min:0',
            'nature_juridique' => 'nullable|string|max:255',
            'etape' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'superficie.numeric' => 'La superficie doit etre numerique.',
            'superficie.min' => 'La superficie doit etre positive.',
        ];
    }

    public function onError(\Throwable $e)
    {
        \Log::error('Article import error: ' . $e->getMessage());
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
