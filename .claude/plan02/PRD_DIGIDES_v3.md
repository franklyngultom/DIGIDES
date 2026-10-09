# PRD v3.0 --- Website Profil Desa dan Layanan Persuratan Online

**Proyek:** DIGIDES --- Sistem Administrasi Desa\
**Versi dokumen:** 3.0\
**Status:** Rancangan kebutuhan produk\
**Tanggal:** 9 Oktober 2026

------------------------------------------------------------------------

## 1. Ringkasan

DIGIDES dikembangkan untuk mendukung administrasi pemerintahan desa.
Versi ini memperluas sistem dengan:

1.  **Website profil desa** yang dapat diakses oleh masyarakat umum.
2.  **Portal masyarakat** agar warga dapat membuat akun dan mengajukan
    layanan surat secara online.
3.  **Integrasi dengan dashboard internal** agar Admin Desa dan Staff
    Desa dapat memeriksa, memproses, dan menyelesaikan pengajuan sesuai
    kewenangannya.

Website publik, portal masyarakat, dan dashboard internal berada dalam
satu aplikasi dan menggunakan satu basis data, tetapi memiliki antarmuka
serta hak akses yang terpisah.

Dokumen ini mendefinisikan kebutuhan produk. Rute Laravel, controller,
middleware, tabel, dan alur teknis yang disebutkan merupakan rancangan
konseptual sampai diverifikasi terhadap source code dan skema database
DIGIDES yang sudah ada.

## 2. Latar Belakang

Sistem administrasi desa yang sudah ada berfokus pada penggunaan
internal oleh Admin Desa dan Staff Desa. Pengembangan ini menambahkan
kanal digital bagi masyarakat tanpa menghilangkan alur kerja
administrasi internal yang telah berjalan.

Masyarakat dapat melihat informasi desa, mempelajari layanan surat,
membuat akun, mengirim pengajuan, dan memantau status pengajuan. Petugas
tetap melakukan pemeriksaan dan pemrosesan melalui dashboard internal
berdasarkan kewenangan masing-masing.

## 3. Tujuan Produk

-   Menyediakan informasi profil desa yang mudah diakses publik.
-   Memungkinkan warga mengajukan layanan surat secara online.
-   Memberikan informasi status dan riwayat pengajuan kepada warga.
-   Mengurangi kebutuhan pengajuan awal secara langsung ke kantor desa.
-   Menghubungkan pengajuan online dengan proses persuratan internal
    DIGIDES.
-   Mempertahankan kontrol akses dan kewenangan petugas yang sudah
    berlaku.

## 4. Batasan dan Cakupan

### 4.1 Termasuk dalam cakupan

-   Website publik profil desa.
-   Halaman informasi dan berita/pengumuman desa.
-   Katalog layanan persuratan.
-   Registrasi dan login akun masyarakat.
-   Profil dasar masyarakat.
-   Formulir pengajuan surat online.
-   Unggah dokumen persyaratan jika dibutuhkan.
-   Pelacakan status dan riwayat pengajuan.
-   Antrean pengajuan pada dashboard internal.
-   Pemeriksaan, pemrosesan, dan pembaruan status oleh petugas
    berwenang.
-   Integrasi dengan modul surat yang sudah ada.
-   Integrasi dengan Buku Ekspedisi/Agenda sesuai alur yang berlaku.
-   Notifikasi status kepada masyarakat.
-   Pembatasan akses berdasarkan peran.

### 4.2 Di luar cakupan

-   Portal layanan mandiri tanpa akun.
-   Persetujuan tambahan yang wajib dilakukan oleh satu peran universal
    untuk semua jenis surat.
-   Penggantian total dashboard administrasi internal yang sudah ada.
-   Perubahan proses bisnis surat yang sudah berjalan tanpa audit dan
    persetujuan kebutuhan lebih lanjut.

## 5. Jenis Pengguna dan Hak Akses

### 5.1 Pengunjung publik

Dapat: - Membuka halaman beranda dan profil desa. - Melihat berita,
pengumuman, dan informasi publik. - Melihat katalog layanan dan
persyaratan umum.

Tidak dapat: - Melihat data pribadi warga. - Melihat pengajuan surat. -
Mengakses dashboard internal.

### 5.2 Masyarakat

