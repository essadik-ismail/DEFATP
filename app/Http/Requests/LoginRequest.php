<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'ppr' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha' => ['required', 'integer', 'min:1', 'max:10'],
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
            'ppr.required' => 'Le PPR est requis.',
            'password.required' => 'Le mot de passe est requis.',
            'captcha.required' => 'La réponse à la question de sécurité est requise.',
            'captcha.integer' => 'La réponse doit être un nombre entier.',
            'captcha.min' => 'La réponse doit être un nombre positif.',
            'captcha.max' => 'La réponse doit être un nombre entre 1 et 10.',
        ];
    }
}
