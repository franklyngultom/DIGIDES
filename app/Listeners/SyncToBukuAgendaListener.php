<?php

namespace App\Listeners;

use App\Events\SuratDiterbitkanEvent;
use App\Models\BukuAgenda;
use Carbon\Carbon;

class SyncToBukuAgendaListener
{
    /**
     * Handle the event.
     */
    public function handle(SuratDiterbitkanEvent $event): void
    {
        $surat = $event->suratArsip;
        $tahun = Carbon::parse($surat->tanggal_terbit)->year;

        $lastNoUrut = BukuAgenda::where('jenis', 'keluar')
            ->where('tahun', $tahun)
            ->max('nomor_urut') ?? 0;

        BukuAgenda::firstOrCreate(
            ['surat_arsip_id' => $surat->id],
            [
                'jenis' => 'keluar',
                'nomor_urut' => $lastNoUrut + 1,
                'tahun' => $tahun,
                'nomor_surat' => $surat->nomor_surat,
                'tanggal_surat' => $surat->tanggal_terbit,
                'tanggal_diterima_dikirim' => $surat->tanggal_terbit,
                'asal_tujuan' => $surat->penduduk?->nama_lengkap ?? 'Pemohon',
                'perihal' => ($surat->template?->nama_surat ?? 'Surat Keterangan') . ' (' . ($surat->keperluan ?? '-') . ')',
                'file_surat_path' => $surat->file_pdf_path,
                'keterangan' => $surat->pengajuan
                    ? "Pengajuan Online ({$surat->pengajuan->nomor_pengajuan})"
                    : 'Pelayanan Surat Walk-In Desk',
                'created_by' => $surat->user_id,
            ]
        );
    }
}