Dapat: - Mendaftar dan login. - Mengelola data profil yang diizinkan. -
Melihat layanan yang tersedia. - Mengajukan surat. - Mengunggah dokumen
persyaratan. - Melihat daftar, status, dan riwayat pengajuannya
sendiri. - Melihat informasi tindak lanjut atau kekurangan berkas.

Tidak dapat: - Melihat pengajuan milik warga lain. - Mengubah hasil
pemeriksaan petugas. - Mengakses fitur Admin Desa atau Staff Desa.

### 5.3 Staff Desa

Dapat: - Mengakses pengajuan yang menjadi kewenangannya. - Memeriksa
kelengkapan data dan dokumen. - Meminta perbaikan atau kelengkapan
kepada pemohon. - Memproses pengajuan sesuai kewenangan. - Memperbarui
status sesuai alur yang diizinkan. - Melanjutkan proses ke modul
persuratan internal apabila sesuai.

### 5.4 Admin Desa

Memiliki akses administratif sesuai konfigurasi peran dan kewenangan
yang berlaku pada DIGIDES. Admin Desa dapat mengelola konfigurasi
layanan dan konten publik jika kewenangan tersebut diberikan.

> Matriks hak akses final harus diselaraskan dengan Role-Based Access
> Control (RBAC) yang sudah ada.

## 6. Struktur Website dan Portal

### 6.1 Website publik

Halaman yang direncanakan:

-   Beranda
-   Profil desa
-   Sejarah desa
-   Visi dan misi
-   Pemerintahan/perangkat desa
-   Informasi wilayah dan demografi yang memang ditetapkan sebagai
    informasi publik
-   Berita dan pengumuman
-   Katalog layanan
-   Detail layanan dan persyaratan
-   Kontak/alamat kantor desa
-   Login dan registrasi masyarakat

Konten yang bersifat pribadi atau internal tidak boleh ditampilkan pada
website publik.

### 6.2 Portal masyarakat

Halaman yang direncanakan:

-   Dashboard masyarakat
-   Profil akun
-   Daftar layanan
-   Form pengajuan surat
-   Daftar pengajuan
-   Detail pengajuan
-   Status dan riwayat pengajuan
-   Informasi permintaan perbaikan atau dokumen tambahan
-   Notifikasi

### 6.3 Dashboard internal

Fitur baru yang direncanakan:

-   Daftar pengajuan online
-   Filter berdasarkan jenis layanan, status, dan tanggal
-   Detail pengajuan dan dokumen
-   Pemeriksaan kelengkapan
-   Permintaan perbaikan
-   Pemrosesan sesuai kewenangan
-   Riwayat tindakan petugas
-   Integrasi dengan modul surat internal

Fitur internal yang sudah ada tetap dipertahankan. Penambahan harus
dilakukan tanpa merusak route, controller, middleware, maupun alur kerja
yang sudah digunakan.

## 7. Kebutuhan Fungsional

### FR-01 --- Informasi publik

Sistem menyediakan halaman profil dan informasi desa yang dapat diakses
tanpa login.

**Kriteria penerimaan:** - Pengunjung dapat membuka halaman publik tanpa
akun. - Halaman hanya menampilkan informasi yang ditetapkan sebagai
publik. - Halaman dapat diakses dari perangkat desktop maupun seluler.

### FR-02 --- Pengelolaan konten

Pengguna internal yang berwenang dapat mengelola konten profil, berita,
dan pengumuman.

**Kriteria penerimaan:** - Hanya peran yang memiliki izin yang dapat
membuat atau mengubah konten. - Konten memiliki status publikasi yang
jelas. - Konten yang belum dipublikasikan tidak terlihat oleh
pengunjung.

### FR-03 --- Akun masyarakat

Masyarakat dapat membuat akun dan login untuk menggunakan layanan
online.

**Kriteria penerimaan:** - Registrasi memvalidasi data yang
diwajibkan. - Kredensial disimpan dengan mekanisme keamanan yang
sesuai. - Pengguna yang belum login tidak dapat mengakses halaman
pengajuan pribadi. - Pengguna hanya dapat mengakses data miliknya
sendiri.

### FR-04 --- Katalog layanan

Sistem menampilkan daftar layanan surat, deskripsi, dan persyaratan.

