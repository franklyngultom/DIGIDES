<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PruneRetentionDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'digides:prune-retention 
                            {--days-notifications=180 : Retensi notifikasi yang sudah dibaca dalam hitungan hari}
                            {--days-logs=730 : Retensi audit log aktivitas dalam hitungan hari}
                            {--dry-run : Menjalankan simulasi pembersihan tanpa menghapus rekaman fisik}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menjalankan pembersihan berkala data kedaluwarsa sesuai Kebijakan Retensi DIGIDES (Notifikasi & Audit Log)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $daysNotif = (int) $this->option('days-notifications');
        $daysLogs = (int) $this->option('days-logs');
        $isDryRun = (bool) $this->option('dry-run');

        $this->info('--- Memulai Eksekusi Kebijakan Retensi Data DIGIDES ---');
        if ($isDryRun) {
            $this->warn('Mode: [DRY-RUN] Data tidak akan dihapus dari basis data.');
        }

        // 1. Pembersihan Notifikasi Terbaca (Retensi standar: 6 Bulan / 180 hari)
        $notifThreshold = now()->subDays($daysNotif);
        $notifCount = 0;
        if (Schema::hasTable('notifications')) {
            $notifQuery = DB::table('notifications')
                ->whereNotNull('read_at')
                ->where('read_at', '<=', $notifThreshold);

            $notifCount = $notifQuery->count();

            if (! $isDryRun && $notifCount > 0) {
                $notifQuery->delete();
            }

            $this->line("Notifikasi terbaca (> {$daysNotif} hari): {$notifCount} rekaman " . ($isDryRun ? 'ditemukan' : 'berhasil dibersihkan') . '.');
        }

        // 2. Pembersihan Log Aktivitas Lama (Retensi standar: 2 Tahun / 730 hari)
        $logThreshold = now()->subDays($daysLogs);
        $logsCount = 0;
        if (Schema::hasTable('activity_log')) {
            $logQuery = DB::table('activity_log')
                ->where('created_at', '<=', $logThreshold);

            $logsCount = $logQuery->count();

            if (! $isDryRun && $logsCount > 0) {
                $logQuery->delete();
            }

            $this->line("Audit Log aktivitas (> {$daysLogs} hari): {$logsCount} rekaman " . ($isDryRun ? 'ditemukan' : 'berhasil dibersihkan') . '.');
        }

        if (! $isDryRun && ($notifCount > 0 || $logsCount > 0)) {
            activity('retensi_data')
                ->log("Pembersihan retensi data otomatis: {$notifCount} notifikasi lama dan {$logsCount} audit log lama telah dibersihkan.");
        }

        $this->info('Pembersihan retensi data selesai dengan sukses.');

        return Command::SUCCESS;
    }
}
