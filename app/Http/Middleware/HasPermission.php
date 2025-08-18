<?php

namespace App\Http\Middleware;

use App\Enums\Permission;
use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasPermission
{

    public static function permission(Permission $permission): string
    {
        return static::class . ':' . $permission->value;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permissionName): Response
    {
        if ($request->user()->can($permissionName)) {
            return $next($request);
        }

        abort(403);
    }
}