**Kriteria penerimaan:** - Setiap layanan memiliki nama dan informasi
persyaratan. - Layanan yang tidak aktif tidak dapat diajukan. -
Informasi layanan dapat dibaca sebelum pengguna memulai pengajuan.

### FR-05 --- Pengajuan surat online

Masyarakat yang sudah login dapat mengisi dan mengirim pengajuan.

**Kriteria penerimaan:** - Form mengikuti kebutuhan data dari jenis
surat yang dipilih. - Validasi dilakukan pada sisi server. - Dokumen
persyaratan dapat diunggah jika diperlukan. - Sistem menyimpan pengajuan
dan memberikan nomor atau identitas pengajuan. - Pengguna menerima
konfirmasi bahwa pengajuan telah tercatat.

### FR-06 --- Pemeriksaan dan pemrosesan

Petugas memeriksa pengajuan melalui dashboard internal dan memprosesnya
sesuai kewenangan.

**Kriteria penerimaan:** - Petugas hanya dapat melakukan tindakan yang
diizinkan perannya. - Petugas dapat melihat data dan dokumen yang
diperlukan untuk memproses pengajuan. - Petugas dapat meminta perbaikan
atau kelengkapan jika diperlukan. - Setiap perubahan status tercatat
dalam riwayat.

### FR-07 --- Status dan riwayat pengajuan

Masyarakat dapat melihat status dan riwayat pengajuan miliknya.

Status konseptual yang dapat digunakan: - Diajukan - Sedang diperiksa -
Perlu perbaikan - Diproses - Selesai - Ditolak

Daftar status final harus disesuaikan dengan alur dan istilah yang
digunakan dalam sistem internal.

**Kriteria penerimaan:** - Status terbaru ditampilkan pada daftar dan
detail pengajuan. - Riwayat mencatat perubahan status dan waktu
kejadian. - Alasan atau catatan yang ditujukan kepada pemohon
ditampilkan secara aman.

### FR-08 --- Integrasi dengan modul surat

Pengajuan yang memenuhi ketentuan dapat diteruskan ke proses pembuatan
surat pada modul internal yang sudah ada.

**Kriteria penerimaan:** - Data pengajuan tidak perlu dimasukkan ulang
jika dapat dipetakan secara aman ke modul surat. - Proses penerbitan
tetap mengikuti kewenangan dan prosedur internal. - Integrasi tidak
membuat surat atau nomor surat ganda. - Kegagalan integrasi dapat
ditangani dan ditelusuri.

### FR-09 --- Integrasi Buku Ekspedisi/Agenda

Jika alur surat yang berlaku mengharuskannya, surat yang diproses
melalui pengajuan online tetap terhubung dengan Buku Ekspedisi/Agenda.

**Kriteria penerimaan:** - Pencatatan mengikuti aturan modul yang sudah
ada. - Data tidak tercatat ganda akibat proses integrasi. - Hubungan
antara pengajuan, surat, dan catatan ekspedisi dapat ditelusuri.

### FR-10 --- Notifikasi

Sistem memberi informasi kepada masyarakat ketika status pengajuan
berubah atau ketika diperlukan tindakan dari pemohon.

**Kriteria penerimaan:** - Notifikasi menampilkan informasi yang relevan
tanpa membocorkan data sensitif. - Kegagalan pengiriman notifikasi tidak
menghilangkan riwayat status. - Kanal notifikasi yang digunakan
ditetapkan pada tahap desain teknis.

### FR-11 --- Audit log

Tindakan penting pada pengajuan dan perubahan status dicatat untuk
kebutuhan audit.

**Kriteria penerimaan:** - Perubahan penting memiliki informasi pelaku
dan waktu. - Riwayat tidak dapat diubah oleh pengguna biasa. -
Pencatatan mengikuti mekanisme audit log DIGIDES jika tersedia.

## 8. Alur Pengajuan

1.  Warga membuka website profil desa.
2.  Warga memilih layanan dan membaca persyaratannya.
3.  Warga login atau membuat akun.
4.  Warga mengisi formulir dan mengunggah dokumen yang diperlukan.
5.  Sistem memvalidasi dan menyimpan pengajuan.
6.  Pengajuan masuk ke antrean dashboard internal.
7.  Petugas yang berwenang memeriksa kelengkapan dan kebenaran data.
8.  Jika ada kekurangan, petugas meminta perbaikan dan warga memperbarui
    pengajuan.
