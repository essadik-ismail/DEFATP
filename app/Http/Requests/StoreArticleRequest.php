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
            'numero' => ['required', 'string', 'max:255'],
            'lot' => ['required', 'string', 'max:255'],
            'parcelle' => ['nullable', 'string', 'max:255'],
            'superficie' => ['required', 'numeric', 'min:0'],
            'dranef_id' => ['required', 'exists:dranefs,id'],
            'dpanef_id' => ['required', 'exists:dpanefs,id'],
            'zdtf_id'   => ['required', 'exists:zdtfs,id'],
            'dfp_id'    => ['required', 'exists:dfps,id'],
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
            'mode_exploitation_ids' => ['required', 'array', 'min:1'],
            'mode_exploitation_ids.*' => ['exists:mode_exploitations,id'],
            'nature_de_coupe_ids' => ['required', 'array', 'min:1'],
            'nature_de_coupe_ids.*' => ['exists:nature_de_coupes,id'],
            'commune_id' => ['nullable', 'exists:communes,id'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'province_ids' => ['nullable', 'array'],
            'province_ids.*' => ['exists:provinces,id'],
            'commune_ids' => ['nullable', 'array'],
            'commune_ids.*' => ['exists:communes,id'],
            'nature_juridique' => ['nullable', 'string', 'max:255'],
            'canton' => ['nullable', 'string', 'max:255'],
            'particuliere' => ['nullable', 'string'],
            'limite_nord' => ['nullable', 'string', 'max:255'],
            'limite_sud' => ['nullable', 'string', 'max:255'],
            'limite_est' => ['nullable', 'string', 'max:255'],
            'limite_ouest' => ['nullable', 'string', 'max:255'],
            'coordonnee_x' => ['required', 'numeric'],
            'coordonnee_y' => ['required', 'numeric'],
            'foret_ids' => ['nullable', 'array'],
            'foret_ids.*' => ['exists:forets,id'],
            'depot_ids' => ['nullable', 'array'],
            'depot_ids.*' => ['exists:depot,id'],
            'products' => ['nullable', 'array'],
            'products.*.essence_id' => ['required_with:products', 'exists:essences,id'],
            'products.*.product_id' => ['required_with:products', 'exists:products,id'],
            'products.*.quantity' => ['required_with:products', 'numeric', 'min:0'],
            'is_on_depot' => ['nullable', 'boolean'],
            'locations_file' => ['nullable', 'file', 'mimes:xlsx,xls', 'max:10240'],
            'cession_id' => ['nullable', 'integer', 'exists:groupe_cession,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'numero.required'               => 'Le numéro de l\'article est obligatoire.',
            'lot.required'                  => 'Le numéro de lot est obligatoire.',
            'superficie.required'           => 'La superficie est obligatoire.',
            'superficie.numeric'            => 'La superficie doit être un nombre.',
            'superficie.min'                => 'La superficie doit être positive.',
            'dranef_id.required'            => 'La DRANEF est obligatoire.',
            'dpanef_id.required'            => 'La DPANEF est obligatoire.',
            'zdtf_id.required'              => 'La ZDTF est obligatoire.',
            'dfp_id.required'               => 'La DFP est obligatoire.',
            'nature_de_coupe_ids.required'  => 'La nature de coupe est obligatoire.',
            'nature_de_coupe_ids.min'       => 'Sélectionnez au moins une nature de coupe.',
            'mode_exploitation_ids.required'=> 'Le mode d\'exploitation est obligatoire.',
            'mode_exploitation_ids.min'     => 'Sélectionnez au moins un mode d\'exploitation.',
            'locations_file.mimes' => 'Le fichier plan de situation doit être au format Excel (.xlsx ou .xls).',
            'locations_file.max' => 'Le fichier plan de situation ne doit pas dépasser 10 Mo.',
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

