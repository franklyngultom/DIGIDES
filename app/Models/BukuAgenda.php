<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BukuAgenda extends Model
{
    use HasFactory;

    protected $table = 'buku_agendas';

    protected $fillable = [
        'jenis',
        'nomor_urut',
        'tahun',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_diterima_dikirim',
        'asal_tujuan',
        'perihal',
        'surat_arsip_id',
        'file_surat_path',
        'keterangan',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_diterima_dikirim' => 'date',
        ];
    }

    /**
     * Relations.
     */
    public function suratArsip(): BelongsTo
    {
        return $this->belongsTo(SuratArsip::class, 'surat_arsip_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scopes.
     */
    public function scopeJenis(Builder $query, ?string $jenis): Builder
    {
        return $jenis ? $query->where('jenis', $jenis) : $query;
    }

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
                ->orWhere('asal_tujuan', 'like', "%{$search}%");
        });
    }
}
