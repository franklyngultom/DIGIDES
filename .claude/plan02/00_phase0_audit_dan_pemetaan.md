# Fase 00 — Audit dan Pemetaan Sistem

## Tujuan

Memahami implementasi DIGIDES saat ini sebelum menambahkan website publik dan portal masyarakat.

## Tugas

1. Baca `00_master_roadmap.md`, PRD yang tersedia, dan struktur folder proyek.
2. Periksa `routes/web.php` serta semua file route yang benar-benar digunakan.
3. Petakan autentikasi, model User, role/permission, middleware, dan alur login.
4. Periksa model serta tabel penduduk/masyarakat, jenis surat, surat keluar, dokumen, ekspedisi/agenda, notifikasi, dan audit log.
5. Telusuri proses pembuatan surat dari form sampai hasil akhir.
6. Identifikasi layout, komponen frontend, pola controller, validasi, dan testing yang digunakan.
7. Identifikasi risiko konflik route, role, data, atau migration.
8. Buat laporan temuan dan usulan perubahan minimal berdasarkan kode aktual.

## Batasan

- Jangan langsung membangun fitur baru.
- Jangan menghapus atau mengganti perilaku fitur yang sudah ada.
- Jangan membuat tabel baru jika fungsi serupa sudah tersedia tanpa alasan yang terdokumentasi.
- Jangan menjalankan migration destruktif pada data penting.

## Kriteria selesai

- Tersedia peta route dan role yang aktual.
- Tersedia peta alur persuratan dan ekspedisi/agenda.
- Tersedia daftar tabel/model yang dapat digunakan kembali.
- Setiap gap terhadap PRD tercatat.
- Rencana implementasi fase berikutnya disesuaikan dengan temuan audit.
