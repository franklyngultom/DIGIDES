<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionDecision extends Model
{
    use HasFactory;

    protected $table = 'institution_decisions';

    protected $fillable = [
        'institution_id',
        'nomor_keputusan',
        'tanggal_keputusan',
        'tentang',
        'uraian_singkat',
        'dokumen_path',
        'tahun',
    ];

    protected $casts = [
        'tanggal_keputusan' => 'date',
        'tahun' => 'integer',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
