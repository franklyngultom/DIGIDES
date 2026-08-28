<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Penduduk extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'penduduks';

    protected $fillable = [
        'nik',
        'no_kk',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'pendidikan_terakhir',
        'pekerjaan',
        'status_perkawinan',
        'status_dalam_keluarga',
        'kewarganegaraan',
        'golongan_darah',
        'alamat_lengkap',
        'rt',
        'rw',
        'dusun',
        'telepon',
        'sumber_data',
        'status_penduduk',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    /**
     * Activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('kependudukan')
            ->setDescriptionForEvent(fn (string $eventName) => "Data kependudukan {$this->nama_lengkap} (NIK: {$this->masked_nik}) was {$eventName}");
    }

    /**
     * Relations.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(PendudukDocument::class);
    }

    public function mutasis(): HasMany
    {
        return $this->hasMany(PendudukMutasi::class)->latest('tanggal_mutasi');
    }

    /**
     * Privacy Masked NIK Accessor (Hanya 6 digit awal dan 4 digit akhir ditampilkan).
     * Contoh: 320211******0001
     */
    protected function maskedNik(): Attribute
    {
        return Attribute::make(
            get: function () {
                $nik = (string) $this->nik;
                if (strlen($nik) === 16) {
                    return substr($nik, 0, 6).'******'.substr($nik, -4);
                }

                return substr($nik, 0, 4).'****'.substr($nik, -2);
            }
        );
    }

    /**
     * Privacy Masked Phone Accessor.
     * Contoh: 0812****7890
     */
    protected function maskedPhone(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (blank($this->telepon)) {
                    return '-';
                }
                $phone = (string) $this->telepon;
                if (strlen($phone) >= 8) {
                    return substr($phone, 0, 4).'****'.substr($phone, -4);
                }

                return $phone;
            }
        );
    }

    /**
     * Age in years calculated from birth date.
     */
    protected function umur(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_lahir ? Carbon::parse($this->tanggal_lahir)->age : 0
        );
    }

    /**
     * Age classification category.
     */
    protected function kategoriUsia(): Attribute
    {
        return Attribute::make(
            get: function () {
                $age = $this->umur;
                if ($age <= 5) {
                    return 'Balita';
                }
                if ($age <= 18) {
                    return 'Usia Sekolah';
                }
                if ($age <= 59) {
                    return 'Produktif';
                }

                return 'Lansia';
            }
        );
    }

    /**
     * Human readable Gender label.
     */
    protected function jenisKelaminLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan'
        );
    }

    /**
     * Human readable Marital Status label.
     */
    protected function statusPerkawinanLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status_perkawinan) {
                'belum_kawin' => 'Belum Kawin',
                'kawin' => 'Kawin',
                'cerai_hidup' => 'Cerai Hidup',
                'cerai_mati' => 'Cerai Mati',
                default => ucfirst(str_replace('_', ' ', (string) $this->status_perkawinan))
            }
        );
    }

    /**
     * Human readable Family Status label.
     */
    protected function statusKeluargaLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status_dalam_keluarga) {
                'kepala_keluarga' => 'Kepala Keluarga',
                'istri' => 'Istri',
                'anak' => 'Anak',
                'famili_lain' => 'Famili Lain',
                default => ucfirst(str_replace('_', ' ', (string) $this->status_dalam_keluarga))
            }
        );
    }

    /**
     * Scopes.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%")
                ->orWhere('no_kk', 'like', "%{$search}%")
                ->orWhere('alamat_lengkap', 'like', "%{$search}%")
                ->orWhere('dusun', 'like', "%{$search}%");
        });
    }

    public function scopeDusun(Builder $query, ?string $dusun): Builder
    {
        return blank($dusun) ? $query : $query->where('dusun', $dusun);
    }

    public function scopeRtRw(Builder $query, ?string $rt, ?string $rw): Builder
    {
        if (! blank($rt)) {
            $query->where('rt', $rt);
        }
        if (! blank($rw)) {
            $query->where('rw', $rw);
        }

        return $query;
    }

    public function scopeJenisKelamin(Builder $query, ?string $jk): Builder
    {
        return blank($jk) ? $query : $query->where('jenis_kelamin', $jk);
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return blank($status) ? $query : $query->where('status_penduduk', $status);
    }

    public function scopeKategoriUsia(Builder $query, ?string $kategori): Builder
    {
        if (blank($kategori)) {
            return $query;
        }

        $now = Carbon::now();

        return match ($kategori) {
            'balita' => $query->whereDate('tanggal_lahir', '>=', $now->copy()->subYears(5)->toDateString()),
            'sekolah' => $query->whereDate('tanggal_lahir', '>=', $now->copy()->subYears(18)->toDateString())
                ->whereDate('tanggal_lahir', '<', $now->copy()->subYears(5)->toDateString()),
            'produktif' => $query->whereDate('tanggal_lahir', '>=', $now->copy()->subYears(59)->toDateString())
                ->whereDate('tanggal_lahir', '<', $now->copy()->subYears(18)->toDateString()),
            'lansia' => $query->whereDate('tanggal_lahir', '<', $now->copy()->subYears(59)->toDateString()),
            default => $query
        };
    }
}
