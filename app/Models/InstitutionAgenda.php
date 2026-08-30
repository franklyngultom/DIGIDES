<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionAgenda extends Model
{
    use HasFactory;

    protected $table = 'institution_agendas';

    protected $fillable = [
        'institution_id',
        'tanggal_agenda',
        'waktu',
        'nama_agenda',
        'tempat',
        'peserta',
        'pembahasan',
        'status',
        'tahun',
    ];

    protected $casts = [
        'tanggal_agenda' => 'date',
        'tahun' => 'integer',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
