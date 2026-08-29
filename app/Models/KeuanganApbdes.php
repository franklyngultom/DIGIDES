<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganApbdes extends Model
{
    use HasFactory;

    protected $table = 'keuangan_apbdes';

    protected $fillable = [
        'tahun_anggaran',
        'kode_rekening',
        'jenis',
        'bidang',
        'uraian',
        'anggaran',
        'realisasi',
        'sumber_dana',
    ];

    protected function casts(): array
    {
        return [
            'tahun_anggaran' => 'integer',
            'anggaran' => 'decimal:2',
            'realisasi' => 'decimal:2',
        ];
    }

    public function getPersentaseRealisasiAttribute(): float
    {
        if ($this->anggaran <= 0) {
            return 0.0;
        }

        return round(($this->realisasi / $this->anggaran) * 100, 2);
    }
}
