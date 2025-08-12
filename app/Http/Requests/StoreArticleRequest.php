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
            'annee' => ['required', 'integer', 'min:2000', 'max:2100'],
            'numero' => ['nullable', 'string', 'max:255'],
            'date_adjudication' => ['required', 'date'],
            'invendu' => ['boolean'],
            'prix_de_retrait' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            
            // Foreign key relationships
            'situation_administrative_id' => ['nullable', 'exists:situation_administratives,id'],
            'foret_id' => ['nullable', 'exists:forets,id'],
            'essence_id' => ['nullable', 'exists:essences,id'],
            'nature_de_coupe_id' => ['nullable', 'exists:nature_de_coupes,id'],
    
            'localisation_id' => ['nullable', 'exists:localisations,id'],

            'lot' => ['nullable', 'integer', 'min:0'],
            'parcelle' => ['nullable', 'integer', 'min:0'],
            'superficie' => ['nullable', 'string', 'max:255'],

            // Volume and weight fields
            'bo_m3' => ['nullable', 'integer', 'min:0'],
            'bi_m3' => ['nullable', 'integer', 'min:0'],
            'bf_st' => ['nullable', 'integer', 'min:0'],
            'tanin_t' => ['nullable', 'integer', 'min:0'],
            'fleur_acacia_t' => ['nullable', 'integer', 'min:0'],
            'caroube_t' => ['nullable', 'integer', 'min:0'],
            'romarin_t' => ['nullable', 'integer', 'min:0'],
            'ps_t' => ['nullable', 'integer', 'min:0'],
            'liége_st' => ['nullable', 'integer', 'min:0'],
            'charbon_bois_ox' => ['nullable', 'integer', 'min:0'],

            'prix_vente' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'fourniture_mise_charge' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],

            'lat' => ['nullable', 'string', 'max:255'],
            'log' => ['nullable', 'string', 'max:255'],

            'date_dr' => ['nullable', 'date'],
            'observations' => ['nullable', 'string'],

            // Contract modal fields
            'type' => ['required', 'in:appel_doffre,adjudication'],
            'nature_juridique' => ['nullable', 'string', 'max:255'],
            'adjudicatire' => ['nullable', 'string', 'max:255'],
            'dc' => ['boolean'],
            'rc' => ['boolean'],
            'date_de_resiliation' => ['nullable', 'date'],
            'date_de_decheance' => ['nullable', 'date'],

            'exploitant_id' => ['nullable', 'exists:exploitants,id'],
            'is_validated' => ['boolean'],
            'is_deleted' => ['boolean'],
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
            'annee.required' => 'L\'année est requise.',
            'annee.integer' => 'L\'année doit être un nombre entier.',
            'annee.min' => 'L\'année doit être supérieure ou égale à 2000.',
            'annee.max' => 'L\'année doit être inférieure ou égale à 2100.',
            'date_adjudication.required' => 'La date d\'adjudication est requise.',
            'date_adjudication.date' => 'La date d\'adjudication doit être une date valide.',
            'type.required' => 'Le type est requis.',
            'type.in' => 'Le type doit être "appel_doffre" ou "adjudication".',
            'prix_de_retrait.numeric' => 'Le prix de retrait doit être un nombre.',
            'prix_de_retrait.min' => 'Le prix de retrait ne peut pas être négatif.',
            'prix_vente.numeric' => 'Le prix de vente doit être un nombre.',
            'prix_vente.min' => 'Le prix de vente ne peut pas être négatif.',
            'fourniture_mise_charge.numeric' => 'La fourniture mise en charge doit être un nombre.',
            'fourniture_mise_charge.min' => 'La fourniture mise en charge ne peut pas être négative.',
            'foret_id.exists' => 'La forêt sélectionnée n\'existe pas.',
            'essence_id.exists' => 'L\'essence sélectionnée n\'existe pas.',
            'nature_de_coupe_id.exists' => 'La nature de coupe sélectionnée n\'existe pas.',
            'situation_administrative_id.exists' => 'La situation administrative sélectionnée n\'existe pas.',
    
            'localisation_id.exists' => 'La localisation sélectionnée n\'existe pas.',
            'exploitant_id.exists' => 'L\'exploitant sélectionné n\'existe pas.',
            'date_dr.date' => 'La date DR doit être une date valide.',
            'date_de_resiliation.date' => 'La date de résiliation doit être une date valide.',
            'date_de_decheance.date' => 'La date de déchéance doit être une date valide.',
        ];
    }
}
