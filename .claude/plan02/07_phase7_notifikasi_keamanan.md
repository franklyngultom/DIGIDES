# Fase 07 — Notifikasi dan Penguatan Keamanan

## Tujuan

Memberi informasi perubahan status kepada pemohon serta meninjau keamanan alur online secara menyeluruh.

## Tugas

1. Konfirmasi kanal notifikasi yang akan digunakan.
2. Implementasikan notifikasi perubahan status dan permintaan perbaikan.
3. Hindari menyertakan data sensitif atau dokumen dalam pesan notifikasi.
4. Audit otorisasi semua endpoint masyarakat dan petugas.
5. Periksa validasi upload, batas ukuran/tipe berkas, CSRF, rate limiting, dan kebijakan penyimpanan dokumen.
6. Pastikan log tidak merekam kata sandi atau rahasia autentikasi.
7. Dokumentasikan kebijakan retensi data dan proses backup/pemulihan.

## Kriteria selesai

- Notifikasi dikirim melalui kanal yang disetujui dan tidak membocorkan data sensitif.
- Endpoint sensitif memiliki autentikasi dan otorisasi yang sesuai.
- Pengujian keamanan dasar dan akses lintas akun lulus.
