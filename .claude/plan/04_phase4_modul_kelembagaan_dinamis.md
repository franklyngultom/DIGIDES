# Plan 04: Modul Kelembagaan Dinamis / Dynamic Institution Engine (Fase 4 - Prioritas P0)

**Modul:** Dynamic Institution Engine, Master Lembaga Desa, 4 Sub-Fungsi Terstandar per Lembaga, Navigasi Halaman Dinamis  
**Referensi PRD:** Section 1 (Poin 2), 5.6, 6.2, 8 (Fase 1)

---

## 1. Tujuan & Ruang Lingkup
Membangun arsitektur kelembagaan desa yang modular dan fleksibel. Admin Desa dapat menambah, mengubah, atau menonaktifkan lembaga desa (BPD, PKK, Posyandu, BUMDes, Koperasi Merah Putih, LPMD, Karang Taruna, RT/RW, Kelompok Tani, dll.) secara dinamis tanpa perlu mengubah baris kode program (*zero-code new institution registration*).

### Standarisasi 4 Sub-Fungsi per Lembaga:
Setiap kali sebuah lembaga baru dibuat di sistem, lembaga tersebut **secara otomatis** mewarisi 4 sub-ruang kerja mandiri:
1. **Data Pengurus / Anggota (Master Data):** Terhubung ke NIK Penduduk, Jabatan, Nomor SK, Periode Jabatan, Status Keaktifan, dan File PDF SK Pengangkatan.
2. **Buku Keputusan Lembaga:** Register Keputusan internal lembaga (Nomor Keputusan, Tanggal, Tentang, Uraian, File PDF Lampiran).
3. **Buku Kegiatan Lembaga:** Pencatatan aktivitas & program kerja (Nama Kegiatan, Tanggal, Lokasi, Pelaksana, Sumber Dana/Anggaran, Uraian, Foto Dokumentasi).
4. **Buku Agenda Lembaga:** Tata kelola persuratan internal lembaga (Surat Masuk & Surat Keluar).

### Fitur Navigasi Dinamis:
- Dukungan penomoran menu lembaga (*Pagination Lembaga - Halaman 1, Halaman 2*) agar antarmuka sidebar/header tetap rapi dan tidak sesak jika terdapat banyak lembaga (misal 15+ Posyandu/Kelompok Masyarakat).
- Menu khusus **Pengaturan Kelembagaan** untuk Admin mengelola master data lembaga.

---

## 2. Struktur Database & Skema Relasi

### Tabel `institutions` (Master Lembaga)
- `id` (bigint, PK)
- `nama_lembaga` (string) - Contoh: "Badan Permusyawaratan Desa (BPD)", "Posyandu Kasih Ibu"
- `singkatan` (string: 20) - Contoh: "BPD", "PKK"
- `slug` (string, unique, index) - Contoh: "bpd", "posyandu-kasih-ibu"
- `kategori` (enum: 'pemerintahan', 'kemasyarakatan', 'ekonomi', 'kesehatan', 'lainnya')
- `nomor_sk_pendirian` (string, nullable)
- `tanggal_sk` (date, nullable)
- `deskripsi` (text, nullable)
- `alamat_sekretariat` (text, nullable)
- `logo_path` (string, nullable)
- `urutan` (integer, default 0)
- `is_active` (boolean, default true)
- `created_at`, `updated_at`

### Tabel `institution_members` (Sub-Fungsi 1: Pengurus & Anggota)
- `id` (bigint, PK)
- `institution_id` (foreignId -> institutions.id, onDelete cascade)
- `penduduk_id` (foreignId -> penduduk.id, onDelete cascade)
- `jabatan` (string) - Contoh: "Ketua", "Sekretaris", "Bendahara", "Anggota"
- `nomor_sk` (string)
- `tanggal_sk` (date, nullable)
- `periode_mulai` (date)
- `periode_selesai` (date, nullable)
- `file_sk_path` (string, nullable)
- `status_keaktifan` (enum: 'aktif', 'demisioner', 'diberhentikan', 'mengundurkan_diri')
- `created_at`, `updated_at`

