<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuAnggaranDesa;
use App\Models\DesaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuAnggaranDesaController extends Controller
{
    public function index(Request $request)
    {
        $tahun  = $request->query('tahun');
        $search = $request->query('search');

        $data = BukuAnggaranDesa::with('creator')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nomor_perdes', 'like', "%$search%")
                  ->orWhere('keterangan', 'like', "%$search%");
            }))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tahuns = BukuAnggaranDesa::selectRaw('DISTINCT tahun')->orderByDesc('tahun')->pluck('tahun');

        return view('administrasi.anggaran-desa.index', compact('data', 'tahuns', 'tahun', 'search'));
    }

    public function create()
    {
        return view('administrasi.anggaran-desa.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun'              => 'required|integer|min:2000|max:2099',
            'jenis_dokumen'      => 'required|in:apbdes,apbdes_perubahan',
            'nomor_perdes'       => 'required|string|max:100',
            'tanggal_penetapan'  => 'required|date',
            'total_pendapatan'   => 'required|numeric|min:0',
            'total_belanja'      => 'required|numeric|min:0',
            'total_pembiayaan'   => 'required|numeric|min:0',
            'keterangan'         => 'nullable|string',
            'file_pdf'           => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('file_pdf')) {
            $validated['file_pdf_path'] = $request->file('file_pdf')
                ->store('anggaran-desa', 'public');
        }

        BukuAnggaranDesa::create($validated);

        return redirect()->route('administrasi.anggaran-desa.index')
            ->with('success', 'Dokumen APBDes berhasil ditambahkan.');
    }

    public function edit(BukuAnggaranDesa $anggaranDesa)
    {
        return view('administrasi.anggaran-desa.form', ['record' => $anggaranDesa]);
    }

    public function update(Request $request, BukuAnggaranDesa $anggaranDesa)
    {
        $validated = $request->validate([
            'tahun'              => 'required|integer|min:2000|max:2099',
            'jenis_dokumen'      => 'required|in:apbdes,apbdes_perubahan',
            'nomor_perdes'       => 'required|string|max:100',
            'tanggal_penetapan'  => 'required|date',
            'total_pendapatan'   => 'required|numeric|min:0',
            'total_belanja'      => 'required|numeric|min:0',
            'total_pembiayaan'   => 'required|numeric|min:0',
            'keterangan'         => 'nullable|string',
            'file_pdf'           => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_pdf')) {
            if ($anggaranDesa->file_pdf_path) {
                Storage::disk('public')->delete($anggaranDesa->file_pdf_path);
            }
            $validated['file_pdf_path'] = $request->file('file_pdf')
                ->store('anggaran-desa', 'public');
        }

        $anggaranDesa->update($validated);

        return redirect()->route('administrasi.anggaran-desa.index')
            ->with('success', 'Dokumen APBDes berhasil diperbarui.');
    }

    public function destroy(BukuAnggaranDesa $anggaranDesa)
    {
        if ($anggaranDesa->file_pdf_path) {
            Storage::disk('public')->delete($anggaranDesa->file_pdf_path);
        }
        $anggaranDesa->delete();

        return redirect()->route('administrasi.anggaran-desa.index')
            ->with('success', 'Dokumen APBDes berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun');
        $desa = DesaProfile::current();

        $data = BukuAnggaranDesa::with('creator')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_penetapan', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.administrasi.rekap_anggaran_desa', compact('data', 'desa', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Anggaran_Desa_' . ($tahun ?: 'Semua_Tahun') . '.pdf');
    }
}
