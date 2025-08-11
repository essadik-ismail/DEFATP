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
            'date' => ['required', 'date'],
            'parcelle' => ['nullable', 'integer', 'min:0'],
            'foret_id' => ['nullable', 'exists:forets,id'],

            // Technical specifications with checkboxes
            'has_lot' => ['boolean'],
            'has_superficie' => ['boolean'],
            'has_bo_m3' => ['boolean'],
            'has_bi_m3' => ['boolean'],
            'has_bf_st' => ['boolean'],
            'has_tanin_t' => ['boolean'],
            'has_fleur_acacia_t' => ['boolean'],
            'has_caroube_t' => ['boolean'],
            'has_romarin_t' => ['boolean'],
            'has_ps_t' => ['boolean'],
            'has_liege_st' => ['boolean'],
            'has_charbon_bois_ox' => ['boolean'],

            // Technical specification values
            'lot' => ['nullable', 'string', 'max:255'],
            'superficie' => ['nullable', 'numeric', 'min:0'],
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

            // Contract modal fields
            'invendu' => ['nullable', 'numeric', 'min:0'],
            'exploitant_id' => ['nullable', 'exists:exploitants,id'],
            'type' => ['nullable', 'in:appel_doffre,adjudication'],
            'session_date' => ['nullable', 'date'],
            'session_numero' => ['nullable', 'string', 'max:255'],
            'nature_juridique' => ['nullable', 'string', 'max:255'],
            'adjudicatire' => ['nullable', 'string', 'max:255'],
            'dc' => ['boolean'],
            'rc' => ['boolean'],
            'date_de_resiliation' => ['nullable', 'date'],
            'date_de_decheance' => ['nullable', 'date'],

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
            'foret_id.exists' => 'La forêt sélectionnée n\'existe pas.',
            'exploitant_id.exists' => 'L\'exploitant sélectionné n\'existe pas.',
            'type.in' => 'Le type doit être "appel_doffre" ou "adjudication".',
        ];
    }
}
