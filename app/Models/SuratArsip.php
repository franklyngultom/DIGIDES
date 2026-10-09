<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SuratArsip extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'surat_arsips';

    protected $fillable = [
        'nomor_surat',
        'surat_template_id',
        'penduduk_id',
        'user_id',
        'keperluan',
        'payload_data',
        'file_pdf_path',
        'tanggal_terbit',
        'status',
        'alasan_pembatalan',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'payload_data' => 'array',
            'tanggal_terbit' => 'date',
        ];
    }

    /**
     * Activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('persuratan')
            ->setDescriptionForEvent(fn (string $eventName) => "Surat {$this->nomor_surat} ({$this->template?->nama_surat}) was {$eventName}");
    }

    /**
     * Relations.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(SuratTemplate::class, 'surat_template_id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ekspedisi(): HasOne
    {
        return $this->hasOne(BukuEkspedisi::class, 'surat_arsip_id');
    }

    public function agenda(): HasOne
    {
        return $this->hasOne(BukuAgenda::class, 'surat_arsip_id');
    }

    public function pengajuan(): HasOne
    {
        return $this->hasOne(PengajuanSurat::class, 'surat_arsip_id');
    }

    /**
     * Scopes.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('nomor_surat', 'like', "%{$search}%")
                ->orWhere('keperluan', 'like', "%{$search}%")
                ->orWhereHas('penduduk', function (Builder $qp) use ($search) {
                    $qp->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                });
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'terbit');
    }
}
