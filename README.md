# VEKTOR

VEKTOR adalah aplikasi web untuk mengelola aset dan alur **pengajuan–persetujuan** (approval). Sistem ini mendukung beberapa peran pengguna dengan hak akses berbeda, sehingga setiap fitur hanya dapat diakses oleh role yang berwenang.

## Fitur Utama

- **Autentikasi** — login, logout, dan manajemen profil
- **Manajemen Pengguna** — CRUD user dengan role dan permission
- **Manajemen Aset** — daftar, tambah, edit, dan hapus data aset
- **Pengajuan** — buat, edit, upload dokumen, selesaikan, atau batalkan pengajuan
- **Approval** — persetujuan atau penolakan pengajuan oleh Admin/PIC
- **Access Management** — kelola role dan permission pengguna (khusus Admin)

## Role Pengguna

| Role     | Deskripsi singkat                                      |
|----------|--------------------------------------------------------|
| Admin    | Mengelola data pengguna, dan permission                |
| Security | Mengelola pengajuan dan melihat data aset              |
| PIC      | Melihat dan menyetujui/menolak pengajuan               |
| view     | Akses terbatas (hanya melihat)                         |

## Persyaratan

- PHP >= 8.2
- Composer
- Node.js & npm
- Ekstensi PHP yang dibutuhkan Laravel (OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath)

## Cara Menjalankan

### 1. Clone dan masuk ke folder proyek

```bash
git clone <url-repository>
cd VEKTOR
```

### 2. Install dependensi

```bash
composer install
npm install
```

### 3. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```
### 4. Migrasi dan seed database

```bash
php artisan migrate
php artisan db:seed
```

Perintah `db:seed` akan menjalankan seeder role, permission, dan akun admin default.

### 5. Jalankan aplikasi

**Opsi A — mode development (disarankan):**

```bash
composer dev
```

Perintah ini menjalankan server Laravel, queue worker, log viewer, dan Vite secara bersamaan.

**Opsi B — manual:**

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```
