<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KeuanganRab extends Model
{
    use HasFactory;

    protected $table = 'keuangan_rabs';

    protected $fillable = [
        'tahun_anggaran',
        'nomor_rab',
        'bidang',
        'sub_bidang',
        'nama_kegiatan',
        'lokasi',
        'waktu_pelaksanaan',
        'sumber_dana',
        'nama_ppkd',
        'jabatan_ppkd',
        'total_anggaran',
        'status',
        'keterangan',
        'file_lampiran_path',
        'created_by',
    ];

    protected $casts = [
        'tahun_anggaran' => 'integer',
        'total_anggaran' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(KeuanganRabItem::class, 'keuangan_rab_id')->orderBy('urutan')->orderBy('id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'          => 'Draft Rancangan',
            'disetujui'      => 'Disetujui PPKD/Kades',
            'direalisasikan' => 'Telah Direalisasikan',
            default          => ucfirst($this->status),
        };
    }

    public function getStatusVariantAttribute(): string
    {
        return match ($this->status) {
            'draft'          => 'slate',
            'disetujui'      => 'emerald',
            'direalisasikan' => 'pine',
            default          => 'slate',
        };
    }

    /**
     * Hitung ulang total anggaran berdasarkan item rincian
     */
    public function recalculateTotal(): void
    {
        $this->total_anggaran = $this->items()->sum('total_harga');
        $this->save();
    }
}
