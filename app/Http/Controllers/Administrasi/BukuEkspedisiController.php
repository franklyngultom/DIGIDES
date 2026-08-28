<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuEkspedisi;
use Illuminate\Http\Request;

class BukuEkspedisiController extends Controller
{
    /**
     * Display a paginated list of expedition book entries.
     */
    public function index(Request $request)
    {
        $query = BukuEkspedisi::query();

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->input('tahun'));
        }
        if ($request->filled('nomor_surat')) {
            $query->where('nomor_surat', 'like', "%{$request->input('nomor_surat')}%");
        }

        $entries = $query->orderByDesc('tanggal_pengiriman')->paginate(20);

        return view('administrasi.buku_ekspedisi_index', compact('entries'));
    }
}
