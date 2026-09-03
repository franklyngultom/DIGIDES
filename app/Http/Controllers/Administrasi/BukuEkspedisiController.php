<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\BukuEkspedisi;
use App\Models\DesaProfile;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BukuEkspedisiController extends Controller
{
    public function index(Request $request)
    {
        $tahun  = $request->query('tahun');
        $search = $request->query('search');

        $data = BukuEkspedisi::with('suratArsip')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%$search%")
                  ->orWhere('perihal', 'like', "%$search%")
                  ->orWhere('tujuan_penerima', 'like', "%$search%")
                  ->orWhere('petugas_pengirim', 'like', "%$search%");
            }))
            ->latest('tanggal_pengiriman')
            ->paginate(10)
            ->withQueryString();

        $tahuns = BukuEkspedisi::selectRaw('DISTINCT tahun')->orderByDesc('tahun')->pluck('tahun');

        return view('administrasi.buku-ekspedisi.index', compact('data', 'tahuns', 'tahun', 'search'));
    }

    public function create()
    {
        $currentYear = (int) date('Y');
        $nextNomorUrut = (BukuEkspedisi::where('tahun', $currentYear)->max('nomor_urut') ?? 0) + 1;

        return view('administrasi.buku-ekspedisi.form', compact('nextNomorUrut', 'currentYear'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_urut'         => 'required|integer|min:1',
            'tahun'              => 'required|integer|min:2000|max:2099',
            'tanggal_pengiriman' => 'required|date',
            'nomor_surat'        => 'required|string|max:100',
            'tanggal_surat'      => 'required|date',
            'perihal'            => 'required|string|max:255',
            'tujuan_penerima'    => 'required|string|max:200',
            'petugas_pengirim'   => 'required|string|max:100',
            'catatan'            => 'nullable|string',
        ]);

        BukuEkspedisi::create($validated);

        return redirect()->route('administrasi.buku-ekspedisi.index')
            ->with('success', 'Buku ekspedisi berhasil ditambahkan.');
    }

    public function edit(BukuEkspedisi $bukuEkspedisi)
    {
        return view('administrasi.buku-ekspedisi.form', ['record' => $bukuEkspedisi]);
    }

    public function update(Request $request, BukuEkspedisi $bukuEkspedisi)
    {
        $validated = $request->validate([
            'nomor_urut'         => 'required|integer|min:1',
            'tahun'              => 'required|integer|min:2000|max:2099',
            'tanggal_pengiriman' => 'required|date',
            'nomor_surat'        => 'required|string|max:100',
            'tanggal_surat'      => 'required|date',
            'perihal'            => 'required|string|max:255',
            'tujuan_penerima'    => 'required|string|max:200',
            'petugas_pengirim'   => 'required|string|max:100',
            'catatan'            => 'nullable|string',
        ]);

        $bukuEkspedisi->update($validated);

        return redirect()->route('administrasi.buku-ekspedisi.index')
            ->with('success', 'Data ekspedisi berhasil diperbarui.');
    }

    public function destroy(BukuEkspedisi $bukuEkspedisi)
    {
        $bukuEkspedisi->delete();

        return redirect()->route('administrasi.buku-ekspedisi.index')
            ->with('success', 'Data ekspedisi berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->query('tahun');
        $desa = DesaProfile::current();

        $data = BukuEkspedisi::with('suratArsip')
            ->when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_pengiriman', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.administrasi.rekap_buku_ekspedisi', compact('data', 'desa', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Ekspedisi_' . ($tahun ?: 'Semua_Tahun') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $tahun = $request->query('tahun');
        $data = BukuEkspedisi::when($tahun, fn($q) => $q->where('tahun', $tahun))
            ->orderBy('tanggal_pengiriman', 'asc')
            ->get();

        $headers = [
            'No Urut',
            'Tahun',
            'Tanggal Pengiriman',
            'Nomor Surat Dikirim',
            'Tanggal Surat',
            'Perihal Surat',
            'Tujuan Penerima',
            'Petugas Pengirim',
            'Catatan / Tanda Terima',
        ];

        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                $item->nomor_urut,
                $item->tahun,
                $item->tanggal_pengiriman ? date('Y-m-d', strtotime($item->tanggal_pengiriman)) : '',
                "'" . $item->nomor_surat,
                $item->tanggal_surat ? date('Y-m-d', strtotime($item->tanggal_surat)) : '',
                $item->perihal,
                $item->tujuan_penerima,
                $item->petugas_pengirim,
                $item->catatan ?? '',
            ];
        }

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Buku_Ekspedisi_Surat_' . ($tahun ?: 'Semua_Tahun') . '_' . date('Ymd_His') . '.csv',
            $headers,
            $rows
        );
    }

    public function downloadTemplate()
    {
        $headers = [
            'nomor_urut',
            'tahun',
            'tanggal_pengiriman',
            'nomor_surat',
            'tanggal_surat',
            'perihal',
            'tujuan_penerima',
            'petugas_pengirim',
            'catatan',
        ];

        $rows = [
            [
                '1',
                date('Y'),
                date('Y') . '-01-15',
                '140/01/Ds-SKM/' . date('Y'),
                date('Y') . '-01-14',
                'Penyampaian Laporan Pertanggungjawaban Realisasi APBDes',
                'Camat Cikole (Kasi Pemerintahan)',
                'Ahmad Fauzi (Kaur Umum)',
                'Diterima oleh Bpk. Hendra (Staf Kecamatan)',
            ]
        ];

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Template_Import_Buku_Ekspedisi.csv',
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

            if (empty($data['nomor_surat']) || empty($data['perihal']) || empty($data['tujuan_penerima'])) {
                continue;
            }

            $tahun = !empty($data['tahun']) ? (int)$data['tahun'] : (int)date('Y');
            $nomorUrut = !empty($data['nomor_urut']) ? (int)$data['nomor_urut'] : (BukuEkspedisi::where('tahun', $tahun)->max('nomor_urut') + 1);

            BukuEkspedisi::create([
                'nomor_urut'         => $nomorUrut,
                'tahun'              => $tahun,
                'tanggal_pengiriman' => !empty($data['tanggal_pengiriman']) ? date('Y-m-d', strtotime($data['tanggal_pengiriman'])) : date('Y-m-d'),
                'nomor_surat'        => $data['nomor_surat'],
                'tanggal_surat'      => !empty($data['tanggal_surat']) ? date('Y-m-d', strtotime($data['tanggal_surat'])) : date('Y-m-d'),
                'perihal'            => $data['perihal'],
                'tujuan_penerima'    => $data['tujuan_penerima'],
                'petugas_pengirim'   => $data['petugas_pengirim'] ?? 'Petugas Ekspedisi Desa',
                'catatan'            => $data['catatan'] ?? null,
            ]);

            $importedCount++;
        }

        return redirect()->route('administrasi.buku-ekspedisi.index')
            ->with('success', "Berhasil mengimpor {$importedCount} data buku ekspedisi.");
    }
}
