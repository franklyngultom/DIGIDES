<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuPeraturanDesa extends Model
{
    protected $table = 'buku_peraturan_desa';

    protected $fillable = [
        'tahun', 'jenis_peraturan', 'nomor_ditetapkan', 'tanggal_ditetapkan',
        'tentang', 'uraian_singkat', 'nomor_kesepakatan_bpd',
        'nomor_diundangkan', 'tanggal_diundangkan', 'file_pdf_path', 'created_by',
    ];

    protected $casts = [
        'tanggal_ditetapkan' => 'date',
        'tanggal_diundangkan' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
