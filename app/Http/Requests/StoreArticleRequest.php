<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
            'date_adjudication' => ['required', 'date'],
            'annee' => ['required', 'integer', 'min:2000', 'max:2100'],
            'numero' => ['nullable', 'string', 'max:255'],
            'localisation_id' => ['required', 'exists:localisations,id'],
            'situation_administrative_id' => ['required', 'exists:situation_administratives,id'],
            'parcelle' => ['nullable', 'integer', 'min:0'],
            'foret_id' => ['required', 'exists:forets,id'],
            'essence_id' => ['required', 'exists:essences,id'],
            'nature_de_coupe_id' => ['required', 'exists:nature_de_coupes,id'],
            'lot' => ['nullable', 'string', 'max:255'],
            'superficie' => ['nullable', 'string', 'max:255'],
            'bo_m3' => ['nullable', 'integer', 'min:0'],
            'bi_m3' => ['nullable', 'integer', 'min:0'],
            'bf_st' => ['nullable', 'integer', 'min:0'],
            'tanin_t' => ['nullable', 'integer', 'min:0'],
            'fleur_acacia_t' => ['nullable', 'integer', 'min:0'],
            'caroube_t' => ['nullable', 'integer', 'min:0'],
            'romarin_t' => ['nullable', 'integer', 'min:0'],
            'ps_t' => ['nullable', 'integer', 'min:0'],
            'liege_st' => ['nullable', 'integer', 'min:0'],
            'charbon_bois_ox' => ['nullable', 'integer', 'min:0'],
            'invendu' => ['nullable', 'boolean'],
            'prix_de_retrait' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'date_dr' => ['nullable', 'date'],
            'exploitant_id' => ['nullable', 'exists:exploitants,id'],
            'type' => ['nullable', 'in:appel_doffre,adjudication'],
            'prix_vente' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'dc' => ['nullable', 'boolean'],
            'rc' => ['nullable', 'boolean'],
            'date_de_resiliation' => ['nullable', 'date'],
            'date_de_decheance' => ['nullable', 'date'],
            'is_validated' => ['nullable', 'boolean'],
            'observations' => ['nullable', 'string'],
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
            'date_adjudication.required' => 'La date d\'adjudication est requise.',
            'date_adjudication.date' => 'La date d\'adjudication doit être une date valide.',
            'annee.required' => 'L\'année est requise.',
            'annee.integer' => 'L\'année doit être un nombre entier.',
            'annee.min' => 'L\'année doit être supérieure ou égale à 2000.',
            'annee.max' => 'L\'année doit être inférieure ou égale à 2100.',
            'localisation_id.required' => 'La localisation est requise.',
            'localisation_id.exists' => 'La localisation sélectionnée n\'existe pas.',
            'situation_administrative_id.required' => 'La situation administrative est requise.',
            'situation_administrative_id.exists' => 'La situation administrative sélectionnée n\'existe pas.',
            'foret_id.required' => 'La forêt est requise.',
            'foret_id.exists' => 'La forêt sélectionnée n\'existe pas.',
            'essence_id.required' => 'L\'essence est requise.',
            'essence_id.exists' => 'L\'essence sélectionnée n\'existe pas.',
            'nature_de_coupe_id.required' => 'La nature de coupe est requise.',
            'nature_de_coupe_id.exists' => 'La nature de coupe sélectionnée n\'existe pas.',
            'type.in' => 'Le type doit être "appel_doffre" ou "adjudication".',
            'prix_de_retrait.numeric' => 'Le prix de retrait doit être un nombre.',
            'prix_de_retrait.min' => 'Le prix de retrait ne peut pas être négatif.',
            'prix_vente.numeric' => 'Le prix de vente doit être un nombre.',
            'prix_vente.min' => 'Le prix de vente ne peut pas être négatif.',
            'exploitant_id.exists' => 'L\'exploitant sélectionné n\'existe pas.',
            'date_dr.date' => 'La date DR doit être une date valide.',
            'date_de_resiliation.date' => 'La date de résiliation doit être une date valide.',
            'date_de_decheance.date' => 'La date de déchéance doit être une date valide.',
        ];
    }
}
