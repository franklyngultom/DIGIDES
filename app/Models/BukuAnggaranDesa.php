<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuAnggaranDesa extends Model
{
    protected $table = 'buku_anggaran_desa';

    protected $fillable = [
        'tahun', 'jenis_dokumen', 'nomor_perdes', 'tanggal_penetapan',
        'total_pendapatan', 'total_belanja', 'total_pembiayaan',
        'keterangan', 'file_pdf_path', 'created_by',
    ];

    protected $casts = [
        'tanggal_penetapan'  => 'date',
        'total_pendapatan'   => 'decimal:2',
        'total_belanja'      => 'decimal:2',
        'total_pembiayaan'   => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
