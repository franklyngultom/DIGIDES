<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuLembaranDesa;
use App\Models\DesaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuLembaranDesaController extends Controller
{
    public function index(Request $request)
    {
        $tahun  = $request->query('tahun');
        $search = $request->query('search');

        $data = BukuLembaranDesa::with('creator')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nomor_seri', 'like', "%$search%")
                  ->orWhere('judul', 'like', "%$search%")
                  ->orWhere('isi_singkat', 'like', "%$search%");
            }))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tahuns = BukuLembaranDesa::selectRaw('DISTINCT tahun')->orderByDesc('tahun')->pluck('tahun');

        return view('administrasi.lembaran-desa.index', compact('data', 'tahuns', 'tahun', 'search'));
    }

    public function create()
    {
        return view('administrasi.lembaran-desa.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun'                => 'required|integer|min:2000|max:2099',
            'jenis'                => 'required|in:lembaran_desa,berita_desa',
            'nomor_seri'           => 'required|string|max:50',
            'tanggal_diundangkan'  => 'required|date',
            'judul'                => 'required|string',
            'isi_singkat'          => 'nullable|string',
            'file_pdf'             => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('file_pdf')) {
            $validated['file_pdf_path'] = $request->file('file_pdf')
                ->store('lembaran-desa', 'public');
        }

        BukuLembaranDesa::create($validated);

        return redirect()->route('administrasi.lembaran-desa.index')
            ->with('success', 'Lembaran/Berita Desa berhasil ditambahkan.');
    }

    public function edit(BukuLembaranDesa $lembaranDesa)
    {
        return view('administrasi.lembaran-desa.form', ['record' => $lembaranDesa]);
    }

    public function update(Request $request, BukuLembaranDesa $lembaranDesa)
    {
        $validated = $request->validate([
            'tahun'                => 'required|integer|min:2000|max:2099',
            'jenis'                => 'required|in:lembaran_desa,berita_desa',
            'nomor_seri'           => 'required|string|max:50',
            'tanggal_diundangkan'  => 'required|date',
            'judul'                => 'required|string',
            'isi_singkat'          => 'nullable|string',
            'file_pdf'             => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_pdf')) {
            if ($lembaranDesa->file_pdf_path) {
                Storage::disk('public')->delete($lembaranDesa->file_pdf_path);
            }
            $validated['file_pdf_path'] = $request->file('file_pdf')
                ->store('lembaran-desa', 'public');
        }

        $lembaranDesa->update($validated);

        return redirect()->route('administrasi.lembaran-desa.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(BukuLembaranDesa $lembaranDesa)
    {
        if ($lembaranDesa->file_pdf_path) {
            Storage::disk('public')->delete($lembaranDesa->file_pdf_path);
        }
        $lembaranDesa->delete();

        return redirect()->route('administrasi.lembaran-desa.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun');
        $desa = DesaProfile::current();

        $data = BukuLembaranDesa::with('creator')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_diundangkan', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.administrasi.rekap_lembaran_desa', compact('data', 'desa', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Lembaran_Desa_' . ($tahun ?: 'Semua_Tahun') . '.pdf');
    }
}
