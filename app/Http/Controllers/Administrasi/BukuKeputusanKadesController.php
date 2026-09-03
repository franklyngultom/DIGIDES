<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuKeputusanKades;
use App\Models\DesaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuKeputusanKadesController extends Controller
{
    public function index(Request $request)
    {
        $tahun  = $request->query('tahun');
        $search = $request->query('search');

        $data = BukuKeputusanKades::with('creator')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nomor_keputusan', 'like', "%$search%")
                  ->orWhere('tentang', 'like', "%$search%");
            }))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $tahuns = BukuKeputusanKades::selectRaw('DISTINCT tahun')->orderByDesc('tahun')->pluck('tahun');

        return view('administrasi.keputusan-kades.index', compact('data', 'tahuns', 'tahun', 'search'));
    }

    public function create()
    {
        return view('administrasi.keputusan-kades.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun'              => 'required|integer|min:2000|max:2099',
            'nomor_keputusan'    => 'required|string|max:100',
            'tanggal_keputusan'  => 'required|date',
            'tentang'            => 'required|string',
            'uraian_singkat'     => 'nullable|string',
            'nomor_dilaporkan'   => 'nullable|string|max:100',
            'file_pdf'           => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('file_pdf')) {
            $validated['file_pdf_path'] = $request->file('file_pdf')
                ->store('keputusan-kades', 'public');
        }

        BukuKeputusanKades::create($validated);

        return redirect()->route('administrasi.keputusan-kades.index')
            ->with('success', 'SK Kepala Desa berhasil ditambahkan.');
    }

    public function edit(BukuKeputusanKades $keputusanKades)
    {
        return view('administrasi.keputusan-kades.form', ['record' => $keputusanKades]);
    }

    public function update(Request $request, BukuKeputusanKades $keputusanKades)
    {
        $validated = $request->validate([
            'tahun'              => 'required|integer|min:2000|max:2099',
            'nomor_keputusan'    => 'required|string|max:100',
            'tanggal_keputusan'  => 'required|date',
            'tentang'            => 'required|string',
            'uraian_singkat'     => 'nullable|string',
            'nomor_dilaporkan'   => 'nullable|string|max:100',
            'file_pdf'           => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_pdf')) {
            if ($keputusanKades->file_pdf_path) {
                Storage::disk('public')->delete($keputusanKades->file_pdf_path);
            }
            $validated['file_pdf_path'] = $request->file('file_pdf')
                ->store('keputusan-kades', 'public');
        }

        $keputusanKades->update($validated);

        return redirect()->route('administrasi.keputusan-kades.index')
            ->with('success', 'Data SK berhasil diperbarui.');
    }

    public function destroy(BukuKeputusanKades $keputusanKades)
    {
        if ($keputusanKades->file_pdf_path) {
            Storage::disk('public')->delete($keputusanKades->file_pdf_path);
        }
        $keputusanKades->delete();

        return redirect()->route('administrasi.keputusan-kades.index')
            ->with('success', 'Data SK berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun');
        $desa = DesaProfile::current();

        $data = BukuKeputusanKades::with('creator')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_keputusan', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.administrasi.rekap_keputusan_kades', compact('data', 'desa', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Keputusan_Kades_' . ($tahun ?: 'Semua_Tahun') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $tahun = $request->query('tahun');
        $data = BukuKeputusanKades::when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_keputusan', 'asc')
            ->get();

        $headers = [
            'No',
            'Tahun',
            'Nomor Keputusan',
            'Tanggal Keputusan',
            'Tentang / Judul SK',
            'Uraian Singkat',
            'Nomor Dilaporkan',
        ];

        $rows = [];
        foreach ($data as $i => $item) {
            $rows[] = [
                $i + 1,
                $item->tahun,
                $item->nomor_keputusan,
                $item->tanggal_keputusan ? date('Y-m-d', strtotime($item->tanggal_keputusan)) : '',
                $item->tentang,
                $item->uraian_singkat ?? '',
                $item->nomor_dilaporkan ?? '',
            ];
        }

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Buku_Keputusan_Kades_' . ($tahun ?: 'Semua_Tahun') . '_' . date('Ymd_His') . '.csv',
            $headers,
            $rows
        );
    }

    public function downloadTemplate()
    {
        $headers = [
            'tahun',
            'nomor_keputusan',
            'tanggal_keputusan',
            'tentang',
            'uraian_singkat',
            'nomor_dilaporkan',
        ];

        $rows = [
            [
                date('Y'),
                '141/01/SK-KDS/' . date('Y'),
                date('Y') . '-01-10',
                'Penetapan Pengurus Lembaga Pemberdayaan Masyarakat Desa (LPMD)',
                'Keputusan pengangkatan struktur pengurus LPMD periode ' . date('Y') . '-' . (date('Y') + 5),
                '141/01/Lap-Kec/' . date('Y'),
            ]
        ];

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Template_Import_Keputusan_Kades.csv',
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

            if (empty($data['nomor_keputusan']) || empty($data['tentang'])) {
                continue;
            }

            BukuKeputusanKades::create([
                'tahun'             => !empty($data['tahun']) ? (int)$data['tahun'] : (int)date('Y'),
                'nomor_keputusan'   => $data['nomor_keputusan'],
                'tanggal_keputusan' => !empty($data['tanggal_keputusan']) ? date('Y-m-d', strtotime($data['tanggal_keputusan'])) : date('Y-m-d'),
                'tentang'           => $data['tentang'],
                'uraian_singkat'    => $data['uraian_singkat'] ?? null,
                'nomor_dilaporkan'  => $data['nomor_dilaporkan'] ?? null,
                'created_by'        => auth()->id(),
            ]);

            $importedCount++;
        }

        return redirect()->route('administrasi.keputusan-kades.index')
            ->with('success', "Berhasil mengimpor {$importedCount} data keputusan kepala desa.");
    }
}
