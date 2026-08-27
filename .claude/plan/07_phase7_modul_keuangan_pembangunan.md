# Plan 07: Modul Keuangan & Pembangunan Desa (Fase 7 - Prioritas P1)

**Modul:** Modul Keuangan Desa (APBDes, RAB, Buku Kas Umum, Bank Desa) & Modul Pembangunan (RKP Desa, Hasil Pembangunan, Kader KPM)  
**Referensi PRD:** Section 5.4, 5.5, 8 (Fase 2)

---

## 1. Tujuan & Ruang Lingkup
Menyediakan tata kelola transparansi keuangan desa sesuai struktur APBDes standar Kemendagri, serta pencatatan perencanaan dan hasil fisik pembangunan infrastruktur desa beserta kader pemberdayaan masyarakat.

---

## 2. Rincian Fitur Modul Keuangan

### 1. Buku APB Desa & Rencana Anggaran Biaya (RAB):
- Master pos pendapatan, belanja (Bidang 1: Penyelenggaraan Pemerintahan, Bidang 2: Pembangunan, Bidang 3: Pembinaan, Bidang 4: Pemberdayaan, Bidang 5: Penanggulangan Bencana/Darurat), dan pembiayaan.
- Input pagu anggaran tahunan vs realisasi penyerapan.

### 2. Buku Kas Umum & Kas Pembantu:
- Pencatatan transaksi harian Masuk / Keluar.
- Kolom wajib: Tanggal, No Bukti Kas, Jenis Transaksi, Kode Rekening/Sumber Dana (Dana Desa/ADD/PAD/Bagi Hasil), Uraian, Nominal Penerimaan/Pengeluaran, File Bukti Vouching/Kwitansi PDF.
- Kalkulasi saldo berjalan (*running balance*) otomatis.

### 3. Buku Bank Desa:
- Rekonsiliasi rekening kas desa di bank (Setoran, Penarikan, Bunga Bank, Biaya Pajak & Administrasi).

---

## 3. Rincian Fitur Modul Pembangunan

### 1. Buku Rencana Kerja Pembangunan Desa (RKP Desa):
- Daftar usulan proyek pembangunan fisik dan non-fisik (Nama Kegiatan, Lokasi Dusun/RT/RW, Volume/Panjang/Luas, Estimasi Biaya, Sumber Dana, Waktu Pelaksanaan).

### 2. Buku Kegiatan Pembangunan & Inventaris Hasil Pembangunan:
- Progress pengerjaan (0%, 50%, 100%), dokumentasi titik nol hingga serah terima, total biaya akhir realisasi, kondisi aset pasca bangun, dan kelompok pemanfaat warga.

### 3. Buku Kader Pemberdayaan Masyarakat (KPM):
- Register kader desa (Kader Posyandu, Pendamping Desa, KPM Stunting, Guru PAUD) terintegrasi ke data NIK Penduduk, nomor SK, dan honorarium.

---

## 4. Struktur Database & Skema Relasi

### Tabel `keuangan_apbdes`
- `id` (bigint, PK)
- `tahun_anggaran` (integer: 4, index)
- `kode_rekening` (string: 30) - Contoh: "4.1.1.01"
- `jenis` (enum: 'pendapatan', 'belanja', 'pembiayaan')
- `bidang` (string, nullable)
- `uraian` (text)
- `anggaran` (decimal: 15,2, default 0)
- `sumber_dana` (enum: 'DDS', 'ADD', 'PBH', 'PAD', 'DLL')
- `created_at`, `updated_at`

### Tabel `keuangan_kas_transaksi`
- `id` (bigint, PK)
- `buku_kas_type` (enum: 'umum', 'kegiatan', 'pajak')
- `tahun_anggaran` (integer: 4, index)
- `tanggal` (date, index)
- `nomor_bukti` (string)
- `kode_rekening` (string: 30, nullable)
- `uraian` (text)
- `penerimaan` (decimal: 15,2, default 0)
- `pengeluaran` (decimal: 15,2, default 0)
- `saldo` (decimal: 15,2, default 0)
- `file_bukti_path` (string, nullable)
- `created_by` (foreignId -> users.id)
- `created_at`, `updated_at`

### Tabel `pembangunan_proyek`
- `id` (bigint, PK)
- `tahun_anggaran` (integer: 4, index)
- `nama_kegiatan` (string)
- `lokasi` (string)
- `volume` (string) - Contoh: "Paving Blok 200m x 3m"
- `anggaran_biaya` (decimal: 15,2)
- `sumber_dana` (string)
- `pelaksana_tpk` (string)
- `status_progres` (enum: 'perencanaan', 'proses', 'selesai', 'tertunda')
- `persentase_selesai` (integer, default 0)
- `foto_titik_nol` (string, nullable)
- `foto_50_persen` (string, nullable)
- `foto_100_persen` (string, nullable)
- `file_rab_path` (string, nullable)
- `created_at`, `updated_at`

---

## 5. Rencana Pengujian (Verification Plan)

- [ ] Input beberapa transaksi kas masuk dan keluar: Verifikasi perhitungan saldo berjalan (*running balance*) selalu akurat secara matematis.
- [ ] Buat satu proyek pembangunan di tabel RKP Desa dan ubah status progress hingga 100%.
- [ ] Upload foto sebelum & sesudah pembangunan serta buka modal lampiran.
- [ ] Cetak rekapitulasi buku kas umum tahun berjalan ke dalam format PDF berstandar akuntansi desa.
