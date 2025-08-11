<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionAdjudicationRequest extends FormRequest
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
            'type' => ['required', 'in:appel_doffre,adjudication'],
            'date' => ['required', 'date'],
            'numero' => ['nullable', 'string', 'max:255'],
            'nature_juridique' => ['nullable', 'string', 'max:255'],
            'adjudicatire' => ['nullable', 'string', 'max:255'],
            'dc' => ['boolean'],
            'rc' => ['boolean'],
            'date_de_resiliation' => ['nullable', 'date'],
            'date_de_decheance' => ['nullable', 'date'],
            'exploitant_id' => ['nullable', 'exists:exploitants,id'],
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
            'type.required' => 'Le type est requis.',
            'type.in' => 'Le type doit être "appel_doffre" ou "adjudication".',
            'date.required' => 'La date est requise.',
            'date.date' => 'La date doit être une date valide.',
            'date_de_resiliation.date' => 'La date de résiliation doit être une date valide.',
            'date_de_decheance.date' => 'La date de déchéance doit être une date valide.',
        ];
    }
}
