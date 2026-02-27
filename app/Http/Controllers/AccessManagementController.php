<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AccessManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['roles.permissions', 'permissions'])
            ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'Admin'))
            ->get();
        $roles = Role::with('permissions')->get();

        // Group permissions by module/menu (exclude view-log - sudah digabung ke pengajuan)
        $permissions = Permission::all()
            ->reject(fn ($p) => $p->name === 'view-log')
            ->groupBy(function ($permission) {
                $name = $permission->name;

                // Handle special cases first
                if (str_contains($name, 'approve') || str_contains($name, 'reject') || str_contains($name, 'view-approval')) {
                    return 'approval';
                }

                $parts = explode('-', $name);
                if (count($parts) >= 2) {
                    return $parts[1];
                }
                return 'other';
            });

        return view('AccessManagement.index', compact('users', 'roles', 'permissions'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $user->syncRoles([$request->role]);

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diupdate',
            'role' => $user->roles->first()?->name
        ]);
    }

    public function updatePermissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user = User::findOrFail($id);
        $permissions = $request->permissions ?? [];
        
        $user->syncPermissions($permissions);

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil diupdate',
            'permissions' => $user->permissions->pluck('name')
        ]);
    }

    public function getUserPermissions($id)
    {
        $user = User::with(['roles.permissions', 'permissions'])->findOrFail($id);
        $role = $user->roles->first();
        $rolePermissions = $role?->permissions->pluck('name') ?? collect();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
            ],
            'role' => $role?->name,
            'permissions' => $user->permissions->pluck('name'),
            'role_permissions' => $rolePermissions->values(),
        ]);
    }

    public function copyFromRole(Request $request, $id)
    {
        $request->validate([
            'role_name' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $role = Role::findByName($request->role_name);
        
        // Copy permissions from role to user
        $user->syncPermissions($role->permissions->pluck('name'));

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil di-copy dari role',
            'permissions' => $user->permissions->pluck('name')
        ]);
    }
}
