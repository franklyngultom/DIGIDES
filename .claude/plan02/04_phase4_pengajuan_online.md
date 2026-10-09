# Fase 04 — Pengajuan Surat Online

## Tujuan

Memungkinkan masyarakat yang login mengirim permohonan layanan dan dokumen pendukung.

## Tugas

1. Rancang struktur permohonan berdasarkan hasil audit; gunakan tabel yang ada bila cocok.
2. Buat form pengajuan berdasarkan jenis layanan dan persyaratannya.
3. Validasi input server-side dan dokumen unggahan.
4. Simpan pemohon, layanan, waktu pengajuan, data form, dan status awal.
5. Buat daftar dan detail pengajuan untuk pemohon.
6. Batasi akses agar pemohon hanya dapat melihat pengajuannya sendiri.
7. Simpan berkas di penyimpanan privat dan sediakan unduhan melalui endpoint terotorisasi.
8. Tambahkan feature test untuk pengajuan valid/tidak valid dan akses lintas pengguna.

## Kriteria selesai

- Pengajuan valid tersimpan dengan identitas yang dapat ditelusuri.
- Pengajuan tidak valid ditolak dengan pesan yang jelas.
- Pengguna tidak dapat melihat, mengubah, atau mengunduh dokumen milik pengguna lain.
- Pengajuan belum otomatis dianggap sebagai surat terbit.
