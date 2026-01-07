<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChargeApayerRequest extends FormRequest
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
            'nom' => ['required', 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:0'],
            'date_echeance' => ['required', 'date'],
            'contrat_vente_id' => ['required', 'exists:contract_ventes,id'],
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
            'nom.required' => 'Le nom de la charge est requis.',
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être positif.',
            'date_echeance.required' => 'La date d\'échéance est requise.',
            'date_echeance.date' => 'La date d\'échéance doit être une date valide.',
            'contrat_vente_id.required' => 'Le contrat de vente est requis.',
            'contrat_vente_id.exists' => 'Le contrat de vente sélectionné n\'existe pas.',
        ];
    }
}

