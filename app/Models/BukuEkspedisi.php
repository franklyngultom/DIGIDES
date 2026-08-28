<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BukuEkspedisi extends Model
{
    use HasFactory;

    protected $table = 'buku_ekspedisis';

    protected $fillable = [
        'nomor_urut',
        'tahun',
        'tanggal_pengiriman',
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'tujuan_penerima',
        'petugas_pengirim',
        'surat_arsip_id',
        'catatan',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'tanggal_pengiriman' => 'date',
            'tanggal_surat' => 'date',
        ];
    }

    /**
     * Relations.
     */
    public function suratArsip(): BelongsTo
    {
        return $this->belongsTo(SuratArsip::class, 'surat_arsip_id');
    }

    /**
     * Scopes.
     */
    public function scopeTahun(Builder $query, ?int $tahun): Builder
    {
        return $tahun ? $query->where('tahun', $tahun) : $query;
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('nomor_surat', 'like', "%{$search}%")
                ->orWhere('perihal', 'like', "%{$search}%")
                ->orWhere('tujuan_penerima', 'like', "%{$search}%")
                ->orWhere('petugas_pengirim', 'like', "%{$search}%");
        });
    }
}
