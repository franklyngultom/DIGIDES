<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanSuratLog extends Model
{
    public $timestamps = false;

    protected $table = 'pengajuan_surat_logs';

    protected $fillable = [
        'pengajuan_surat_id',
        'user_id',
        'status_sebelum',
        'status_sesudah',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_surat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
