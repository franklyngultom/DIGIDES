<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitizenProfile extends Model
{
    use HasFactory;

    protected $table = 'citizen_profiles';

    protected $fillable = [
        'user_id',
        'penduduk_id',
        'nik',
        'no_kk',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'pekerjaan',
        'agama',
        'status_perkawinan',
        'foto_ktp_path',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Relationship to User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Penduduk (if matched with village database).
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    /**
     * Accessor for full structured address.
     */
    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: function () {
                $rtRw = '';
                if ($this->rt && $this->rw) {
                    $rtRw = " RT {$this->rt} / RW {$this->rw}";
                }
                $dusun = $this->dusun ? ", Dusun {$this->dusun}" : '';
                return "{$this->alamat}{$rtRw}{$dusun}";
            }
        );
    }

    /**
     * Check if profile is verified.
     */
    public function isVerified(): bool
    {
        return $this->status_verifikasi === 'terverifikasi';
    }
}
