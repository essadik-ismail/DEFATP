<?php

namespace App\Imports;

use App\Models\Location;
use App\Models\Article;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Validation\Rule;

class LocationsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use Importable, SkipsErrors;

    protected $articleId;

    public function __construct($articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Location([
            'mat' => $row['mat'] ?? null,
            'x' => $row['x'] ?? null,
            'y' => $row['y'] ?? null,
            'article_id' => $this->articleId,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'mat' => 'nullable|string|max:255',
            'x' => 'nullable|numeric',
            'y' => 'nullable|numeric',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'mat.string' => 'Le matériel doit être une chaîne de caractères.',
            'mat.max' => 'Le matériel ne peut pas dépasser 255 caractères.',
            'x.numeric' => 'La coordonnée X doit être un nombre.',
            'y.numeric' => 'La coordonnée Y doit être un nombre.',
        ];
    }
}