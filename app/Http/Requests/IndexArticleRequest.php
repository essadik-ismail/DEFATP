<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexArticleRequest extends FormRequest
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
            'annee' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'numero' => ['nullable', 'string', 'max:255'],
            'foret_id' => ['nullable', 'exists:forets,id'],
            'essence_id' => ['nullable', 'exists:essences,id'],
            'nature_de_coupe_id' => ['nullable', 'exists:nature_de_coupes,id'],
            'situation_administrative_id' => ['nullable', 'exists:situation_administratives,id'],
    
            'localisation_id' => ['nullable', 'exists:localisations,id'],
            'exploitant_id' => ['nullable', 'exists:exploitants,id'],
            'invendu' => ['nullable', 'boolean'],
            'type' => ['nullable', 'in:appel_doffre,adjudication'],
            'is_validated' => ['nullable', 'boolean'],
            'is_deleted' => ['nullable', 'boolean'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'prix_min' => ['nullable', 'numeric', 'min:0'],
            'prix_max' => ['nullable', 'numeric', 'min:0'],
            'sort' => ['nullable', 'string', 'in:annee,numero,date_adjudication,prix_vente,created_at,updated_at'],
            'direction' => ['nullable', 'string', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'in:10,15,25,50,100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'annee.integer' => 'L\'année doit être un nombre entier.',
            'annee.min' => 'L\'année doit être supérieure ou égale à 2000.',
            'annee.max' => 'L\'année doit être inférieure ou égale à 2100.',
            'foret_id.exists' => 'La forêt sélectionnée n\'existe pas.',
            'essence_id.exists' => 'L\'essence sélectionnée n\'existe pas.',
            'nature_de_coupe_id.exists' => 'La nature de coupe sélectionnée n\'existe pas.',
            'situation_administrative_id.exists' => 'La situation administrative sélectionnée n\'existe pas.',
    
            'localisation_id.exists' => 'La localisation sélectionnée n\'existe pas.',
            'exploitant_id.exists' => 'L\'exploitant sélectionné n\'existe pas.',
            'type.in' => 'Le type doit être "appel_doffre" ou "adjudication".',
            'sort.in' => 'Le tri doit être un champ valide.',
            'direction.in' => 'La direction doit être "asc" ou "desc".',
            'per_page.in' => 'Le nombre par page doit être une valeur valide.',
            'prix_min.numeric' => 'Le prix minimum doit être un nombre.',
            'prix_min.min' => 'Le prix minimum ne peut pas être négatif.',
            'prix_max.numeric' => 'Le prix maximum doit être un nombre.',
            'prix_max.min' => 'Le prix maximum ne peut pas être négatif.',
            'date_from.date' => 'La date de début doit être une date valide.',
            'date_to.date' => 'La date de fin doit être une date valide.',
        ];
    }
}
