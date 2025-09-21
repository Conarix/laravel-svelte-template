<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PasswordResetMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        if ($user->reset_password_on_login) {
            Redirect::setIntendedUrl($request->fullUrl());
            return redirect()->route('password-reset.edit');
        }

        return $next($request);
    }
}
