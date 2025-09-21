<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordResetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'new_password' => ['required', Password::defaults(), 'confirmed'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
