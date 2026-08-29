<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuTanahDesa;
use App\Models\DesaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuTanahDesaController extends Controller
{
    public function index(Request $request)
    {
        $jenis  = $request->query('jenis');
        $search = $request->query('search');

        $data = BukuTanahDesa::with('creator')
            ->when($jenis, fn($q) => $q->where('jenis_tanah', $jenis))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nomor_sertifikat_letter_c', 'like', "%$search%")
                  ->orWhere('nama_pemilik_asal', 'like', "%$search%")
                  ->orWhere('lokasi_blok', 'like', "%$search%")
                  ->orWhere('peruntukan_saat_ini', 'like', "%$search%");
            }))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('administrasi.tanah-desa.index', compact('data', 'jenis', 'search'));
    }

    public function create()
    {
        return view('administrasi.tanah-desa.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_tanah'                => 'required|in:tanah_kas_desa,tanah_bengkok,tanah_warga',
            'nomor_sertifikat_letter_c'  => 'required|string|max:100',
            'nama_pemilik_asal'          => 'required|string|max:200',
            'luas_m2'                    => 'required|numeric|min:0',
            'kelas_tanah'                => 'nullable|string|max:50',
            'lokasi_blok'                => 'required|string|max:200',
            'peruntukan_saat_ini'        => 'required|string|max:200',
            'patok_tanda_batas'          => 'nullable|string',
            'file_warkah'                => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('file_warkah')) {
            $validated['file_warkah_path'] = $request->file('file_warkah')
                ->store('tanah-desa', 'public');
        }

        BukuTanahDesa::create($validated);

        return redirect()->route('administrasi.tanah-desa.index')
            ->with('success', 'Data tanah berhasil ditambahkan.');
    }

    public function edit(BukuTanahDesa $tanahDesa)
    {
        return view('administrasi.tanah-desa.form', ['record' => $tanahDesa]);
    }

    public function update(Request $request, BukuTanahDesa $tanahDesa)
    {
        $validated = $request->validate([
            'jenis_tanah'                => 'required|in:tanah_kas_desa,tanah_bengkok,tanah_warga',
            'nomor_sertifikat_letter_c'  => 'required|string|max:100',
            'nama_pemilik_asal'          => 'required|string|max:200',
            'luas_m2'                    => 'required|numeric|min:0',
            'kelas_tanah'                => 'nullable|string|max:50',
            'lokasi_blok'                => 'required|string|max:200',
            'peruntukan_saat_ini'        => 'required|string|max:200',
            'patok_tanda_batas'          => 'nullable|string',
            'file_warkah'                => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file_warkah')) {
            if ($tanahDesa->file_warkah_path) {
                Storage::disk('public')->delete($tanahDesa->file_warkah_path);
            }
            $validated['file_warkah_path'] = $request->file('file_warkah')
                ->store('tanah-desa', 'public');
        }

        $tanahDesa->update($validated);

        return redirect()->route('administrasi.tanah-desa.index')
            ->with('success', 'Data tanah berhasil diperbarui.');
    }

    public function destroy(BukuTanahDesa $tanahDesa)
    {
        if ($tanahDesa->file_warkah_path) {
            Storage::disk('public')->delete($tanahDesa->file_warkah_path);
        }
        $tanahDesa->delete();

        return redirect()->route('administrasi.tanah-desa.index')
            ->with('success', 'Data tanah berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $jenis = $request->query('jenis');
        $desa = DesaProfile::current();

        $data = BukuTanahDesa::with('creator')
            ->when($jenis, fn($q) => $q->where('jenis_tanah', $jenis))
            ->orderBy('id', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.administrasi.rekap_tanah_desa', compact('data', 'desa', 'jenis'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Tanah_Desa_' . ($jenis ?: 'Semua_Jenis') . '.pdf');
    }
}
