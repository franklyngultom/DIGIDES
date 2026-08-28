<?php

namespace App\Actions\Kependudukan;

use App\Models\Penduduk;
use App\Models\PendudukMutasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecordMutasiAction
{
    /**
     * Record a population mutation event and update penduduk status.
     */
    public function handle(Penduduk $penduduk, array $data): PendudukMutasi
    {
        return DB::transaction(function () use ($penduduk, $data) {
            // Map jenis_mutasi to updated status_penduduk
            $newStatus = match ($data['jenis_mutasi']) {
                'mati' => 'meninggal',
                'pindah_keluar' => 'pindah',
                'pindah_masuk' => 'tetap',
                'lahir' => 'tetap',
                default => $penduduk->status_penduduk,
            };

            // Update penduduk's living status accordingly
            $penduduk->update(['status_penduduk' => $newStatus]);

            // Record the mutation event
            return $penduduk->mutasis()->create([
                'jenis_mutasi' => $data['jenis_mutasi'],
                'tanggal_mutasi' => $data['tanggal_mutasi'],
                'keterangan' => $data['keterangan'] ?? null,
                'berkas_pendukung_path' => $data['berkas_pendukung_path'] ?? null,
                'created_by' => Auth::id(),
            ]);
        });
    }
}
