<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Dashboard
            'view-dashboard',
            
            // User Management
            'view-user',
            'create-user',
            'edit-user',
            'delete-user',
            
            // Aset Management
            'view-aset',
            'create-aset',
            'edit-aset',
            'delete-aset',
            'detail-aset',
            
            // Pengajuan
            'view-pengajuan',
            'create-pengajuan',
            'edit-pengajuan',
            'delete-pengajuan',
            'detail-pengajuan',
            'upload-pengajuan',
            'selesai-pengajuan',
            'cancel-pengajuan',
            
            // Approval
            'view-approval',
            'approve-pengajuan',
            'reject-pengajuan',
            
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $securityRole = Role::firstOrCreate(['name' => 'Security', 'guard_name' => 'web']);
        $picRole = Role::firstOrCreate(['name' => 'PIC', 'guard_name' => 'web']);
        $viewRole = Role::firstOrCreate(['name' => 'view', 'guard_name' => 'web']);

        $adminRole->syncPermissions(Permission::all());

        $securityRole->syncPermissions([
            'view-dashboard',
            'view-pengajuan',
            'detail-pengajuan',
            'upload-pengajuan',
            'create-pengajuan',
            'edit-pengajuan',
            'delete-pengajuan',
            'cancel-pengajuan',
            'selesai-pengajuan',
            'view-aset',
            'detail-aset',
        ]);

        $picRole->syncPermissions([
            'view-dashboard',
            'view-user',
            'view-aset',
            'create-aset',
            'edit-aset',
            'delete-aset',
            'detail-aset',
            'approve-pengajuan',
            'reject-pengajuan',
            'view-pengajuan',
            'detail-pengajuan',
            'View-Approval',
        ]);

        $viewRole->syncPermissions([
            'view-dashboard',
            'view-aset',
            'detail-aset',
            'view-pengajuan',
            'detail-pengajuan',
        ]);

        $this->command->info('Roles and Permissions created successfully!');
        $this->command->info('Roles: Admin, Security, PIC, view');
    }
}
