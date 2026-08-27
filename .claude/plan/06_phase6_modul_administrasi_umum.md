# Plan 06: Modul Administrasi Umum (Fase 6 - Prioritas P1)

**Modul:** 8 Buku Register Administrasi Umum Desa, Utilitas Standard (Filter Tahun, Instant Search, PDF Preview Modal, Cetak Rekap)  
**Referensi PRD:** Section 5.1, 7.2, 8 (Fase 2)

---

## 1. Tujuan & Ruang Lingkup
Digitalisasi 8 buku register administrasi umum sesuai pedoman tata kelola kearsipan dan administrasi desa nasional, dilengkapi fitur utilitas terpadu untuk pencarian cepat, pratinjau lampiran dokumen PDF, dan cetak laporan tahunan.

### 8 Daftar Buku Register Umum:
1. **Buku Peraturan di Desa:** Register Perdes, Peraturan Bersama Kades, dan Peraturan Kepala Desa.
2. **Buku Keputusan Kepala Desa:** Register Surat Keputusan (SK) Kades.
3. **Buku Inventaris dan Kekayaan Desa:** Aset fisik barang/bangunan, tanggal perolehan, asal barang, kondisi, dan lokasi.
4. **Buku Anggaran Pemerintah Desa:** Register arsip penetapan dokumen APBDes & Perubahan.
5. **Buku Tanah Kas Desa & Tanah di Desa:** Pencatatan letter C, sertifikat, peruntukan tanah kas/warga, luas, dan batas.
6. **Buku Agenda (Surat Masuk & Surat Keluar):** Pencatatan surat dinas umum desa.
7. **Buku Ekspedisi:** Register pengiriman surat dan bukti serah terima berkas fisik.
8. **Buku Lembaran Desa & Berita Desa:** Pengundangan dan publikasi resmi lembaran desa.

---

## 2. Standardisasi Utilitas Wajib pada Seluruh Buku Register

Seluruh 8 sub-modul administrasi umum wajib memiliki 4 utilitas UI/UX standar:
1. **Filter Berbasis Tahun:** Dropdown filter cepat di header tabel (Contoh: `2024`, `2025`, `2026`, `Semua Tahun`).
2. **Instant Search & Pagination:** Pencarian real-time pada seluruh kolom teks dengan pagination 10, 25, 50 entri per halaman.
3. **Modal Pratinjau Dokumen (`Lihat File / Berkas`):** Modal dialog popup yang merender dokumen fisik PDF lampiran tanpa harus membuka tab baru.
4. **Cetak Rekapitulasi (`Print / Export`):** Tombol ekspor data yang terfilter ke format PDF cetak resmi berstandar kop kantor desa.

---

## 3. Struktur Database & Skema Relasi

### Tabel `buku_peraturan_desa`
- `id` (bigint, PK)
- `tahun` (integer: 4, index)
- `jenis_peraturan` (enum: 'perdes', 'perkades', 'peraturan_bersama')
- `nomor_ditetapkan` (string)
- `tanggal_ditetapkan` (date)
- `tentang` (text)
- `uraian_singkat` (text, nullable)
- `nomor_kesepakatan_bpd` (string, nullable)
- `nomor_diundangkan` (string, nullable)
- `tanggal_diundangkan` (date, nullable)
- `file_pdf_path` (string, nullable)
- `created_by` (foreignId -> users.id)
- `created_at`, `updated_at`

### Tabel `buku_keputusan_kades`
- `id` (bigint, PK)
- `tahun` (integer: 4, index)
- `nomor_keputusan` (string)
- `tanggal_keputusan` (date)
- `tentang` (text)
- `uraian_singkat` (text, nullable)
- `nomor_dilaporkan` (string, nullable)
- `file_pdf_path` (string, nullable)
- `created_by` (foreignId -> users.id)
- `created_at`, `updated_at`

### Tabel `buku_inventaris_aset`
- `id` (bigint, PK)
- `tahun_pengadaan` (integer: 4, index)
- `jenis_barang` (string)
- `kode_barang` (string, nullable)
- `identitas_barang` (text) - Merk, tipe, ukuran
- `asal_usul` (enum: 'apbdes', 'bantuan_pemerintah', 'bantuan_provinsi', 'bantuan_kabupaten', 'hibah', 'lainnya')
- `harga_perolehan` (decimal: 15,2, default 0)
- `kondisi` (enum: 'baik', 'rusak_ringan', 'rusak_berat')
- `lokasi_penempatan` (string)
- `foto_barang_path` (string, nullable)
- `created_by` (foreignId -> users.id)
- `created_at`, `updated_at`

### Tabel `buku_tanah_desa`
- `id` (bigint, PK)
- `jenis_tanah` (enum: 'tanah_kas_desa', 'tanah_bengkok', 'tanah_warga')
- `nomor_sertifikat_letter_c` (string)
- `nama_pemilik_asal` (string)
- `luas_m2` (decimal: 10,2)
- `kelas_tanah` (string, nullable)
- `lokasi_blok` (string)
- `peruntukan_saat_ini` (string)
- `patok_tanda_batas` (text, nullable)
- `file_warkah_path` (string, nullable)
- `created_by` (foreignId -> users.id)
- `created_at`, `updated_at`

---

## 4. Rencana Implementasi & Pengujian

1. **Reusable Generic Table Component:**
   - Membuat komponen Blade `<x-admin.register-table>` yang menerima parameter kolom, endpoint data, filter tahun, dan aksi preview.
2. **Controller & Export Engine:**
   - Standardisasi `App\Http\Controllers\Administrasi\Buku...Controller` dengan method `index`, `store`, `update`, `destroy`, `previewFile`, dan `exportPdf`.
3. **Pengujian:**
   - Verifikasi pengunggahan file PDF SK/Perdes.
   - Buka modal "Lihat File" dan pastikan PDF viewer bekerja mulus.
   - Uji filter dropdown tahun: pastikan query database mengeksekusi `where('tahun', $year)`.
