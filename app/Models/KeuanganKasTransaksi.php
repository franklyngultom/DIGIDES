<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeuanganKasTransaksi extends Model
{
    use HasFactory;

    protected $table = 'keuangan_kas_transaksi';

    protected $fillable = [
        'buku_kas_type',
        'kategori_kas',
        'jenis_pembantu',
        'tahun_anggaran',
        'tanggal',
        'nomor_bukti',
        'kode_rekening',
        'uraian',
        'penerimaan',
        'pengeluaran',
        'saldo',
        'sumber_dana',
        'file_bukti_path',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tahun_anggaran' => 'integer',
            'tanggal' => 'date',
            'penerimaan' => 'decimal:2',
            'pengeluaran' => 'decimal:2',
            'saldo' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeTunai($query)
    {
        return $query->where('kategori_kas', 'tunai');
    }

    public function scopeBank($query)
    {
        return $query->where('kategori_kas', 'bank');
    }

    /**
     * Recalculate running balance for a given year and kas category (tunai / bank / type).
     */
    public static function recalculateBalances(int $tahun, string $kategoriOrType = 'tunai'): void
    {
        $kategori = in_array($kategoriOrType, ['bank', 'saldo']) ? 'bank' : 'tunai';
        $legacyType = $kategori === 'bank' ? 'bank' : 'umum';

        $transactions = self::where('tahun_anggaran', $tahun)
            ->where(function ($q) use ($kategori, $legacyType) {
                $q->where('kategori_kas', $kategori)
                  ->orWhere('buku_kas_type', $legacyType);
            })
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $runningSaldo = 0;
        foreach ($transactions as $item) {
            $runningSaldo += ($item->penerimaan - $item->pengeluaran);
            $item->updateQuietly(['saldo' => $runningSaldo]);
        }
    }
}
