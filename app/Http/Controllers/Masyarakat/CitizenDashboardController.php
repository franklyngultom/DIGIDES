<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\DesaProfile;
use App\Models\PengajuanSurat;
use App\Models\SuratTemplate;
use Illuminate\Support\Facades\Auth;

class CitizenDashboardController extends Controller
{
    /**
     * Display citizen self-service portal dashboard.
     */
    public function index()
    {
        $user    = Auth::user();
        $desa    = DesaProfile::current();
        $profile = $user->citizenProfile;

        // Quick available letter services (online only)
        $availableTemplates = SuratTemplate::online()->orderBy('nama_surat')->take(6)->get();

        // Real stats from pengajuan_surat
        $baseQuery = PengajuanSurat::byUser($user->id);

        $stats = [
            'total_pengajuan'  => (clone $baseQuery)->count(),
            'diproses'         => (clone $baseQuery)->where('status', 'diproses')->count(),
            'selesai'          => (clone $baseQuery)->where('status', 'selesai')->count(),
            'perlu_perbaikan'  => (clone $baseQuery)->where('status', 'perlu_perbaikan')->count(),
        ];

        // 3 most recent submissions
        $recentRequests = PengajuanSurat::with('suratTemplate')
            ->byUser($user->id)
            ->latest()
            ->take(3)
            ->get();

        return view('masyarakat.dashboard', compact(
            'user',
            'desa',
            'profile',
            'availableTemplates',
            'stats',
            'recentRequests'
        ));
    }
}
