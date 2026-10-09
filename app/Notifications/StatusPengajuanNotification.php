<?php

namespace App\Notifications;

use App\Models\PengajuanSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusPengajuanNotification extends Notification
{
    use Queueable;

    public function __construct(
        public PengajuanSurat $pengajuan,
        public ?string $pesanKhusus = null
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $status = $this->pengajuan->status;
        $label = $this->pengajuan->statusLabel();
        $namaSurat = $this->pengajuan->suratTemplate?->nama_surat ?? 'Layanan Surat';

        [$title, $defaultMessage] = match ($status) {
            'diproses' => [
                'Permohonan Sedang Diproses',
                "Permohonan {$namaSurat} Anda sedang diperiksa dan diproses oleh staf pelayanan desa.",
            ],
            'perlu_perbaikan' => [
                'Perlu Perbaikan Berkas',
                "Petugas memerlukan perbaikan atau kelengkapan berkas untuk permohonan {$namaSurat} Anda. Silakan periksa catatan petugas.",
            ],
            'disetujui' => [
                'Permohonan Telah Disetujui',
                "Permohonan {$namaSurat} Anda telah disetujui dan sedang dalam tahap penerbitan surat resmi.",
            ],
            'selesai' => [
                'Surat Resmi Selesai Diterbitkan',
                "Surat resmi permohonan {$namaSurat} Anda telah diterbitkan dan siap diunduh atau diambil.",
            ],
            'ditolak' => [
                'Permohonan Tidak Dapat Diproses',
                "Permohonan {$namaSurat} Anda belum dapat disetujui. Silakan periksa alasan penolakan petugas.",
            ],
            default => [
                "Pembaruan Status: {$label}",
                "Status permohonan {$namaSurat} Anda telah diperbarui menjadi {$label}.",
            ],
        };

        // Pesan ringkas, prioritaskan pesan petugas jika ada tanpa membocorkan data pribadi
        $finalMessage = $this->pesanKhusus ? trim($this->pesanKhusus) : $defaultMessage;

        return [
            'pengajuan_id'     => $this->pengajuan->id,
            'nomor_pengajuan'  => $this->pengajuan->nomor_pengajuan,
            'nama_surat'       => $namaSurat,
            'status'           => $status,
            'status_label'     => $label,
            'title'            => $title,
            'message'          => $finalMessage,
            'action_url'       => route('masyarakat.pengajuan.show', $this->pengajuan),
        ];
    }
}
