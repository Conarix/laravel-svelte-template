<?php

namespace App\Providers;

use App\Enums\ToastType;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Stringable;

/**
 * @mixin RedirectResponse
 * @mixin Stringable
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RedirectResponse::macro(
            'withToast',
            function (ToastType $type, string $message) {
                $this->with($type->value, $message);

                return $this;
            }
        );

        Stringable::macro('unfinish', function (string $suffix) {
            return $this->endsWith($suffix)
                ? $this->substr(0, -strlen($suffix))
                : $this;
        });

        // Permissions Gate
        Gate::before(function (User $user, $ability) {
            $permissions = $user->role->permissions->merge($user->permissions)->unique();

            return $permissions->contains(fn (Permission $permission) => $permission->name === $ability);
        });
    }
}
