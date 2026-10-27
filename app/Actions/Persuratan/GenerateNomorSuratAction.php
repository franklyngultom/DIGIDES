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

        $pengaturan = \App\Models\SuratPengaturan::current();
        if ($pengaturan && $pengaturan->aturan_penomoran === 'Berlaku Secara Umum' && ! empty($pengaturan->format_nomor_umum)) {
            $pengaturan->increment('inkremen_terakhir');
            $sequence = $pengaturan->inkremen_terakhir;
            $format = $pengaturan->format_nomor_umum;
        } else {
            // Count existing letters of this template in the same year
            $count = SuratArsip::where('surat_template_id', $template->id)
                ->whereYear('tanggal_terbit', $year)
                ->count();
            $sequence = $count + 1;
            $format = $template->penomoran_format ?: '{nomor}/{kode}/{bulan}/{tahun}';
        }

        $formattedSequence = str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);

        return str_replace(
            ['[nnn]', '{no}', '{nomor}', '[kode]', '{kode}', '[br]', '{romawi_bulan}', '[bln]', '{bulan}', '[t]', '{tahun}'],
            [$formattedSequence, $formattedSequence, $formattedSequence, $template->kode_surat, $template->kode_surat, $romanMonth, $romanMonth, str_pad((string) $month, 2, '0', STR_PAD_LEFT), str_pad((string) $month, 2, '0', STR_PAD_LEFT), (string) $year, (string) $year],
            $format
        );
    }
}
