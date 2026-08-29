<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuEkspedisi;
use App\Models\DesaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BukuEkspedisiController extends Controller
{
    public function index(Request $request)
    {
        $tahun  = $request->query('tahun');
        $search = $request->query('search');

        $data = BukuEkspedisi::with('suratArsip')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%$search%")
                  ->orWhere('perihal', 'like', "%$search%")
                  ->orWhere('tujuan_penerima', 'like', "%$search%")
                  ->orWhere('petugas_pengirim', 'like', "%$search%");
            }))
            ->latest('tanggal_pengiriman')
            ->paginate(10)
            ->withQueryString();

        $tahuns = BukuEkspedisi::selectRaw('DISTINCT tahun')->orderByDesc('tahun')->pluck('tahun');

        return view('administrasi.buku-ekspedisi.index', compact('data', 'tahuns', 'tahun', 'search'));
    }

    public function create()
    {
        $currentYear = (int) date('Y');
        $nextNomorUrut = (BukuEkspedisi::where('tahun', $currentYear)->max('nomor_urut') ?? 0) + 1;

        return view('administrasi.buku-ekspedisi.form', compact('nextNomorUrut', 'currentYear'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_urut'         => 'required|integer|min:1',
            'tahun'              => 'required|integer|min:2000|max:2099',
            'tanggal_pengiriman' => 'required|date',
            'nomor_surat'        => 'required|string|max:100',
            'tanggal_surat'      => 'required|date',
            'perihal'            => 'required|string|max:255',
            'tujuan_penerima'    => 'required|string|max:200',
            'petugas_pengirim'   => 'required|string|max:100',
            'catatan'            => 'nullable|string',
        ]);

        BukuEkspedisi::create($validated);

        return redirect()->route('administrasi.buku-ekspedisi.index')
            ->with('success', 'Buku ekspedisi berhasil ditambahkan.');
    }

    public function edit(BukuEkspedisi $bukuEkspedisi)
    {
        return view('administrasi.buku-ekspedisi.form', ['record' => $bukuEkspedisi]);
    }

    public function update(Request $request, BukuEkspedisi $bukuEkspedisi)
    {
        $validated = $request->validate([
            'nomor_urut'         => 'required|integer|min:1',
            'tahun'              => 'required|integer|min:2000|max:2099',
            'tanggal_pengiriman' => 'required|date',
            'nomor_surat'        => 'required|string|max:100',
            'tanggal_surat'      => 'required|date',
            'perihal'            => 'required|string|max:255',
            'tujuan_penerima'    => 'required|string|max:200',
            'petugas_pengirim'   => 'required|string|max:100',
            'catatan'            => 'nullable|string',
        ]);

        $bukuEkspedisi->update($validated);

        return redirect()->route('administrasi.buku-ekspedisi.index')
            ->with('success', 'Data ekspedisi berhasil diperbarui.');
    }

    public function destroy(BukuEkspedisi $bukuEkspedisi)
    {
        $bukuEkspedisi->delete();

        return redirect()->route('administrasi.buku-ekspedisi.index')
            ->with('success', 'Data ekspedisi berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun');
        $desa = DesaProfile::current();

        $data = BukuEkspedisi::with('suratArsip')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_pengiriman', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.administrasi.rekap_buku_ekspedisi', compact('data', 'desa', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Ekspedisi_' . ($tahun ?: 'Semua_Tahun') . '.pdf');
    }
}
