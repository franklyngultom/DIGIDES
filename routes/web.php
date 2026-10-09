<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\DesaProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Administrasi\AdministrasiHubController;
use App\Http\Controllers\Administrasi\AparaturController;
use App\Http\Controllers\Administrasi\BukuAgendaController;
use App\Http\Controllers\Administrasi\BukuAnggaranDesaController;
use App\Http\Controllers\Administrasi\BukuEkspedisiController;
use App\Http\Controllers\Administrasi\BukuInventarisAsetController;
use App\Http\Controllers\Administrasi\BukuKeputusanKadesController;
use App\Http\Controllers\Administrasi\BukuLembaranDesaController;
use App\Http\Controllers\Administrasi\BukuPeraturanDesaController;
use App\Http\Controllers\Administrasi\BukuTanahDesaController;
use App\Http\Controllers\Administrasi\KelembagaanController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\Kependudukan\DocumentController;
use App\Http\Controllers\Kependudukan\DuplicateScannerController;
use App\Http\Controllers\Kependudukan\MutasiController;
use App\Http\Controllers\Kependudukan\PendudukController;
use App\Http\Controllers\Keuangan\ApbdesController;
use App\Http\Controllers\Keuangan\BukuKasController;
use App\Http\Controllers\Keuangan\KeuanganHubController;
use App\Http\Controllers\Keuangan\RabController;
use App\Http\Controllers\Masyarakat\CitizenDashboardController;
use App\Http\Controllers\Masyarakat\CitizenNotificationController;
use App\Http\Controllers\Masyarakat\CitizenPengajuanController;
use App\Http\Controllers\Masyarakat\CitizenProfileController;
use App\Http\Controllers\Pembangunan\InventarisHasilController;
use App\Http\Controllers\Pembangunan\KaderPemberdayaanController;
use App\Http\Controllers\Pembangunan\PembangunanHubController;
use App\Http\Controllers\Pembangunan\ProyekPembangunanController;
use App\Http\Controllers\Persuratan\ArsipSuratController;
use App\Http\Controllers\Persuratan\PelayananSuratController;
use App\Http\Controllers\Persuratan\PengajuanAntreanController;
use App\Http\Controllers\PublicWebsiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - DIGIDES v3
|--------------------------------------------------------------------------
*/

