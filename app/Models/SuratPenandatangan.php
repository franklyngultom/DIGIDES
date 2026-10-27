<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SuratPenandatangan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'surat_penandatangans';

    protected $fillable = [
        'aparatur_id',
        'nama',
        'jabatan',
        'nip',
        'urutan',
        'bisa_tanda_tangan',
        'is_camat',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
            'bisa_tanda_tangan' => 'boolean',
            'is_camat' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('surat_penandatangan')
            ->setDescriptionForEvent(fn (string $eventName) => "Penandatangan surat {$this->nama} was {$eventName}");
    }

    public function aparatur(): BelongsTo
    {
        return $this->belongsTo(Aparatur::class, 'aparatur_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('bisa_tanda_tangan', true)->orderBy('urutan');
    }
}
