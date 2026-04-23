<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:100', Rule::unique('roles', 'name')->ignore($this->role)],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Le nom du rôle est requis.',
            'name.unique'           => 'Ce nom de rôle existe déjà.',
            'name.max'              => 'Le nom ne peut pas dépasser 100 caractères.',
            'permissions.array'     => 'Les permissions doivent être un tableau.',
            'permissions.*.exists'  => 'Une ou plusieurs permissions sont invalides.',
        ];
    }
}
