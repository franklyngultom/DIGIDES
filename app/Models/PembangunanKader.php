<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembangunanKader extends Model
{
    use HasFactory;

    protected $table = 'pembangunan_kader';

    protected $fillable = [
        'penduduk_id',
        'jenis_kader',
        'jabatan',
        'nomor_sk',
        'tanggal_sk',
        'honor_bulanan',
        'keterangan',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_sk' => 'date',
            'honor_bulanan' => 'decimal:2',
            'status_aktif' => 'boolean',
        ];
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }
}
