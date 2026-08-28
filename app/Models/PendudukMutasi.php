<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PendudukMutasi extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'penduduk_mutasis';

    protected $fillable = [
        'penduduk_id',
        'jenis_mutasi',
        'tanggal_mutasi',
        'keterangan',
        'berkas_pendukung_path',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mutasi' => 'date',
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
            ->useLogName('mutasi_penduduk');
    }

    /**
     * Relations.
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Human-readable mutation type label.
     */
    protected function jenisMutasiLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->jenis_mutasi) {
                'lahir' => 'Kelahiran',
                'mati' => 'Kematian',
                'pindah_keluar' => 'Pindah Keluar',
                'pindah_masuk' => 'Pindah Masuk',
                default => ucfirst(str_replace('_', ' ', (string) $this->jenis_mutasi)),
            }
        );
    }

    /**
     * Badge color for mutation type.
     */
    protected function badgeColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->jenis_mutasi) {
                'lahir' => 'emerald',
                'mati' => 'red',
                'pindah_keluar' => 'amber',
                'pindah_masuk' => 'blue',
                default => 'gray',
            }
        );
    }
}
