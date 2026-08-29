<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembangunanInventarisHasil extends Model
{
    use HasFactory;

    protected $table = 'pembangunan_inventaris_hasil';

    protected $fillable = [
        'tahun_anggaran',
        'nomor_inventaris',
        'nama_hasil_pembangunan',
        'pembangunan_proyek_id',
        'kategori_aset',
        'volume',
        'lokasi',
        'tanggal_serah_terima',
        'sumber_dana',
        'nilai_aset',
        'kondisi',
        'status_pengelolaan',
        'penanggung_jawab',
        'keterangan',
        'foto_hasil_path',
        'file_bast_path',
        'created_by',
    ];

    protected $casts = [
        'tahun_anggaran'       => 'integer',
        'nilai_aset'           => 'decimal:2',
        'tanggal_serah_terima' => 'date',
    ];

    public function proyek(): BelongsTo
    {
        return $this->belongsTo(PembangunanProyek::class, 'pembangunan_proyek_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori_aset) {
            'jalan_jembatan'    => 'Jalan & Jembatan',
            'bangunan_gedung'   => 'Bangunan & Gedung',
            'irigasi_sanitasi'  => 'Irigasi & Drainase',
            'sarana_air_bersih' => 'Sarana Air Bersih',
            'sarana_olahraga'   => 'Sarana Olahraga',
            'fasilitas_umum'    => 'Fasilitas Umum & Sosial',
            default             => 'Lainnya',
        };
    }

    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi) {
            'baik'         => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            default        => ucfirst($this->kondisi),
        };
    }

    public function getKondisiVariantAttribute(): string
    {
        return match ($this->kondisi) {
            'baik'         => 'emerald',
            'rusak_ringan' => 'amber',
            'rusak_berat'  => 'rose',
            default        => 'slate',
        };
    }

    public function getStatusPengelolaanLabelAttribute(): string
    {
        return match ($this->status_pengelolaan) {
            'dikelola_desa'            => 'Dikelola Pemerintah Desa',
            'diserahkan_ke_masyarakat' => 'Diserahkan ke Masyarakat (RW/RT)',
            'dikelola_bumdes'          => 'Dikelola BUMDes',
            'dihibahkan'               => 'Dihibahkan',
            default                    => ucfirst(str_replace('_', ' ', $this->status_pengelolaan)),
        };
    }
}
