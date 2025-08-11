<?php

namespace App\Imports;

use App\Models\SituationAdministrative;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;

class SituationAdministrativesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable, SkipsErrors;

    public function model(array $row)
    {
        return new SituationAdministrative([
            'commune' => $row['commune'] ?? $row['Commune'] ?? null,
            'province' => $row['province'] ?? $row['Province'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'commune' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'commune.required' => 'Le champ commune est requis.',
            'commune.string' => 'Le champ commune doit être une chaîne de caractères.',
            'commune.max' => 'Le champ commune ne peut pas dépasser 255 caractères.',
            'province.string' => 'Le champ province doit être une chaîne de caractères.',
            'province.max' => 'Le champ province ne peut pas dépasser 255 caractères.',
        ];
    }

    public function onError(\Throwable $e)
    {
        // Log the error or handle it as needed
        \Log::error('SituationAdministrative import error: ' . $e->getMessage());
    }
}
