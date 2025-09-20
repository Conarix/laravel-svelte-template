<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AuditTrack\ChangeType;
use App\Enums\Permission;
use App\Enums\ToastType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UserCreateRequest;
use App\Http\Requests\Admin\Users\UserEditRequest;
use App\Models\User;
use App\Services\UserService;
use App\Traits\HandlesSearches;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    use HandlesSearches;

    public function __construct(
        private readonly UserService $userService
    ) {
        Gate::authorize(Permission::USERS_VIEW->value);
    }

    public function index(Request $request): Response
    {
        $users = User::withTrashed();

        $users = $this->applySearchConditions(
            $request,
            $users,
            ['name', 'email']
        );

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function show(User $user): Response
    {
        $user->load('auditTracks');

        if (session()->has('token')) {
            $user->setAttribute('api_token', session()->get('token'));
        }

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
        ]);
    }

    public function create(): Response
    {
        [
            'roles' => $roles,
            'permissions' => $permissions,
        ] = $this->userService->getFormData();

        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store(UserCreateRequest $request)
    {
        $validated = $request->validated();

        [
            'user' => $user,
            'token' => $token,
        ] = $this->userService->createUser($validated);

        return redirect()
            ->route('admin.users.show', [$user])
            ->with('token', $token)
            ->withToast(ToastType::Success, 'User created successfully.');
    }

    public function edit(User $user): Response
    {
        [
            'roles' => $roles,
            'permissions' => $permissions,
        ] = $this->userService->getFormData();

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function update(UserEditRequest $request, User $user)
    {
        $validated = $request->validated();

        $this->userService->updateUser($user, $validated);

        return redirect()->route('admin.users.show', [$user]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.show', [$user])
            ->withToast(ToastType::Success, 'User deleted successfully.');
    }

    public function restore(User $user)
    {
        if ($user->deleted_at) {
            $user->restore();
        }

        return redirect()->route('admin.users.show', [$user])
            ->withToast(ToastType::Success, 'User restored successfully.');
    }

    public function regenerateToken(User $user): RedirectResponse
    {
        $user->tokens()->delete();
        $token = $user->createToken('User Token')->plainTextToken;

        create_audit_track(
            $user,
            ChangeType::Update,
            [
                'API Token' => 'Regenerated API Token'
            ]
        );

        return redirect()->route('admin.users.show', [$user])
            ->with('token', $token)
            ->withToast(ToastType::Success, 'User token generated successfully.');
    }
}
