<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticlesByYearRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'foret_id' => ['nullable', 'integer', 'exists:forets,id'],
            'essence_id' => ['nullable', 'integer', 'exists:essences,id'],
            'invendu' => ['nullable', 'in:0,1'],
        ];
    }

    /**
     * Custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'year.integer' => "L'année doit être un nombre entier.",
            'year.min' => "L'année doit être au moins 2000.",
            'year.max' => "L'année doit être au plus 2100.",
            'foret_id.exists' => "La forêt sélectionnée n'existe pas.",
            'essence_id.exists' => "L'essence sélectionnée n'existe pas.",
            'invendu.in' => 'Le statut doit être 0 (vendu) ou 1 (invendu).',
        ];
    }
}


