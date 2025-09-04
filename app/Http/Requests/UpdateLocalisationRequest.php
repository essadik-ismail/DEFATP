<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocalisationRequest extends FormRequest
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
            'CODE' => ['required', 'string', 'max:255', Rule::unique('localisations')->ignore($this->localisation)],
            'DRANEF' => 'required|string|max:255',
            'DPANEF' => 'required|string|max:255',
            'ENTITE' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'CODE.required' => 'Le code est requis.',
            'CODE.unique' => 'Ce code existe déjà.',
            'DRANEF.required' => 'Le DRANEF est requis.',
            'DPANEF.required' => 'Le DPANEF est requis.',
        ];
    }
}
