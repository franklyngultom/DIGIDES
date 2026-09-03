<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AparaturController extends Controller
{
    /**
     * Display listing of aparat desa.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Aparatur::with('penduduk')
            ->when($search, function ($q, $s) {
                $q->where('jabatan', 'like', "%{$s}%")
                    ->orWhere('nip', 'like', "%{$s}%")
                    ->orWhereHas('penduduk', fn($p) => $p->where('nama_lengkap', 'like', "%{$s}%")->orWhere('nik', 'like', "%{$s}%"));
            })
            ->when($status !== null && $status !== '', function ($q) use ($status) {
                $q->where('status_aktif', $status == '1');
            })
            ->orderBy('status_aktif', 'desc')
            ->orderBy('id', 'asc');

        $aparatur = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Aparatur::count(),
            'aktif' => Aparatur::where('status_aktif', true)->count(),
            'perangkat' => Aparatur::where('status_kepegawaian', 'perangkat_desa')->count(),
            'pns_pppk' => Aparatur::whereIn('status_kepegawaian', ['pns', 'pppk'])->count(),
        ];

        return view('administrasi.aparatur.index', compact('aparatur', 'stats', 'search', 'status'));
    }

    public function create()
    {
        $penduduks = Penduduk::orderBy('nama_lengkap', 'asc')->get();
        return view('administrasi.aparatur.form', compact('penduduks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id|unique:aparatur,penduduk_id',
            'nip' => 'nullable|string|max:30',
            'jabatan' => 'required|string|max:100',
            'jam_masuk_standar' => 'required|string',
            'jam_pulang_standar' => 'required|string',
            'toleransi_terlambat_menit' => 'required|integer|min:0|max:120',
            'status_kepegawaian' => 'required|in:pns,pppk,perangkat_desa,honorer',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['qr_token'] = Str::random(32);
        $validated['status_aktif'] = $request->boolean('status_aktif', true);

        Aparatur::create($validated);

        return redirect()->route('administrasi.aparatur.index')
            ->with('success', 'Data aparat desa berhasil ditambahkan ke buku register.');
    }

    public function edit(Aparatur $aparatur)
    {
        $penduduks = Penduduk::orderBy('nama_lengkap', 'asc')->get();
        return view('administrasi.aparatur.form', compact('aparatur', 'penduduks'));
    }

    public function update(Request $request, Aparatur $aparatur)
    {
        $validated = $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id|unique:aparatur,penduduk_id,' . $aparatur->id,
            'nip' => 'nullable|string|max:30',
            'jabatan' => 'required|string|max:100',
            'jam_masuk_standar' => 'required|string',
            'jam_pulang_standar' => 'required|string',
            'toleransi_terlambat_menit' => 'required|integer|min:0|max:120',
            'status_kepegawaian' => 'required|in:pns,pppk,perangkat_desa,honorer',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['status_aktif'] = $request->boolean('status_aktif', true);

        $aparatur->update($validated);

        return redirect()->route('administrasi.aparatur.index')
            ->with('success', 'Data aparat desa berhasil diperbarui.');
    }

    public function destroy(Aparatur $aparatur)
    {
        $aparatur->delete();
        return redirect()->route('administrasi.aparatur.index')
            ->with('success', 'Data aparat desa berhasil dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $status = $request->query('status');
        $desa = \App\Models\DesaProfile::current();

        $data = Aparatur::with('penduduk')
            ->when($status !== null && $status !== '', fn($q) => $q->where('status_aktif', $status == '1'))
            ->orderBy('id', 'asc')
            ->get();

        $tahun = date('Y');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.administrasi.rekap_aparatur', compact('data', 'desa', 'tahun'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Buku_Aparat_Pemerintah_Desa_' . date('Ymd_His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $status = $request->query('status');
        $data = Aparatur::with('penduduk')
            ->when($status !== null && $status !== '', fn($q) => $q->where('status_aktif', $status == '1'))
            ->orderBy('id', 'asc')
            ->get();

        $headers = [
            'No',
            'Kode Aparat',
            'NIK',
            'Nama Lengkap',
            'NIP / NIAP',
            'Jabatan',
            'Status Kepegawaian',
            'Pendidikan',
            'Jam Masuk Standar',
            'Jam Pulang Standar',
            'Toleransi Terlambat (Menit)',
            'Status Aktif',
        ];

        $rows = [];
        foreach ($data as $i => $item) {
            $rows[] = [
                $i + 1,
                'APRT-' . $item->id,
                "'" . ($item->penduduk->nik ?? ''),
                $item->penduduk->nama_lengkap ?? '-',
                "'" . ($item->nip ?? ''),
                $item->jabatan,
                $item->status_kepegawaian,
                $item->penduduk->pendidikan_terakhir ?? '-',
                $item->jam_masuk_standar ?? '08:00:00',
                $item->jam_pulang_standar ?? '16:00:00',
                $item->toleransi_terlambat_menit ?? 15,
                $item->status_aktif ? 'Aktif' : 'Non-Aktif',
            ];
        }

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Buku_Aparatur_Pemerintah_Desa_' . date('Ymd_His') . '.csv',
            $headers,
            $rows
        );
    }

    public function downloadTemplate()
    {
        $headers = [
            'nik',
            'nama_lengkap',
            'nip',
            'jabatan',
            'status_kepegawaian',
            'jam_masuk_standar',
            'jam_pulang_standar',
            'toleransi_terlambat_menit',
            'status_aktif',
        ];

        $rows = [
            [
                '3202111504700001',
                'Budi Santoso',
                '198505102010011009',
                'Sekretaris Desa',
                'pns',
                '08:00:00',
                '16:00:00',
                '15',
                '1',
            ],
            [
                '3202111504700002',
                'Ahmad Fauzi',
                '',
                'Kaur Keuangan',
                'perangkat_desa',
                '08:00:00',
                '16:00:00',
                '15',
                '1',
            ]
        ];

        return \App\Services\AdministrasiImportExportService::exportCsv(
            'Template_Import_Aparat_Desa.csv',
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

            if (empty($data['nama_lengkap']) || empty($data['jabatan'])) {
                continue;
            }

            // Find or create Penduduk
            $nik = !empty($data['nik']) ? preg_replace('/[^0-9]/', '', $data['nik']) : null;
            $penduduk = null;

            if ($nik) {
                $penduduk = Penduduk::where('nik', $nik)->first();
            }

            if (!$penduduk) {
                $penduduk = Penduduk::where('nama_lengkap', $data['nama_lengkap'])->first();
            }

            if (!$penduduk) {
                $autoNik = $nik ?: ('3202' . date('ymd') . rand(1000, 9999));
                $penduduk = Penduduk::create([
                    'nik' => $autoNik,
                    'no_kk' => $autoNik,
                    'nama_lengkap' => $data['nama_lengkap'],
                    'tempat_lahir' => 'Sukabumi',
                    'tanggal_lahir' => '1985-01-01',
                    'jenis_kelamin' => 'L',
                    'agama' => 'Islam',
                    'alamat_lengkap' => 'Kantor Desa Sukamaju',
                    'rt' => '001',
                    'rw' => '001',
                    'status_penduduk' => 'tetap',
                    'sumber_data' => 'manual',
                ]);
            }

            // Check if already an aparatur
            $existingAparat = Aparatur::where('penduduk_id', $penduduk->id)->first();
            if ($existingAparat) {
                $existingAparat->update([
                    'nip' => !empty($data['nip']) ? $data['nip'] : $existingAparat->nip,
                    'jabatan' => $data['jabatan'],
                    'status_kepegawaian' => in_array($data['status_kepegawaian'] ?? '', ['pns', 'pppk', 'perangkat_desa', 'honorer']) ? $data['status_kepegawaian'] : 'perangkat_desa',
                    'jam_masuk_standar' => !empty($data['jam_masuk_standar']) ? $data['jam_masuk_standar'] : '08:00:00',
                    'jam_pulang_standar' => !empty($data['jam_pulang_standar']) ? $data['jam_pulang_standar'] : '16:00:00',
                    'toleransi_terlambat_menit' => isset($data['toleransi_terlambat_menit']) ? (int)$data['toleransi_terlambat_menit'] : 15,
                    'status_aktif' => isset($data['status_aktif']) ? (bool)$data['status_aktif'] : true,
                ]);
            } else {
                Aparatur::create([
                    'penduduk_id' => $penduduk->id,
                    'nip' => $data['nip'] ?? null,
                    'jabatan' => $data['jabatan'],
                    'qr_token' => Str::random(32),
                    'jam_masuk_standar' => !empty($data['jam_masuk_standar']) ? $data['jam_masuk_standar'] : '08:00:00',
                    'jam_pulang_standar' => !empty($data['jam_pulang_standar']) ? $data['jam_pulang_standar'] : '16:00:00',
                    'toleransi_terlambat_menit' => isset($data['toleransi_terlambat_menit']) ? (int)$data['toleransi_terlambat_menit'] : 15,
                    'status_kepegawaian' => in_array($data['status_kepegawaian'] ?? '', ['pns', 'pppk', 'perangkat_desa', 'honorer']) ? $data['status_kepegawaian'] : 'perangkat_desa',
                    'status_aktif' => isset($data['status_aktif']) ? (bool)$data['status_aktif'] : true,
                ]);
            }

            $importedCount++;
        }

        return redirect()->route('administrasi.aparatur.index')
            ->with('success', "Berhasil mengimpor {$importedCount} data aparatur pemerintah desa.");
    }
}
