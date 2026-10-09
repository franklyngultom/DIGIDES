# Kebijakan Keamanan, Privasi, Retensi Data, dan Pemulihan (DIGIDES v3.0)

Dokumen ini mendefinisikan standar keamanan, privasi data warga, kebijakan retensi dokumen, serta prosedur pencadangan dan pemulihan data untuk sistem DIGIDES.

---

## 1. Arsitektur Otorisasi dan Kontrol Akses (RBAC)

### 1.1 Pemisahan Hak Akses
1. **Pengunjung Publik**:
   - Hanya dapat mengakses profil desa, berita publik, katalog informasi layanan surat, kontak kantor desa, dan lacak status surat publik.
   - Tidak memiliki akses ke data kependudukan, dashboard internal, maupun dokumen privat pemohon.
2. **Masyarakat (Warga Pemohon)**:
   - Dibatasi secara ketat hanya pada data miliknya sendiri (`isOwnedBy($user)` / IDOR protection).
   - Mengunggah dokumen persyaratan ke storage privat (`Storage::disk('private')`).
   - Menerima notifikasi status permohonan melalui tabel notifikasi in-app akun tanpa mengekspos NIK/KK.
3. **Staff Desa**:
   - Memeriksa kelengkapan berkas, memperbarui status antrean, meminta revisi/perbaikan berkas, dan menerbitkan surat resmi sesuai izin `persuratan.view` dan `persuratan.create`.
4. **Admin Desa**:
   - Mengelola konfigurasi desa, manajemen aparatur, hak akses peran, audit log, dan eksekusi pencadangan (backup) database.

---

## 2. Penguatan Keamanan & Pencegahan Kerentanan

### 2.1 Perlindungan IDOR (Insecure Direct Object Reference)
- Setiap aksi unduh berkas privat (`downloadDokumen`) dan unduh surat hasil terbit (`downloadSurat`) memvalidasi kepemilikan data (`$pengajuan->isOwnedBy($user)`) atau kepemilikan izin petugas (`persuratan.view`).
- Akses notifikasi di-scope langsung ke `$user->notifications()` sehingga pengguna lain tidak dapat membaca atau menandai notifikasi milik warga lain.

### 2.2 Pencegahan Kebocoran Data Pribadi (Privacy by Design)
- Notifikasi pembaruan status ke akun warga (`StatusPengajuanNotification`) hanya memuat: nomor registrasi, nama layanan, status terkini, dan instruksi petugas.
- NIK, Nomor KK, kata sandi, maupun dokumen sensitif **dilarang keras** disertakan dalam payload notifikasi publik.
- Nomor Induk Kependudukan (NIK) di halaman depan disamarkan (*masked NIK*) untuk melindungi privasi warga.

### 2.3 Validasi Berkas & Perlindungan Unggah File
- Dokumen pendukung pengajuan dibatasi maksimal 5 berkas per pengajuan.
- Setiap berkas dibatasi maksimal 5 MB (`max:5120`).
- Validasi ketat tipe MIME dan ekstensi yang diizinkan: `pdf`, `jpg`, `jpeg`, `png`.
- Berkas berbahaya (seperti `.php`, `.exe`, `.sh`, `.phtml`) ditolak langsung pada lapisan validasi server.
- Seluruh dokumen pemohon disimpan di dalam disk privat (`storage/app/private/`) yang tidak dapat diakses langsung via URL web publik.

### 2.4 Pembatasan Laju Permintaan (Rate Limiting)
- Endpoint autentikasi login dilindungi oleh `RateLimiter` (maksimal 5 percobaan sebelum dikunci/lockout).
- Endpoint pelacakan surat publik (`/lacak-surat`) dibatasi dengan middleware `throttle:60,1` untuk mencegah scraping atau enumerasi nomor surat.
- Endpoint pengiriman permohonan online (`/masyarakat/pengajuan`) dibatasi dengan middleware `throttle:20,1` untuk mencegah spamming transaksi.

