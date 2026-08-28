<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aparatur extends Model
{
    use HasFactory;

    protected $fillable = [
        'penduduk_id',
        'nip',
        'jabatan',
        'qr_token',
        'jam_masuk_standar',
        'jam_pulang_standar',
        'toleransi_terlambat_menit',
        'status_kepegawaian',
        'status_aktif',
    ];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}
