<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PermissionLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function logs(Request $request)
    {
        $query = PermissionLog::with(['admin:id,nama', 'targetUser:id,nama,email'])
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('targetUser', function ($u) use ($search) {
                    $u->where('nama', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('admin', function ($a) use ($search) {
                    $a->where('nama', 'like', "%{$search}%");
                })->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('AccessManagement.logs', compact('logs'));
    }

    public function updateRole(Request $request, $id)
    {
        // 1. Cek Apakah Target User == Self?
        if ((int) $id === (int) Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengubah role akun Anda sendiri.'
            ], 403);
        }

        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $oldRole = $user->roles->first()?->name;

        // 3. Simpan Role & Otomatis Tulis Record ke Audit Log
        $user->syncRoles([$request->role]);

        PermissionLog::create([
            'admin_id'         => Auth::id(),
            'user_id'          => $user->id,
            'aksi'             => 'update_role',
            'permissions_lama' => $oldRole ? [$oldRole] : [],
            'permissions_baru' => [$request->role],
            'keterangan'       => 'Mengubah role user dari ' . ($oldRole ?? 'tidak ada') . ' menjadi ' . $request->role,
        ]);

        try {
            Notification::create([
                'user_id' => $user->id,
                'type'    => 'permission_changed',
                'title'   => 'Role Berhasil Diperbarui',
                'message' => 'Role Anda telah diubah menjadi ' . $request->role . ' oleh ' . Auth::user()->nama . '.',
                'data'    => ['role' => $request->role],
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal kirim notifikasi update role: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diupdate',
            'role'    => $user->roles->first()?->name
        ]);
    }

    public function updatePermissions(Request $request, $id)
    {
        // 1. Cek Apakah Target User == Self?
        if ((int) $id === (int) Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengubah permission akun Anda sendiri.'
            ], 403);
        }

        $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user = User::findOrFail($id);
        $permissions = $request->permissions ?? [];

        // 2. Cek Apakah Permission Memicu SOD? (Contoh: Security + Approve)
        $approvalPermissions = ['approve-pengajuan', 'reject-pengajuan'];
        $hasApprovalRequested = count(array_intersect($permissions, $approvalPermissions)) > 0;

        if ($user->hasRole('Security') && $hasApprovalRequested) {
            return response()->json([
                'success' => false,
                'message' => 'Konflik Pemisahan Tugas (SoD): User dengan role Security tidak boleh diberikan akses Approval (Approve / Reject).'
            ], 422);
        }

        // 3. Simpan Permission & Otomatis Tulis Record ke Audit Log
        $oldPermissions = $user->permissions->pluck('name')->toArray();
        $user->syncPermissions($permissions);

        $added = array_values(array_diff($permissions, $oldPermissions));
        $removed = array_values(array_diff($oldPermissions, $permissions));

        $details = [];
        if (!empty($added)) {
            $details[] = 'Menambah: ' . implode(', ', $added);
        }
        if (!empty($removed)) {
            $details[] = 'Menghapus: ' . implode(', ', $removed);
        }

        $keterangan = !empty($details) 
            ? implode(' | ', $details)
            : 'Tidak ada perubahan permission';

        PermissionLog::create([
            'admin_id'         => Auth::id(),
            'user_id'          => $user->id,
            'aksi'             => 'update_permissions',
            'permissions_lama' => $oldPermissions,
            'permissions_baru' => $permissions,
            'keterangan'       => $keterangan,
        ]);

        try {
            $notifMsg = 'Hak akses (permission) Anda telah diperbarui oleh ' . Auth::user()->nama . '.';
            if (!empty($details)) {
                $notifMsg .= ' (' . implode('; ', $details) . ')';
            }

            Notification::create([
                'user_id' => $user->id,
                'type'    => 'permission_changed',
                'title'   => 'Permission Diperbarui',
                'message' => $notifMsg,
                'data'    => [
                    'permissions' => $permissions,
                    'added'       => $added,
                    'removed'     => $removed,
                ],
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal kirim notifikasi update permission: ' . $e->getMessage());
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Permission berhasil diupdate',
            'permissions' => $user->permissions->pluck('name')
        ]);
    }

    public function getUserPermissions($id)
    {
        $user = User::with(['roles.permissions', 'permissions'])->findOrFail($id);
        $role = $user->roles->first();
        $rolePermissions = $role?->permissions->pluck('name') ?? collect();
        $logs = PermissionLog::with('admin:id,nama')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return response()->json([
            'user' => [
                'id'    => $user->id,
                'nama'  => $user->nama,
                'email' => $user->email,
            ],
            'role'             => $role?->name,
            'permissions'      => $user->permissions->pluck('name'),
            'role_permissions' => $rolePermissions->values(),
            'logs'             => $logs,
        ]);
    }

    public function copyFromRole(Request $request, $id)
    {
        // 1. Cek Apakah Target User == Self?
        if ((int) $id === (int) Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menyalin permission ke akun Anda sendiri.'
            ], 403);
        }

        $request->validate([
            'role_name' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $role = Role::findByName($request->role_name);
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        // 2. Cek Apakah Permission Memicu SOD? (Contoh: Security + Approve)
        $approvalPermissions = ['approve-pengajuan', 'reject-pengajuan'];
        $hasApprovalPermissions = count(array_intersect($rolePermissions, $approvalPermissions)) > 0;

        if ($user->hasRole('Security') && $hasApprovalPermissions) {
            return response()->json([
                'success' => false,
                'message' => 'Konflik Pemisahan Tugas (SoD): Permission dari role ' . $request->role_name . ' memicu konflik SOD (Approval) untuk user Security.'
            ], 422);
        }

        // 3. Simpan Permission & Otomatis Tulis Record ke Audit Log
        $oldPermissions = $user->permissions->pluck('name')->toArray();
        $user->syncPermissions($rolePermissions);

        PermissionLog::create([
            'admin_id'         => Auth::id(),
            'user_id'          => $user->id,
            'aksi'             => 'copy_role',
            'permissions_lama' => $oldPermissions,
            'permissions_baru' => $rolePermissions,
            'keterangan'       => 'Menyalin permission dari role ' . $request->role_name,
        ]);

        try {
            Notification::create([
                'user_id' => $user->id,
                'type'    => 'permission_changed',
                'title'   => 'Permission Disalin dari Role',
                'message' => 'Permission Anda telah disalin dari role ' . $request->role_name . ' oleh ' . Auth::user()->nama . '.',
                'data'    => ['role_name' => $request->role_name],
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal kirim notifikasi copy role: ' . $e->getMessage());
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Permission berhasil di-copy dari role',
            'permissions' => $user->permissions->pluck('name')
        ]);
    }
}
