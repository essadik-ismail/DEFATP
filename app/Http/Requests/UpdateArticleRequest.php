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
            'numero_adjudication' => ['nullable', 'string', 'max:255'],
            'lot' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:appel_doffre,adjudication,marche_negocié'],
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
            'parcelle' => ['nullable', 'string', 'max:255'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'log' => ['nullable', 'numeric', 'between:-180,180'],
            'superficie' => ['nullable', 'numeric', 'min:0'],
            'ps_t' => ['nullable', 'numeric', 'min:0'],
            'fourniture_mise_charge' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'nommer_a_la_vente' => ['nullable', 'boolean'],
            'taxe_refection_chemins' => ['nullable', 'numeric', 'min:0'],
            'service_rendu_anef' => ['nullable', 'numeric', 'min:0'],
            'bois_chauffage_volume' => ['nullable', 'numeric', 'min:0'],
            'bois_chauffage_destination' => ['nullable', 'string', 'max:255'],
            'date_payement_service_anef' => ['nullable', 'date'],
            'date_livaison_mise_en_charge_bf' => ['nullable', 'date'],
            'zdtf_id' => ['nullable', 'exists:zdtfs,id'],
            'mode_exploitation_ids' => ['nullable', 'array'],
            'mode_exploitation_ids.*' => ['integer', 'exists:mode_exploitations,id'],
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
            'type.required' => 'Le type est requis.',
            'type.in' => 'Le type doit être "appel_doffre", "adjudication" ou "marche_negocié".',
            'exploitant_id.exists' => 'L\'exploitant sélectionné n\'existe pas.',
            'lat.numeric' => 'La latitude doit être un nombre.',
            'lat.between' => 'La latitude doit être entre -90 et 90.',
            'log.numeric' => 'La longitude doit être un nombre.',
            'log.between' => 'La longitude doit être entre -180 et 180.',
            'foret_ids.required' => 'Au moins une forêt doit être sélectionnée.',
            'foret_ids.min' => 'Au moins une forêt doit être sélectionnée.',
            'essence_ids.required' => 'Au moins une essence doit être sélectionnée.',
            'essence_ids.min' => 'Au moins une essence doit être sélectionnée.',
            'situation_administrative_ids.required' => 'Au moins une situation administrative doit être sélectionnée.',
            'situation_administrative_ids.min' => 'Au moins une situation administrative doit être sélectionnée.',
            'nature_de_coupe_ids.required' => 'Au moins une nature de coupe doit être sélectionnée.',
            'nature_de_coupe_ids.min' => 'Au moins une nature de coupe doit être sélectionnée.',
        ];
    }
}
