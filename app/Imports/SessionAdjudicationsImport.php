<?php

namespace App\Imports;

use App\Models\SessionAdjudication;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;

class SessionAdjudicationsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable, SkipsErrors;

    public function model(array $row)
    {
        return new SessionAdjudication([
            'type' => $row['type'] ?? null,
            'date' => $row['date'] ?? null,
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:appel_doffre,adjudication',
            'date' => 'nullable|date',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'type.required' => 'Le type est requis.',
            'type.in' => 'Le type doit être "appel_doffre" ou "adjudication".',
            'date.date' => 'La date doit être au format valide.',
            'description.max' => 'La description ne peut pas dépasser 500 caractères.',
        ];
    }

    public function onError(\Throwable $e)
    {
        // Log the error or handle it as needed
        \Log::error('SessionAdjudication import error: ' . $e->getMessage());
    }
}
