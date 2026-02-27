# Cara Membuat Akun Admin

Ada beberapa cara untuk membuat akun admin, pilih yang paling sesuai dengan kondisi Anda:

## 🎯 Opsi 1: Via Seeder (RECOMMENDED - Untuk Admin Pertama)

Jika belum ada admin sama sekali, gunakan cara ini:

### Langkah-langkah:

1. **Jalankan seeder:**
   ```bash
   php artisan db:seed --class=CreateAdminUserSeeder
   ```

2. **Login dengan kredensial default:**
   - **Email:** `admin@vektor.com`
   - **Password:** `admin123`

3. **⚠️ PENTING:** Segera ganti password setelah login pertama kali!

### Informasi Default:
- **Nama:** Administrator
- **Username:** admin
- **Email:** admin@vektor.com
- **Password:** admin123
- **Role:** admin

---

## 🎯 Opsi 2: Via Form (Jika Sudah Ada Admin)

Jika sudah ada user dengan role admin yang bisa login:

1. **Login sebagai admin**
2. Buka menu **Pengguna > Data User**
3. Klik tombol **Register** atau buka form create user
4. Isi semua field:
   - Nama Lengkap
   - Username
   - Email
   - Jabatan
   - Divisi
   - Lokasi Penugasan
   - **Role: Pilih "Admin"**
   - Password
   - Konfirmasi Password
5. Klik **Simpan User**

---

## 🎯 Opsi 3: Via Tinker (Manual)

Jika ingin membuat admin secara manual via command line:

1. **Buka tinker:**
   ```bash
   php artisan tinker
   ```

2. **Jalankan perintah berikut:**
   ```php
   use App\Models\User;
   use Illuminate\Support\Facades\Hash;

   $admin = User::create([
       'nama' => 'Nama Admin',
       'username' => 'username_admin',
       'email' => 'admin@example.com',
       'jabatan' => 'Administrator',
       'divisi' => 'IT',
       'lokasi_penugasan' => 'BBT',
       'password' => Hash::make('password_anda'),
   ]);

   $admin->assignRole('admin');
   ```

3. **Ganti data sesuai kebutuhan:**
   - `'Nama Admin'` → nama lengkap
   - `'username_admin'` → username yang diinginkan
   - `'admin@example.com'` → email yang diinginkan
   - `'password_anda'` → password yang diinginkan

---

## 🎯 Opsi 4: Assign Role ke User yang Sudah Ada

Jika sudah ada user dan ingin mengubahnya menjadi admin:

### Via Form:
1. Login sebagai admin
2. Buka menu **Pengguna > Data User**
3. Klik **Detail** pada user yang ingin diubah
4. Klik **Edit**
5. Ubah field **Role** menjadi **Admin**
6. Klik **Update User**

### Via Tinker:
```bash
php artisan tinker
```

```php
use App\Models\User;

$user = User::find(1); // Ganti 1 dengan ID user
$user->assignRole('admin');
```

---

## ✅ Verifikasi Admin Berhasil Dibuat

Setelah membuat admin, verifikasi dengan cara:

### Via Tinker:
```bash
php artisan tinker
```

```php
use App\Models\User;

// Cek semua admin
$admins = User::role('admin')->get(['id', 'nama', 'email']);
foreach($admins as $admin) {
    echo $admin->id . ' - ' . $admin->nama . ' (' . $admin->email . ')' . PHP_EOL;
}
```

### Via Login:
1. Logout dari akun saat ini (jika ada)
2. Login dengan email dan password admin yang baru dibuat
3. Pastikan semua menu muncul (User Management, Aset, Approval, dll)

---

## 🔒 Keamanan

**⚠️ PENTING:**
1. **Ganti password default** segera setelah login pertama kali
2. **Jangan share** kredensial admin ke orang yang tidak berwenang
3. **Gunakan password yang kuat** (minimal 8 karakter, kombinasi huruf, angka, simbol)
4. **Hapus seeder** atau ubah password default setelah admin pertama dibuat

---

## 🆘 Troubleshooting

### Problem: "Target class [files] does not exist"
**Solution:** Pastikan sudah menjalankan migration dan seeder permission:
```bash
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
```

### Problem: User tidak bisa login sebagai admin
**Solution:** 
1. Pastikan role sudah di-assign: `$user->assignRole('admin')`
2. Clear cache: `php artisan permission:cache-reset`
3. Cek email dan password sudah benar

### Problem: Menu tidak muncul setelah login sebagai admin
**Solution:**
1. Clear cache permission: `php artisan permission:cache-reset`
2. Logout dan login lagi
3. Pastikan user benar-benar punya role admin

---

## 📝 Catatan

- **Default password dari seeder:** `admin123` (GANTI SETELAH LOGIN!)
- **Role admin memiliki full access** ke semua fitur aplikasi
- **Hanya admin yang bisa** membuat/mengedit user lain
- **Admin bisa assign role** ke user lain (admin, security, PIC, view)

---

## 🎉 Selesai!

Setelah admin berhasil dibuat, Anda bisa:
1. Login sebagai admin
2. Membuat user lain dengan berbagai role
3. Manage semua fitur aplikasi

**Selamat menggunakan!** 🚀

