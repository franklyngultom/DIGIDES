<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembangunanProyek extends Model
{
    use HasFactory;

    protected $table = 'pembangunan_proyek';

    protected $fillable = [
        'tahun_anggaran',
        'nama_kegiatan',
        'lokasi',
        'volume',
        'anggaran_biaya',
        'realisasi_biaya',
        'sumber_dana',
        'pelaksana_tpk',
        'status_progres',
        'persentase_selesai',
        'foto_titik_nol',
        'foto_50_persen',
        'foto_100_persen',
        'file_rab_path',
        'manfaat_warga',
    ];

    protected function casts(): array
    {
        return [
            'tahun_anggaran' => 'integer',
            'anggaran_biaya' => 'decimal:2',
            'realisasi_biaya' => 'decimal:2',
            'persentase_selesai' => 'integer',
        ];
    }

    public function inventarisHasil()
    {
        return $this->hasOne(PembangunanInventarisHasil::class, 'pembangunan_proyek_id');
    }
}
