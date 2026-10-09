<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VillageNews extends Model
{
    use HasFactory;

    protected $table = 'village_news';

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'ringkasan',
        'konten',
        'gambar_path',
        'penulis',
        'views_count',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'views_count' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->judul) . '-' . Str::lower(Str::random(5));
            }
            if (empty($news->published_at)) {
                $news->published_at = now();
            }
        });
    }

    /**
     * Accessor for full image URL.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->gambar_path && file_exists(public_path('storage/' . $this->gambar_path))) {
                    return asset('storage/' . $this->gambar_path);
                }

                if ($this->gambar_path && file_exists(public_path($this->gambar_path))) {
                    return asset($this->gambar_path);
                }

                return asset('images/public/kantor-desa.jpg');
            }
        );
    }

    /**
     * Scope only published news.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope search by keyword.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
                ->orWhere('ringkasan', 'like', "%{$search}%")
                ->orWhere('konten', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%");
        });
    }
}
