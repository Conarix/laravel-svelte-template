<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Auth/Login');
    }

    public function attemptLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember_me' => ['required', 'boolean'],
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $validated['remember_me'])) {
            throw ValidationException::withMessages([
                'email' => "Those credentials do not match our records.",
            ]);
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
