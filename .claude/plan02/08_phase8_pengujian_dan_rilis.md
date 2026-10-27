# Fase 08 — Pengujian, UAT, dan Rilis

## Tujuan

Memastikan seluruh alur publik, masyarakat, dan internal bekerja bersama sebelum dirilis.

## Checklist pengujian

- [x] Halaman publik dapat diakses tanpa login (`PublicWebsiteTest.php`, `RilisEndToEndRegressionTest.php`).
- [x] Registrasi, login, logout, dan pemulihan akun berjalan sesuai rancangan (`AuthenticationTest.php`, `RegistrationTest.php`, `CitizenAuthAndProfileTest.php`, `RilisEndToEndRegressionTest.php`).
- [x] Masyarakat tidak dapat mengakses dashboard internal (`CitizenAuthAndProfileTest.php`, `KeamananDanNotifikasiTest.php`, `RilisEndToEndRegressionTest.php`).
- [x] Masyarakat tidak dapat mengakses permohonan atau dokumen akun lain (`KeamananDanNotifikasiTest.php`, `RilisEndToEndRegressionTest.php`).
- [x] Validasi form dan upload diuji (`PengajuanAntreanTest.php`, `KeamananDanNotifikasiTest.php`, `RilisEndToEndRegressionTest.php`).
- [x] Transisi status dan riwayat diuji (`PengajuanAntreanTest.php`, `IntegrasiPersuratanTest.php`, `RilisEndToEndRegressionTest.php`).
- [x] Hak akses Staff Desa dan Admin Desa diuji (`UserManagementTest.php`, `IntegrasiPersuratanTest.php`, `RilisEndToEndRegressionTest.php`).
- [x] Integrasi generator surat diuji (`IntegrasiPersuratanTest.php`, `RilisEndToEndRegressionTest.php`).
- [x] Buku Ekspedisi/Agenda tidak menghasilkan data ganda (`IntegrasiPersuratanTest.php`, `RilisEndToEndRegressionTest.php`).
- [x] Fitur internal lama lulus uji regresi (153 tests: Kependudukan, Administrasi Umum, Keuangan, Pembangunan, Absensi/Aparatur, Dashboard, Backup, Logs).
- [x] Migration dan rollback ditinjau dengan aman (`php artisan migrate:status`, 41 migrations synced).
- [x] Backup dan pemulihan diuji di lingkungan yang sesuai (`BackupTest.php`, `kebijakan_keamanan_dan_retensi.md`, `RilisEndToEndRegressionTest.php`).
- [x] UAT & Alur Lengkap terverifikasi (`RilisEndToEndRegressionTest.php`).

## Prosedur rilis

1. **Tinjau perubahan dan konfigurasi environment:**
   - Seluruh route publik, portal warga, dan modul internal telah diverifikasi terisolasi dengan Spatie RBAC & Gates.
   - Konfigurasi private disk (`storage/app/private`) dan public disk aktif.
2. **Backup database dan dokumen sesuai prosedur:**
   - Menjalankan perintah backup atau trigger via `/admin/backup` (`App\Services\BackupService`).
   - Menyimpan snapshot sebelum deployment.
3. **Jalankan migration sesuai urutan rilis:**
   - 41 migration skema terverifikasi berjalan berurutan tanpa konflik atau data loss.
4. **Jalankan tes dan smoke test:**
   - Menjalankan `php artisan test` mencakup 153 test case dan >600 assertions dengan status 100% lulus.
5. **Pantau error log dan laporan pengguna:**
   - Monitor `storage/logs/laravel.log` serta log audit di tabel `activity_log`.
6. **Siapkan prosedur rollback yang aman:**
   - Prosedur disaster recovery dan database import didokumentasikan di `kebijakan_keamanan_dan_retensi.md`.

## Kriteria selesai

Rilis hanya dilakukan jika kriteria penerimaan utama terpenuhi, risiko diketahui, dan prosedur pemulihan tersedia.
*Status: Seluruh kriteria Fase 00 hingga Fase 08 telah selesai dan terverifikasi.*
