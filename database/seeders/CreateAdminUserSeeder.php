<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah sudah ada Admin
        $adminExists = User::role('Admin')->exists();
        
        if ($adminExists) {
            $this->command->info('Admin user sudah ada!');
            return;
        }

        // Buat user Admin
        $admin = User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'jabatan' => 'Administrator',
            'divisi' => 'IT',
            'lokasi_penugasan' => 'BBT',
            'password' => Hash::make('admin123'),
        ]);

        // Assign role admin
        $admin->assignRole('Admin');

        $this->command->info('Admin user berhasil dibuat!');
        $this->command->info('Email: admin@gmail.com');
        $this->command->info('Password: admin123');
    }
}
