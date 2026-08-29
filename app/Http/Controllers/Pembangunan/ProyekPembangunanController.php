<?php

namespace App\Http\Controllers\Pembangunan;

use App\Http\Controllers\Controller;
use App\Models\DesaProfile;
use App\Models\PembangunanProyek;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProyekPembangunanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $status = $request->query('status');
        $search = $request->query('search');

        $query = PembangunanProyek::when($tahun, fn($q) => $q->where('tahun_anggaran', $tahun))
            ->when($status, fn($q) => $q->where('status_progres', $status))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%$search%")
                  ->orWhere('lokasi', 'like', "%$search%")
                  ->orWhere('pelaksana_tpk', 'like', "%$search%");
            }))
            ->orderBy('id', 'desc');

        $data = $query->paginate(12)->withQueryString();

        $tahuns = PembangunanProyek::selectRaw('DISTINCT tahun_anggaran as tahun')->orderByDesc('tahun')->pluck('tahun');
        if ($tahuns->isEmpty()) {
            $tahuns = collect([date('Y')]);
        }

        return view('pembangunan.proyek.index', compact('data', 'tahuns', 'tahun', 'status', 'search'));
    }

    public function show(PembangunanProyek $proyek)
    {
        return view('pembangunan.proyek.show', compact('proyek'));
    }

    public function create()
    {
        return view('pembangunan.proyek.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_anggaran'     => 'required|integer|min:2000|max:2099',
            'nama_kegiatan'      => 'required|string|max:255',
            'lokasi'             => 'required|string|max:255',
            'volume'             => 'required|string|max:100',
            'anggaran_biaya'     => 'required|numeric|min:0',
            'realisasi_biaya'    => 'nullable|numeric|min:0',
            'sumber_dana'        => 'required|string|max:100',
            'pelaksana_tpk'      => 'required|string|max:150',
            'status_progres'     => 'required|in:perencanaan,proses,selesai,tertunda',
            'persentase_selesai' => 'required|integer|min:0|max:100',
            'manfaat_warga'      => 'nullable|string',
            'foto_titik_nol'     => 'nullable|image|max:5120',
            'foto_50_persen'     => 'nullable|image|max:5120',
            'foto_100_persen'    => 'nullable|image|max:5120',
            'file_rab'           => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['realisasi_biaya'] = $validated['realisasi_biaya'] ?? 0;

        if ($request->hasFile('foto_titik_nol')) {
            $validated['foto_titik_nol'] = $request->file('foto_titik_nol')->store('proyek/foto', 'public');
        }
        if ($request->hasFile('foto_50_persen')) {
            $validated['foto_50_persen'] = $request->file('foto_50_persen')->store('proyek/foto', 'public');
        }
        if ($request->hasFile('foto_100_persen')) {
            $validated['foto_100_persen'] = $request->file('foto_100_persen')->store('proyek/foto', 'public');
        }
        if ($request->hasFile('file_rab')) {
            $validated['file_rab_path'] = $request->file('file_rab')->store('proyek/rab', 'public');
        }

        $proyek = PembangunanProyek::create($validated);

        return redirect()->route('pembangunan.proyek.show', $proyek)
            ->with('success', 'Data proyek pembangunan berhasil ditambahkan.');
    }

    public function edit(PembangunanProyek $proyek)
    {
        return view('pembangunan.proyek.form', ['record' => $proyek]);
    }

    public function update(Request $request, PembangunanProyek $proyek)
    {
        $validated = $request->validate([
            'tahun_anggaran'     => 'required|integer|min:2000|max:2099',
            'nama_kegiatan'      => 'required|string|max:255',
            'lokasi'             => 'required|string|max:255',
            'volume'             => 'required|string|max:100',
            'anggaran_biaya'     => 'required|numeric|min:0',
            'realisasi_biaya'    => 'nullable|numeric|min:0',
            'sumber_dana'        => 'required|string|max:100',
            'pelaksana_tpk'      => 'required|string|max:150',
            'status_progres'     => 'required|in:perencanaan,proses,selesai,tertunda',
            'persentase_selesai' => 'required|integer|min:0|max:100',
            'manfaat_warga'      => 'nullable|string',
            'foto_titik_nol'     => 'nullable|image|max:5120',
            'foto_50_persen'     => 'nullable|image|max:5120',
            'foto_100_persen'    => 'nullable|image|max:5120',
            'file_rab'           => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['realisasi_biaya'] = $validated['realisasi_biaya'] ?? 0;

        if ($request->hasFile('foto_titik_nol')) {
            if ($proyek->foto_titik_nol) {
                Storage::disk('public')->delete($proyek->foto_titik_nol);
            }
            $validated['foto_titik_nol'] = $request->file('foto_titik_nol')->store('proyek/foto', 'public');
        }
        if ($request->hasFile('foto_50_persen')) {
            if ($proyek->foto_50_persen) {
                Storage::disk('public')->delete($proyek->foto_50_persen);
            }
            $validated['foto_50_persen'] = $request->file('foto_50_persen')->store('proyek/foto', 'public');
        }
        if ($request->hasFile('foto_100_persen')) {
            if ($proyek->foto_100_persen) {
                Storage::disk('public')->delete($proyek->foto_100_persen);
            }
            $validated['foto_100_persen'] = $request->file('foto_100_persen')->store('proyek/foto', 'public');
        }
        if ($request->hasFile('file_rab')) {
            if ($proyek->file_rab_path) {
                Storage::disk('public')->delete($proyek->file_rab_path);
            }
            $validated['file_rab_path'] = $request->file('file_rab')->store('proyek/rab', 'public');
        }

        $proyek->update($validated);

        return redirect()->route('pembangunan.proyek.show', $proyek)
            ->with('success', 'Data proyek pembangunan berhasil diperbarui.');
    }

    public function destroy(PembangunanProyek $proyek)
    {
        $tahun = $proyek->tahun_anggaran;
        if ($proyek->foto_titik_nol) Storage::disk('public')->delete($proyek->foto_titik_nol);
        if ($proyek->foto_50_persen) Storage::disk('public')->delete($proyek->foto_50_persen);
        if ($proyek->foto_100_persen) Storage::disk('public')->delete($proyek->foto_100_persen);
        if ($proyek->file_rab_path) Storage::disk('public')->delete($proyek->file_rab_path);

        $proyek->delete();

        return redirect()->route('pembangunan.proyek.index', ['tahun' => $tahun])
            ->with('success', 'Data proyek pembangunan berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $desa = DesaProfile::current();

        $data = PembangunanProyek::where('tahun_anggaran', $tahun)
            ->orderBy('id', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.pembangunan.laporan_pembangunan', compact('data', 'desa', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Pembangunan_Desa_' . $tahun . '.pdf');
    }
}
