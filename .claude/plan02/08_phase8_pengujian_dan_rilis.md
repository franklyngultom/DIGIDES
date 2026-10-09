# Fase 08 — Pengujian, UAT, dan Rilis

## Tujuan

Memastikan seluruh alur publik, masyarakat, dan internal bekerja bersama sebelum dirilis.

## Checklist pengujian

- [ ] Halaman publik dapat diakses tanpa login.
- [ ] Registrasi, login, logout, dan pemulihan akun berjalan sesuai rancangan.
- [ ] Masyarakat tidak dapat mengakses dashboard internal.
- [ ] Masyarakat tidak dapat mengakses permohonan atau dokumen akun lain.
- [ ] Validasi form dan upload diuji.
- [ ] Transisi status dan riwayat diuji.
- [ ] Hak akses Staff Desa dan Admin Desa diuji.
- [ ] Integrasi generator surat diuji.
- [ ] Buku Ekspedisi/Agenda tidak menghasilkan data ganda.
- [ ] Fitur internal lama lulus uji regresi.
- [ ] Migration dan rollback ditinjau dengan aman.
- [ ] Backup dan pemulihan diuji di lingkungan yang sesuai.
- [ ] UAT dilakukan oleh perwakilan pengguna yang relevan.

## Prosedur rilis

1. Tinjau perubahan dan konfigurasi environment.
2. Backup database dan dokumen sesuai prosedur.
3. Jalankan migration sesuai urutan rilis.
4. Jalankan tes dan smoke test.
5. Pantau error log dan laporan pengguna.
6. Siapkan prosedur rollback yang aman.

## Kriteria selesai

Rilis hanya dilakukan jika kriteria penerimaan utama terpenuhi, risiko diketahui, dan prosedur pemulihan tersedia.
