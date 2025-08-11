<?php

namespace App\Imports;

use App\Models\NatureDeCoupe;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;

class NatureDeCoupesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable, SkipsErrors;

    public function model(array $row)
    {
        return new NatureDeCoupe([
            'nature_de_coupe' => $row['nature_de_coupe'] ?? $row['Nature de coupe'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nature_de_coupe' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nature_de_coupe.required' => 'Le champ nature de coupe est requis.',
            'nature_de_coupe.string' => 'Le champ nature de coupe doit être une chaîne de caractères.',
            'nature_de_coupe.max' => 'Le champ nature de coupe ne peut pas dépasser 255 caractères.',
        ];
    }

    public function onError(\Throwable $e)
    {
        // Log the error or handle it as needed
        \Log::error('NatureDeCoupe import error: ' . $e->getMessage());
    }
}
