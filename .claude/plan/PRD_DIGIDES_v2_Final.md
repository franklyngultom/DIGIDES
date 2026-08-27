# PRD — DIGIDES (Digitalisasi Administrasi dan Pelayanan Desa)

**Versi:** 2.0 (Final Blueprint)  
**Status:** Disetujui / Siap Pengkodean  
**Tanggal:** 27 Agustus 2026  
**Target Framework:** Laravel (PHP 8.2+)  
**Fokus Pengguna:** Admin Desa & Staff Desa (Internal Workspace)  

---

## 1. Ringkasan Produk

**DIGIDES** adalah sistem informasi digitalisasi administrasi internal dan operasional pelayanan kantor desa. Sistem ini dirancang untuk memodernisasi seluruh buku register administrasi desa, tata kelola kependudukan, keuangan, pembangunan, kelembagaan desa, serta otomatisasi layanan persuratan *walk-in* dan pencatatan absensi aparatur desa.

Berdasarkan hasil evaluasi dan finalisasi kebutuhan:
1. **Cakupan Akses Terisolasi Internal:** Sistem **hanya** digunakan oleh **Admin Desa** dan **Staff Desa**. Seluruh portal publik/masyarakat *self-service* dikeluarkan dari lingkup proyek ini untuk menjamin efisiensi, fokus operasional internal, dan keamanan data kependudukan.
2. **Dynamic Institution Engine:** Seluruh kelembagaan desa (BPD, PKK, Posyandu, BUMDes, Koperasi, LPMD, dll.) dikelola secara dinamis dengan 4 sub-fungsi terstandar per lembaga.
3. **Otomatisasi Persuratan Desk-Service:** Layanan persuratan dilakukan langsung oleh Staff saat warga datang ke kantor desa (*walk-in service*), yang secara otomatis terhubung dengan modul kependudukan dan pencatatan **Buku Ekspedisi/Agenda**.

---

## 2. Latar Belakang & Pembaharuan Scope

Migrasi dari sistem legacy ke framework **Laravel** bukan hanya sekadar refactoring kode dasar, melainkan penataan ulang arsitektur sistem secara menyeluruh agar:

- **RBAC Terstruktur:** Pengelolaan hak akses yang fleksibel dan teratur antara Admin Desa dan Staff Operasional.
- **Standarisasi Fitur Generik:** Penataan fitur dasar tabel seperti filter berbasis tahun, utilitas pratinjau dokumen PDF (*Lihat File/Berkas*), tombol cetak langsung (*Print*), serta proteksi kerahasiaan data kependudukan.
- **Pencatatan Otomatis (Audit Trail):** Semua aksi *Create, Update, Delete* terekam dengan jelas untuk akuntabilitas internal.
- **Kemudahan Pemeliharaan:** Struktur arsitektur modular yang memudahkan pengujian (*testing*), pembaruan, serta backup & recovery data secara berkala.

---

## 3. Problem Statement

Beberapa permasalahan utama sistem lama yang diselesaikan pada versi 2.0 ini:

1. **Struktur Role Tidak Polaris:** Tidak ada pembatasan yang jelas antara hak akses pengawas (Admin) dan pelaksana harian (Staff).
2. **Resiko NIK Ganda & Keamanan Data:** Belum ada perangkat otomatis pemindai duplikasi NIK dan penyembunyian data sensitif di layar publik/monitor pelayanan.
3. **Kekakuan Modul Kelembagaan:** Setiap ada lembaga desa baru (misal Posyandu tambahan atau Kelompok Tani), sistem lama harus mengubah kode program secara manual.
4. **Alur Persuratan Terpisah:** Pembuatan surat warga belum terhubung langsung ke Buku Ekspedisi/Agenda Persuratan Umum.
5. **Absensi Manual & Kehadiran Aparat:** Membutuhkan sistem pencatatan presensi yang cepat (QR Scan browser-based) dan fleksibel (manual override).

---

## 4. Target User & Role Architecture (RBAC)

