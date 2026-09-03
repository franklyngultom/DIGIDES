<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuAgenda;
use App\Models\DesaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuAgendaController extends Controller
{
    public function index(Request $request)
    {
        $tahun  = $request->query('tahun');
        $jenis  = $request->query('jenis');
        $search = $request->query('search');

        $data = BukuAgenda::with(['createdBy', 'suratArsip'])
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%$search%")
                  ->orWhere('perihal', 'like', "%$search%")
                  ->orWhere('asal_tujuan', 'like', "%$search%");
            }))
            ->latest('tanggal_surat')
            ->paginate(10)
            ->withQueryString();

        $tahuns = BukuAgenda::selectRaw('DISTINCT tahun')->orderByDesc('tahun')->pluck('tahun');

        return view('administrasi.buku-agenda.index', compact('data', 'tahuns', 'tahun', 'jenis', 'search'));
    }

    public function create()
    {
        return view('administrasi.buku-agenda.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis'                    => 'required|in:masuk,keluar',
            'nomor_urut'               => 'required|integer|min:1',
            'tahun'                    => 'required|integer|min:2000|max:2099',
            'nomor_surat'              => 'required|string|max:100',
            'tanggal_surat'            => 'required|date',
            'tanggal_diterima_dikirim' => 'required|date',
            'asal_tujuan'              => 'required|string|max:200',
            'perihal'                  => 'required|string|max:255',
            'keterangan'               => 'nullable|string',
            'file_surat'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('file_surat')) {
            $validated['file_pdf_path'] = $request->file('file_surat')
                ->store('buku-agenda', 'public');
            $validated['file_surat_path'] = $validated['file_pdf_path'];
        }

        BukuAgenda::create($validated);

        return redirect()->route('administrasi.buku-agenda.index')
            ->with('success', 'Agenda surat berhasil ditambahkan.');
    }

    public function edit(BukuAgenda $bukuAgenda)
    {
        return view('administrasi.buku-agenda.form', ['record' => $bukuAgenda]);
    }

    public function update(Request $request, BukuAgenda $bukuAgenda)
    {
        $validated = $request->validate([
            'jenis'                    => 'required|in:masuk,keluar',
            'nomor_urut'               => 'required|integer|min:1',
            'tahun'                    => 'required|integer|min:2000|max:2099',
            'nomor_surat'              => 'required|string|max:100',
            'tanggal_surat'            => 'required|date',
            'tanggal_diterima_dikirim' => 'required|date',
            'asal_tujuan'              => 'required|string|max:200',
            'perihal'                  => 'required|string|max:255',
            'keterangan'               => 'nullable|string',
            'file_surat'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file_surat')) {
            if ($bukuAgenda->file_surat_path) {
                Storage::disk('public')->delete($bukuAgenda->file_surat_path);
            }
            $validated['file_surat_path'] = $request->file('file_surat')
                ->store('buku-agenda', 'public');
        }

        $bukuAgenda->update($validated);

        return redirect()->route('administrasi.buku-agenda.index')
            ->with('success', 'Data agenda surat berhasil diperbarui.');
    }

    public function destroy(BukuAgenda $bukuAgenda)
    {
        if ($bukuAgenda->file_surat_path) {
            Storage::disk('public')->delete($bukuAgenda->file_surat_path);
        }
        $bukuAgenda->delete();

        return redirect()->route('administrasi.buku-agenda.index')
            ->with('success', 'Data agenda surat berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun');
        $jenis = $request->query('jenis');
        $desa = DesaProfile::current();

        $data = BukuAgenda::with(['createdBy', 'suratArsip'])
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->orderBy('tanggal_surat', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.administrasi.rekap_buku_agenda', compact('data', 'desa', 'tahun', 'jenis'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Agenda_' . ($jenis ?: 'Semua_Jenis') . '_' . ($tahun ?: 'Semua_Tahun') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $tahun = $request->query('tahun');
        $jenis = $request->query('jenis');
        $data = BukuAgenda::when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->orderBy('tanggal_surat', 'asc')
            ->get();

        $headers = [
            'No Urut',
            'Tahun',
            'Jenis Agenda (masuk/keluar)',
            'Nomor Surat',
            'Tanggal Surat',
            'Tanggal Diterima / Dikirim',
            'Asal / Tujuan Surat',
            'Perihal / Isi Ringkas',
            'Keterangan',
        ];

        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                $item->nomor_urut,
                $item->tahun,
                $item->jenis,
                "'" . $item->nomor_surat,
                $item->tanggal_surat ? date('Y-m-d', strtotime($item->tanggal_surat)) : '',
                $item->tanggal_diterima_dikirim ? date('Y-m-d', strtotime($item->tanggal_diterima_dikirim)) : '',
                $item->asal_tujuan,
                $item->perihal,
                $item->keterangan ?? '',
            ];
        }

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Buku_Agenda_Surat_' . ($jenis ?: 'Semua') . '_' . ($tahun ?: 'Semua_Tahun') . '_' . date('Ymd_His') . '.csv',
            $headers,
            $rows
        );
    }

    public function downloadTemplate()
    {
        $headers = [
            'nomor_urut',
            'tahun',
            'jenis',
            'nomor_surat',
            'tanggal_surat',
            'tanggal_diterima_dikirim',
            'asal_tujuan',
            'perihal',
            'keterangan',
        ];

        $rows = [
            [
                '1',
                date('Y'),
                'masuk',
                '005/124/Kec/' . date('Y'),
                date('Y') . '-01-10',
                date('Y') . '-01-11',
                'Kecamatan Cikole',
                'Undangan Rapat Koordinasi Kepala Desa Se-Kecamatan',
                'Dihadiri Kades & Sekdes',
            ],
            [
                '2',
                date('Y'),
                'keluar',
                '140/01/Ds-SKM/' . date('Y'),
                date('Y') . '-01-12',
                date('Y') . '-01-12',
                'Dinas Pemberdayaan Masyarakat dan Desa (DPMD)',
                'Laporan Realisasi APBDesa Semester Akhir',
                'Dikirim via Kurir Ekspedisi',
            ]
        ];

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Template_Import_Buku_Agenda.csv',
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

            if (empty($data['nomor_surat']) || empty($data['perihal']) || empty($data['asal_tujuan'])) {
                continue;
            }

            $tahun = !empty($data['tahun']) ? (int)$data['tahun'] : (int)date('Y');
            $jenis = in_array($data['jenis'] ?? '', ['masuk', 'keluar']) ? $data['jenis'] : 'masuk';

            $nomorUrut = !empty($data['nomor_urut']) ? (int)$data['nomor_urut'] : (BukuAgenda::where('tahun', $tahun)->where('jenis', $jenis)->max('nomor_urut') + 1);

            BukuAgenda::create([
                'nomor_urut'               => $nomorUrut,
                'tahun'                    => $tahun,
                'jenis'                    => $jenis,
                'nomor_surat'              => $data['nomor_surat'],
                'tanggal_surat'            => !empty($data['tanggal_surat']) ? date('Y-m-d', strtotime($data['tanggal_surat'])) : date('Y-m-d'),
                'tanggal_diterima_dikirim' => !empty($data['tanggal_diterima_dikirim']) ? date('Y-m-d', strtotime($data['tanggal_diterima_dikirim'])) : date('Y-m-d'),
                'asal_tujuan'              => $data['asal_tujuan'],
                'perihal'                  => $data['perihal'],
                'keterangan'               => $data['keterangan'] ?? null,
                'created_by'               => auth()->id(),
            ]);

            $importedCount++;
        }

        return redirect()->route('administrasi.buku-agenda.index')
            ->with('success', "Berhasil mengimpor {$importedCount} data agenda surat.");
    }
}
