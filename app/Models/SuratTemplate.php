<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratTemplate extends Model
{
    use HasFactory;

    protected $table = 'surat_templates';

    protected $fillable = [
        'kode_surat',
        'nama_surat',
        'penomoran_format',
        'template_blade',
        'schema_fields_json',
        'icon',
        'deskripsi',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'schema_fields_json' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relations.
     */
    public function arsips(): HasMany
    {
        return $this->hasMany(SuratArsip::class, 'surat_template_id');
    }

    /**
     * Active scope.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
