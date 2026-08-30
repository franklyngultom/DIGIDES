<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use App\Models\BukuAgenda;
use App\Models\BukuEkspedisi;
use App\Models\BukuInventarisAset;
use App\Models\BukuKeputusanKades;
use App\Models\BukuLembaranDesa;
use App\Models\BukuPeraturanDesa;
use App\Models\BukuTanahDesa;
use App\Models\Institution;
use App\Models\InstitutionActivity;
use App\Models\InstitutionAgenda;
use App\Models\InstitutionDecision;
use App\Models\InstitutionMember;
use Illuminate\Http\Request;

class AdministrasiHubController extends Controller
{
    /**
     * Display the Administrasi Hub Dashboard (Administrasi Umum & Administrasi Kelembagaan).
     */
    public function index(Request $request)
    {
        $currentYear = (int) ($request->query('tahun', date('Y')));
        $activeTab = $request->query('tab', 'umum');

        // ==========================================
        // 1. STATS: ADMINISTRASI UMUM (9 BUKU REGISTER)
        // ==========================================
        $statsUmum = [
            'peraturan_desa' => [
                'total' => BukuPeraturanDesa::count(),
                'this_year' => BukuPeraturanDesa::where('tahun', $currentYear)->count(),
            ],
            'keputusan_kades' => [
                'total' => BukuKeputusanKades::count(),
                'this_year' => BukuKeputusanKades::where('tahun', $currentYear)->count(),
            ],
            'inventaris_aset' => [
                'total' => BukuInventarisAset::count(),
                'total_nilai' => BukuInventarisAset::sum('harga_perolehan'),
            ],
            'aparat_desa' => [
                'total' => Aparatur::count(),
                'aktif' => Aparatur::where('status_aktif', true)->count(),
            ],
            'tanah_kas_desa' => [
                'total' => BukuTanahDesa::where('jenis_tanah', 'tanah_kas_desa')->count(),
                'total_luas' => BukuTanahDesa::where('jenis_tanah', 'tanah_kas_desa')->sum('luas_m2'),
            ],
            'luas_tanah_desa' => [
                'total' => BukuTanahDesa::count(),
                'total_luas' => BukuTanahDesa::sum('luas_m2'),
            ],
            'buku_agenda' => [
                'total' => BukuAgenda::count(),
                'masuk' => BukuAgenda::where('jenis', 'masuk')->count(),
                'keluar' => BukuAgenda::where('jenis', 'keluar')->count(),
            ],
            'buku_ekspedisi' => [
                'total' => BukuEkspedisi::count(),
                'this_year' => BukuEkspedisi::where('tahun', $currentYear)->count(),
            ],
            'lembaran_desa' => [
                'total' => BukuLembaranDesa::count(),
                'this_year' => BukuLembaranDesa::where('tahun', $currentYear)->count(),
            ],
        ];

        // ==========================================
        // 2. STATS: ADMINISTRASI KELEMBAGAAN (8 LEMBAGA)
        // ==========================================
        $institutions = Institution::withCount([
            'members',
            'activeMembers',
            'decisions',
            'activities',
            'agendas',
        ])->orderBy('urutan', 'asc')->get();

        $statsKelembagaan = [
            'total_lembaga' => $institutions->count(),
            'total_anggota' => InstitutionMember::where('status_aktif', true)->count(),
            'total_keputusan' => InstitutionDecision::count(),
            'total_kegiatan' => InstitutionActivity::count(),
            'total_agenda' => InstitutionAgenda::count(),
            'institutions' => $institutions,
        ];

        return view('administrasi.index', compact(
            'statsUmum',
            'statsKelembagaan',
            'currentYear',
            'activeTab'
        ));
    }
}
