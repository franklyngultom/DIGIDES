<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupRecord;
use App\Services\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    /**
     * Display a listing of database backups.
     */
    public function index(): View
    {
        $backups = BackupRecord::with('user')->latest()->paginate(10);
        $totalSize = BackupRecord::where('status', 'success')->sum('size_bytes');

        return view('admin.backup.index', compact('backups', 'totalSize'));
    }

    /**
     * Trigger a new database backup.
     */
    public function store(BackupService $backupService): RedirectResponse
    {
        $record = $backupService->createBackup(Auth::id());

        if ($record->status === 'success') {
            return redirect()->route('admin.backup.index')
                ->with('success', "Cadangan database berhasil dibuat ({$record->filename}).");
        }

        return redirect()->route('admin.backup.index')
            ->with('error', "Gagal membuat cadangan database: {$record->error_message}");
    }

    /**
     * Download the specified backup file.
     */
    public function download(BackupRecord $backup): BinaryFileResponse|RedirectResponse
    {
        $fullPath = storage_path('app/'.$backup->file_path);

        if (! file_exists($fullPath)) {
            return redirect()->back()
                ->with('error', 'Berkas cadangan fisik tidak ditemukan di penyimpanan server.');
        }

        activity('database_backup')
            ->causedBy(Auth::user())
            ->performedOn($backup)
            ->log("Mengunduh berkas cadangan database {$backup->filename}");

        return response()->download($fullPath, $backup->filename);
    }

    /**
     * Remove the specified backup file and record.
     */
    public function destroy(BackupRecord $backup, BackupService $backupService): RedirectResponse
    {
        $filename = $backup->filename;
        $backupService->deleteBackup($backup);

        return redirect()->route('admin.backup.index')
            ->with('success', "Berkas cadangan {$filename} berhasil dihapus.");
    }
}
