# Code Quality, Testing & Workflow Rules

**Konteks:** Standar kualitas kode, format, pengujian otomatis (Testing), dan alur kerja pengembangan DIGIDES v2.

---

## 1. Standar Penulisan Kode (Coding Style & Conventions)

1. **PSR-12 & Pint:**
   - Seluruh kode PHP wajib mematuhi standar PSR-12. Jalankan `vendor/bin/pint` atau `php artisan pint` untuk memformat kode secara otomatis sebelum committing.
   - Gunakan type hinting eksplisit untuk parameter dan return type:
     ```php
     public function generateSurat(Penduduk $penduduk, string $kodeSurat, array $payload): SuratArsip
     ```
2. **Konvensi Penamaan:**
   - **Controllers:** PascalCase dengan akhiran `Controller` (contoh: `PelayananSuratController`).
   - **Models:** PascalCase singular (contoh: `Penduduk`, `Aparatur`, `SuratArsip`).
   - **Migrations:** snake_case dengan timestamp (contoh: `create_surat_arsip_table`).
   - **Services / Actions:** PascalCase dengan akhiran `Service` atau `Action` (contoh: `DuplicateScannerService`, `GenerateSuratAction`).
   - **Blade Views:** kebab-case diorganisasi per direktori modul (contoh: `resources/views/persuratan/pelayanan-index.blade.php`).

---

## 2. Standar Seeder & Data Dummy Realistis

1. **Seeder Komprehensif:**
   - `DatabaseSeeder` wajib dapat dijalankan sekali perintah `php artisan migrate:fresh --seed` tanpa error.
   - Gunakan data dummy kependudukan Indonesia yang realistis (NIK Jawa Barat/Sukabumi dengan format valid `3202...`, nama lengkap, dusun/RT/RW nyata).
   - Pastikan terdapat seeder lembaga default: BPD, PKK, Posyandu Kasih Ibu, BUMDes Sukamaju, LPMD.

---

## 3. Strategi Pengujian (Testing Strategy)

1. **Feature Tests:**
   - Pengujian alur otentikasi & pembatasan izin RBAC.
   - Pengujian penerbitan surat: pemanggilan form, input payload, pengecekan baris baru di `surat_arsip`, serta penulisan otomatis ke `buku_ekspedisi`.
   - Pengujian scanner QR absensi: payload valid vs payload kadaluarsa/tidak valid.
2. **Unit Tests:**
   - Pengujian service deteksi NIK duplikat dan algoritma parsing nomor surat otomatis.
3. **Eksekusi Pengujian:**
   - Jalankan `php artisan test` atau `vendor/bin/phpunit` dan pastikan seluruh test suite lolos (*green*).
