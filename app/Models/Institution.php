<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    use HasFactory;

    protected $table = 'institutions';

    protected $fillable = [
        'nama_lembaga',
        'singkatan',
        'slug',
        'kategori',
        'nomor_sk_pendirian',
        'tanggal_sk',
        'deskripsi',
        'alamat_sekretariat',
        'logo_path',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'tanggal_sk' => 'date',
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(InstitutionMember::class);
    }

    public function activeMembers(): HasMany
    {
        return $this->hasMany(InstitutionMember::class)->where('status_aktif', true);
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(InstitutionDecision::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(InstitutionActivity::class);
    }

    public function agendas(): HasMany
    {
        return $this->hasMany(InstitutionAgenda::class);
    }
}
