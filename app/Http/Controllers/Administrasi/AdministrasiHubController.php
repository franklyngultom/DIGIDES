<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuAgenda;
use App\Models\BukuAnggaranDesa;
use App\Models\BukuEkspedisi;
use App\Models\BukuInventarisAset;
use App\Models\BukuKeputusanKades;
use App\Models\BukuLembaranDesa;
use App\Models\BukuPeraturanDesa;
use App\Models\BukuTanahDesa;
use Illuminate\Http\Request;

class AdministrasiHubController extends Controller
{
    /**
     * Display the Administrasi Umum 8-Register Hub Dashboard.
     */
    public function index(Request $request)
    {
        $currentYear = (int) ($request->query('tahun', date('Y')));

        $stats = [
            'peraturan_desa' => [
                'total' => BukuPeraturanDesa::count(),
                'this_year' => BukuPeraturanDesa::where('tahun', $currentYear)->count(),
                'latest' => BukuPeraturanDesa::latest()->first(),
            ],
            'keputusan_kades' => [
                'total' => BukuKeputusanKades::count(),
                'this_year' => BukuKeputusanKades::where('tahun', $currentYear)->count(),
                'latest' => BukuKeputusanKades::latest()->first(),
            ],
            'inventaris_aset' => [
                'total' => BukuInventarisAset::count(),
                'total_nilai' => BukuInventarisAset::sum('harga_perolehan'),
                'latest' => BukuInventarisAset::latest()->first(),
            ],
            'tanah_desa' => [
                'total' => BukuTanahDesa::count(),
                'total_luas' => BukuTanahDesa::sum('luas_m2'),
                'latest' => BukuTanahDesa::latest()->first(),
            ],
            'anggaran_desa' => [
                'total' => BukuAnggaranDesa::count(),
                'this_year' => BukuAnggaranDesa::where('tahun', $currentYear)->count(),
                'latest' => BukuAnggaranDesa::latest()->first(),
            ],
            'lembaran_desa' => [
                'total' => BukuLembaranDesa::count(),
                'this_year' => BukuLembaranDesa::where('tahun', $currentYear)->count(),
                'latest' => BukuLembaranDesa::latest()->first(),
            ],
            'buku_agenda' => [
                'total' => BukuAgenda::count(),
                'masuk' => BukuAgenda::where('jenis', 'masuk')->count(),
                'keluar' => BukuAgenda::where('jenis', 'keluar')->count(),
                'latest' => BukuAgenda::latest()->first(),
            ],
            'buku_ekspedisi' => [
                'total' => BukuEkspedisi::count(),
                'this_year' => BukuEkspedisi::where('tahun', $currentYear)->count(),
                'latest' => BukuEkspedisi::latest()->first(),
            ],
        ];

        return view('administrasi.index', compact('stats', 'currentYear'));
    }
}