9.  Jika memenuhi syarat, petugas memproses pengajuan sesuai
    kewenangannya.
10. Proses penerbitan menggunakan modul surat internal yang relevan.
11. Buku Ekspedisi/Agenda diperbarui apabila diwajibkan oleh alur yang
    berlaku.
12. Warga melihat status akhir dan informasi tindak lanjut melalui
    portal.

Alur rinci, termasuk status transisi yang diperbolehkan, perlu
ditetapkan setelah pemeriksaan proses bisnis dan implementasi yang sudah
ada.

## 9. Rancangan Route Laravel

Bagian ini merupakan **contoh rancangan**, bukan bukti bahwa route telah
dibuat atau diterapkan pada source code.

Contoh pemisahan route:

``` php
// Website publik
Route::get('/', [PublicController::class, 'home']);
Route::get('/profil-desa', [PublicController::class, 'profile']);
Route::get('/berita', [NewsController::class, 'index']);
Route::get('/layanan', [ServiceController::class, 'index']);

// Portal masyarakat
Route::prefix('masyarakat')
    ->name('masyarakat.')
    ->middleware(['auth', 'role:masyarakat'])
    ->group(function () {
        Route::get('/dashboard', [CitizenDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/pengajuan', [ServiceRequestController::class, 'index'])
            ->name('pengajuan.index');

        Route::post('/pengajuan', [ServiceRequestController::class, 'store'])
            ->name('pengajuan.store');
    });

// Route Admin/Staf yang sudah ada tetap dipertahankan.
```

Catatan implementasi: - Nama controller dan middleware di atas bersifat
ilustratif. - Middleware `role:masyarakat` harus benar-benar tersedia
dan dikonfigurasi, atau diganti dengan mekanisme otorisasi proyek. -
Route publik, masyarakat, dan internal harus memiliki batas akses yang
jelas. - Route yang sudah ada perlu diperiksa sebelum menambahkan route
baru. - Daftar route final harus mengikuti struktur `routes/web.php`,
file route tambahan, controller, dan middleware aktual.

## 10. Rancangan Data Konseptual

Tabel berikut merupakan usulan awal. Struktur final harus mengikuti
audit database yang sudah ada, konvensi penamaan proyek, serta kebutuhan
relasi dan migrasi.

  Entitas                       Tujuan
  ----------------------------- -------------------------------------------------
  `citizen_profiles`            Data profil masyarakat yang terkait dengan akun
  `village_pages`               Konten halaman profil desa
  `village_news`                Berita dan pengumuman desa
  `service_types`               Katalog jenis layanan surat
  `service_requests`            Data utama pengajuan surat
  `service_request_documents`   Dokumen persyaratan pengajuan
  `service_request_histories`   Riwayat status dan tindakan
  `notifications`               Informasi/notifikasi kepada pengguna

Sebelum membuat migrasi, periksa apakah entitas serupa sudah tersedia
pada tabel pengguna, penduduk, layanan surat, arsip, notifikasi, atau
audit log yang ada. Hindari duplikasi data dan tabel yang fungsinya
tumpang tindih.

## 11. Keamanan dan Privasi

-   Gunakan autentikasi untuk area masyarakat dan dashboard internal.
-   Terapkan otorisasi server-side pada setiap route dan tindakan.
-   Pastikan masyarakat hanya dapat melihat pengajuan miliknya sendiri.
-   Validasi semua input di server.
-   Batasi jenis, ukuran, dan akses file yang diunggah.
-   Simpan dokumen pengajuan pada lokasi yang tidak dapat diakses publik
    secara langsung.
-   Hindari menampilkan data pribadi pada halaman publik.
-   Terapkan perlindungan terhadap CSRF, XSS, akses tidak sah, dan
    enumerasi data.
-   Catat tindakan penting melalui audit log.
-   Terapkan kebijakan retensi dan penghapusan data sesuai ketentuan
    yang berlaku.
-   Pastikan backup mencakup data yang diperlukan dan dapat dipulihkan.

## 12. Kebutuhan Nonfungsional

### Kinerja

-   Halaman publik dan portal harus tetap responsif pada penggunaan
    normal.
-   Daftar pengajuan mendukung pagination dan filter bila volume data
    membesar.
