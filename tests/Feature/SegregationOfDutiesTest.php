<?php

namespace Tests\Feature;

use App\Models\Pengajuan;
use App\Models\PermissionLog;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SegregationOfDutiesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function createUser(array $attributes = []): User
    {
        static $counter = 1;
        $i = $counter++;

        return User::create(array_merge([
            'nama'             => 'Test User ' . $i,
            'username'         => 'testuser_' . $i,
            'email'            => "user{$i}@test.com",
            'password'         => bcrypt('password'),
            'jabatan'          => 'Staff',
            'divisi'           => 'IT',
            'lokasi_penugasan' => 'Batam',
        ], $attributes));
    }

    private function createPengajuan(int $creatorId, array $attributes = []): Pengajuan
    {
        static $counter = 1;
        $i = sprintf('%03d', $counter++);

        return Pengajuan::create(array_merge([
            'kode_pengajuan'      => 'PGJ-' . $i,
            'jenis'               => 'umum',
            'perusahaan_tamu'     => 'PT Tamu',
            'perusahaan_pengutus' => 'PT Pengutus',
            'jumlah'              => '5',
            'kategori'            => 'Pekerjaan',
            'keperluan'           => 'Maintenance',
            'jam_mulai'           => '08:00',
            'jam_selesai'         => '17:00',
            'lama_berkerja'       => '9 jam',
            'identitas'           => 'KTP',
            'masa_berlaku'        => '2026-12-31',
            'creator_id'          => $creatorId,
            'status'              => 'open',
            'progress'            => 'pending',
        ], $attributes));
    }

    public function test_admin_cannot_approve_own_pengajuan(): void
    {
        $admin = $this->createUser(['nama' => 'Admin User']);
        $admin->assignRole('Admin');

        $pengajuan = $this->createPengajuan($admin->id);

        $response = $this->actingAs($admin)
            ->putJson("/approval/{$pengajuan->id}/approve");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Anda tidak dapat menyetujui pengajuan yang Anda buat sendiri.',
            ]);

        $this->assertEquals('pending', $pengajuan->fresh()->progress);
    }

    public function test_admin_cannot_reject_own_pengajuan(): void
    {
        $admin = $this->createUser(['nama' => 'Admin User']);
        $admin->assignRole('Admin');

        $pengajuan = $this->createPengajuan($admin->id);

        $response = $this->actingAs($admin)
            ->putJson("/approval/{$pengajuan->id}/reject", [
                'keterangan' => 'Tolak sendiri',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Anda tidak dapat menolak pengajuan yang Anda buat sendiri.',
            ]);

        $this->assertEquals('pending', $pengajuan->fresh()->progress);
    }

    public function test_admin_cannot_approve_any_pengajuan(): void
    {
        $admin = $this->createUser(['nama' => 'Admin User']);
        $admin->assignRole('Admin');

        $security = $this->createUser(['nama' => 'Security User']);
        $security->assignRole('Security');

        $pengajuan = $this->createPengajuan($security->id);

        $response = $this->actingAs($admin)
            ->putJson("/approval/{$pengajuan->id}/approve");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Admin tidak dapat menyetujui pengajuan. Hak persetujuan hanya dimiliki oleh PIC.',
            ]);

        $this->assertEquals('pending', $pengajuan->fresh()->progress);
    }

    public function test_pic_can_approve_security_pengajuan(): void
    {
        $security = $this->createUser(['nama' => 'Security User']);
        $security->assignRole('Security');

        $pic = $this->createUser(['nama' => 'PIC User']);
        $pic->assignRole('PIC');

        $pengajuan = $this->createPengajuan($security->id);

        $response = $this->actingAs($pic)
            ->putJson("/approval/{$pengajuan->id}/approve");

        $response->assertStatus(200)
            ->assertJson([
                'success'  => true,
                'progress' => 'approved',
            ]);

        $this->assertEquals('approved', $pengajuan->fresh()->progress);
    }

    public function test_admin_cannot_update_own_permissions_or_role(): void
    {
        $admin = $this->createUser(['nama' => 'Admin User']);
        $admin->assignRole('Admin');

        // Step 1 Flowchart: Target User == Self? -> 403 Forbidden
        $response = $this->actingAs($admin)
            ->putJson("/access-management/{$admin->id}/permissions", [
                'permissions' => ['view-user'],
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Anda tidak dapat mengubah permission akun Anda sendiri.',
            ]);
    }

    public function test_sod_conflict_blocks_security_plus_approve(): void
    {
        $admin = $this->createUser(['nama' => 'Admin User']);
        $admin->assignRole('Admin');

        $security = $this->createUser(['nama' => 'Security User']);
        $security->assignRole('Security');

        // Step 2 Flowchart: Apakah Permission Memicu SOD? (Security + Approve) -> 422 Block & Warn
        $response = $this->actingAs($admin)
            ->putJson("/access-management/{$security->id}/permissions", [
                'permissions' => ['approve-pengajuan'],
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Konflik Pemisahan Tugas (SoD): User dengan role Security tidak boleh diberikan akses Approval (Approve / Reject).',
            ]);
    }

    public function test_admin_can_assign_valid_permissions_and_audit_log_written(): void
    {
        $admin = $this->createUser(['nama' => 'Admin User']);
        $admin->assignRole('Admin');

        $security = $this->createUser(['nama' => 'Security User']);
        $security->assignRole('Security');

        // Step 3 Flowchart: Simpan Permission -> Otomatis Tulis Record ke Audit Log
        $response = $this->actingAs($admin)
            ->putJson("/access-management/{$security->id}/permissions", [
                'permissions' => ['view-user'],
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertTrue($security->fresh()->hasDirectPermission('view-user'));

        $this->assertDatabaseHas('permission_log', [
            'admin_id' => $admin->id,
            'user_id'  => $security->id,
            'aksi'     => 'update_permissions',
        ]);
    }
}
