<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionMember extends Model
{
    use HasFactory;

    protected $table = 'institution_members';

    protected $fillable = [
        'institution_id',
        'penduduk_id',
        'nama_lengkap',
        'nik',
        'jabatan',
        'nomor_sk_pengangkatan',
        'tanggal_sk',
        'periode_mulai',
        'periode_selesai',
        'kontak',
        'keterangan',
        'status_aktif',
    ];

    protected $casts = [
        'tanggal_sk' => 'date',
        'periode_mulai' => 'integer',
        'periode_selesai' => 'integer',
        'status_aktif' => 'boolean',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }
}