### Tabel `institution_decisions` (Sub-Fungsi 2: Buku Keputusan)
- `id` (bigint, PK)
- `institution_id` (foreignId -> institutions.id, onDelete cascade)
- `tahun` (integer: 4, index)
- `nomor_keputusan` (string)
- `tanggal_keputusan` (date)
- `tentang` (text)
- `uraian_singkat` (text, nullable)
- `file_pdf_path` (string, nullable)
- `created_by` (foreignId -> users.id)
- `created_at`, `updated_at`

### Tabel `institution_activities` (Sub-Fungsi 3: Buku Kegiatan)
- `id` (bigint, PK)
- `institution_id` (foreignId -> institutions.id, onDelete cascade)
- `nama_kegiatan` (string)
- `tanggal_kegiatan` (date)
- `lokasi` (string)
- `pelaksana` (string)
- `anggaran` (decimal: 15,2, default 0)
- `sumber_dana` (string, nullable)
- `uraian_hasil` (text)
- `foto_dokumentasi_path` (string, nullable)
- `created_by` (foreignId -> users.id)
- `created_at`, `updated_at`

### Tabel `institution_agendas` (Sub-Fungsi 4: Buku Agenda Persuratan)
- `id` (bigint, PK)
- `institution_id` (foreignId -> institutions.id, onDelete cascade)
- `jenis_surat` (enum: 'masuk', 'keluar')
- `nomor_surat` (string)
- `tanggal_surat` (date)
- `tanggal_terima_kirim` (date)
- `pengirim_tujuan` (string)
- `perihal` (text)
- `file_pdf_path` (string, nullable)
- `created_by` (foreignId -> users.id)
- `created_at`, `updated_at`

---

## 3. Rincian Implementasi Teknis

1. **Routing Dinamis:**
   ```php
   // Master Pengaturan Lembaga (Admin Only)
   Route::resource('admin/institutions', InstitutionMasterController::class);

   // 4 Sub-Fungsi Workspace per Lembaga
   Route::prefix('kelembagaan/{institution:slug}')->name('kelembagaan.')->group(function () {
       Route::get('/', [InstitutionWorkspaceController::class, 'dashboard'])->name('dashboard');
       Route::resource('members', InstitutionMemberController::class);
       Route::resource('decisions', InstitutionDecisionController::class);
       Route::resource('activities', InstitutionActivityController::class);
       Route::resource('agendas', InstitutionAgendaController::class);
   });
   ```

2. **Global View Composer:**
   - Menyuntikkan list lembaga aktif (`Institution::where('is_active', true)->orderBy('urutan')->get()`) ke layout navigasi/sidebar secara global.
   - Fitur chunking/pagination pada dropdown lembaga.

3. **Komponen UI Sub-Fungsi:**
   - Tabs Navigasi Bersama: `[Pengurus & Anggota]` | `[Buku Keputusan]` | `[Buku Kegiatan]` | `[Buku Agenda]`.
   - Form anggota terintegrasi autocomplete Select2 / Live Search NIK Penduduk.
   - Filter tahun dan tombol ekspor/cetak rekap per sub-fungsi.

---

## 4. Rencana Pengujian (Verification Plan)

- [ ] Daftarkan 2 lembaga baru (contoh: "BPD" dan "Posyandu Melati 01") dari menu Pengaturan Kelembagaan.
- [ ] Verifikasi bahwa slug otomatis terbuat dan kedua lembaga muncul di navigasi menu.
- [ ] Buka masing-masing lembaga dan uji fungsionalitas 4 sub-fungsi:
  - Input Pengurus dengan NIK yang terdaftar.
  - Tambah Keputusan Lembaga & upload lampiran PDF.
  - Tambah Kegiatan Lembaga & upload foto dokumentasi.
  - Tambah Agenda Surat Masuk/Keluar.
- [ ] Pastikan penghapusan/nonaktif lembaga tidak merusak modul lain.
