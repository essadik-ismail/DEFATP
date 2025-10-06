<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticlesByEssenceRequest extends FormRequest
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
            'essence_id' => ['nullable', 'integer', 'exists:essences,id'],
            'foret_id' => ['nullable', 'integer', 'exists:forets,id'],
            'invendu' => ['nullable', 'in:0,1'],
        ];
    }

    /**
     * Custom messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'essence_id.exists' => "L'essence sélectionnée n'existe pas.",
            'foret_id.exists' => "La forêt sélectionnée n'existe pas.",
            'invendu.in' => 'Le statut doit être 0 (vendu) ou 1 (invendu).',
        ];
    }
}


