<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class PasswordResetController extends Controller
{
    public function edit()
    {
        if (! Auth::user()->reset_password_on_login) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Auth/PasswordReset', [
            'rules' => Password::defaults()->appliedRules()
        ]);
    }

    public function update(PasswordResetRequest $request)
    {
        $validated = $request->validated();

        Auth::user()->update([
            'password' => Hash::make($validated['new_password']),
            'reset_password_on_login' => false
        ]);

        return redirect()->intended();
    }
}