-   Proses unggah dokumen memiliki batas ukuran yang jelas.

### Kompatibilitas

-   Antarmuka mendukung browser modern.
-   Halaman publik dan portal responsif pada desktop dan perangkat
    seluler.

### Keandalan

-   Pengajuan yang berhasil disimpan harus dapat ditelusuri.
-   Kegagalan notifikasi tidak boleh menghapus data pengajuan.
-   Integrasi dengan modul surat harus menangani kegagalan secara aman.

### Pemeliharaan

-   Perubahan mengikuti struktur dan konvensi kode DIGIDES.
-   Penambahan tidak boleh merusak fungsi internal yang sudah ada.
-   Migrasi database harus dapat dijalankan dan diuji pada lingkungan
    pengembangan/staging.

## 13. Kriteria Penerimaan Tingkat Produk

Produk dapat dianggap memenuhi cakupan utama apabila:

-   Website profil desa dapat diakses publik.
-   Informasi publik dapat dikelola oleh pengguna internal yang
    berwenang.
-   Masyarakat dapat mendaftar, login, dan mengajukan layanan.
-   Masyarakat dapat melihat status dan riwayat pengajuannya sendiri.
-   Petugas dapat memeriksa dan memproses pengajuan sesuai kewenangan.
-   Pengajuan dapat dihubungkan dengan modul persuratan internal tanpa
    duplikasi.
-   Integrasi Buku Ekspedisi/Agenda mengikuti alur yang berlaku.
-   Hak akses dan privasi data diterapkan.
-   Perubahan penting tercatat dalam audit log.
-   Fitur lama tetap berjalan setelah penambahan.

## 14. Tahapan Implementasi yang Disarankan

### Tahap 1 --- Audit sistem yang ada

-   Periksa struktur route Laravel.
-   Periksa autentikasi, RBAC, middleware, dan model pengguna.
-   Periksa modul penduduk dan persuratan.
-   Periksa Buku Ekspedisi/Agenda.
-   Periksa skema database dan audit log.
-   Petakan alur surat yang sudah berjalan.

### Tahap 2 --- Fondasi website publik

-   Tata letak publik.
-   Halaman profil desa.
-   Berita/pengumuman.
-   Katalog layanan.
-   Pengelolaan konten dan status publikasi.

### Tahap 3 --- Akun dan portal masyarakat

-   Registrasi dan login.
-   Profil masyarakat.
-   Dashboard dan daftar pengajuan.
-   Pembatasan akses data per pengguna.

### Tahap 4 --- Pengajuan dan pemrosesan

-   Form pengajuan.
-   Dokumen persyaratan.
-   Antrean internal.
-   Pemeriksaan, permintaan perbaikan, dan perubahan status.
-   Riwayat pengajuan.

### Tahap 5 --- Integrasi dan pengujian

-   Integrasi modul surat.
-   Integrasi Buku Ekspedisi/Agenda.
-   Notifikasi.
-   Pengujian hak akses, keamanan, dan regresi.
-   Uji pemulihan backup dan kesiapan rilis.

## 15. Asumsi dan Hal yang Perlu Dikonfirmasi

1.  Data identitas masyarakat akan diambil dari data penduduk yang sudah
    ada, akun baru, atau kombinasi keduanya.
2.  Persyaratan dan formulir tiap jenis surat perlu dipetakan dari modul
    persuratan yang sudah tersedia.
3.  Mekanisme verifikasi akun masyarakat belum ditentukan.
4.  Kanal notifikasi belum ditentukan.
5.  Aturan biaya, jika ada, belum ditentukan.
6.  Status dan transisi final harus mengikuti proses bisnis desa.
7.  Hak pengelolaan konten publik perlu ditetapkan pada RBAC.
8.  Struktur tabel dan route final belum dapat dipastikan sebelum source
    code dan database diaudit.

## 16. Catatan Implementasi

PRD ini adalah dokumen kebutuhan dan rancangan. Dokumen ini **tidak
menyatakan bahwa fitur, tabel, controller, middleware, atau route telah
diimplementasikan**. Implementasi harus dimulai dengan audit terhadap
source code DIGIDES yang ada, kemudian rancangan disesuaikan agar
konsisten dengan arsitektur proyek dan tidak mengganggu fungsi yang
telah berjalan.
