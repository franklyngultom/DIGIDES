<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DesaProfile extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'desa_profiles';

    protected $fillable = [
        'nama_desa',
        'kode_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'alamat_kantor',
        'email_desa',
        'telepon_desa',
        'website',
        'logo_path',
        'nama_kades',
        'nip_kades',
        'nik_kades',
    ];

    /**
     * Activity log configuration.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('desa_profile')
            ->setDescriptionForEvent(fn (string $eventName) => "Profil desa {$this->nama_desa} was {$eventName}");
    }

    /**
     * Helper to get or initialize the active village profile.
     */
    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'nama_desa' => 'Desa Sukamaju',
                'kode_desa' => '3202112001',
                'kecamatan' => 'Cikole',
                'kabupaten' => 'Sukabumi',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '43113',
                'alamat_kantor' => 'Jl. Raya Sukamaju No. 01, Kec. Cikole, Kab. Sukabumi',
                'email_desa' => 'kontak@desa-sukamaju.id',
                'telepon_desa' => '0266-221144',
                'website' => 'https://desa-sukamaju.id',
                'nama_kades' => 'H. Rahmat Hidayat, S.IP',
                'nip_kades' => '197508172005011003',
                'nik_kades' => '3202111708750001',
            ]
        );
    }

    /**
     * Accessor for village logo URL.
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->logo_path && file_exists(public_path('storage/'.$this->logo_path))) {
                    return asset('storage/'.$this->logo_path);
                }

                if ($this->logo_path && file_exists(public_path($this->logo_path))) {
                    return asset($this->logo_path);
                }

                return null;
            }
        );
    }

    /**
     * Accessor for full structured address.
     */
    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->alamat_kantor}, Kec. {$this->kecamatan}, Kab. {$this->kabupaten}, {$this->provinsi} {$this->kode_pos}"
        );
    }
}
