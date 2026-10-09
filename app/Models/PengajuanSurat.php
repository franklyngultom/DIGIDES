<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'nomor_pengajuan',
        'user_id',
        'citizen_profile_id',
        'surat_template_id',
        'pemohon_nama',
        'pemohon_nik',
        'pemohon_no_kk',
        'pemohon_alamat',
        'pemohon_phone',
        'form_data_json',
        'dokumen_path_json',
        'status',
        'catatan_petugas',
        'pesan_ke_pemohon',
        'diproses_oleh',
        'diproses_pada',
        'selesai_pada',
        'surat_arsip_id',
    ];

    protected function casts(): array
    {
        return [
            'form_data_json'    => 'array',
            'dokumen_path_json' => 'array',
            'diproses_pada'     => 'datetime',
            'selesai_pada'      => 'datetime',
        ];
    }

    // ==============================================================
    //  Boot: auto-generate unique submission number
    // ==============================================================
    protected static function boot()
    {
        parent::boot();

        static::creating(function (PengajuanSurat $model) {
            if (empty($model->nomor_pengajuan)) {
                $year  = now()->year;
                $count = static::whereYear('created_at', $year)->count() + 1;
                $model->nomor_pengajuan = 'PGJ-' . $year . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
            }
        });

        // Automatically log status changes
        static::updating(function (PengajuanSurat $model) {
            if ($model->isDirty('status')) {
                $model->logs()->create([
                    'user_id'        => auth()->id(),
                    'status_sebelum' => $model->getOriginal('status'),
                    'status_sesudah' => $model->status,
                    'catatan'        => $model->catatan_petugas,
                ]);
            }
        });
    }

    // ==============================================================
    //  Relationships
    // ==============================================================
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function citizenProfile(): BelongsTo
    {
        return $this->belongsTo(CitizenProfile::class, 'citizen_profile_id');
    }

    public function suratTemplate(): BelongsTo
    {
        return $this->belongsTo(SuratTemplate::class, 'surat_template_id');
    }

    public function diprosesByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function suratArsip(): BelongsTo
    {
        return $this->belongsTo(SuratArsip::class, 'surat_arsip_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PengajuanSuratLog::class)->orderBy('created_at', 'desc');
    }

    // ==============================================================
    //  Scopes
    // ==============================================================
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['menunggu', 'diproses', 'perlu_perbaikan']);
    }

    // ==============================================================
    //  Helpers
    // ==============================================================
    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu'        => 'Menunggu Review',
            'diproses'        => 'Sedang Diproses',
            'perlu_perbaikan' => 'Perlu Perbaikan',
            'disetujui'       => 'Disetujui',
            'selesai'         => 'Selesai',
            'ditolak'         => 'Ditolak',
            default           => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'menunggu'        => 'amber',
            'diproses'        => 'blue',
            'perlu_perbaikan' => 'orange',
            'disetujui'       => 'teal',
            'selesai'         => 'green',
            'ditolak'         => 'red',
            default           => 'gray',
        };
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function getKeperluanAttribute(): ?string
    {
        return $this->form_data_json['keperluan'] ?? '-';
    }
}
