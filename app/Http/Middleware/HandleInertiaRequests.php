<?php

namespace App\Http\Middleware;

use App\Enums\ToastType;
use App\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $toastMessages = [];

        foreach (ToastType::cases() as $toastType) {
            $toastMessages[$toastType->name] = fn () => session()->get($toastType->value);
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'environment' => app()->environment(),
            'messages' => $toastMessages,
            'auth' => [
                'user' => $request->user(),
                'permissions' => Permission::all()
                    ->map(fn (Permission $permission) => [$permission->name => $request->user()?->can($permission->name)])
                    ->collapse()
                    ->toArray(),
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
                'current_route' => $request->route()->getName(),
            ]
        ];
    }
}
