# Master Plan — DIGIDES Plan 02

**Tujuan:** Mengembangkan website profil desa dan layanan persuratan online tanpa merusak fungsi administrasi internal DIGIDES yang sudah berjalan.

## Dokumen acuan

- `PRD_DIGIDES_v3.md` — kebutuhan website publik dan portal masyarakat.
- `00_master_roadmap.md` pada folder `.claude/plan` — roadmap besar DIGIDES yang sudah ada.
- Source code dan database aktual — sumber kebenaran implementasi.

> Jangan menganggap route, controller, tabel, middleware, atau fitur telah tersedia sebelum memeriksa source code. Nama tabel dan class di plan ini bersifat usulan sampai diaudit.

## Prinsip pengerjaan

1. Kerjakan fase secara berurutan.
2. Sebelum mengubah kode, baca roadmap, PRD, file plan terkait, serta implementasi yang ada.
3. Pertahankan fungsi internal Admin Desa dan Staff Desa.
4. Jangan membuat tabel atau route duplikat sebelum memeriksa yang sudah ada.
5. Gunakan migration untuk perubahan skema database.
6. Terapkan validasi, autentikasi, otorisasi, dan pengujian pada setiap fase yang relevan.
7. Jangan lanjut ke fase berikutnya sebelum kriteria penerimaan fase aktif terpenuhi.
8. Setelah implementasi, laporkan file yang berubah, migration/route baru, hasil tes, risiko, dan pekerjaan tersisa.

## Daftar fase

| Fase | Dokumen | Hasil utama |
|---|---|---|
| 00 | `00_phase0_audit_dan_pemetaan.md` | Audit sistem dan rencana teknis berbasis kode aktual |
| 01 | `01_phase1_website_publik.md` | Website profil desa, berita, dan informasi layanan |
| 02 | `02_phase2_akun_masyarakat.md` | Registrasi/login dan profil masyarakat |
| 03 | `03_phase3_katalog_layanan.md` | Katalog layanan dan persyaratan |
| 04 | `04_phase4_pengajuan_online.md` | Form pengajuan dan unggah dokumen |
| 05 | `05_phase5_antrean_petugas.md` | Antrean, pemeriksaan, status, dan riwayat |
| 06 | `06_phase6_integrasi_persuratan.md` | Integrasi dengan generator surat dan Buku Ekspedisi/Agenda |
| 07 | `07_phase7_notifikasi_keamanan.md` | Notifikasi dan penguatan keamanan |
| 08 | `08_phase8_pengujian_dan_rilis.md` | Uji regresi, backup, dan rilis |

## Definition of Done umum

- Kriteria penerimaan fase terpenuhi.
- Validasi dan otorisasi sesuai area akses.
- Tidak ada akses lintas akun yang tidak sah.
- Pengujian terkait berjalan atau kegagalan dijelaskan dengan jelas.
- Fitur internal yang terdampak telah diuji regresi.
- Dokumentasi implementasi diperbarui.
- Tidak ada klaim bahwa pekerjaan selesai jika masih ada tes atau langkah penting yang gagal.

## Cara menggunakan plan

Mulai dari Fase 00. Minta coding agent membaca `00_master_plan.md`, `PRD_DIGIDES_v3.md`, dan file fase aktif. Implementasikan hanya fase aktif, lalu berhenti dan laporkan hasil sebelum lanjut.
