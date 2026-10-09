<?php

namespace App\Actions\Persuratan;

use App\Events\SuratDiterbitkanEvent;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\SuratArsip;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TerbitkanSuratPengajuanAction
{
    public function __construct(
        protected GenerateNomorSuratAction $nomorAction,
        protected RenderSuratPdfAction $pdfAction
    ) {}

    /**
     * Terbitkan surat resmi dari permohonan online warga yang telah disetujui / diproses.
     * Idempotent: jika surat sudah pernah diterbitkan, kembalikan record yang ada tanpa duplikasi.
     */
    public function execute(PengajuanSurat $pengajuan, User $petugas, array $options = []): SuratArsip
    {
        // 1. Cek idempotensi: jika sudah ada surat yang terhubung, return yang ada
        if ($pengajuan->surat_arsip_id && $pengajuan->suratArsip) {
            return $pengajuan->suratArsip;
        }

        if ($pengajuan->status === 'ditolak') {
            throw new \DomainException("Pengajuan yang telah ditolak tidak dapat diterbitkan suratnya.");
        }

        return DB::transaction(function () use ($pengajuan, $petugas, $options) {
            // Lock pengajuan baris untuk menghindari race condition
            $pengajuan = PengajuanSurat::lockForUpdate()->find($pengajuan->id);

            if ($pengajuan->surat_arsip_id && $pengajuan->suratArsip) {
                return $pengajuan->suratArsip;
            }

            // 2. Hubungkan atau cari Penduduk
            $penduduk = $this->resolvePenduduk($pengajuan);

            // 3. Template surat
            $template = $pengajuan->suratTemplate;
            if (! $template) {
                throw new \RuntimeException("Template surat untuk pengajuan ini tidak ditemukan.");
            }

            // 4. Generate nomor surat resmi (format sequential DIGIDES standar)
            $nomorSurat = $this->nomorAction->execute($template);

            // 5. Rakit payload snapshot data pemohon & variabel isian formulir
            $payload = array_merge(
                $penduduk->only([
                    'nik', 'no_kk', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
                    'jenis_kelamin', 'agama', 'pekerjaan', 'alamat_lengkap', 'rt', 'rw',
                    'dusun', 'telepon', 'status_penduduk'
                ]),
                $pengajuan->form_data_json ?? [],
                [
                    'nomor_pengajuan' => $pengajuan->nomor_pengajuan,
                    'pemohon_nik'     => $pengajuan->pemohon_nik,
                    'pemohon_nama'    => $pengajuan->pemohon_nama,
                    'pemohon_alamat'  => $pengajuan->pemohon_alamat,
                    'keperluan'       => $pengajuan->keperluan,
                ]
            );

            // 6. Buat record SuratArsip resmi
            $suratArsip = SuratArsip::create([
                'nomor_surat'       => $nomorSurat,
                'surat_template_id' => $template->id,
                'penduduk_id'       => $penduduk->id,
                'user_id'           => $petugas->id,
                'keperluan'         => $pengajuan->keperluan,
                'payload_data'      => $payload,
                'tanggal_terbit'    => now()->toDateString(),
                'status'            => 'terbit',
            ]);

            // 7. Render dan simpan dokumen fisik PDF ke storage
            $this->pdfAction->renderAndStore($suratArsip);

            // 8. Perbarui status pengajuan menjadi selesai dan link dengan surat_arsip_id
            $previousStatus = $pengajuan->status;
            $catatanPetugas = $options['catatan_petugas'] ?? "Surat resmi nomor {$nomorSurat} telah berhasil diterbitkan oleh {$petugas->name}.";
            $pesanPemohon   = $options['pesan_ke_pemohon'] ?? "Surat permohonan Anda telah resmi diterbitkan dengan nomor {$nomorSurat}. Dokumen digital dapat diunduh melalui portal ini.";

            $pengajuan->update([
                'surat_arsip_id'   => $suratArsip->id,
                'status'           => 'selesai',
                'selesai_pada'     => now(),
                'diproses_oleh'    => $petugas->id,
                'diproses_pada'    => $pengajuan->diproses_pada ?? now(),
                'catatan_petugas'  => $catatanPetugas,
                'pesan_ke_pemohon' => $pesanPemohon,
            ]);

            // Catat log transisi status
            $pengajuan->logs()->create([
                'user_id'        => $petugas->id,
                'status_sebelum' => $previousStatus,
                'status_sesudah' => 'selesai',
                'catatan'        => $catatanPetugas,
            ]);

            // Catat ke Spatie activity log
            activity('persuratan_online')
                ->causedBy($petugas)
                ->performedOn($pengajuan)
                ->log("Petugas {$petugas->name} menerbitkan surat resmi {$nomorSurat} untuk permohonan {$pengajuan->nomor_pengajuan}.");

            // 9. Dispatch event untuk sinkronisasi otomatis ke Buku Ekspedisi dan Buku Agenda
            SuratDiterbitkanEvent::dispatch($suratArsip);

            // 10. Kirim notifikasi aman ke akun warga pemohon
            if ($pengajuan->user) {
                $pengajuan->user->notify(new \App\Notifications\StatusPengajuanNotification($pengajuan, $pesanPemohon));
            }

            return $suratArsip;
        });
    }

    /**
     * Temukan data Penduduk yang berelasi atau buat baru jika warga baru mengajukan online.
     */
    protected function resolvePenduduk(PengajuanSurat $pengajuan): Penduduk
    {
        // 1. Cek dari citizen profile jika sudah terkait
        if ($pengajuan->citizenProfile?->penduduk) {
            return $pengajuan->citizenProfile->penduduk;
        }

        // 2. Cek berdasarkan NIK
        $penduduk = Penduduk::where('nik', $pengajuan->pemohon_nik)->first();
        if ($penduduk) {
            if ($pengajuan->citizenProfile && ! $pengajuan->citizenProfile->penduduk_id) {
                $pengajuan->citizenProfile->update(['penduduk_id' => $penduduk->id]);
            }
            return $penduduk;
        }

        // 3. Buat record Penduduk baru dari data profil warga
        $profile = $pengajuan->citizenProfile;

        // Map jenis_kelamin from various formats to enum ('L' / 'P')
        $rawGender = $profile?->jenis_kelamin ?? 'L';
        $jenisKelamin = match (strtolower(trim($rawGender))) {
            'l', 'laki-laki', 'laki_laki', 'male', 'm' => 'L',
            'p', 'perempuan', 'female', 'f'             => 'P',
            default                                      => 'L',
        };

        $penduduk = Penduduk::create([
            'nik'                 => $pengajuan->pemohon_nik,
            'no_kk'               => $pengajuan->pemohon_no_kk ?? $profile?->no_kk ?? '3201010000000001',
            'nama_lengkap'        => $pengajuan->pemohon_nama,
            'tempat_lahir'        => $profile?->tempat_lahir ?? 'Sukabumi',
            'tanggal_lahir'       => $profile?->tanggal_lahir ?? now()->subYears(25)->toDateString(),
            'jenis_kelamin'       => $jenisKelamin,
            'agama'               => $profile?->agama ?? 'Islam',
            'pekerjaan'           => $profile?->pekerjaan ?? 'Wiraswasta',
            'alamat_lengkap'      => $pengajuan->pemohon_alamat ?? ($profile?->full_address ?? 'Desa Sukamaju'),
            'rt'                  => $profile?->rt ?? '000',
            'rw'                  => $profile?->rw ?? '000',
            'dusun'               => $profile?->dusun ?? null,
            'telepon'             => $pengajuan->pemohon_phone ?? $pengajuan->user?->phone,
            'status_penduduk'     => 'tetap',
            'sumber_data'         => 'online',
        ]);

        if ($profile) {
            $profile->update(['penduduk_id' => $penduduk->id]);
        }

        return $penduduk;
    }
}
