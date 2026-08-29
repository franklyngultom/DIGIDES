<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuTanahDesa extends Model
{
    protected $table = 'buku_tanah_desa';

    protected $fillable = [
        'jenis_tanah', 'nomor_sertifikat_letter_c', 'nama_pemilik_asal',
        'luas_m2', 'kelas_tanah', 'lokasi_blok', 'peruntukan_saat_ini',
        'patok_tanda_batas', 'file_warkah_path', 'created_by',
    ];

    protected $casts = [
        'luas_m2' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