// Public Website Routes (Fase 01 - Website Profil & Layanan Desa)
Route::get('/', [PublicWebsiteController::class, 'home'])->name('public.home');
Route::get('/profil-desa', [PublicWebsiteController::class, 'profil'])->name('public.profil');
Route::get('/layanan', [PublicWebsiteController::class, 'layanan'])->name('public.layanan');
Route::get('/berita', [PublicWebsiteController::class, 'berita'])->name('public.berita');
Route::get('/berita/{slug}', [PublicWebsiteController::class, 'beritaDetail'])->name('public.berita.detail');
Route::get('/kontak', [PublicWebsiteController::class, 'kontak'])->name('public.kontak');
Route::get('/lacak-surat', [PublicWebsiteController::class, 'lacakSurat'])->name('public.lacak-surat')->middleware('throttle:60,1');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Portal Masyarakat (Fase 02 - Layanan Mandiri Warga)
    Route::prefix('masyarakat')->name('masyarakat.')->group(function () {
        Route::get('/dashboard', [CitizenDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profil', [CitizenProfileController::class, 'show'])->name('profil');
        Route::put('/profil', [CitizenProfileController::class, 'update'])->name('profil.update');
        Route::put('/profil/password', [CitizenProfileController::class, 'updatePassword'])->name('profil.password');

        // Pengajuan Surat Online (Fase 04)
        Route::get('/pengajuan', [CitizenPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/buat', [CitizenPengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [CitizenPengajuanController::class, 'store'])->name('pengajuan.store')->middleware('throttle:20,1');
        Route::get('/pengajuan/{pengajuan}', [CitizenPengajuanController::class, 'show'])->name('pengajuan.show');
        Route::get('/pengajuan/{pengajuan}/dokumen/{index}', [CitizenPengajuanController::class, 'downloadDokumen'])->name('pengajuan.dokumen.download');
        Route::get('/pengajuan/{pengajuan}/surat-download', [CitizenPengajuanController::class, 'downloadSurat'])->name('pengajuan.surat.download');

        // Notifikasi Mandiri Warga (Fase 07)
        Route::get('/notifikasi', [CitizenNotificationController::class, 'index'])->name('notifikasi.index');
        Route::get('/notifikasi/{id}/baca', [CitizenNotificationController::class, 'read'])->name('notifikasi.read');
        Route::post('/notifikasi/tandai-semua', [CitizenNotificationController::class, 'markAllAsRead'])->name('notifikasi.read-all');
    });

    // Main 3-Column Working Productivity Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Staff Profile Avatar Management (Only for Staff)
    Route::post('/staff/avatar', [StaffProfileController::class, 'updateAvatar'])->name('staff.avatar.update');
    Route::delete('/staff/avatar', [StaffProfileController::class, 'deleteAvatar'])->name('staff.avatar.delete');

    // Upcoming Schedule Management
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // Admin & Core Infrastructure Routes
    Route::prefix('admin')->name('admin.')->group(function () {

        // 1. Profil Desa
        Route::middleware('permission:desa.view')->group(function () {
            Route::get('/desa', [DesaProfileController::class, 'index'])->name('desa.index');
        });
        Route::middleware('permission:desa.update')->group(function () {
            Route::put('/desa', [DesaProfileController::class, 'update'])->name('desa.update');
            Route::post('/desa/foto', [DesaProfileController::class, 'updateFoto'])->name('desa.foto.update');
            Route::delete('/desa/foto', [DesaProfileController::class, 'deleteFoto'])->name('desa.foto.delete');
        });

        // 2. Manajemen Pengguna & RBAC
        Route::middleware('permission:user.view')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
        });
        Route::middleware('permission:user.create')->group(function () {
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
        });
        Route::middleware('permission:user.edit')->group(function () {
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        });
        Route::middleware('permission:user.delete')->group(function () {
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        // 3. Audit Trail (Activity Logs)
        Route::middleware('permission:audit.view')->group(function () {
            Route::get('/audit-log', [ActivityLogController::class, 'index'])->name('audit.index');
        });

        // 4. Cadangan Database & Recovery
        Route::middleware('permission:backup.manage')->group(function () {
            Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
            Route::post('/backup', [BackupController::class, 'store'])->name('backup.store');
            Route::get('/backup/{backup}/download', [BackupController::class, 'download'])->name('backup.download');
            Route::delete('/backup/{backup}', [BackupController::class, 'destroy'])->name('backup.destroy');
        });
    });

    // =============================================================
    // Modul Kependudukan (Fase 2)
    // =============================================================
    Route::prefix('kependudukan')->name('kependudukan.')->group(function () {

        // Buku Induk Penduduk - Export & Import Operations
        Route::middleware('permission:kependudukan.view')->group(function () {
            Route::get('/export-pdf', [PendudukController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/export-excel', [PendudukController::class, 'exportExcel'])->name('export-excel');
            Route::get('/export-csv', [PendudukController::class, 'exportCsv'])->name('export-csv');
            Route::get('/import-template', [PendudukController::class, 'downloadTemplate'])->name('import-template');
        });

        Route::middleware('permission:kependudukan.create')->group(function () {
            Route::post('/import', [PendudukController::class, 'import'])->name('import');
            Route::get('/create', [PendudukController::class, 'create'])->name('create');
            Route::post('/', [PendudukController::class, 'store'])->name('store');
        });

        Route::middleware('permission:kependudukan.delete')->group(function () {
            Route::post('/bulk-delete', [PendudukController::class, 'bulkDestroy'])->name('bulk-delete');
        });

        Route::middleware('permission:kependudukan.view')->group(function () {
            Route::get('/', [PendudukController::class, 'index'])->name('index');
            Route::get('/{penduduk}', [PendudukController::class, 'show'])->name('show');
        });

        Route::middleware('permission:kependudukan.edit')->group(function () {
            Route::get('/{penduduk}/edit', [PendudukController::class, 'edit'])->name('edit');
            Route::put('/{penduduk}', [PendudukController::class, 'update'])->name('update');
        });

        Route::middleware('permission:kependudukan.delete')->group(function () {
            Route::delete('/{penduduk}', [PendudukController::class, 'destroy'])->name('destroy');
        });

        // Manajemen Berkas Warga
        Route::middleware('permission:kependudukan.view')->group(function () {
            Route::get('/documents/{document}/stream', [DocumentController::class, 'stream'])->name('documents.stream');
        });
        Route::middleware('permission:kependudukan.edit')->group(function () {
            Route::post('/{penduduk}/documents', [DocumentController::class, 'store'])->name('documents.store');
            Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
        });

        // Buku Register Mutasi
        Route::middleware('permission:kependudukan.view')->group(function () {
            Route::get('/mutasi/register', [MutasiController::class, 'index'])->name('mutasi.index');
        });
        Route::middleware('permission:kependudukan.edit')->group(function () {
            Route::post('/{penduduk}/mutasi', [MutasiController::class, 'store'])->name('mutasi.store');
        });

        // Pemindai Duplikasi NIK
        Route::middleware('permission:kependudukan.view')->group(function () {
            Route::get('/tools/duplicate-scanner', [DuplicateScannerController::class, 'index'])->name('duplicates');
        });
    });

    // =============================================================
    // Modul Persuratan Walk-In, Arsip, & Antrean Pengajuan Online
    // =============================================================
    Route::prefix('persuratan')->name('persuratan.')->group(function () {
        Route::middleware('permission:persuratan.view')->group(function () {
            // Antrean Pengajuan Online (Fase 05 & Fase 06)
            Route::get('/antrean', [PengajuanAntreanController::class, 'index'])->name('antrean.index');
            Route::get('/antrean/{pengajuan}', [PengajuanAntreanController::class, 'show'])->name('antrean.show');
            Route::post('/antrean/{pengajuan}/status', [PengajuanAntreanController::class, 'updateStatus'])->name('antrean.status');
            Route::get('/antrean/{pengajuan}/dokumen/{index}', [PengajuanAntreanController::class, 'downloadDokumen'])->name('antrean.dokumen.download');
            Route::get('/antrean/{pengajuan}/surat-download', [PengajuanAntreanController::class, 'downloadSurat'])->name('antrean.surat.download');

            // Layanan Walk-In & Arsip
            Route::get('/pelayanan', [PelayananSuratController::class, 'create'])->name('create');
            Route::get('/search-penduduk', [PelayananSuratController::class, 'searchPenduduk'])->name('search-penduduk');
            Route::get('/arsip', [ArsipSuratController::class, 'index'])->name('arsip.index');
            Route::get('/arsip/{suratArsip}/download', [ArsipSuratController::class, 'download'])->name('arsip.download');
        });
        Route::middleware('permission:persuratan.create')->group(function () {
            Route::post('/pelayanan', [PelayananSuratController::class, 'store'])->name('store');
            Route::post('/antrean/{pengajuan}/terbitkan', [PengajuanAntreanController::class, 'terbitkanSurat'])->name('antrean.terbitkan');
        });
    });

    // =============================================================
    // Modul Administrasi Umum (Fase 6 - 8 Buku Register Desa)
    // =============================================================
    Route::prefix('administrasi')->name('administrasi.')->middleware('permission:administrasi.view')->group(function () {
        // Portal Hub
        Route::get('/', [AdministrasiHubController::class, 'index'])->name('index');

        // 1. Buku Peraturan Desa
        Route::get('/peraturan-desa', [BukuPeraturanDesaController::class, 'index'])->name('peraturan-desa.index');
        Route::get('/peraturan-desa/export-pdf', [BukuPeraturanDesaController::class, 'exportPdf'])->name('peraturan-desa.export-pdf');
        Route::get('/peraturan-desa/export-excel', [BukuPeraturanDesaController::class, 'exportExcel'])->name('peraturan-desa.export-excel');
        Route::get('/peraturan-desa/import-template', [BukuPeraturanDesaController::class, 'downloadTemplate'])->name('peraturan-desa.import-template');
        Route::middleware('permission:administrasi.manage')->group(function () {
            Route::post('/peraturan-desa/import', [BukuPeraturanDesaController::class, 'import'])->name('peraturan-desa.import');
            Route::get('/peraturan-desa/create', [BukuPeraturanDesaController::class, 'create'])->name('peraturan-desa.create');
            Route::post('/peraturan-desa', [BukuPeraturanDesaController::class, 'store'])->name('peraturan-desa.store');
            Route::get('/peraturan-desa/{peraturanDesa}/edit', [BukuPeraturanDesaController::class, 'edit'])->name('peraturan-desa.edit');
            Route::put('/peraturan-desa/{peraturanDesa}', [BukuPeraturanDesaController::class, 'update'])->name('peraturan-desa.update');
            Route::delete('/peraturan-desa/{peraturanDesa}', [BukuPeraturanDesaController::class, 'destroy'])->name('peraturan-desa.destroy');
        });

        // 2. Buku Keputusan Kepala Desa
        Route::get('/keputusan-kades', [BukuKeputusanKadesController::class, 'index'])->name('keputusan-kades.index');
        Route::get('/keputusan-kades/export-pdf', [BukuKeputusanKadesController::class, 'exportPdf'])->name('keputusan-kades.export-pdf');
        Route::get('/keputusan-kades/export-excel', [BukuKeputusanKadesController::class, 'exportExcel'])->name('keputusan-kades.export-excel');
        Route::get('/keputusan-kades/import-template', [BukuKeputusanKadesController::class, 'downloadTemplate'])->name('keputusan-kades.import-template');
        Route::middleware('permission:administrasi.manage')->group(function () {
            Route::post('/keputusan-kades/import', [BukuKeputusanKadesController::class, 'import'])->name('keputusan-kades.import');
            Route::get('/keputusan-kades/create', [BukuKeputusanKadesController::class, 'create'])->name('keputusan-kades.create');
            Route::post('/keputusan-kades', [BukuKeputusanKadesController::class, 'store'])->name('keputusan-kades.store');
            Route::get('/keputusan-kades/{keputusanKades}/edit', [BukuKeputusanKadesController::class, 'edit'])->name('keputusan-kades.edit');
            Route::put('/keputusan-kades/{keputusanKades}', [BukuKeputusanKadesController::class, 'update'])->name('keputusan-kades.update');
            Route::delete('/keputusan-kades/{keputusanKades}', [BukuKeputusanKadesController::class, 'destroy'])->name('keputusan-kades.destroy');
        });

        // 3. Buku Inventaris dan Kekayaan Desa
        Route::get('/inventaris-aset', [BukuInventarisAsetController::class, 'index'])->name('inventaris-aset.index');
        Route::get('/inventaris-aset/export-pdf', [BukuInventarisAsetController::class, 'exportPdf'])->name('inventaris-aset.export-pdf');
        Route::get('/inventaris-aset/export-excel', [BukuInventarisAsetController::class, 'exportExcel'])->name('inventaris-aset.export-excel');
        Route::get('/inventaris-aset/import-template', [BukuInventarisAsetController::class, 'downloadTemplate'])->name('inventaris-aset.import-template');
        Route::middleware('permission:administrasi.manage')->group(function () {
            Route::post('/inventaris-aset/import', [BukuInventarisAsetController::class, 'import'])->name('inventaris-aset.import');
            Route::get('/inventaris-aset/create', [BukuInventarisAsetController::class, 'create'])->name('inventaris-aset.create');
            Route::post('/inventaris-aset', [BukuInventarisAsetController::class, 'store'])->name('inventaris-aset.store');
            Route::get('/inventaris-aset/{inventarisAset}/edit', [BukuInventarisAsetController::class, 'edit'])->name('inventaris-aset.edit');
            Route::put('/inventaris-aset/{inventarisAset}', [BukuInventarisAsetController::class, 'update'])->name('inventaris-aset.update');
            Route::delete('/inventaris-aset/{inventarisAset}', [BukuInventarisAsetController::class, 'destroy'])->name('inventaris-aset.destroy');
        });

        // 4. Buku Tanah Kas & Tanah di Desa
        Route::get('/tanah-desa', [BukuTanahDesaController::class, 'index'])->name('tanah-desa.index');
        Route::get('/tanah-desa/export-pdf', [BukuTanahDesaController::class, 'exportPdf'])->name('tanah-desa.export-pdf');
        Route::get('/tanah-desa/export-excel', [BukuTanahDesaController::class, 'exportExcel'])->name('tanah-desa.export-excel');
        Route::get('/tanah-desa/import-template', [BukuTanahDesaController::class, 'downloadTemplate'])->name('tanah-desa.import-template');
        Route::middleware('permission:administrasi.manage')->group(function () {
            Route::post('/tanah-desa/import', [BukuTanahDesaController::class, 'import'])->name('tanah-desa.import');
            Route::get('/tanah-desa/create', [BukuTanahDesaController::class, 'create'])->name('tanah-desa.create');
            Route::post('/tanah-desa', [BukuTanahDesaController::class, 'store'])->name('tanah-desa.store');
            Route::get('/tanah-desa/{tanahDesa}/edit', [BukuTanahDesaController::class, 'edit'])->name('tanah-desa.edit');
            Route::put('/tanah-desa/{tanahDesa}', [BukuTanahDesaController::class, 'update'])->name('tanah-desa.update');
            Route::delete('/tanah-desa/{tanahDesa}', [BukuTanahDesaController::class, 'destroy'])->name('tanah-desa.destroy');
        });

        // 5. Buku Anggaran Pemerintah Desa (APBDes)
        Route::get('/anggaran-desa', [BukuAnggaranDesaController::class, 'index'])->name('anggaran-desa.index');
        Route::get('/anggaran-desa/export-pdf', [BukuAnggaranDesaController::class, 'exportPdf'])->name('anggaran-desa.export-pdf');
        Route::get('/anggaran-desa/export-excel', [BukuAnggaranDesaController::class, 'exportExcel'])->name('anggaran-desa.export-excel');
        Route::get('/anggaran-desa/import-template', [BukuAnggaranDesaController::class, 'downloadTemplate'])->name('anggaran-desa.import-template');
        Route::middleware('permission:administrasi.manage')->group(function () {
            Route::post('/anggaran-desa/import', [BukuAnggaranDesaController::class, 'import'])->name('anggaran-desa.import');
            Route::get('/anggaran-desa/create', [BukuAnggaranDesaController::class, 'create'])->name('anggaran-desa.create');
            Route::post('/anggaran-desa', [BukuAnggaranDesaController::class, 'store'])->name('anggaran-desa.store');
            Route::get('/anggaran-desa/{anggaranDesa}/edit', [BukuAnggaranDesaController::class, 'edit'])->name('anggaran-desa.edit');
            Route::put('/anggaran-desa/{anggaranDesa}', [BukuAnggaranDesaController::class, 'update'])->name('anggaran-desa.update');
            Route::delete('/anggaran-desa/{anggaranDesa}', [BukuAnggaranDesaController::class, 'destroy'])->name('anggaran-desa.destroy');
        });

        // 6. Buku Lembaran Desa & Berita Desa
        Route::get('/lembaran-desa', [BukuLembaranDesaController::class, 'index'])->name('lembaran-desa.index');
        Route::get('/lembaran-desa/export-pdf', [BukuLembaranDesaController::class, 'exportPdf'])->name('lembaran-desa.export-pdf');
        Route::get('/lembaran-desa/export-excel', [BukuLembaranDesaController::class, 'exportExcel'])->name('lembaran-desa.export-excel');
        Route::get('/lembaran-desa/import-template', [BukuLembaranDesaController::class, 'downloadTemplate'])->name('lembaran-desa.import-template');
        Route::middleware('permission:administrasi.manage')->group(function () {
            Route::post('/lembaran-desa/import', [BukuLembaranDesaController::class, 'import'])->name('lembaran-desa.import');
            Route::get('/lembaran-desa/create', [BukuLembaranDesaController::class, 'create'])->name('lembaran-desa.create');
            Route::post('/lembaran-desa', [BukuLembaranDesaController::class, 'store'])->name('lembaran-desa.store');
            Route::get('/lembaran-desa/{lembaranDesa}/edit', [BukuLembaranDesaController::class, 'edit'])->name('lembaran-desa.edit');
            Route::put('/lembaran-desa/{lembaranDesa}', [BukuLembaranDesaController::class, 'update'])->name('lembaran-desa.update');
            Route::delete('/lembaran-desa/{lembaranDesa}', [BukuLembaranDesaController::class, 'destroy'])->name('lembaran-desa.destroy');
        });

        // 7. Buku Agenda Surat Masuk & Keluar
        Route::get('/buku-agenda', [BukuAgendaController::class, 'index'])->name('buku-agenda.index');
        Route::get('/buku-agenda/export-pdf', [BukuAgendaController::class, 'exportPdf'])->name('buku-agenda.export-pdf');
        Route::get('/buku-agenda/export-excel', [BukuAgendaController::class, 'exportExcel'])->name('buku-agenda.export-excel');
        Route::get('/buku-agenda/import-template', [BukuAgendaController::class, 'downloadTemplate'])->name('buku-agenda.import-template');
        Route::middleware('permission:administrasi.manage')->group(function () {
            Route::post('/buku-agenda/import', [BukuAgendaController::class, 'import'])->name('buku-agenda.import');
            Route::get('/buku-agenda/create', [BukuAgendaController::class, 'create'])->name('buku-agenda.create');
            Route::post('/buku-agenda', [BukuAgendaController::class, 'store'])->name('buku-agenda.store');
            Route::get('/buku-agenda/{bukuAgenda}/edit', [BukuAgendaController::class, 'edit'])->name('buku-agenda.edit');
            Route::put('/buku-agenda/{bukuAgenda}', [BukuAgendaController::class, 'update'])->name('buku-agenda.update');
            Route::delete('/buku-agenda/{bukuAgenda}', [BukuAgendaController::class, 'destroy'])->name('buku-agenda.destroy');
        });

        // 8. Buku Ekspedisi
        Route::get('/buku-ekspedisi', [BukuEkspedisiController::class, 'index'])->name('buku-ekspedisi.index');
        Route::get('/buku-ekspedisi/export-pdf', [BukuEkspedisiController::class, 'exportPdf'])->name('buku-ekspedisi.export-pdf');
        Route::get('/buku-ekspedisi/export-excel', [BukuEkspedisiController::class, 'exportExcel'])->name('buku-ekspedisi.export-excel');
        Route::get('/buku-ekspedisi/import-template', [BukuEkspedisiController::class, 'downloadTemplate'])->name('buku-ekspedisi.import-template');
        Route::middleware('permission:administrasi.manage')->group(function () {
            Route::post('/buku-ekspedisi/import', [BukuEkspedisiController::class, 'import'])->name('buku-ekspedisi.import');
            Route::get('/buku-ekspedisi/create', [BukuEkspedisiController::class, 'create'])->name('buku-ekspedisi.create');
            Route::post('/buku-ekspedisi', [BukuEkspedisiController::class, 'store'])->name('buku-ekspedisi.store');
            Route::get('/buku-ekspedisi/{bukuEkspedisi}/edit', [BukuEkspedisiController::class, 'edit'])->name('buku-ekspedisi.edit');
            Route::put('/buku-ekspedisi/{bukuEkspedisi}', [BukuEkspedisiController::class, 'update'])->name('buku-ekspedisi.update');
            Route::delete('/buku-ekspedisi/{bukuEkspedisi}', [BukuEkspedisiController::class, 'destroy'])->name('buku-ekspedisi.destroy');
        });

        // 9. Buku Aparat Desa (Aparatur Pemerintah Desa)
        Route::get('/aparatur', [AparaturController::class, 'index'])->name('aparatur.index');
        Route::get('/aparatur/export-pdf', [AparaturController::class, 'exportPdf'])->name('aparatur.export-pdf');
        Route::get('/aparatur/export-excel', [AparaturController::class, 'exportExcel'])->name('aparatur.export-excel');
        Route::get('/aparatur/import-template', [AparaturController::class, 'downloadTemplate'])->name('aparatur.import-template');
        Route::middleware('permission:administrasi.manage')->group(function () {
            Route::post('/aparatur/import', [AparaturController::class, 'import'])->name('aparatur.import');
            Route::get('/aparatur/create', [AparaturController::class, 'create'])->name('aparatur.create');
            Route::post('/aparatur', [AparaturController::class, 'store'])->name('aparatur.store');
            Route::get('/aparatur/{aparatur}/edit', [AparaturController::class, 'edit'])->name('aparatur.edit');
            Route::put('/aparatur/{aparatur}', [AparaturController::class, 'update'])->name('aparatur.update');
            Route::delete('/aparatur/{aparatur}', [AparaturController::class, 'destroy'])->name('aparatur.destroy');
        });

        // 10. Administrasi Kelembagaan (8 Lembaga: BPD, BUMDes, Kopdes, LPMD, PKK, Posyandu, Karang Taruna, LMP)
        Route::prefix('kelembagaan/{institution:slug}')->name('kelembagaan.')->group(function () {
            Route::get('/', [KelembagaanController::class, 'show'])->name('show');
            Route::get('/export-pdf', [KelembagaanController::class, 'exportPdf'])->name('export-pdf');
            Route::get('/export-excel', [KelembagaanController::class, 'exportExcel'])->name('export-excel');
            Route::get('/import-template', [KelembagaanController::class, 'downloadTemplate'])->name('import-template');
            Route::post('/import', [KelembagaanController::class, 'import'])->name('import');

            // Anggota
            Route::middleware('permission:administrasi.manage')->group(function () {
                Route::get('/members/create', [KelembagaanController::class, 'createMember'])->name('members.create');
                Route::post('/members', [KelembagaanController::class, 'storeMember'])->name('members.store');
                Route::get('/members/{member}/edit', [KelembagaanController::class, 'editMember'])->name('members.edit');
                Route::put('/members/{member}', [KelembagaanController::class, 'updateMember'])->name('members.update');
                Route::delete('/members/{member}', [KelembagaanController::class, 'destroyMember'])->name('members.destroy');

                // Keputusan
                Route::get('/decisions/create', [KelembagaanController::class, 'createDecision'])->name('decisions.create');
                Route::post('/decisions', [KelembagaanController::class, 'storeDecision'])->name('decisions.store');
                Route::get('/decisions/{decision}/edit', [KelembagaanController::class, 'editDecision'])->name('decisions.edit');
                Route::put('/decisions/{decision}', [KelembagaanController::class, 'updateDecision'])->name('decisions.update');
                Route::delete('/decisions/{decision}', [KelembagaanController::class, 'destroyDecision'])->name('decisions.destroy');

                // Kegiatan
                Route::get('/activities/create', [KelembagaanController::class, 'createActivity'])->name('activities.create');
                Route::post('/activities', [KelembagaanController::class, 'storeActivity'])->name('activities.store');
                Route::get('/activities/{activity}/edit', [KelembagaanController::class, 'editActivity'])->name('activities.edit');
                Route::put('/activities/{activity}', [KelembagaanController::class, 'updateActivity'])->name('activities.update');
                Route::delete('/activities/{activity}', [KelembagaanController::class, 'destroyActivity'])->name('activities.destroy');

                // Agenda
                Route::get('/agendas/create', [KelembagaanController::class, 'createAgenda'])->name('agendas.create');
                Route::post('/agendas', [KelembagaanController::class, 'storeAgenda'])->name('agendas.store');
                Route::get('/agendas/{agenda}/edit', [KelembagaanController::class, 'editAgenda'])->name('agendas.edit');
                Route::put('/agendas/{agenda}', [KelembagaanController::class, 'updateAgenda'])->name('agendas.update');
                Route::delete('/agendas/{agenda}', [KelembagaanController::class, 'destroyAgenda'])->name('agendas.destroy');
            });
        });
    });

    // =============================================================
    // Modul Keuangan Desa (Fase 7)
    // =============================================================
    Route::prefix('keuangan')->name('keuangan.')->middleware('permission:keuangan.view')->group(function () {
        // Portal Hub Keuangan
        Route::get('/', [KeuanganHubController::class, 'index'])->name('index');

        // Master & Realisasi APBDes
        Route::get('/apbdes', [ApbdesController::class, 'index'])->name('apbdes.index');
        Route::get('/apbdes/export-pdf', [ApbdesController::class, 'exportPdf'])->name('apbdes.export-pdf');
        Route::middleware('permission:keuangan.manage')->group(function () {
            Route::get('/apbdes/create', [ApbdesController::class, 'create'])->name('apbdes.create');
            Route::post('/apbdes', [ApbdesController::class, 'store'])->name('apbdes.store');
            Route::get('/apbdes/{apbde}/edit', [ApbdesController::class, 'edit'])->name('apbdes.edit');
            Route::put('/apbdes/{apbde}', [ApbdesController::class, 'update'])->name('apbdes.update');
            Route::delete('/apbdes/{apbde}', [ApbdesController::class, 'destroy'])->name('apbdes.destroy');
        });

        // Buku Kas Umum & Kas Bank
        Route::get('/kas', [BukuKasController::class, 'index'])->name('kas.index');
        Route::get('/kas/export-pdf', [BukuKasController::class, 'exportPdf'])->name('kas.export-pdf');
        Route::middleware('permission:keuangan.manage')->group(function () {
            Route::get('/kas/create', [BukuKasController::class, 'create'])->name('kas.create');
            Route::post('/kas', [BukuKasController::class, 'store'])->name('kas.store');
            Route::get('/kas/{ka}/edit', [BukuKasController::class, 'edit'])->name('kas.edit');
            Route::put('/kas/{ka}', [BukuKasController::class, 'update'])->name('kas.update');
            Route::delete('/kas/{ka}', [BukuKasController::class, 'destroy'])->name('kas.destroy');
        });

        // Rencana Anggaran Biaya (RAB) Desa
        Route::get('/rab', [RabController::class, 'index'])->name('rab.index');
        Route::get('/rab/{rab}/export-pdf', [RabController::class, 'exportPdf'])->name('rab.export-pdf');
        Route::get('/rab/{rab}', [RabController::class, 'show'])->name('rab.show');
        Route::middleware('permission:keuangan.manage')->group(function () {
            Route::get('/rab-baru/create', [RabController::class, 'create'])->name('rab.create');
            Route::post('/rab', [RabController::class, 'store'])->name('rab.store');
            Route::get('/rab/{rab}/edit', [RabController::class, 'edit'])->name('rab.edit');
            Route::put('/rab/{rab}', [RabController::class, 'update'])->name('rab.update');
            Route::patch('/rab/{rab}/status', [RabController::class, 'updateStatus'])->name('rab.update-status');
            Route::delete('/rab/{rab}', [RabController::class, 'destroy'])->name('rab.destroy');
        });
    });

    // =============================================================
    // Modul Pembangunan Desa (Fase 7)
    // =============================================================
    Route::prefix('pembangunan')->name('pembangunan.')->middleware('permission:pembangunan.view')->group(function () {
        // Portal Hub Pembangunan
        Route::get('/', [PembangunanHubController::class, 'index'])->name('index');

        // Proyek Pembangunan Fisik / RKP Desa
        Route::get('/proyek', [ProyekPembangunanController::class, 'index'])->name('proyek.index');
        Route::get('/proyek/export-pdf', [ProyekPembangunanController::class, 'exportPdf'])->name('proyek.export-pdf');
        Route::get('/proyek/{proyek}', [ProyekPembangunanController::class, 'show'])->name('proyek.show');
        Route::middleware('permission:pembangunan.manage')->group(function () {
            Route::get('/proyek-baru/create', [ProyekPembangunanController::class, 'create'])->name('proyek.create');
            Route::post('/proyek', [ProyekPembangunanController::class, 'store'])->name('proyek.store');
            Route::get('/proyek/{proyek}/edit', [ProyekPembangunanController::class, 'edit'])->name('proyek.edit');
            Route::put('/proyek/{proyek}', [ProyekPembangunanController::class, 'update'])->name('proyek.update');
            Route::delete('/proyek/{proyek}', [ProyekPembangunanController::class, 'destroy'])->name('proyek.destroy');
        });

        // Buku Kader Pemberdayaan Masyarakat (KPM)
        Route::get('/kader', [KaderPemberdayaanController::class, 'index'])->name('kader.index');
        Route::get('/kader/export-pdf', [KaderPemberdayaanController::class, 'exportPdf'])->name('kader.export-pdf');
        Route::middleware('permission:pembangunan.manage')->group(function () {
            Route::get('/kader-baru/create', [KaderPemberdayaanController::class, 'create'])->name('kader.create');
            Route::post('/kader', [KaderPemberdayaanController::class, 'store'])->name('kader.store');
            Route::get('/kader/{kader}/edit', [KaderPemberdayaanController::class, 'edit'])->name('kader.edit');
            Route::put('/kader/{kader}', [KaderPemberdayaanController::class, 'update'])->name('kader.update');
            Route::delete('/kader/{kader}', [KaderPemberdayaanController::class, 'destroy'])->name('kader.destroy');
        });

        // Buku Inventaris Hasil Pembangunan
        Route::get('/inventaris-hasil', [InventarisHasilController::class, 'index'])->name('inventaris-hasil.index');
        Route::get('/inventaris-hasil/export-pdf', [InventarisHasilController::class, 'exportPdf'])->name('inventaris-hasil.export-pdf');
        Route::get('/inventaris-hasil/{inventarisHasil}', [InventarisHasilController::class, 'show'])->name('inventaris-hasil.show');
        Route::middleware('permission:pembangunan.manage')->group(function () {
            Route::get('/inventaris-hasil-baru/create', [InventarisHasilController::class, 'create'])->name('inventaris-hasil.create');
            Route::post('/inventaris-hasil', [InventarisHasilController::class, 'store'])->name('inventaris-hasil.store');
            Route::get('/inventaris-hasil/{inventarisHasil}/edit', [InventarisHasilController::class, 'edit'])->name('inventaris-hasil.edit');
            Route::put('/inventaris-hasil/{inventarisHasil}', [InventarisHasilController::class, 'update'])->name('inventaris-hasil.update');
            Route::delete('/inventaris-hasil/{inventarisHasil}', [InventarisHasilController::class, 'destroy'])->name('inventaris-hasil.destroy');
        });
    });
});
