<?php

namespace App\Http\Requests\Admin\Users;

use App\Enums\Permission;
use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows(Permission::USERS_EDIT->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'role' => ['required', 'string', Rule::enum(Role::class)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['required', Rule::enum(Permission::class)],
            'password' => ['nullable', 'string', Password::defaults(), 'confirmed'],
            'reset_password_on_login' => ['required', 'boolean'],
        ];
    }
}
