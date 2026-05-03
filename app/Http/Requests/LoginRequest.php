<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'remember' => $this->boolean('remember'),
        ]);
    }

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
            'ppr' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha' => ['required', 'integer', 'min:2', 'max:18'],
            'remember' => ['boolean'],
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
            'ppr.required'      => 'Champs obligatoires manquants.',
            'password.required' => 'Champs obligatoires manquants.',
            'captcha.required'  => 'Champs obligatoires manquants.',
            'captcha.integer'   => 'Résultat incorrect. Veuillez réessayer.',
            'captcha.min'       => 'Résultat incorrect. Veuillez réessayer.',
            'captcha.max'       => 'Résultat incorrect. Veuillez réessayer.',
        ];
    }
}
