<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SuratTemplate extends Model
{
    use HasFactory;

    protected $table = 'surat_templates';

    protected $fillable = [
        'kode_surat',
        'slug',
        'nama_surat',
        'penomoran_format',
        'template_blade',
        'schema_fields_json',
        'persyaratan_json',
        'dokumen_wajib_json',
        'estimasi_proses',
        'biaya',
        'icon',
        'deskripsi',
        'is_active',
        'is_online_available',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'schema_fields_json' => 'array',
            'persyaratan_json' => 'array',
            'dokumen_wajib_json' => 'array',
            'is_active' => 'boolean',
            'is_online_available' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($template) {
            if (empty($template->slug)) {
                $template->slug = Str::slug($template->nama_surat) . '-' . Str::lower(Str::random(4));
            }
        });
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

    /**
     * Online available scope.
     */
    public function scopeOnline(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('is_online_available', true);
    }
}