Aplikasi ini menggunakan model **Role-Based Access Control (RBAC)** internal berbasis paket `spatie/laravel-permission`.

### 4.1 Admin Desa (Superadmin)
- **Akses:** Akses penuh (*Full Control*) ke seluruh modul sistem.
- **Tanggung Jawab:**
  - Pengelolaan data master dan profil desa.
  - Manajemen akun pengguna (Admin & Staff) dan pembagian *permissions*.
  - Pemantauan *Activity Log* (Audit Trail).
  - Eksekusi Backup & Restore database sistem.
  - Konfigurasi master Kelembagaan Desa.

### 4.2 Staff Desa (Operational User)
- **Akses:** Berbasis izin (*Permission-based*) per-modul atau per-bidang tugas.
- **Contoh Profil Staff:**
  - **Staff Kependudukan:** Akses Modul Penduduk, Verifikasi Berkas, & Penerbitan Surat.
  - **Staff Keuangan:** Akses Modul Keuangan (APB Desa, RAB, Buku Kas, Bank Desa).
  - **Staff Pembangunan:** Akses Modul Pembangunan (RKP Desa, Hasil Pembangunan).
  - **Staff Sekretariat:** Akses Modul Umum (Buku Peraturan, Keputusan, Agenda, Ekspedisi) & Modul Kelembagaan.

### Matriks Hak Akses Ringkas

| Fitur / Modul | Admin Desa | Staff Desa |
|---|:---:|:---:|
| Login & Dashboard | Ya | Ya |
| Profil Desa | Kelola | Lihat |
| Manajemen User & Hak Akses | Kelola | Tidak |
| Modul Penduduk (CRUD & Cek NIK) | Kelola | Sesuai Permission |
| Penerbitan Surat Otomatis | Kelola | Sesuai Permission |
| Modul Umum (Buku Register) | Kelola | Sesuai Permission |
| Modul Keuangan (APBDes & Kas) | Kelola | Sesuai Permission |
| Modul Pembangunan | Kelola | Sesuai Permission |
| Modul Kelembagaan (Dynamic Engine) | Kelola Master & Isi | Kelola Isi Sesuai Hak Akses |
| Absensi Aparat (Scan & Override) | Kelola & Rekap | Gunakan / Rekap |
| Backup & Restore Data | Kelola | Tidak |
| Audit Trail / Log Aktivitas | Lihat / Kelola | Tidak |

---

## 5. Spesifikasi Detail Modul Aplikasi

### 5.1 Modul Administrasi Umum
Menampung seluruh register buku administrasi umum desa dengan utilitas terstandarisasi.

- **Daftar Buku Register:**
  1. Buku Peraturan di Desa
  2. Buku Keputusan Kepala Desa
  3. Buku Inventaris dan Kekayaan Desa
  4. Buku Anggaran Pemerintah Desa
  5. Buku Tanah Kas Desa & Buku Tanah di Desa
  6. Buku Agenda (Surat Masuk & Keluar)
  7. Buku Ekspedisi
  8. Buku Lembaran Desa & Berita Desa

- **Fitur Utilitas Wajib pada Modul Umum:**
  - **Filter Tahun:** Dropdown filter cepat berbasis tahun dokumen/anggaran.
  - **Pencarian & Pagination:** Instant live search & pagination server-side.
  - **Pratinjau File PDF (`Lihat File`):** Modal/viewer untuk melihat dokumen fisik PDF terunggah.
  - **Cetak Rekap (`Print`):** Ekspor/cetak rekap data yang sedang difilter dalam format PDF/Print-friendly.

---

### 5.2 Modul Kependudukan
Pusat data kependudukan desa dengan fitur perlindungan kerahasiaan data dan validasi NIK.

- **Daftar Buku & Fitur Kependudukan:**
  1. Buku Induk Penduduk
  2. Klasifikasi Data Penduduk
  3. Buku Mutasi Penduduk Desa
  4. Buku Rekapitulasi Jumlah Penduduk
  5. Buku Penduduk Sementara
  6. Buku KTP & Buku KK

