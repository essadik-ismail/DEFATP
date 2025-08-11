<?php

namespace App\Imports;

use App\Models\Foret;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;

class ForetsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable, SkipsErrors;

    public function model(array $row)
    {
        return new Foret([
            'foret' => $row['foret'] ?? $row['Forêt'] ?? null,
            'lat' => $row['lat'] ?? $row['Latitude'] ?? null,
            'log' => $row['log'] ?? $row['Longitude'] ?? null,
            'province' => $row['province'] ?? $row['Province'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'foret' => 'required|string|max:255',
            'lat' => 'nullable|numeric',
            'log' => 'nullable|numeric',
            'province' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'foret.required' => 'Le champ forêt est requis.',
            'foret.string' => 'Le champ forêt doit être une chaîne de caractères.',
            'foret.max' => 'Le champ forêt ne peut pas dépasser 255 caractères.',
            'lat.numeric' => 'La latitude doit être un nombre.',
            'log.numeric' => 'La longitude doit être un nombre.',
            'province.string' => 'Le champ province doit être une chaîne de caractères.',
            'province.max' => 'Le champ province ne peut pas dépasser 255 caractères.',
        ];
    }

    public function onError(\Throwable $e)
    {
        // Log the error or handle it as needed
        \Log::error('Foret import error: ' . $e->getMessage());
    }
}
