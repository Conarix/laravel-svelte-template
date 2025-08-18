<?php

namespace App\Services;

use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Role as RoleModel;
use App\Models\Permission as PermissionModel;
use App\Models\User;
use App\Utils\OptionList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getFormData(): array
    {
        $roles = OptionList::make()->addFromEnum(Role::class, 'Roles');
        $permissions = OptionList::make()->addFromEnum(Permission::class);

        return compact('roles', 'permissions');
    }

    /**
     * @param array $validated
     * @return array{user: User, token: string}
     */
    public function createUser(array $validated): array
    {
        // Get role
        $role = $this->getRoleFromRequest($validated['role']);

        // Get permissions
        $permissions = $this->getPermissionsFromRequest($validated['permissions']);

        $user = User::create([
            'role_id' => $role->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->permissions()->sync($permissions->pluck('id'));

        $token = $user->createToken('User Token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function updateUser(User $user, array $validated): User
    {
        // Get role
        $role = $this->getRoleFromRequest($validated['role']);

        // Get permissions
        $permissions = $this->getPermissionsFromRequest($validated['permissions']);

        $password = $validated['password'] != null
            ? ['password' => Hash::make($validated['password'])]
            : [];

        $user->update([
            'role_id' => $role->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            ...$password,
        ]);

        $user->permissions()->sync($permissions->pluck('id'));

        return $user->refresh();
    }

    private function getRoleFromRequest(string $roleName): ?RoleModel
    {
        return RoleModel::findByEnum(Role::from($roleName));
    }

    /**
     * @param array $permissionNames
     * @return Collection<array-key, PermissionModel>
     */
    private function getPermissionsFromRequest(array $permissionNames): Collection
    {
        return PermissionModel::findManyByEnums(
            array_map(fn ($val) => Permission::from($val), $permissionNames),
        );
    }
}
