<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionActivity extends Model
{
    use HasFactory;

    protected $table = 'institution_activities';

    protected $fillable = [
        'institution_id',
        'nama_kegiatan',
        'tanggal_kegiatan',
        'lokasi',
        'penanggung_jawab',
        'anggaran',
        'sumber_dana',
        'output_hasil',
        'foto_dokumentasi',
        'tahun',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'anggaran' => 'decimal:2',
        'tahun' => 'integer',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
