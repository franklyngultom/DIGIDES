<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\KeuanganApbdes;
use App\Models\KeuanganKasTransaksi;
use App\Models\KeuanganRab;
use Illuminate\Http\Request;

class KeuanganHubController extends Controller
{
    public function index(Request $request)
    {
        $tahun = (int) ($request->query('tahun', date('Y')));

        // APBDes summary
        $totalPendapatan = KeuanganApbdes::where('tahun_anggaran', $tahun)->where('jenis', 'pendapatan')->sum('anggaran');
        $realisasiPendapatan = KeuanganApbdes::where('tahun_anggaran', $tahun)->where('jenis', 'pendapatan')->sum('realisasi');

        $totalBelanja = KeuanganApbdes::where('tahun_anggaran', $tahun)->where('jenis', 'belanja')->sum('anggaran');
        $realisasiBelanja = KeuanganApbdes::where('tahun_anggaran', $tahun)->where('jenis', 'belanja')->sum('realisasi');

        $totalPembiayaan = KeuanganApbdes::where('tahun_anggaran', $tahun)->where('jenis', 'pembiayaan')->sum('anggaran');
        $realisasiPembiayaan = KeuanganApbdes::where('tahun_anggaran', $tahun)->where('jenis', 'pembiayaan')->sum('realisasi');

        // Kas summary
        $kasUmumPenerimaan = KeuanganKasTransaksi::where('tahun_anggaran', $tahun)->where('buku_kas_type', 'umum')->sum('penerimaan');
        $kasUmumPengeluaran = KeuanganKasTransaksi::where('tahun_anggaran', $tahun)->where('buku_kas_type', 'umum')->sum('pengeluaran');
        $saldoKasUmum = $kasUmumPenerimaan - $kasUmumPengeluaran;

        $kasBankPenerimaan = KeuanganKasTransaksi::where('tahun_anggaran', $tahun)->where('buku_kas_type', 'bank')->sum('penerimaan');
        $kasBankPengeluaran = KeuanganKasTransaksi::where('tahun_anggaran', $tahun)->where('buku_kas_type', 'bank')->sum('pengeluaran');
        $saldoKasBank = $kasBankPenerimaan - $kasBankPengeluaran;

        // RAB summary
        $totalRabCount = KeuanganRab::where('tahun_anggaran', $tahun)->count();
        $totalRabAnggaran = (float) KeuanganRab::where('tahun_anggaran', $tahun)->sum('total_anggaran');

        $recentTransactions = KeuanganKasTransaksi::with('creator')
            ->where('tahun_anggaran', $tahun)
            ->latest('tanggal')
            ->take(6)
            ->get();

        $tahuns = KeuanganApbdes::selectRaw('DISTINCT tahun_anggaran as tahun')
            ->union(KeuanganKasTransaksi::selectRaw('DISTINCT tahun_anggaran as tahun'))
            ->union(KeuanganRab::selectRaw('DISTINCT tahun_anggaran as tahun'))
            ->orderByDesc('tahun')
            ->pluck('tahun');

        if ($tahuns->isEmpty()) {
            $tahuns = collect([$tahun]);
        }

        return view('keuangan.index', compact(
            'tahun',
            'tahuns',
            'totalPendapatan',
            'realisasiPendapatan',
            'totalBelanja',
            'realisasiBelanja',
            'totalPembiayaan',
            'realisasiPembiayaan',
            'saldoKasUmum',
            'saldoKasBank',
            'totalRabCount',
            'totalRabAnggaran',
            'recentTransactions'
        ));
    }
}
