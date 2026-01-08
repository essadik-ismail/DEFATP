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
            'numero' => ['nullable', 'string', 'max:255'],
            'annee' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'lot' => ['nullable', 'string', 'max:255'],
            'parcelle' => ['nullable', 'string', 'max:255'],
            'superficie' => ['nullable', 'numeric', 'min:0'],
            'fourniture_mise_charge' => ['nullable', 'numeric', 'min:0'],
            'taxe_refection_chemins' => ['nullable', 'numeric', 'min:0'],
            'service_rendu_anef' => ['nullable', 'numeric', 'min:0'],
            'bois_chauffage_volume' => ['nullable', 'numeric', 'min:0'],
            'bois_chauffage_destination' => ['nullable', 'string', 'max:255'],
            'date_payement_service_anef' => ['nullable', 'date'],
            'date_livaison_mise_en_charge_bf' => ['nullable', 'date'],
            'invandu' => ['nullable', 'boolean'],
            'mode_exploitation_ids' => ['nullable', 'array'],
            'mode_exploitation_ids.*' => ['exists:mode_exploitations,id'],
            'nature_de_coupe_ids' => ['nullable', 'array'],
            'nature_de_coupe_ids.*' => ['exists:nature_de_coupes,id'],
            'commune_id' => ['nullable', 'exists:communes,id'],
            'province_id' => ['nullable', 'exists:provinces,id'],
            'province_ids' => ['nullable', 'array'],
            'province_ids.*' => ['exists:provinces,id'],
            'dranef_code' => ['nullable', 'string', 'max:255'],
            'dpanef_code' => ['nullable', 'string', 'max:255'],
            'zdtf_code' => ['nullable', 'string', 'max:255'],
            'dfp_code' => ['nullable', 'string', 'max:255'],
            'foret_ids' => ['nullable', 'array'],
            'foret_ids.*' => ['exists:forets,id'],
            'parcelle_ids' => ['nullable', 'array'],
            'parcelle_ids.*' => ['exists:parcelles,id'],
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert empty strings to null for code fields
        $this->merge([
            'dranef_code' => $this->dranef_code ? trim($this->dranef_code) : null,
            'dpanef_code' => $this->dpanef_code ? trim($this->dpanef_code) : null,
            'zdtf_code' => $this->zdtf_code ? trim($this->zdtf_code) : null,
            'dfp_code' => $this->dfp_code ? trim($this->dfp_code) : null,
        ]);
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
            'annee.min' => 'L\'année doit être supérieure ou égale à 1900.',
            'annee.max' => 'L\'année doit être inférieure ou égale à 2100.',
            'superficie.numeric' => 'La superficie doit être un nombre.',
            'superficie.min' => 'La superficie doit être positive.',
            'dranef_code.exists' => 'Le code DRANEF sélectionné n\'existe pas.',
            'dpanef_code.exists' => 'Le code DPANEF sélectionné n\'existe pas.',
            'zdtf_code.exists' => 'Le code ZDTF sélectionné n\'existe pas.',
            'dfp_code.exists' => 'Le code DFP sélectionné n\'existe pas.',
        ];
    }
}