### 2.5 Keamanan Audit Log & Kredensial
- Password akun selalu di-hash menggunakan algoritma Bcrypt/Argon2.
- Field `password` dan `remember_token` masuk ke dalam `$hidden` model dan dikecualikan secara eksplisit dari Spatie Activity Log.
- Log aktivitas hanya mencatat aktor, waktu, nomor pengajuan, dan deskripsi tindakan administratif.

---

## 3. Kebijakan Retensi Data

| Kategori Data | Lokasi Penyimpanan | Periode Retensi Aktif | Tindakan Pasca Retensi |
|---|---|---|---|
| **Akun & Profil Warga** | Basis Data (`users`, `citizen_profiles`) | Selama akun aktif | Diarsipkan jika nonaktif > 3 tahun |
| **Pengajuan Surat Online** | Basis Data (`pengajuan_surat`, `pengajuan_surat_logs`) | 5 Tahun sejak permohonan selesai/ditolak | Dipindahkan ke cold archive database |
| **Dokumen Lampiran Warga** | Disk Privat (`storage/app/private/pengajuan/`) | 1 Tahun pasca penerbitan surat | Dihapus atau dipindahkan ke arsip dingin berkala |
| **Surat Resmi & Arsip Persuratan** | Basis Data (`surat_arsips`, `buku_ekspedisis`, `buku_agendas`) & PDF lokal | Permanen / Selaras aturan kearsipan desa | Disimpan permanen untuk pembukuan register desa |
| **Notifikasi Akun Warga** | Basis Data (`notifications`) | 6 Bulan sejak status dibaca (`read_at`) | Dibersihkan via batch prune command berkala |
| **Log Aktivitas (Audit Log)** | Basis Data (`activity_log`) | Minimal 2 Tahun | Diekspor ke cold storage log |

---

## 4. Prosedur Pencadangan (Backup) dan Pemulihan (Disaster Recovery)

### 4.1 Mekanisme Pencadangan Database
- DIGIDES dilengkapi dengan layanan bawaan `App\Services\BackupService` dan modul `Admin\BackupController`.
- Setiap pencadangan menghasilkan berkas snapshot utuh berformat `.sql` atau `.sqlite` dengan nama berstempel waktu (`digides_backup_YYYY-MM-DD_His.sql`).
- Riwayat ukuran berkas, status, waktu pembuatan, dan operator pelaksana dicatat dalam tabel `backup_records`.

### 4.2 Jadwal & Penyimpanan Cadangan
1. **Cadangan Harian (Daily Snapshot)**: Dijalankan otomatis setiap malam hari di luar jam kerja aktif desa (pukul 23:00).
2. **Cadangan Sebelum Pembaruan Sistem (Pre-Deployment)**: Wajib dieksekusi oleh Administrator Desa sebelum menerapkan migration skema atau rilis baru.
3. **Penyimpanan Offsite**: Berkas cadangan disalin secara berkala ke media penyimpanan terpisah (cloud bucket terenkripsi / media penyimpanan eksternal kantor desa).

### 4.3 Prosedur Pemulihan Data (Restoration / Recovery)
1. **Identifikasi Titik Pulih (Point in Time)**: Pilih berkas cadangan terakhir yang valid dari daftar cadangan sukses di menu Administrator.
2. **Penghentian Akses Sementara**: Aktifkan mode pemeliharaan aplikasi:
   ```bash
   php artisan down --message="Pemulihan basis data sedang berlangsung. Harap tunggu."
   ```
3. **Import Basis Data**:
   - Untuk database MySQL / MariaDB:
     ```bash
     mysql -u [username] -p [database_name] < storage/app/backups/[nama_backup].sql
     ```
   - Untuk database SQLite:
     Salin berkas cadangan ke jalur database aktif:
     ```bash
     copy storage\app\backups\[nama_backup].sqlite database\database.sqlite
     ```
4. **Verifikasi Integritas**: Jalankan uji konektivitas dan periksa konsistensi tabel penting (`users`, `penduduks`, `surat_arsips`, `pengajuan_surat`).
5. **Aktivasi Kembali**: Nonaktifkan mode pemeliharaan:
   ```bash
   php artisan up
   ```
6. **Pencatatan Insiden**: Catat tanggal, alasan pemulihan, dan hasil verifikasi pada log administrasi desa.
