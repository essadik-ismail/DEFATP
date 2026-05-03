<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'numero' => ['nullable', 'string', 'max:255'],
            'lot' => ['nullable', 'string', 'max:255'],
            'parcelle' => ['nullable', 'string', 'max:255'],
            'superficie' => ['nullable', 'numeric', 'min:0'],
            'dranef_code' => ['nullable', 'exists:dranefs,code'],
            'dpanef_code' => ['nullable', 'exists:dpanefs,code'],
            'zdtf_code'   => ['nullable', 'exists:zdtfs,code'],
            'dfp_code'    => ['nullable', 'exists:dfps,code'],
            'canton' => ['nullable', 'string', 'max:255'],
            'particuliere' => ['nullable', 'string'],
            'fourniture_mise_charge' => ['nullable', 'numeric', 'min:0'],
            'taxe_refection_chemins' => ['nullable', 'numeric', 'min:0'],
            'date_echeance_taxe_refection_chemins' => ['nullable', 'date'],
            'service_rendu_anef' => ['nullable', 'numeric', 'min:0'],
            'date_echeance_service_rendu_anef' => ['nullable', 'date'],
            'bois_chauffage_volume' => ['nullable', 'numeric', 'min:0'],
            'bois_chauffage_destination' => ['nullable', 'string', 'max:255'],
            'mise_en_charge_destination' => ['nullable', 'string', 'max:255'],
            'mise_en_charge_volume' => ['nullable', 'numeric', 'min:0'],
            'date_echeance_mise_en_charge' => ['nullable', 'date'],
            'date_payement_service_anef' => ['nullable', 'date'],
            'date_livaison_mise_en_charge_bf' => ['nullable', 'date'],
            'invandu' => ['nullable', 'boolean'],
            'mode_exploitation_ids' => ['nullable', 'array'],
            'mode_exploitation_ids.*' => ['exists:mode_exploitations,id'],
            'nature_de_coupe_ids' => ['nullable', 'array'],
            'nature_de_coupe_ids.*' => ['exists:nature_de_coupes,id'],
            'province_ids' => ['nullable', 'array'],
            'province_ids.*' => ['exists:provinces,id'],
            'commune_ids' => ['nullable', 'array'],
            'commune_ids.*' => ['exists:communes,id'],
            'foret_ids' => ['nullable', 'array'],
            'foret_ids.*' => ['exists:forets,id'],
            'parcelle_ids' => ['nullable', 'array'],
            'parcelle_ids.*' => ['exists:parcelles,id'],
            'nature_juridique' => ['nullable', 'string', 'max:255'],
            'limite_nord' => ['nullable', 'string', 'max:255'],
            'limite_sud' => ['nullable', 'string', 'max:255'],
            'limite_est' => ['nullable', 'string', 'max:255'],
            'limite_ouest' => ['nullable', 'string', 'max:255'],
            'limite_se' => ['nullable', 'string', 'max:255'],
            'limite_so' => ['nullable', 'string', 'max:255'],
            'limite_ne' => ['nullable', 'string', 'max:255'],
            'limite_no' => ['nullable', 'string', 'max:255'],
            'date_livraison_bois_chauffage' => ['nullable', 'date'],
            'coordonnee_x' => ['nullable', 'numeric'],
            'coordonnee_y' => ['nullable', 'numeric'],
            'depot_ids' => ['nullable', 'array'],
            'depot_ids.*' => ['exists:depot,id'],
            'products' => ['nullable', 'array'],
            'products.*.essence_id' => ['required_with:products', 'exists:essences,id'],
            'products.*.product_id' => ['required_with:products', 'exists:products,id'],
            'products.*.quantity' => ['required_with:products', 'numeric', 'min:0'],
            'is_on_depot' => ['nullable', 'boolean'],
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
            'superficie.numeric' => 'La superficie doit être un nombre.',
            'superficie.min' => 'La superficie doit être positive.',
            'limite_nord.required' => 'La limite Nord est requise.',
            'limite_sud.required' => 'La limite Sud est requise.',
            'limite_est.required' => 'La limite Est est requise.',
            'limite_ouest.required' => 'La limite Ouest est requise.',
            'coordonnee_x.required' => 'La coordonnée X est requise.',
            'coordonnee_x.numeric' => 'La coordonnée X doit être un nombre.',
            'coordonnee_y.required' => 'La coordonnée Y est requise.',
            'coordonnee_y.numeric' => 'La coordonnée Y doit être un nombre.',
        ];
    }
}

