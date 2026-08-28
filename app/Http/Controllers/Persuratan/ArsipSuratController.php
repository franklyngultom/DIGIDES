<?php

namespace App\Http\Controllers\Persuratan;

use App\Http\Controllers\Controller;
use App\Models\SuratArsip;
use Illuminate\Http\Request;

class ArsipSuratController extends Controller
{
    /**
     * Display a list of archived surat.
     */
    public function index(Request $request)
    {
        $query = SuratArsip::query();

        if ($request->filled('template_id')) {
            $query->where('surat_template_id', $request->input('template_id'));
        }
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_terbit', '>=', $request->input('tanggal_awal'));
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_terbit', '<=', $request->input('tanggal_akhir'));
        }

        $arsips = $query->orderByDesc('tanggal_terbit')->paginate(15);

        return view('persuratan.arsip_surat', compact('arsips'));
    }
}
