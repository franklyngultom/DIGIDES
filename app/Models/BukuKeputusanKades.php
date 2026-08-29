<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuKeputusanKades extends Model
{
    protected $table = 'buku_keputusan_kades';

    protected $fillable = [
        'tahun', 'nomor_keputusan', 'tanggal_keputusan', 'tentang',
        'uraian_singkat', 'nomor_dilaporkan', 'file_pdf_path', 'created_by',
    ];

    protected $casts = [
        'tanggal_keputusan' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
