# Plan 05: Modul Absensi Aparatur Desa & Scanner QR (Fase 5 - Prioritas P0)

**Modul:** Master Aparatur Desa, Generator QR Code Token Terenkripsi, Browser Webcam Scanner (`html5-qrcode`), Manual Override, Rekap Kehadiran  
**Referensi PRD:** Section 3 (Poin 5), 5.7 (A), 6.1, 8 (Fase 1)

---

## 1. Tujuan & Ruang Lingkup
Menyediakan sistem pencatatan presensi harian aparatur dan perangkat desa yang cepat, akurat, dan dapat berjalan langsung dari browser laptop kantor desa menggunakan webcam bawaan tanpa memerlukan perangkat mesin absensi fingerprint khusus.

### Fitur Utama:
1. **Master Data Aparatur Desa:**
   - Terhubung ke data NIK Penduduk, NIP/Nomor Induk Aparatur, Jabatan (Kades, Sekdes, Kaur, Kasi, Kadus), Status Kepegawaian, Jam Masuk & Jam Pulang Kerja Standar.
2. **Generator Kartu QR Aparatur (QR Token Generator):**
   - Menghasilkan token terenkripsi yang di-encode menjadi gambar QR Code unik untuk tiap aparatur.
   - Fitur cetak kartu tanda pengenal fisik (ID Card QR Presensi).
3. **Browser QR Scanner:**
   - Menggunakan library JavaScript modern `html5-qrcode`.
   - Cukup mengarahkan kartu QR aparatur ke arah webcam laptop/komputer pelayanan -> Sistem langsung mengenali aparatur, mencatat jam scan, menentukan status (*Hadir Tepat Waktu* atau *Terlambat*), serta memunculkan animasi sukses & suara konfirmasi.
4. **Pencatatan Presensi & Manual Override:**
   - Opsi input kehadiran manual oleh Admin/Staff jika aparatur lupa membawa kartu QR, izin, dinas luar, atau sakit.
5. **Rekapitulasi & Laporan Presensi:**
   - Filter rentang tanggal, filter aparatur, dan ekspor rekapitulasi presensi bulanan ke format PDF & Excel.

---

## 2. Struktur Database & Skema Relasi

### Tabel `aparatur`
- `id` (bigint, PK)
- `penduduk_id` (foreignId -> penduduk.id, unique)
- `nip` (string: 30, nullable)
- `jabatan` (string) - Contoh: "Kepala Desa", "Sekretaris Desa", "Kaur Keuangan"
- `qr_token` (string: 64, unique, index) - Hash token aman untuk scanning
- `jam_masuk_standar` (time, default '08:00:00')
- `jam_pulang_standar` (time, default '16:00:00')
- `toleransi_terlambat_menit` (integer, default 15)
- `status_kepegawaian` (enum: 'pns', 'pppk', 'perangkat_desa', 'honorer')
- `status_aktif` (boolean, default true)
- `created_at`, `updated_at`

### Tabel `absensi`
- `id` (bigint, PK)
- `aparatur_id` (foreignId -> aparatur.id, onDelete cascade)
- `tanggal` (date, index)
- `jam_masuk` (time, nullable)
- `jam_pulang` (time, nullable)
- `status_kehadiran` (enum: 'hadir', 'terlambat', 'izin', 'sakit', 'dinas_luar', 'alpa')
- `metode_absen` (enum: 'qr_scanner', 'manual_override')
- `catatan` (text, nullable)
- `verified_by` (foreignId -> users.id, nullable)
- `created_at`, `updated_at`

---

## 3. Rincian Implementasi Teknis

1. **Instalasi Package Pendukung:**
   ```bash
   composer require simplesoftwareio/simple-qrcode
   npm install html5-qrcode
   ```

2. **Logika Validasi Presensi (Attendance Engine):**
   - Saat payload QR dipindai via fetch API:
     ```php
     // Cari aparatur berdasarkan qr_token
     $aparatur = Aparatur::where('qr_token', $token)->where('status_aktif', true)->firstOrFail();
     $today = Carbon::today();
     $now = Carbon::now();

     $absensi = Absensi::firstOrNew([
         'aparatur_id' => $aparatur->id,
         'tanggal' => $today->toDateString()
     ]);

     if (!$absensi->jam_masuk) {
         // Absen Masuk
         $absensi->jam_masuk = $now->toTimeString();
         $batasMasuk = Carbon::parse($today->toDateString() . ' ' . $aparatur->jam_masuk_standar)->addMinutes($aparatur->toleransi_terlambat_menit);
         $absensi->status_kehadiran = $now->gt($batasMasuk) ? 'terlambat' : 'hadir';
         $absensi->metode_absen = 'qr_scanner';
         $absensi->save();
         return response()->json(['status' => 'success', 'type' => 'masuk', 'aparatur' => $aparatur, 'waktu' => $now->format('H:i')]);
     } else if (!$absensi->jam_pulang) {
         // Absen Pulang
         $absensi->jam_pulang = $now->toTimeString();
         $absensi->save();
         return response()->json(['status' => 'success', 'type' => 'pulang', 'aparatur' => $aparatur, 'waktu' => $now->format('H:i')]);
     }
     ```

3. **Komponen Front-End Scanner:**
   - Blade View `resources/views/absensi/scanner.blade.php`.
   - Visual viewport kamera dengan garis pandu pemindaian (*scanning box overlay* berwarna hijau mint/lime).
   - Audio feedback (Beep sukses).

---

## 4. Rencana Pengujian (Verification Plan)

- [ ] Daftarkan data aparatur dan generate kartu QR.
- [ ] Buka halaman scanner di browser laptop, izinkan akses kamera webcam.
- [ ] Arahkan kartu QR aparatur ke kamera: Pastikan respon scan muncul dalam $< 500$ ms.
- [ ] Uji skenario: Absen masuk sebelum jam batas (Status: Hadir) vs sesudah jam batas (Status: Terlambat).
- [ ] Uji input manual override untuk aparatur yang berhalangan hadir (Sakit/Izin/Dinas).
- [ ] Verifikasi rekapitulasi presensi bulanan dapat di-export ke format PDF.
