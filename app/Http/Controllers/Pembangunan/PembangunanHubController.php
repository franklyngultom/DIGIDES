<?php

namespace App\Http\Controllers\Pembangunan;

use App\Http\Controllers\Controller;
use App\Models\PembangunanInventarisHasil;
use App\Models\PembangunanKader;
use App\Models\PembangunanProyek;
use Illuminate\Http\Request;

class PembangunanHubController extends Controller
{
    public function index(Request $request)
    {
        $tahun = (int) ($request->query('tahun', date('Y')));

        $totalProyek = PembangunanProyek::where('tahun_anggaran', $tahun)->count();
        $totalAnggaranProyek = PembangunanProyek::where('tahun_anggaran', $tahun)->sum('anggaran_biaya');
        $totalRealisasiProyek = PembangunanProyek::where('tahun_anggaran', $tahun)->sum('realisasi_biaya');

        $proyekSelesai = PembangunanProyek::where('tahun_anggaran', $tahun)->where('status_progres', 'selesai')->count();
        $proyekProses = PembangunanProyek::where('tahun_anggaran', $tahun)->where('status_progres', 'proses')->count();
        $proyekPerencanaan = PembangunanProyek::where('tahun_anggaran', $tahun)->where('status_progres', 'perencanaan')->count();

        $totalInventaris = PembangunanInventarisHasil::where('tahun_anggaran', $tahun)->count();
        $totalNilaiInventaris = (float) PembangunanInventarisHasil::where('tahun_anggaran', $tahun)->sum('nilai_aset');

        $totalKader = PembangunanKader::where('status_aktif', true)->count();
        $kaderPosyandu = PembangunanKader::where('status_aktif', true)->where('jenis_kader', 'posyandu')->count();
        $kaderStunting = PembangunanKader::where('status_aktif', true)->where('jenis_kader', 'kpm_stunting')->count();
        $kaderLainnya = $totalKader - $kaderPosyandu - $kaderStunting;

        $recentProyek = PembangunanProyek::where('tahun_anggaran', $tahun)
            ->latest()
            ->take(4)
            ->get();

        $tahuns = PembangunanProyek::selectRaw('DISTINCT tahun_anggaran as tahun')
            ->union(PembangunanInventarisHasil::selectRaw('DISTINCT tahun_anggaran as tahun'))
            ->orderByDesc('tahun')
            ->pluck('tahun');

        if ($tahuns->isEmpty()) {
            $tahuns = collect([$tahun]);
        }

        return view('pembangunan.index', compact(
            'tahun',
            'tahuns',
            'totalProyek',
            'totalAnggaranProyek',
            'totalRealisasiProyek',
            'proyekSelesai',
            'proyekProses',
            'proyekPerencanaan',
            'totalInventaris',
            'totalNilaiInventaris',
            'totalKader',
            'kaderPosyandu',
            'kaderStunting',
            'kaderLainnya',
            'recentProyek'
        ));
    }
}
