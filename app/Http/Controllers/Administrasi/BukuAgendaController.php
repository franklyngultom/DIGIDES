<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuAgenda;
use Illuminate\Http\Request;

class BukuAgendaController extends Controller
{
    /**
     * Display a paginated list of agenda book entries.
     */
    public function index(Request $request)
    {
        $query = BukuAgenda::query();

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->input('tahun'));
        }
        if ($request->filled('nomor_surat')) {
            $query->where('nomor_surat', 'like', "%{$request->input('nomor_surat')}%");
        }

        $entries = $query->orderByDesc('tanggal_surat')->paginate(20);

        return view('administrasi.buku_agenda_index', compact('entries'));
    }
}
