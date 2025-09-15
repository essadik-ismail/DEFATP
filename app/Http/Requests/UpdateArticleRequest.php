<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
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
            'numero_adjudication' => ['nullable', 'string', 'max:255'],
            'lot' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:appel_doffre,adjudication'],
            'situation_administrative_id' => ['required', 'exists:situation_administratives,id'],
            'foret_id' => ['required', 'exists:forets,id'],
            'essence_id' => ['required', 'exists:essences,id'],
            'nature_de_coupe_id' => ['required', 'exists:nature_de_coupes,id'],
            'localisation_id' => ['required', 'exists:localisations,id'],
            'exploitant_id' => ['nullable', 'exists:exploitants,id'],
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
            'situation_administrative_id.required' => 'La situation administrative est requise.',
            'situation_administrative_id.exists' => 'La situation administrative sélectionnée n\'existe pas.',
            'foret_id.required' => 'La forêt est requise.',
            'foret_id.exists' => 'La forêt sélectionnée n\'existe pas.',
            'essence_id.required' => 'L\'essence est requise.',
            'essence_id.exists' => 'L\'essence sélectionnée n\'existe pas.',
            'nature_de_coupe_id.required' => 'La nature de coupe est requise.',
            'nature_de_coupe_id.exists' => 'La nature de coupe sélectionnée n\'existe pas.',
            'localisation_id.required' => 'La localisation est requise.',
            'localisation_id.exists' => 'La localisation sélectionnée n\'existe pas.',
            'exploitant_id.exists' => 'L\'exploitant sélectionné n\'existe pas.',
            'lat.numeric' => 'La latitude doit être un nombre.',
            'lat.between' => 'La latitude doit être entre -90 et 90.',
            'log.numeric' => 'La longitude doit être un nombre.',
            'log.between' => 'La longitude doit être entre -180 et 180.',
            'prix_retrait.numeric' => 'Le prix de retrait doit être un nombre.',
            'prix_retrait.min' => 'Le prix de retrait ne peut pas être négatif.',
            'prix_vente.numeric' => 'Le prix de vente doit être un nombre.',
            'prix_vente.min' => 'Le prix de vente ne peut pas être négatif.',
        ];
    }
}
