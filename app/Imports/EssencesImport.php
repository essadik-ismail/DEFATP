<?php

namespace App\Imports;

use App\Models\Essence;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;

class EssencesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable, SkipsErrors;

    public function model(array $row)
    {
        return new Essence([
            'essence' => $row['essence'] ?? $row['Essence'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'essence' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'essence.required' => 'Le champ essence est requis.',
            'essence.string' => 'Le champ essence doit être une chaîne de caractères.',
            'essence.max' => 'Le champ essence ne peut pas dépasser 255 caractères.',
        ];
    }

    public function onError(\Throwable $e)
    {
        // Log the error or handle it as needed
        \Log::error('Essence import error: ' . $e->getMessage());
    }
}
