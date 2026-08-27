# Plan 01: Core Infrastructure, Auth & RBAC (Fase 1 - Prioritas P0)

**Modul:** Infrastruktur Dasar, Autentikasi, Hak Akses Granular, Profil Desa, Audit Trail & Cadangan Database  
**Referensi PRD:** Section 4, 5.7, 6.1, 8 (Fase 1)

---

## 1. Tujuan & Ruang Lingkup
Membangun pondasi arsitektur aplikasi Laravel yang aman, terstandarisasi, dan terisolasi khusus untuk staf kantor desa.

### Komponen Utama:
1. **Autentikasi & RBAC:**
   - Multi-role via `spatie/laravel-permission`: **Admin Desa** (Superadmin) dan **Staff Desa** (Operational User).
   - Middleware proteksi rute berbasis izin per modul (misal: `permission:kependudukan.view`, `permission:keuangan.manage`).
2. **Manajemen Pengguna (User Management):**
   - CRUD Staff Desa dengan opsi penetapan hak akses spesifik.
   - Fitur aktivasi/nonaktif akun dan reset kata sandi dengan Bcrypt cost 12.
3. **Profil & Identitas Desa:**
   - Pengaturan Nama Desa, Kode Desa, Kecamatan, Kabupaten, Logo Resmi, Informasi Kepala Desa (Nama, NIK, NIP).
4. **Audit Trail (Activity Log):**
   - Integrasi `spatie/laravel-activitylog` untuk merekam aksi Create, Update, Delete, Login, Export, dan Backup.
5. **Database Backup & Recovery:**
   - Pencadangan manual / terjadwal database MySQL ke penyimpanan lokal dengan opsi unduh dan verifikasi integritas restore.

---

## 2. Struktur Database & Migrasi

### Tabel `users`
- `id` (bigint, PK)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `phone` (string, nullable)
- `avatar_path` (string, nullable)
- `is_active` (boolean, default true)
- `last_login_at` (timestamp, nullable)
- `created_at`, `updated_at`

### Tabel `desa_profile`
- `id` (bigint, PK)
- `nama_desa` (string)
- `kode_desa` (string, nullable)
- `kecamatan` (string)
- `kabupaten` (string)
- `provinsi` (string)
- `kode_pos` (string, nullable)
- `alamat_kantor` (text)
- `email_desa` (string, nullable)
- `telepon_desa` (string, nullable)
- `website` (string, nullable)
- `logo_path` (string, nullable)
- `nama_kades` (string)
- `nip_kades` (string, nullable)
- `nik_kades` (string, nullable)
- `created_at`, `updated_at`

### Tabel `backup_records`
- `id` (bigint, PK)
- `user_id` (foreignId -> users.id)
- `filename` (string)
- `file_path` (string)
- `size_bytes` (bigint)
- `status` (enum: success, failed)
- `created_at`

---

## 3. Matriks Permissions (Spatie)

```php
$permissions = [
    // Desa Profile
    'desa.view', 'desa.update',
    // User & RBAC
    'user.view', 'user.create', 'user.edit', 'user.delete',
    // Kependudukan
    'kependudukan.view', 'kependudukan.create', 'kependudukan.edit', 'kependudukan.delete', 'kependudukan.verify',
    // Persuratan
    'persuratan.view', 'persuratan.create', 'persuratan.print',
    // Kelembagaan
    'kelembagaan.view', 'kelembagaan.manage_master', 'kelembagaan.edit_content',
    // Absensi
    'absensi.scan', 'absensi.override', 'absensi.rekap',
    // Administrasi Umum
    'administrasi.view', 'administrasi.manage',
    // Keuangan
    'keuangan.view', 'keuangan.manage',
    // Pembangunan
    'pembangunan.view', 'pembangunan.manage',
    // Sistem & Audit
    'audit.view', 'backup.manage'
];
```

---

## 4. Rincian Implementasi Teknis

1. **Install Dependencies:**
   ```bash
   composer require spatie/laravel-permission spatie/laravel-activitylog spatie/laravel-backup
   ```
2. **Seeders Inti:**
   - `RoleAndPermissionSeeder`: Mendaftarkan role `Admin Desa` dan `Staff Desa` beserta matriks permissions.
   - `DesaProfileSeeder`: Memuat profil default desa awal.
   - `SuperAdminSeeder`: Akun admin default (`admin@desa.id`).
3. **Form Request & Controller:**
   - `App\Http\Controllers\Admin\UserController`
   - `App\Http\Controllers\Admin\DesaProfileController`
   - `App\Http\Controllers\Admin\ActivityLogController`
   - `App\Http\Controllers\Admin\BackupController`
4. **UI View & Komponen:**
   - Tampilan form edit profil desa dengan upload logo langsung.
   - Tabel manajemen staf dengan modal toggle status aktif & assign permissions visual.

---

## 5. Rencana Pengujian (Verification Plan)

- [ ] Jalankan `php artisan migrate --seed` dan verifikasi seluruh tabel terbuat.
- [ ] Pengujian autentikasi: Login sebagai Admin vs Login sebagai Staff tanpa hak tertentu.
- [ ] Cek proteksi rute middleware: Pastikan staff yang dibatasi tidak bisa membuka halaman pengaturan sistem.
- [ ] Buat satu data uji dan cek tabel `activity_log` untuk memverifikasi pencatatan otomatis.
