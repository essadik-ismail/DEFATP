<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{

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
            'numero_adjudication' => ['nullable', 'string', 'max:255'],
            'lot' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:appel_doffre,adjudication'],
            'exploitant_id' => ['nullable', 'exists:exploitants,id'],
            // Pivot ID arrays - make required to match form requirements
            'foret_ids' => ['required', 'array', 'min:1'],
            'foret_ids.*' => ['integer', 'exists:forets,id'],
            'essence_ids' => ['required', 'array', 'min:1'],
            'essence_ids.*' => ['integer', 'exists:essences,id'],
            'situation_administrative_ids' => ['required', 'array', 'min:1'],
            'situation_administrative_ids.*' => ['integer', 'exists:situation_administratives,id'],
            'nature_de_coupe_ids' => ['required', 'array', 'min:1'],
            'nature_de_coupe_ids.*' => ['integer', 'exists:nature_de_coupes,id'],
            'localisation_ids' => ['required', 'array', 'min:1'],
            'localisation_ids.*' => ['integer', 'exists:localisations,id'],
            'nature_juridique' => ['nullable', 'string', 'max:255'],
            'parcelle' => ['nullable', 'string', 'max:255'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'log' => ['nullable', 'numeric', 'between:-180,180'],
            'superficie' => ['nullable', 'numeric', 'min:0'],
            'bo_m3' => ['nullable', 'numeric', 'min:0'],
            'bi_m3' => ['nullable', 'numeric', 'min:0'],
            'bf_st' => ['nullable', 'numeric', 'min:0'],
            'tanin_t' => ['nullable', 'numeric', 'min:0'],
            'fleur_acacia_t' => ['nullable', 'numeric', 'min:0'],
            'caroube_t' => ['nullable', 'numeric', 'min:0'],
            'romarin_t' => ['nullable', 'numeric', 'min:0'],
            'liége_st' => ['nullable', 'numeric', 'min:0'],
            'charbon_bois_ox' => ['nullable', 'numeric', 'min:0'],
            'prix_de_retrait' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'prix_vente' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'products' => ['nullable', 'array'],
            'products.*.name' => ['nullable', 'string', 'max:255'],
            'products.*.quantity' => ['nullable', 'integer', 'min:1'],
            'locations' => ['nullable', 'array'],
            'locations.*.mat' => ['nullable', 'string', 'max:255'],
            'locations.*.x' => ['nullable', 'numeric'],
            'locations.*.y' => ['nullable', 'numeric'],
            'locations_file' => ['nullable', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
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
            'exploitant_id.exists' => 'L\'exploitant sélectionné n\'existe pas.',
            'lat.numeric' => 'La latitude doit être un nombre.',
            'lat.between' => 'La latitude doit être entre -90 et 90.',
            'log.numeric' => 'La longitude doit être un nombre.',
            'log.between' => 'La longitude doit être entre -180 et 180.',
            'prix_de_retrait.numeric' => 'Le prix de retrait doit être un nombre.',
            'prix_de_retrait.min' => 'Le prix de retrait ne peut pas être négatif.',
            'prix_vente.numeric' => 'Le prix de vente doit être un nombre.',
            'prix_vente.min' => 'Le prix de vente ne peut pas être négatif.',
            'foret_ids.required' => 'Au moins une forêt doit être sélectionnée.',
            'foret_ids.min' => 'Au moins une forêt doit être sélectionnée.',
            'essence_ids.required' => 'Au moins une essence doit être sélectionnée.',
            'essence_ids.min' => 'Au moins une essence doit être sélectionnée.',
            'situation_administrative_ids.required' => 'Au moins une situation administrative doit être sélectionnée.',
            'situation_administrative_ids.min' => 'Au moins une situation administrative doit être sélectionnée.',
            'nature_de_coupe_ids.required' => 'Au moins une nature de coupe doit être sélectionnée.',
            'nature_de_coupe_ids.min' => 'Au moins une nature de coupe doit être sélectionnée.',
            'localisation_ids.required' => 'Au moins une localisation doit être sélectionnée.',
            'localisation_ids.min' => 'Au moins une localisation doit être sélectionnée.',
        ];
    }
}
