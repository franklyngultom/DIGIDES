# Fase 02 — Akun dan Profil Masyarakat

## Tujuan

Menyiapkan autentikasi dan profil masyarakat yang terpisah secara otorisasi dari Admin Desa dan Staff Desa.

## Tugas

1. Berdasarkan hasil audit, tentukan apakah model User dan sistem role yang ada dapat diperluas dengan aman.
2. Tentukan proses registrasi, verifikasi identitas, dan pemulihan akun.
3. Implementasikan registrasi/login/logout sesuai pola autentikasi proyek.
4. Hubungkan akun dengan data penduduk yang sesuai; jangan mengasumsikan kecocokan identitas tanpa verifikasi.
5. Terapkan middleware/otorisasi khusus masyarakat.
6. Buat halaman dashboard dan profil dasar.
7. Tambahkan pengujian akses lintas peran.

## Keamanan

- Akun masyarakat tidak boleh mendapat akses ke dashboard internal.
- Lindungi endpoint profil dan hanya izinkan pemilik atau petugas berwenang mengakses data.
- Jangan mempercayai role yang dikirim dari form pengguna.
- Terapkan validasi dan perlindungan autentikasi yang sesuai.

## Kriteria selesai

- Masyarakat dapat masuk dan mengelola profil yang diizinkan.
- Akses ke area internal ditolak untuk akun masyarakat.
- Tes membuktikan satu akun tidak dapat melihat profil akun lain.
