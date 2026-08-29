<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\DesaProfile;
use App\Models\KeuanganApbdes;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ApbdesController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $jenis = $request->query('jenis');
        $search = $request->query('search');

        $query = KeuanganApbdes::when($tahun, fn($q) => $q->where('tahun_anggaran', $tahun))
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('kode_rekening', 'like', "%$search%")
                  ->orWhere('uraian', 'like', "%$search%")
                  ->orWhere('bidang', 'like', "%$search%");
            }))
            ->orderBy('kode_rekening', 'asc');

        $data = $query->paginate(15)->withQueryString();

        $tahuns = KeuanganApbdes::selectRaw('DISTINCT tahun_anggaran as tahun')->orderByDesc('tahun')->pluck('tahun');
        if ($tahuns->isEmpty()) {
            $tahuns = collect([date('Y')]);
        }

        $totalAnggaran = KeuanganApbdes::when($tahun, fn($q) => $q->where('tahun_anggaran', $tahun))->sum('anggaran');
        $totalRealisasi = KeuanganApbdes::when($tahun, fn($q) => $q->where('tahun_anggaran', $tahun))->sum('realisasi');

        return view('keuangan.apbdes.index', compact('data', 'tahuns', 'tahun', 'jenis', 'search', 'totalAnggaran', 'totalRealisasi'));
    }

    public function create()
    {
        return view('keuangan.apbdes.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_anggaran' => 'required|integer|min:2000|max:2099',
            'kode_rekening'  => 'required|string|max:30',
            'jenis'          => 'required|in:pendapatan,belanja,pembiayaan',
            'bidang'         => 'nullable|string|max:100',
            'uraian'         => 'required|string',
            'anggaran'       => 'required|numeric|min:0',
            'realisasi'      => 'nullable|numeric|min:0',
            'sumber_dana'    => 'required|in:DDS,ADD,PBH,PAD,DLL',
        ]);

        $validated['realisasi'] = $validated['realisasi'] ?? 0;

        KeuanganApbdes::create($validated);

        return redirect()->route('keuangan.apbdes.index', ['tahun' => $validated['tahun_anggaran']])
            ->with('success', 'Pos anggaran APBDes berhasil ditambahkan.');
    }

    public function edit(KeuanganApbdes $apbde)
    {
        return view('keuangan.apbdes.form', ['record' => $apbde]);
    }

    public function update(Request $request, KeuanganApbdes $apbde)
    {
        $validated = $request->validate([
            'tahun_anggaran' => 'required|integer|min:2000|max:2099',
            'kode_rekening'  => 'required|string|max:30',
            'jenis'          => 'required|in:pendapatan,belanja,pembiayaan',
            'bidang'         => 'nullable|string|max:100',
            'uraian'         => 'required|string',
            'anggaran'       => 'required|numeric|min:0',
            'realisasi'      => 'nullable|numeric|min:0',
            'sumber_dana'    => 'required|in:DDS,ADD,PBH,PAD,DLL',
        ]);

        $validated['realisasi'] = $validated['realisasi'] ?? 0;

        $apbde->update($validated);

        return redirect()->route('keuangan.apbdes.index', ['tahun' => $validated['tahun_anggaran']])
            ->with('success', 'Pos anggaran APBDes berhasil diperbarui.');
    }

    public function destroy(KeuanganApbdes $apbde)
    {
        $tahun = $apbde->tahun_anggaran;
        $apbde->delete();

        return redirect()->route('keuangan.apbdes.index', ['tahun' => $tahun])
            ->with('success', 'Pos anggaran APBDes berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $desa = DesaProfile::current();

        $data = KeuanganApbdes::where('tahun_anggaran', $tahun)
            ->orderBy('kode_rekening', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.keuangan.realisasi_apbdes', compact('data', 'desa', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Realisasi_APBDes_' . $tahun . '.pdf');
    }
}
