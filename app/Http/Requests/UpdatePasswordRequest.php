<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password', 'exclude'],
            'new_password' => ['required', Password::default(), 'confirmed'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
