<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuPeraturanDesa;
use App\Models\DesaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuPeraturanDesaController extends Controller
{
    public function index(Request $request)
    {
        $tahun  = $request->query('tahun');
        $search = $request->query('search');

        $query = BukuPeraturanDesa::with('creator')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nomor_ditetapkan', 'like', "%$search%")
                  ->orWhere('tentang', 'like', "%$search%")
                  ->orWhere('jenis_peraturan', 'like', "%$search%");
            }))
            ->latest();

        $data   = $query->paginate(10)->withQueryString();
        $tahuns = BukuPeraturanDesa::selectRaw('DISTINCT tahun')->orderByDesc('tahun')->pluck('tahun');

        return view('administrasi.peraturan-desa.index', compact('data', 'tahuns', 'tahun', 'search'));
    }

    public function create()
    {
        return view('administrasi.peraturan-desa.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun'                   => 'required|integer|min:2000|max:2099',
            'jenis_peraturan'         => 'required|in:perdes,perkades,peraturan_bersama',
            'nomor_ditetapkan'        => 'required|string|max:100',
            'tanggal_ditetapkan'      => 'required|date',
            'tentang'                 => 'required|string',
            'uraian_singkat'          => 'nullable|string',
            'nomor_kesepakatan_bpd'   => 'nullable|string|max:100',
            'nomor_diundangkan'       => 'nullable|string|max:100',
            'tanggal_diundangkan'     => 'nullable|date',
            'file_pdf'                => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('file_pdf')) {
            $validated['file_pdf_path'] = $request->file('file_pdf')
                ->store('peraturan-desa', 'public');
        }

        BukuPeraturanDesa::create($validated);

        return redirect()->route('administrasi.peraturan-desa.index')
            ->with('success', 'Peraturan desa berhasil ditambahkan.');
    }

    public function edit(BukuPeraturanDesa $peraturanDesa)
    {
        return view('administrasi.peraturan-desa.form', ['record' => $peraturanDesa]);
    }

    public function update(Request $request, BukuPeraturanDesa $peraturanDesa)
    {
        $validated = $request->validate([
            'tahun'                   => 'required|integer|min:2000|max:2099',
            'jenis_peraturan'         => 'required|in:perdes,perkades,peraturan_bersama',
            'nomor_ditetapkan'        => 'required|string|max:100',
            'tanggal_ditetapkan'      => 'required|date',
            'tentang'                 => 'required|string',
            'uraian_singkat'          => 'nullable|string',
            'nomor_kesepakatan_bpd'   => 'nullable|string|max:100',
            'nomor_diundangkan'       => 'nullable|string|max:100',
            'tanggal_diundangkan'     => 'nullable|date',
            'file_pdf'                => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_pdf')) {
            if ($peraturanDesa->file_pdf_path) {
                Storage::disk('public')->delete($peraturanDesa->file_pdf_path);
            }
            $validated['file_pdf_path'] = $request->file('file_pdf')
                ->store('peraturan-desa', 'public');
        }

        $peraturanDesa->update($validated);

        return redirect()->route('administrasi.peraturan-desa.index')
            ->with('success', 'Data peraturan berhasil diperbarui.');
    }

    public function destroy(BukuPeraturanDesa $peraturanDesa)
    {
        if ($peraturanDesa->file_pdf_path) {
            Storage::disk('public')->delete($peraturanDesa->file_pdf_path);
        }
        $peraturanDesa->delete();

        return redirect()->route('administrasi.peraturan-desa.index')
            ->with('success', 'Data peraturan berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun');
        $desa = DesaProfile::current();

        $data = BukuPeraturanDesa::with('creator')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_ditetapkan', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.administrasi.rekap_peraturan_desa', compact('data', 'desa', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Peraturan_Desa_' . ($tahun ?: 'Semua_Tahun') . '.pdf');
    }
}
