<?php

namespace App\Imports;

use App\Models\Localisation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;

class LocalisationsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable, SkipsErrors;

    public function model(array $row)
    {
        return new Localisation([
            'CODE' => $row['code'] ?? $row['Code'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'code.required' => 'Le champ code est requis.',
            'code.string' => 'Le champ code doit être une chaîne de caractères.',
            'code.max' => 'Le champ code ne peut pas dépasser 255 caractères.',
        ];
    }

    public function onError(\Throwable $e)
    {
        // Log the error or handle it as needed
        \Log::error('Localisation import error: ' . $e->getMessage());
    }
}
