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
            'date' => ['required', 'date'],
            'invendu' => ['boolean'],
            'prix_de_retrait' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            
            // Foreign key relationships
            'situation_administrative_id' => ['nullable', 'exists:situation_administratives,id'],
            'foret_id' => ['nullable', 'exists:forets,id'],
            'essence_id' => ['nullable', 'exists:essences,id'],
            'nature_de_coupe_id' => ['nullable', 'exists:nature_de_coupes,id'],
            'session_adjudication_id' => ['nullable', 'exists:session_adjudications,id'],
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
            'date.required' => 'La date est requise.',
            'date.date' => 'La date doit être une date valide.',
            'prix_de_retrait.numeric' => 'Le prix de retrait doit être un nombre.',
            'prix_de_retrait.min' => 'Le prix de retrait ne peut pas être négatif.',
            'prix_vente.numeric' => 'Le prix de vente doit être un nombre.',
            'prix_vente.min' => 'Le prix de vente ne peut pas être négatif.',
            'fourniture_mise_charge.numeric' => 'La fourniture mise en charge doit être un nombre.',
            'fourniture_mise_charge.min' => 'La fourniture mise en charge ne peut pas être négative.',
            'date_dr.date' => 'La date DR doit être une date valide.',
        ];
    }
}