- **Fitur Operasional Khusus (Refined Features):**
  - **Cek NIK Terduplikasi:** Perangkat utilitas sekali klik untuk memindai basis data dan mendeteksi adanya NIK ganda/inkonsisten.
  - **Sembunyikan Data (Privacy Mode Toggle):** Tombol *switch/toggle* pada header tabel untuk menyamarkan/menyembunyikan NIK dan nomor kontak saat layar dihadapkan ke warga.
  - **Pencatatan Sumber Data:** Labeling asal-usul data kependudukan (*Prodeskel*, Input Manual, Migrasi DB Legacy).
  - **Manajemen Berkas Pendukung (`Lihat Berkas`):** Pengelolaan lampiran digital dokumen warga (Scan KTP, KK, Akta Lahir, Surat Nikah).

---

### 5.3 Modul Pelayanan Persuratan (Walk-In Service)
Fitur integrasi operasional pelayanan langsung di kantor desa.

- **Alur Pelayanan:**
  1. Warga datang membawa berkas persyarakatan.
  2. Staff mencari data warga berbasis NIK di Modul Penduduk.
  3. Staff memilih jenis surat (Surat Keterangan Domisili, SKU, SKTM, Pengantar SKCK, Surat Keterangan Nikah, dll.).
  4. Sistem melakukan auto-fill data diri warga ke dalam template surat.
  5. Staff melengkapi variabel khusus surat dan menekan *Generate*.
  6. Sistem menerbitkan PDF Surat Resmi siap cetak.
  7. **Integrasi Ekspedisi:** Nomor registrasi surat otomatis terinput ke **Buku Ekspedisi/Agenda (Modul Umum)** tanpa input manual ulang.

---

### 5.4 Modul Keuangan Desa
Pencatatan transaksi dan pelaporan keuangan desa sesuai standar APBDes.

- **Daftar Buku Keuangan:**
  1. Buku APB Desa
  2. Buku Rencana Anggaran Biaya (RAB)
  3. Buku Kas Pembantu Kegiatan
  4. Buku Kas Umum
  5. Buku Kas Pembantu (Pajak/Lainnya)
  6. Buku Bank Desa

- **Standardisasi Transaksi:** Tanggal, Jenis (Masuk/Keluar), Nominal, Kode Rekening/Sumber Dana, Uraian/Keterangan, Upload Bukti Vouching/Nota PDF, User Recorder & Timestamp.

---

### 5.5 Modul Pembangunan
Pengelolaan perencanaan dan evaluasi fisik pembangunan desa.

- **Daftar Buku:**
  1. Buku Rencana Kerja Pembangunan (RKP Desa)
  2. Buku Kegiatan Pembangunan
  3. Buku Inventaris Hasil-Hasil Pembangunan
  4. Buku Kader Pemberdayaan Masyarakat

---

### 5.6 Modul Kelembagaan Dinamis (Dynamic Institution Engine)
Arsitektur kelembagaan fleksibel yang memungkinkan penambahan lembaga baru tanpa mengubah struktur kode.

- **Navigasi Submenu Dinamis:** Support penomoran halaman menu kelembagaan (*Halaman 1, Halaman 2*) dan menu **Pengaturan Kelembagaan** untuk pendaftaran master lembaga baru (BPD, PKK, Posyandu Kasih Ibu, Posyandu ROSE, BUMDes, Koperasi Merah Putih, LPMD, Karang Taruna, dll.).
- **Standardisasi 4 Sub-Fungsi per Lembaga:**
  Setiap lembaga yang dibuat oleh Admin otomatis memiliki 4 ruang kerja:
  1. **Data Pengurus / Anggota (Master Data):** Terintegrasi ke NIK Penduduk, Jabatan, Periode SK, dan File SK Pengangkatan.
  2. **Buku Keputusan:** Register Keputusan Internal Lembaga (Nomor, Tanggal, Tentang, File Lampiran).
  3. **Buku Kegiatan:** Pencatatan Aktivitas Lembaga (Nama Kegiatan, Pelaksana, Tanggal, Uraian, Lokasi).
  4. **Buku Agenda:** Persuratan & Agenda Kerja Internal Lembaga (Surat Masuk/Keluar).

