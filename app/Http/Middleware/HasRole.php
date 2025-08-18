<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasRole
{

    public static function role(Role $role): string
    {
        return static::class . ':' . $role->value;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roleName): Response
    {
        if ($request->user()->role->name === $roleName) {
            return $next($request);
        }

        abort(403);
    }
}
