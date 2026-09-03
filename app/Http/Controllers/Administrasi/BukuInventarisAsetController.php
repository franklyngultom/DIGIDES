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

    public function exportExcel(Request $request)
    {
        $tahun = $request->query('tahun');
        $data = BukuInventarisAset::when($tahun, fn($q) => $q->where('tahun_pengadaan', $tahun))
            ->orderBy('tahun_pengadaan', 'asc')
            ->get();

        $headers = [
            'No',
            'Tahun Pengadaan',
            'Jenis Barang',
            'Kode Barang',
            'Identitas / Spesifikasi',
            'Asal Usul',
            'Harga Perolehan (Rp)',
            'Kondisi',
            'Lokasi Penempatan',
        ];

        $rows = [];
        foreach ($data as $i => $item) {
            $rows[] = [
                $i + 1,
                $item->tahun_pengadaan,
                $item->jenis_barang,
                $item->kode_barang ?? '',
                $item->identitas_barang,
                $item->asal_usul,
                $item->harga_perolehan,
                $item->kondisi,
                $item->lokasi_penempatan,
            ];
        }

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Buku_Inventaris_Aset_Desa_' . ($tahun ?: 'Semua_Tahun') . '_' . date('Ymd_His') . '.csv',
            $headers,
            $rows
        );
    }

    public function downloadTemplate()
    {
        $headers = [
            'tahun_pengadaan',
            'jenis_barang',
            'kode_barang',
            'identitas_barang',
            'asal_usul',
            'harga_perolehan',
            'kondisi',
            'lokasi_penempatan',
        ];

        $rows = [
            [
                date('Y'),
                'Laptop Kantor Asus Vivobook',
                'AST-01/' . date('Y'),
                'Intel Core i5, RAM 16GB, SSD 512GB',
                'apbdes',
                '12500000',
                'baik',
                'Ruang Sekretariat Desa',
            ]
        ];

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Template_Import_Inventaris_Aset.csv',
            $headers,
            $rows
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $rows = \App\Services\AdministrasiImportExportService::parseCsv($request->file('file')->getRealPath());

        if (count($rows) <= 1) {
            return redirect()->back()->with('error', 'Berkas kosong atau format tidak sesuai.');
        }

        $headers = array_map('strtolower', $rows[0]);
        $importedCount = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (empty(array_filter($row))) continue;

            $data = array_combine($headers, array_pad($row, count($headers), null));

            if (empty($data['jenis_barang']) || empty($data['identitas_barang'])) {
                continue;
            }

            BukuInventarisAset::create([
                'tahun_pengadaan'   => !empty($data['tahun_pengadaan']) ? (int)$data['tahun_pengadaan'] : (int)date('Y'),
                'jenis_barang'      => $data['jenis_barang'],
                'kode_barang'       => $data['kode_barang'] ?? null,
                'identitas_barang'  => $data['identitas_barang'],
                'asal_usul'         => in_array($data['asal_usul'] ?? '', ['apbdes', 'bantuan_pemerintah', 'bantuan_provinsi', 'bantuan_kabupaten', 'hibah', 'lainnya']) ? $data['asal_usul'] : 'apbdes',
                'harga_perolehan'   => !empty($data['harga_perolehan']) ? (float)str_replace(['Rp', '.', ','], '', $data['harga_perolehan']) : 0,
                'kondisi'           => in_array($data['kondisi'] ?? '', ['baik', 'rusak_ringan', 'rusak_berat']) ? $data['kondisi'] : 'baik',
                'lokasi_penempatan' => $data['lokasi_penempatan'] ?? 'Kantor Desa',
                'created_by'        => auth()->id(),
            ]);

            $importedCount++;
        }

        return redirect()->route('administrasi.inventaris-aset.index')
            ->with('success', "Berhasil mengimpor {$importedCount} data inventaris aset desa.");
    }
}
