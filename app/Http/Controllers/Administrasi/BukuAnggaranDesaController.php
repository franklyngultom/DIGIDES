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

    public function exportExcel(Request $request)
    {
        $tahun = $request->query('tahun');
        $data = BukuAnggaranDesa::when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_penetapan', 'asc')
            ->get();

        $headers = [
            'No',
            'Tahun Anggaran',
            'Jenis Dokumen (apbdes/apbdes_perubahan)',
            'Nomor Perdes',
            'Tanggal Penetapan',
            'Total Pendapatan (Rp)',
            'Total Belanja (Rp)',
            'Total Pembiayaan (Rp)',
            'Keterangan',
        ];

        $rows = [];
        foreach ($data as $i => $item) {
            $rows[] = [
                $i + 1,
                $item->tahun,
                $item->jenis_dokumen,
                "'" . $item->nomor_perdes,
                $item->tanggal_penetapan ? date('Y-m-d', strtotime($item->tanggal_penetapan)) : '',
                $item->total_pendapatan,
                $item->total_belanja,
                $item->total_pembiayaan,
                $item->keterangan ?? '',
            ];
        }

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Buku_Anggaran_Desa_' . ($tahun ?: 'Semua_Tahun') . '_' . date('Ymd_His') . '.csv',
            $headers,
            $rows
        );
    }

    public function downloadTemplate()
    {
        $headers = [
            'tahun',
            'jenis_dokumen',
            'nomor_perdes',
            'tanggal_penetapan',
            'total_pendapatan',
            'total_belanja',
            'total_pembiayaan',
            'keterangan',
        ];

        $rows = [
            [
                date('Y'),
                'apbdes',
                '01/PRD-SKM/' . date('Y'),
                date('Y') . '-01-10',
                '1450000000',
                '1420000000',
                '30000000',
                'Anggaran Pendapatan dan Belanja Desa Induk Tahun ' . date('Y'),
            ]
        ];

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Template_Import_Anggaran_Desa.csv',
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

            if (empty($data['nomor_perdes'])) {
                continue;
            }

            BukuAnggaranDesa::create([
                'tahun'             => !empty($data['tahun']) ? (int)$data['tahun'] : (int)date('Y'),
                'jenis_dokumen'     => in_array($data['jenis_dokumen'] ?? '', ['apbdes', 'apbdes_perubahan']) ? $data['jenis_dokumen'] : 'apbdes',
                'nomor_perdes'      => $data['nomor_perdes'],
                'tanggal_penetapan' => !empty($data['tanggal_penetapan']) ? date('Y-m-d', strtotime($data['tanggal_penetapan'])) : date('Y-m-d'),
                'total_pendapatan'  => !empty($data['total_pendapatan']) ? (float)str_replace(['Rp', '.', ','], '', $data['total_pendapatan']) : 0,
                'total_belanja'     => !empty($data['total_belanja']) ? (float)str_replace(['Rp', '.', ','], '', $data['total_belanja']) : 0,
                'total_pembiayaan'  => !empty($data['total_pembiayaan']) ? (float)str_replace(['Rp', '.', ','], '', $data['total_pembiayaan']) : 0,
                'keterangan'        => $data['keterangan'] ?? null,
                'created_by'        => auth()->id(),
            ]);

            $importedCount++;
        }

        return redirect()->route('administrasi.anggaran-desa.index')
            ->with('success', "Berhasil mengimpor {$importedCount} data buku anggaran desa.");
    }
}
