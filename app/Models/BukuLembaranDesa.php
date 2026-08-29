<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuLembaranDesa extends Model
{
    protected $table = 'buku_lembaran_desa';

    protected $fillable = [
        'tahun', 'jenis', 'nomor_seri', 'tanggal_diundangkan',
        'judul', 'isi_singkat', 'file_pdf_path', 'created_by',
    ];

    protected $casts = [
        'tanggal_diundangkan' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
