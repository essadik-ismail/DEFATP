<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepotRequest extends FormRequest
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
            'id_dpanef' => ['nullable', 'exists:dpanefs,id'],
            'article_ids' => ['nullable', 'array'],
            'article_ids.*' => ['exists:articles,id'],
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
            'nom.required' => 'Le nom du dépôt est requis.',
            'nom.max' => 'Le nom du dépôt ne peut pas dépasser 255 caractères.',
            'id_dpanef.exists' => 'Le DPANEF sélectionné n\'existe pas.',
        ];
    }
}

