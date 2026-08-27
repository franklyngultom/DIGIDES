# Plan 02: Modul Kependudukan & Perlindungan Privasi (Fase 2 - Prioritas P0)

**Modul:** Master Kependudukan, Cek NIK Duplikat, Privacy Mode Toggle, Manajemen Berkas Warga, Mutasi  
**Referensi PRD:** Section 5.2, 7.1, 8 (Fase 1)

---

## 1. Tujuan & Ruang Lingkup
Membangun pusat data kependudukan desa yang akurat, cepat, dan aman dengan perlindungan privasi data sensitif pada antarmuka meja pelayanan (*desk-service*).

### Fitur Utama:
1. **Buku Induk Penduduk:**
   - CRUD data warga (NIK, No KK, Nama, Tempat/Tgl Lahir, Jenis Kelamin, Agama, Pekerjaan, Golongan Darah, Status Nikah, Alamat, RT/RW, Dusun, Status Kependudukan).
2. **Perangkat Pemindai Duplikasi NIK (Duplicate NIK Scanner):**
   - Utilitas otomatis sekali klik untuk mendeteksi NIK yang terdaftar lebih dari satu kali atau inkonsisten dengan tanggal lahir/wilayah.
3. **Privacy Mode Toggle (Sembunyikan Data):**
   - Saklar (*switch toggle*) pada header tabel yang memotong/menyembunyikan NIK (`320211******0001`) dan nomor telepon saat monitor menghadap ke warga.
4. **Pencatatan Sumber Data & Klasifikasi:**
   - Labeling asal data: *Prodeskel*, *Input Manual Staff*, atau *Migrasi DB Legacy*.
   - Filter klasifikasi umur (Balita, Usia Sekolah, Produktif, Lansia) dan jenis kelamin.
5. **Manajemen Berkas Pendukung (`Lihat Berkas`):**
   - Penyimpanan lampiran digital dokumen warga (Scan KTP, KK, Akta Kelahiran, Surat Nikah) dengan modal viewer dokumen instan.
6. **Buku Mutasi Penduduk:**
   - Pencatatan peristiwa mutasi: Pindah Masuk, Pindah Keluar, Meninggal, Lahir, beserta tanggal dan bukti surat keterangan.

---

## 2. Struktur Database & Migrasi

### Tabel `penduduk`
- `id` (bigint, PK)
- `nik` (string: 16, unique, index)
- `no_kk` (string: 16, index)
- `nama_lengkap` (string, index)
- `tempat_lahir` (string)
- `tanggal_lahir` (date, index)
- `jenis_kelamin` (enum: 'L', 'P')
- `agama` (string)
- `pendidikan_terakhir` (string, nullable)
- `pekerjaan` (string, nullable)
- `status_perkawinan` (string)
- `status_dalam_keluarga` (string: Kepala Keluarga, Istri, Anak, dll.)
- `kewarganegaraan` (string, default 'WNI')
- `golongan_darah` (string: 3, nullable)
- `alamat_lengkap` (text)
- `rt` (string: 3)
- `rw` (string: 3)
- `dusun` (string, nullable)
- `telepon` (string, nullable)
- `sumber_data` (enum: 'prodeskel', 'manual', 'migrasi_legacy')
- `status_penduduk` (enum: 'tetap', 'sementara', 'pindah', 'meninggal')
- `created_at`, `updated_at`

### Tabel `penduduk_documents`
- `id` (bigint, PK)
- `penduduk_id` (foreignId -> penduduk.id, onDelete cascade)
- `jenis_dokumen` (enum: 'ktp', 'kk', 'akta_lahir', 'surat_nikah', 'ijazah', 'lainnya')
- `nama_file` (string)
- `file_path` (string)
- `file_size` (bigint)
- `mime_type` (string)
- `created_at`, `updated_at`

### Tabel `penduduk_mutasi`
- `id` (bigint, PK)
- `penduduk_id` (foreignId -> penduduk.id)
- `jenis_mutasi` (enum: 'lahir', 'mati', 'pindah_keluar', 'pindah_masuk')
- `tanggal_mutasi` (date)
- `keterangan` (text, nullable)
- `berkas_pendukung_path` (string, nullable)
- `created_by` (foreignId -> users.id)
- `created_at`, `updated_at`

---

## 3. Rincian Implementasi Teknis

1. **Model & Mutator Kependudukan:**
   - `App\Models\Penduduk`:
     - Method `getMaskedNikAttribute()`: Mengembalikan `substr($nik, 0, 6) . '******' . substr($nik, -4)` saat mode privasi aktif.
     - Scope query untuk filter umur, RT/RW, Dusun, dan status aktif.
2. **Duplication Scanner Service:**
   - `App\Services\Kependudukan\DuplicateScannerService`:
     - Menjalankan kueri `SELECT nik, COUNT(*) FROM penduduk GROUP BY nik HAVING COUNT(*) > 1`.
     - Analisis anomali format (panjang string bukan 16 digit, karakter non-numerik).
3. **Controller & Endpoints:**
   - `App\Http\Controllers\Kependudukan\PendudukController` (Index, Create, Store, Show, Edit, Update, Destroy)
   - `App\Http\Controllers\Kependudukan\DocumentController` (Upload, Pratinjau, Delete)
   - `App\Http\Controllers\Kependudukan\MutasiController`
   - `App\Http\Controllers\Kependudukan\DuplicateCheckController`
4. **Front-End & UI Components:**
   - **Tabel Penduduk:** Kolom NIK dengan toggle button `Sembunyikan NIK / Privacy Mode`.
   - **Tombol Cek NIK Ganda:** Menampilkan badge peringatan merah jika ditemukan inkonsistensi.
   - **Modal Viewer Berkas:** Menampilkan preview PDF atau gambar KTP/KK tanpa reload halaman.

---

## 4. Rencana Pengujian (Verification Plan)

- [ ] Uji input NIK valid 16 digit vs NIK kurang dari 16 digit (Validasi Form Request).
- [ ] Uji tombol Privacy Mode: Pastikan NIK dan nomor kontak disamarkan secara dinamis di browser.
- [ ] Buat dua data dengan NIK sama (untuk testing environment) dan jalankan tool pemindai duplikasi.
- [ ] Uji upload dokumen KTP (PDF dan JPG) dan verifikasi dokumen dapat dibuka via modal viewer.