---

### 5.7 Modul Sistem, Absensi & Operasional

#### A. Absensi Aparat Desa
- **Metode Absensi:** Pemindaian QR Code (menggunakan kamera browser laptop/webcam via `html5-qrcode`) atau Input Kode Kartu / Manual Override oleh Admin/Staff.
- **Master QR Aparat:** Kartu QR Unik berstempel token terenkripsi per aparatur.
- **Monitoring & Status:** Status otomatis (Hadir, Terlambat, Belum Hadir) berdasarkan pengaturan jam masuk/pulang kantor desa.
- **Rekapitulasi:** Rekap harian/bulanan dengan filter status dan tombol ekspor.

#### B. Manajemen Pengguna & RBAC
- CRUD User (Admin & Staff).
- Assign Roles & Permissions granular.
- Reset Password, Hashing Bcrypt/Argon2, dan Penguncian Akun Nonaktif.

#### C. Pengaturan Profil Desa
- Pengelolaan identitas desa: Nama Desa/Kepenghuluan, Kecamatan, Kabupaten, Kode Pos, Alamat Kantor, Email, Website, Logo Desa, serta Nama & NIP/NIK Kepala Desa.

#### D. Backup & Restore Data
- Backup otomatis/manual basis data MySQL (Export SQL/ZIP).
- Modul Pulihkan Cadangan (*Restore*) dengan verifikasi struktur file dan konfirmasi berlapis.

#### E. Audit Trail (Log Aktivitas)
- Pencatatan otomatis setiap aksi krusial: *Siapa (User ID), Aksi (Create/Update/Delete/Restore), Modul, Timestamp, dan perubahan delta data*.

---

## 6. Rekomendasi Tech Stack & Arsitektur Laravel

### 6.1 Technology Stack

| Layer | Teknology / Package | Deskripsi / Alasan |
|---|---|---|
| **Backend Framework** | Laravel 10.x / 11.x | Robust ORM, Security, Routing, & Migration System |
| **Admin UI Framework** | Filament v3 / Inertia.js + Vue 3 | Filament v3 direkomendasikan untuk akselerasi pembuatan Form CRUD & Table kompleks |
| **Authentication & RBAC** | `spatie/laravel-permission` | Standar industri manajemen Role & Permission granular |
| **PDF Generator** | `barryvdh/laravel-dompdf` / WeasyPrint | Generator template surat resmi & cetak rekap buku register |
| **Audit Log** | `spatie/laravel-activitylog` | Otomatisasi pencatatan rekam jejak aksi pengguna |
| **Database Backup** | `spatie/laravel-backup` | Pembuatan backup SQL & Storage secara aman |
| **QR Scanner Front-end** | `html5-qrcode` (JS) + `simplesoftwareio/simple-qrcode` | Pemindaian QR browser tanpa hardware pembaca khusus |

---

### 6.2 Skema Relasi Basis Data Utama (Database Schema Concept)

