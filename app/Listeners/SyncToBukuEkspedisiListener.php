<?php

namespace App\Listeners;

use App\Events\SuratDiterbitkanEvent;
use App\Models\BukuEkspedisi;
use Carbon\Carbon;

class SyncToBukuEkspedisiListener
{
    /**
     * Handle the event.
     */
    public function handle(SuratDiterbitkanEvent $event): void
    {
        $surat = $event->suratArsip;
        $tahun = Carbon::parse($surat->tanggal_terbit)->year;

        $lastNoUrut = BukuEkspedisi::where('tahun', $tahun)->max('nomor_urut') ?? 0;

        BukuEkspedisi::firstOrCreate(
            ['surat_arsip_id' => $surat->id],
            [
                'nomor_urut' => $lastNoUrut + 1,
                'tahun' => $tahun,
                'tanggal_pengiriman' => $surat->tanggal_terbit,
                'nomor_surat' => $surat->nomor_surat,
                'tanggal_surat' => $surat->tanggal_terbit,
                'perihal' => ($surat->template?->nama_surat ?? 'Surat Keterangan') . ' - ' . ($surat->penduduk?->nama_lengkap ?? '-'),
                'tujuan_penerima' => $surat->payload_data['tujuan_surat'] ?? $surat->payload_data['keperluan'] ?? 'Pemohon / Instansi Terkait',
                'petugas_pengirim' => $surat->user?->name ?? 'Petugas Pelayanan',
                'catatan' => $surat->pengajuan
                    ? "Diterbitkan melalui Pengajuan Online ({$surat->pengajuan->nomor_pengajuan})"
                    : 'Diterbitkan otomatis melalui Pelayanan Walk-In Desk',
            ]
        );
    }
}
