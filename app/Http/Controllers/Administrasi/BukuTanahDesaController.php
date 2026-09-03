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

    public function exportExcel(Request $request)
    {
        $jenis = $request->query('jenis');
        $data = BukuTanahDesa::when($jenis, fn($q) => $q->where('jenis_tanah', $jenis))
            ->orderBy('id', 'asc')
            ->get();

        $headers = [
            'No',
            'Jenis Tanah',
            'Nomor Sertifikat / Letter C',
            'Nama Pemilik Asal',
            'Luas (M2)',
            'Kelas Tanah',
            'Lokasi / Blok',
            'Peruntukan Saat Ini',
            'Patok Tanda Batas',
        ];

        $rows = [];
        foreach ($data as $i => $item) {
            $rows[] = [
                $i + 1,
                $item->jenis_tanah,
                "'" . $item->nomor_sertifikat_letter_c,
                $item->nama_pemilik_asal,
                $item->luas_m2,
                $item->kelas_tanah ?? '',
                $item->lokasi_blok,
                $item->peruntukan_saat_ini,
                $item->patok_tanda_batas ?? '',
            ];
        }

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Buku_Tanah_Desa_' . ($jenis ?: 'Semua_Jenis') . '_' . date('Ymd_His') . '.csv',
            $headers,
            $rows
        );
    }

    public function downloadTemplate()
    {
        $headers = [
            'jenis_tanah',
            'nomor_sertifikat_letter_c',
            'nama_pemilik_asal',
            'luas_m2',
            'kelas_tanah',
            'lokasi_blok',
            'peruntukan_saat_ini',
            'patok_tanda_batas',
        ];

        $rows = [
            [
                'tanah_kas_desa',
                'C-104/SKM',
                'Tanah Kas Desa (Pemerintah Desa Sukamaju)',
                '15000',
                'S.I',
                'Blok Lapangan Utama RT 02/01',
                'Lapangan Olahraga & Gedung Serbaguna',
                'Patok Beton BPN No. 01 - 04',
            ],
            [
                'tanah_warga',
                'SHM No. 4452',
                'Budi Santoso',
                '450',
                'D.II',
                'Blok Cikole RT 01/01',
                'Pemukiman Rumah Tinggal',
                'Pagar Tembok & Patok Kayu',
            ]
        ];

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Template_Import_Tanah_Desa.csv',
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

            if (empty($data['nomor_sertifikat_letter_c']) || empty($data['nama_pemilik_asal'])) {
                continue;
            }

            BukuTanahDesa::create([
                'jenis_tanah'               => in_array($data['jenis_tanah'] ?? '', ['tanah_kas_desa', 'tanah_bengkok', 'tanah_warga']) ? $data['jenis_tanah'] : 'tanah_kas_desa',
                'nomor_sertifikat_letter_c' => $data['nomor_sertifikat_letter_c'],
                'nama_pemilik_asal'         => $data['nama_pemilik_asal'],
                'luas_m2'                   => !empty($data['luas_m2']) ? (float)$data['luas_m2'] : 0,
                'kelas_tanah'               => $data['kelas_tanah'] ?? null,
                'lokasi_blok'               => $data['lokasi_blok'] ?? 'Blok Desa',
                'peruntukan_saat_ini'       => $data['peruntukan_saat_ini'] ?? 'Fasilitas Desa',
                'patok_tanda_batas'         => $data['patok_tanda_batas'] ?? null,
                'created_by'                => auth()->id(),
            ]);

            $importedCount++;
        }

        return redirect()->route('administrasi.tanah-desa.index')
            ->with('success', "Berhasil mengimpor {$importedCount} data register tanah desa.");
    }
}
