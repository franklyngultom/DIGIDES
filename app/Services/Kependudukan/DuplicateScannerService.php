<?php

namespace App\Services\Kependudukan;

use App\Models\Penduduk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DuplicateScannerService
{
    /**
     * Scan for duplicate NIKs in the database.
     *
     * @return Collection<int, array{nik: string, count: int, records: Collection}>
     */
    public function scanDuplicates(): Collection
    {
        // Find NIKs that appear more than once
        $duplicateNiks = DB::table('penduduks')
            ->select('nik', DB::raw('COUNT(*) as count'))
            ->groupBy('nik')
            ->having('count', '>', 1)
            ->pluck('count', 'nik');

        if ($duplicateNiks->isEmpty()) {
            return collect();
        }

        return $duplicateNiks->map(function ($count, $nik) {
            return [
                'nik' => $nik,
                'count' => $count,
                'records' => Penduduk::where('nik', $nik)->get(),
            ];
        })->values();
    }

    /**
     * Scan for NIKs with invalid format (not 16 numeric digits).
     *
     * @return Collection<int, Penduduk>
     */
    public function scanAnomaliFormat(): Collection
    {
        return Penduduk::all()->filter(function (Penduduk $penduduk) {
            $nik = (string) $penduduk->nik;

            // Must be exactly 16 digits and all numeric
            return strlen($nik) !== 16 || ! ctype_digit($nik);
        })->values();
    }

    /**
     * Run a full scan and return a structured summary.
     *
     * @return array{duplicates: Collection, anomali: Collection, total_issues: int}
     */
    public function fullScan(): array
    {
        $duplicates = $this->scanDuplicates();
        $anomali = $this->scanAnomaliFormat();

        return [
            'duplicates' => $duplicates,
            'anomali' => $anomali,
            'total_issues' => $duplicates->count() + $anomali->count(),
        ];
    }
}
