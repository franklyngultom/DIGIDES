<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeuanganRabItem extends Model
{
    use HasFactory;

    protected $table = 'keuangan_rab_items';

    protected $fillable = [
        'keuangan_rab_id',
        'kode_rekening',
        'kategori',
        'uraian',
        'volume',
        'satuan',
        'harga_satuan',
        'total_harga',
        'keterangan',
        'urutan',
    ];

    protected $casts = [
        'volume'       => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total_harga'  => 'decimal:2',
        'urutan'       => 'integer',
    ];

    public function rab(): BelongsTo
    {
        return $this->belongsTo(KeuanganRab::class, 'keuangan_rab_id');
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'bahan_material'    => 'Bahan & Material',
            'upah_tenaga_kerja' => 'Upah Tenaga Kerja (HOK)',
            'sewa_alat'         => 'Sewa Peralatan',
            'operasional'       => 'Operasional & Lainnya',
            default             => ucfirst(str_replace('_', ' ', $this->kategori)),
        };
    }
}