```text
users (id, name, email, password, status, created_at, ...)
roles (id, name, guard_name, ...)
permissions (id, name, guard_name, ...)
model_has_roles / model_has_permissions

desa_profile (id, nama_desa, kecamatan, kabupaten, alamat, logo, kades_name, kades_nip, ...)

penduduk (id, nik, nama_lengkap, tempat_lahir, tgl_lahir, jenis_kelamin, alamat, sumber_data, status_mutasi, ...)
dokumen_penduduk (id, penduduk_id, jenis_dokumen, file_path, ...)

institutions (id, nama_lembaga, slug, kategori, created_at, ...)
institution_members (id, institution_id, penduduk_id, jabatan, no_sk, file_sk, status_aktif, ...)
institution_decisions (id, institution_id, no_keputusan, tgl_keputusan, tentang, file_pdf, ...)
institution_activities (id, institution_id, nama_kegiatan, tgl_kegiatan, pelaksana, uraian, ...)
institution_agendas (id, institution_id, jenis_surat, no_surat, tgl_surat, perihal, file_pdf, ...)

surat_generated (id, no_surat, jenis_surat, penduduk_id, user_id, payload_json, file_pdf, created_at, ...)
buku_ekspedisi (id, no_urut, tgl_pengiriman, no_surat, tgl_surat, perihal, tujuan, generated_surat_id, ...)

aparatur (id, penduduk_id, jabatan, qr_code_token, jam_masuk, jam_keluar, status_aktif, ...)
absensi (id, aparatur_id, tanggal, jam_scanned, status, metode_absensi, ...)

activity_logs (id, user_id, log_name, description, subject_type, subject_id, properties, created_at, ...)
backup_logs (id, user_id, file_name, file_size, status, created_at, ...)
```

---

## 7. Non-Functional Requirements (NFR)

1. **Keamanan (Security):**
   - Hashing password menggunakan Bcrypt (cost level min. 10).
   - Server-side validation pada seluruh Request Form.
   - Protection terhadap CSRF, XSS, dan SQL Injection (Eloquent ORM strict scoping).
   - Fitur Sembunyikan Data pada tampilan monitor meja pelayanan.

2. **Performa & Optimalisasi Query:**
   - Waktu muat halaman $< 1.5$ detik pada jaringan lokal/cloud standar.
   - Penerapan database indexing pada kolom `nik`, `tahun`, `created_at`, dan `institution_id`.
   - Server-side pagination (10, 25, 50, 100 rows per page) untuk tabel berukuran besar.

3. **Usability & Responsif:**
   - Visual UI bersih dengan skema warna yang konsisten (Desaturated Forest Green & Slate Grey).
   - Tata letak tabel responsif dengan scroll horizontal halus untuk layar beresolusi minimal $1280 	imes 720$.

---

## 8. Roadmap Pengembangan & Prioritas MVP

### Prioritas P0 (Fase 1 — Core Infrastructure & Administration)
- Setup Laravel 11 + Filament v3 / Inertia.
- Authentication & Setup Role (Admin & Staff).
- Modul Profil Desa & Manajemen User.
- Modul Penduduk (CRUD, Cek NIK Duplikat, Berkas Pendukung, Sembunyikan Data).
- Modul Persuratan Walk-In + Integrasi Buku Ekspedisi.
- Engine Kelembagaan Dinamis (Master Lembaga & 4 Sub-Fungsi).
- Absensi Aparat (QR Scanner & Rekap).
- Activity Log & Backup System.

### Prioritas P1 (Fase 2 — Modul Spesifik & Keuangan)
- Modul Administrasi Umum Lengkap (Buku Peraturan, Keputusan, Kas Desa, dll.).
- Modul Keuangan (APBDes, RAB, Kas Umum, Bank Desa).
- Modul Pembangunan (RKP Desa, Hasil Pembangunan).

### Prioritas P2 (Fase 3 — Polishing & Analytics)
- Dashboard Analitik & Visualisasi Grafik Stunting/Mutasi Kependudukan.
- Export PDF/Excel lanjutan untuk laporan berkala kecamatan.

---

## 9. Kesimpulan & Status Dokumen

Dokumen PRD Versi 2.0 ini telah **dikunci (locked)** dan mencakup seluruh kebutuhan fungsional terkini, termasuk penyesuaian role internal (Admin/Staff), *Dynamic Institution Engine*, validasi data kependudukan, serta otomatisasi persuratan desk-service. Dokumen ini menjadi panduan tunggal bagi tim pengembang software dalam merancang basis data dan mengimplementasikan aplikasi berbasis **Laravel**.
