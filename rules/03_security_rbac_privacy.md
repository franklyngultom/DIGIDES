# Security, RBAC & Data Privacy Rules

**Konteks:** Standar keamanan, kontrol akses (RBAC), perlindungan data kependudukan (Privacy Mode), dan integritas file pada DIGIDES v2.

---

## 1. Aturan Hak Akses & RBAC

1. **Prinsip Least Privilege:**
   - Role **Admin Desa (Superadmin):** Memiliki akses penuh ke master data, manajemen pengguna, activity log, dan backup/restore.
   - Role **Staff Desa (Operational):** Hanya memiliki hak akses sesuai dengan pembagian tugas (*Permission-based assignment*). Contoh: Staff Pelayanan tidak boleh mengakses konfigurasi sistem atau modul keuangan jika tidak diberi izin.
2. **Proteksi Multi-Lapis:**
   - Setiap rute wajib dilindungi oleh `auth` dan `permission:...`.
   - Di tingkat Controller/Action, gunakan `$this->authorize('permission.name')`.
   - Di tingkat Blade UI, gunakan direktif `@can('permission.name') ... @endcan` untuk menyembunyikan tombol aksi.

---

## 2. Standar Perlindungan Privasi Data Kependudukan (Privacy Protection)

1. **Masking NIK & Nomor Kontak:**
   - Pada antarmuka publik atau monitor yang dapat terlihat oleh warga saat pelayanan, sistem wajib mendukung penyembunyian NIK:
     `320211******0001` (Hanya 6 digit awal kode wilayah dan 4 digit akhir nomor urut yang ditampilkan).
   - Nomor HP/Telepon disamarkan: `0812****7890`.
   - Implementasikan mutator/helper `$penduduk->masked_nik` dan atribut data `data-real-nik` vs `data-masked-nik` dengan toggle JS.
2. **Validasi NIK 16 Digit:**
   - NIK wajib terdiri dari tepat 16 digit angka numerik.
   - Validasi struktur: 2 digit provinsi, 2 digit kab/kota, 2 digit kecamatan, 6 digit tanggal lahir (dengan penambahan 40 untuk perempuan), dan 4 digit urut.

---

## 3. Keamanan File Upload & Dokumen Fisik

1. **Validasi File Strict:**
   - Unggahan dokumen (KTP, KK, SK, Perdes, Bukti Kas) hanya menerima ekstensi yang diizinkan: `pdf`, `jpg`, `jpeg`, `png`.
   - Batas ukuran file maksimum: 5 MB untuk PDF, 2 MB untuk gambar.
   - Periksa MIME type asli pada server-side: `'mimes:pdf,jpg,jpeg,png'`.
2. **Penyimpanan Aman (Private Storage vs Public):**
   - Dokumen kependudukan sensitif disimpan pada disk non-public (`storage/app/private/documents/...`) dan diakses melalui Controller streaming yang memverifikasi izin pengguna.

---

## 4. Keamanan Web Umum & Audit Trail

1. **CSRF & XSS Protection:**
   - Seluruh formulir POST/PUT/DELETE wajib menyertakan `@csrf`.
   - Jangan pernah menggunakan `{!! $var !!}` tanpa proses sanitasi `e()` atau `strip_tags()` untuk input teks warga.
2. **Audit Trail (Activity Log):**
   - Setiap aksi penghapusan (*Delete*), perubahan status, dan penerbitan dokumen resmi wajib dicatat ke tabel `activity_log`.
