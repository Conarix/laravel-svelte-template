<?php

namespace App\Http\Controllers;

use App\Enums\ToastType;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function show()
    {
        $user = User::find(Auth::id());

        return Inertia::render('Account/Show', [
            'user' => $user,
            'rules' => Password::defaults()->appliedRules(),
        ]);
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Auth::user()->update([
            'password' => $validated['new_password'],
        ]);

        return redirect()->route('account.show')
            ->withToast(ToastType::Success, 'Password updated successfully.');
    }

    public function destroy()
    {
        Auth::user()->delete();
        Auth::logout();

        return redirect()->route('auth.login')
            ->withToast(ToastType::Success, 'Account deleted successfully.');
    }
}
