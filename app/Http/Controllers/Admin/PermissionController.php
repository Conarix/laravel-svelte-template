<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ToastType;
use App\Http\Requests\Admin\RolePermissionsRequest;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public function edit()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return Inertia::render('Admin/Permissions/Edit', [
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    public function update(RolePermissionsRequest $request)
    {
        $validated = $request->validated();

        // Build Insert Data
        $data = [];
        foreach ($validated['role_permissions'] as $roleId => $permissions) {
            foreach ($permissions as $permissionId) {
                $data[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId
                ];
            }
        }

        try {
            DB::beginTransaction();
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withToast(ToastType::Error, "Failed to update role permissions");
        }

        try {
            DB::table('permission_role')->delete();

            DB::table('permission_role')->insert($data);
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withToast(ToastType::Error, "Failed to update role permissions");
        }

        DB::commit();

        return redirect()->back()
            ->withToast(ToastType::Success, "Role permissions updated successfully");
    }
}
