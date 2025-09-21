<?php

namespace App\Http\Requests\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RolePermissionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role_permissions' => ['required', 'array'],
            'role_permissions.*' => ['nullable', 'array'],
            'role_permissions.*.*' => ['required', 'integer', Rule::exists(Permission::class, 'id')],

            'role_permissions_keys' => ['required', 'array'],
            'role_permissions_keys.*' => ['required', 'integer', Rule::exists(Role::class, 'id')],
        ];
    }

    public function authorize(): bool
    {
        return Gate::allows(\App\Enums\Permission::PERMISSIONS_EDIT->value);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'role_permissions_keys' => array_keys($this->get('role_permissions', []))
        ]);
    }
}
