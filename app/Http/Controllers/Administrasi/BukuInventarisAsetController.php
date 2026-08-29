<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuInventarisAset;
use App\Models\DesaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuInventarisAsetController extends Controller
{
    public function index(Request $request)
    {
        $tahun  = $request->query('tahun');
        $search = $request->query('search');

        $data = BukuInventarisAset::with('creator')
            ->when($tahun, fn($q) => $q->where('tahun_pengadaan', $tahun))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('jenis_barang', 'like', "%$search%")
                  ->orWhere('kode_barang', 'like', "%$search%")
                  ->orWhere('identitas_barang', 'like', "%$search%")
                  ->orWhere('lokasi_penempatan', 'like', "%$search%");
            }))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tahuns = BukuInventarisAset::selectRaw('DISTINCT tahun_pengadaan as tahun')->orderByDesc('tahun_pengadaan')->pluck('tahun');

        return view('administrasi.inventaris-aset.index', compact('data', 'tahuns', 'tahun', 'search'));
    }

    public function create()
    {
        return view('administrasi.inventaris-aset.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_pengadaan'    => 'required|integer|min:2000|max:2099',
            'jenis_barang'       => 'required|string|max:200',
            'kode_barang'        => 'nullable|string|max:50',
            'identitas_barang'   => 'required|string',
            'asal_usul'          => 'required|in:apbdes,bantuan_pemerintah,bantuan_provinsi,bantuan_kabupaten,hibah,lainnya',
            'harga_perolehan'    => 'required|numeric|min:0',
            'kondisi'            => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi_penempatan'  => 'required|string|max:200',
            'foto_barang'        => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('foto_barang')) {
            $validated['foto_barang_path'] = $request->file('foto_barang')
                ->store('inventaris-aset', 'public');
        }

        BukuInventarisAset::create($validated);

        return redirect()->route('administrasi.inventaris-aset.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function edit(BukuInventarisAset $inventarisAset)
    {
        return view('administrasi.inventaris-aset.form', ['record' => $inventarisAset]);
    }

    public function update(Request $request, BukuInventarisAset $inventarisAset)
    {
        $validated = $request->validate([
            'tahun_pengadaan'    => 'required|integer|min:2000|max:2099',
            'jenis_barang'       => 'required|string|max:200',
            'kode_barang'        => 'nullable|string|max:50',
            'identitas_barang'   => 'required|string',
            'asal_usul'          => 'required|in:apbdes,bantuan_pemerintah,bantuan_provinsi,bantuan_kabupaten,hibah,lainnya',
            'harga_perolehan'    => 'required|numeric|min:0',
            'kondisi'            => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi_penempatan'  => 'required|string|max:200',
            'foto_barang'        => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
        ]);

        if ($request->hasFile('foto_barang')) {
            if ($inventarisAset->foto_barang_path) {
                Storage::disk('public')->delete($inventarisAset->foto_barang_path);
            }
            $validated['foto_barang_path'] = $request->file('foto_barang')
                ->store('inventaris-aset', 'public');
        }

        $inventarisAset->update($validated);

        return redirect()->route('administrasi.inventaris-aset.index')
            ->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(BukuInventarisAset $inventarisAset)
    {
        if ($inventarisAset->foto_barang_path) {
            Storage::disk('public')->delete($inventarisAset->foto_barang_path);
        }
        $inventarisAset->delete();

        return redirect()->route('administrasi.inventaris-aset.index')
            ->with('success', 'Data aset berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun');
        $desa = DesaProfile::current();

        $data = BukuInventarisAset::with('creator')
            ->when($tahun, fn($q) => $q->where('tahun_pengadaan', $tahun))
            ->orderBy('tahun_pengadaan', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.administrasi.rekap_inventaris_aset', compact('data', 'desa', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Inventaris_Aset_' . ($tahun ?: 'Semua_Tahun') . '.pdf');
    }
}
