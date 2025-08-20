<?php

use App\Enums\Permission;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\HasPermission;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::prefix('admin')->name('admin.')->group(function () {
        Route::middleware(HasPermission::permission(Permission::USERS_VIEW))->group(function () {
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('/{user}/show', [UserController::class, 'show'])->withTrashed()->name('show');

                Route::middleware(HasPermission::permission(Permission::USERS_CREATE))->group(function () {
                    Route::get('/create', [UserController::class, 'create'])->name('create');
                    Route::post('/', [UserController::class, 'store'])->name('store');
                });

                Route::middleware(HasPermission::permission(Permission::USERS_EDIT))->group(function () {
                    Route::get('/{user}/edit', [UserController::class, 'edit'])->withTrashed()->name('edit');
                    Route::put('/{user}', [UserController::class, 'update'])->withTrashed()->name('update');

                    Route::post('/{user}/token/regenerate', [UserController::class, 'regenerateToken'])->withTrashed()->name('token.regenerate');
                });

                Route::middleware(HasPermission::permission(Permission::USERS_DELETE))->group(function () {
                    Route::delete('/{user}', [UserController::class, 'destroy'])->name('delete');
                    Route::post('/{user}/restore', [UserController::class, 'restore'])->withTrashed()->name('restore');
                });
            });
        });
    });
});
