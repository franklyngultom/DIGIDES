<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\DesaProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Kependudukan\DocumentController;
use App\Http\Controllers\Kependudukan\DuplicateScannerController;
use App\Http\Controllers\Kependudukan\MutasiController;
use App\Http\Controllers\Kependudukan\PendudukController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - DIGIDES v2
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard or login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Main 3-Column Working Productivity Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin & Core Infrastructure Routes
    Route::prefix('admin')->name('admin.')->group(function () {

        // 1. Profil Desa
        Route::middleware('permission:desa.view')->group(function () {
            Route::get('/desa', [DesaProfileController::class, 'index'])->name('desa.index');
        });
        Route::middleware('permission:desa.update')->group(function () {
            Route::put('/desa', [DesaProfileController::class, 'update'])->name('desa.update');
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

        // Buku Induk Penduduk
        Route::middleware('permission:kependudukan.view')->group(function () {
            Route::get('/', [PendudukController::class, 'index'])->name('index');
            Route::get('/{penduduk}', [PendudukController::class, 'show'])->name('show');
        });

        Route::middleware('permission:kependudukan.create')->group(function () {
            Route::get('/create', [PendudukController::class, 'create'])->name('create');
            Route::post('/', [PendudukController::class, 'store'])->name('store');
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
// Absensi Module Routes
Route::prefix('absensi')->name('absensi.')->middleware('permission:absensi.access')->group(function () {
    Route::get('/scanner', [App\Http\Controllers\Administrasi\AbsensiController::class, 'scanner'])->name('scanner');
    Route::post('/scan', [App\Http\Controllers\Administrasi\AbsensiController::class, 'scan'])->name('scan');
    Route::get('/manual', [App\Http\Controllers\Administrasi\AbsensiController::class, 'manualForm'])->name('manual.form');
    Route::post('/manual', [App\Http\Controllers\Administrasi\AbsensiController::class, 'manualStore'])->name('manual.store');
    Route::get('/export', [App\Http\Controllers\Administrasi\AbsensiController::class, 'exportPdf'])->name('export.pdf');
});
});
