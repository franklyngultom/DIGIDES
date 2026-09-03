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

    public function exportExcel(Request $request)
    {
        $tahun = $request->query('tahun');
        $data = BukuLembaranDesa::when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_diundangkan', 'asc')
            ->get();

        $headers = [
            'No',
            'Tahun',
            'Jenis (lembaran_desa/berita_desa)',
            'Nomor Seri',
            'Tanggal Diundangkan',
            'Judul Publikasi / Peraturan',
            'Isi Singkat',
        ];

        $rows = [];
        foreach ($data as $i => $item) {
            $rows[] = [
                $i + 1,
                $item->tahun,
                $item->jenis,
                "'" . $item->nomor_seri,
                $item->tanggal_diundangkan ? date('Y-m-d', strtotime($item->tanggal_diundangkan)) : '',
                $item->judul,
                $item->isi_singkat ?? '',
            ];
        }

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Buku_Lembaran_Berita_Desa_' . ($tahun ?: 'Semua_Tahun') . '_' . date('Ymd_His') . '.csv',
            $headers,
            $rows
        );
    }

    public function downloadTemplate()
    {
        $headers = [
            'tahun',
            'jenis',
            'nomor_seri',
            'tanggal_diundangkan',
            'judul',
            'isi_singkat',
        ];

        $rows = [
            [
                date('Y'),
                'lembaran_desa',
                '01/LD-SKM/' . date('Y'),
                date('Y') . '-01-20',
                'Pengundangan Peraturan Desa Nomor 01 Tahun ' . date('Y') . ' tentang APBDesa',
                'Pemberitahuan resmi pengundangan APBDesa Sukamaju tahun anggaran berjalan',
            ],
            [
                date('Y'),
                'berita_desa',
                '01/BD-SKM/' . date('Y'),
                date('Y') . '-02-05',
                'Pengundangan Peraturan Kepala Desa Nomor 01 Tahun ' . date('Y'),
                'Petunjuk teknis pelaksanaan kegiatan operasional desa',
            ]
        ];

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Template_Import_Lembaran_Desa.csv',
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

            if (empty($data['nomor_seri']) || empty($data['judul'])) {
                continue;
            }

            BukuLembaranDesa::create([
                'tahun'               => !empty($data['tahun']) ? (int)$data['tahun'] : (int)date('Y'),
                'jenis'               => in_array($data['jenis'] ?? '', ['lembaran_desa', 'berita_desa']) ? $data['jenis'] : 'lembaran_desa',
                'nomor_seri'          => $data['nomor_seri'],
                'tanggal_diundangkan' => !empty($data['tanggal_diundangkan']) ? date('Y-m-d', strtotime($data['tanggal_diundangkan'])) : date('Y-m-d'),
                'judul'               => $data['judul'],
                'isi_singkat'         => $data['isi_singkat'] ?? null,
                'created_by'          => auth()->id(),
            ]);

            $importedCount++;
        }

        return redirect()->route('administrasi.lembaran-desa.index')
            ->with('success', "Berhasil mengimpor {$importedCount} data lembaran/berita desa.");
    }
}
