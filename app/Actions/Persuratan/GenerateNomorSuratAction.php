<?php

namespace App\Actions\Persuratan;

use App\Models\SuratArsip;
use App\Models\SuratTemplate;
use Carbon\Carbon;

class GenerateNomorSuratAction
{
    /**
     * Roman numerals for months.
     *
     * @var array<int, string>
     */
    private const ROMAN_MONTHS = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
        7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
    ];

    /**
     * Generate sequential formatted letter number for given template and date.
     */
    public function execute(SuratTemplate $template, ?Carbon $date = null): string
    {
        $date = $date ?? Carbon::now();
        $year = $date->year;
        $month = $date->month;
        $romanMonth = self::ROMAN_MONTHS[$month] ?? (string) $month;

        // Count existing letters of this template in the same year
        $count = SuratArsip::where('surat_template_id', $template->id)
            ->whereYear('tanggal_terbit', $year)
            ->count();

        $sequence = $count + 1;
        $formattedSequence = str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);

        $format = $template->penomoran_format;

        return str_replace(
            ['{no}', '{kode}', '{bulan}', '{romawi_bulan}', '{tahun}'],
            [$formattedSequence, $template->kode_surat, str_pad((string) $month, 2, '0', STR_PAD_LEFT), $romanMonth, (string) $year],
            $format
        );
    }
}
