# Backend Architecture & Laravel Standards Rules

**Konteks:** Standar pengembangan backend aplikasi DIGIDES v2 berbasis Laravel 11/13 dan PHP 8.2+.

---

## 1. Prinsip Utama Arsitektur

1. **Strict Scoping & Layering:**
   - **Controllers:** Ramping (*Thin Controllers*). Hanya bertugas menangani HTTP Request, memanggil Form Request untuk validasi, mendelegasikan logika ke Service/Action Class, dan mengembalikan Response (View/JSON).
   - **Form Requests:** Seluruh validasi input wajib ditempatkan di kelas terpisah `app/Http/Requests/...`. Dilarang menuliskan `$request->validate()` secara inline di dalam Controller untuk alur CRUD utama.
   - **Service / Action Classes:** Tempatkan proses bisnis kompleks (misal: generator nomor surat, sinkronisasi buku ekspedisi, pemindaian NIK duplikat, kalkulasi presensi QR) pada `app/Services/...` atau `app/Actions/...`.
   - **Eloquent Models:** Definisikan relasi dengan type-hint lengkap, mutators/accessors (menggunakan format Laravel modern `Attribute`), casting tipe data, dan Query Scopes.

2. **Database Integrity & Transaksi:**
   - Gunakan `DB::transaction(function () { ... })` pada seluruh operasi yang melibatkan lebih dari satu tabel (contoh: pembuatan surat yang disusul penulisan ke buku ekspedisi dan activity log).
   - Selalu tentukan foreign key constraints dengan opsi cascade yang tepat (`onDelete('cascade')` atau `nullOnDelete()`).
   - Wajib menambahkan **Database Indexing** pada kolom: `nik`, `no_kk`, `tahun`, `slug`, `qr_token`, `institution_id`, `created_at`.

3. **RBAC & Authorization:**
   - Manfaatkan middleware spatie `permission:nama.permission` pada rute dan method `$this->authorize('permission_name')` atau Gates/Policies.
   - Jangan pernah melakukan *hardcode* role check seperti `if ($user->role == 'admin')` secara langsung di controller tanpa Spatie Permission.

4. **Penanganan Error & Logging:**
   - Gunakan blok `try-catch` dengan logging terstruktur `Log::error('Error message', ['context' => $context])`.
   - Kembalikan pesan flash interaktif yang ramah pengguna (*user-friendly toast/alert*).

5. **Pencatatan Audit Trail:**
   - Pastikan model utama mengimplementasikan trait `LogsActivity` dari Spatie Activitylog untuk merekam perubahan data sensitif secara otomatis.
