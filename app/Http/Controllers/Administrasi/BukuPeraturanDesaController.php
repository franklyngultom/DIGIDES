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

    public function exportExcel(Request $request)
    {
        $tahun = $request->query('tahun');
        $data = BukuPeraturanDesa::when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_ditetapkan', 'asc')
            ->get();

        $headers = [
            'No',
            'Tahun',
            'Jenis Peraturan',
            'Nomor Ditetapkan',
            'Tanggal Ditetapkan',
            'Tentang / Judul',
            'Uraian Singkat',
            'Nomor Kesepakatan BPD',
            'Nomor Diundangkan',
            'Tanggal Diundangkan',
        ];

        $rows = [];
        foreach ($data as $i => $item) {
            $rows[] = [
                $i + 1,
                $item->tahun,
                $item->jenis_peraturan,
                $item->nomor_ditetapkan,
                $item->tanggal_ditetapkan ? date('Y-m-d', strtotime($item->tanggal_ditetapkan)) : '',
                $item->tentang,
                $item->uraian_singkat ?? '',
                $item->nomor_kesepakatan_bpd ?? '',
                $item->nomor_diundangkan ?? '',
                $item->tanggal_diundangkan ? date('Y-m-d', strtotime($item->tanggal_diundangkan)) : '',
            ];
        }

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Buku_Peraturan_Desa_' . ($tahun ?: 'Semua_Tahun') . '_' . date('Ymd_His') . '.csv',
            $headers,
            $rows
        );
    }

    public function downloadTemplate()
    {
        $headers = [
            'tahun',
            'jenis_peraturan',
            'nomor_ditetapkan',
            'tanggal_ditetapkan',
            'tentang',
            'uraian_singkat',
            'nomor_kesepakatan_bpd',
            'nomor_diundangkan',
            'tanggal_diundangkan',
        ];

        $rows = [
            [
                date('Y'),
                'perdes',
                '01/PRD-SKM/' . date('Y'),
                date('Y') . '-01-15',
                'Rencana Kerja Pemerintah Desa Tahun Anggaran ' . date('Y'),
                'Penetapan RKPDesa Sukamaju tahun berjalan',
                '02/BPD-SKM/' . date('Y'),
                '01/LMB-SKM/' . date('Y'),
                date('Y') . '-01-20',
            ]
        ];

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Template_Import_Peraturan_Desa.csv',
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

            if (empty($data['nomor_ditetapkan']) || empty($data['tentang'])) {
                continue;
            }

            BukuPeraturanDesa::create([
                'tahun'                 => !empty($data['tahun']) ? (int)$data['tahun'] : (int)date('Y'),
                'jenis_peraturan'       => in_array($data['jenis_peraturan'] ?? '', ['perdes', 'perkades', 'peraturan_bersama']) ? $data['jenis_peraturan'] : 'perdes',
                'nomor_ditetapkan'      => $data['nomor_ditetapkan'],
                'tanggal_ditetapkan'    => !empty($data['tanggal_ditetapkan']) ? date('Y-m-d', strtotime($data['tanggal_ditetapkan'])) : date('Y-m-d'),
                'tentang'               => $data['tentang'],
                'uraian_singkat'        => $data['uraian_singkat'] ?? null,
                'nomor_kesepakatan_bpd' => $data['nomor_kesepakatan_bpd'] ?? null,
                'nomor_diundangkan'     => $data['nomor_diundangkan'] ?? null,
                'tanggal_diundangkan'   => !empty($data['tanggal_diundangkan']) ? date('Y-m-d', strtotime($data['tanggal_diundangkan'])) : null,
                'created_by'            => auth()->id(),
            ]);

            $importedCount++;
        }

        return redirect()->route('administrasi.peraturan-desa.index')
            ->with('success', "Berhasil mengimpor {$importedCount} data peraturan desa.");
    }
}
