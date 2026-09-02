<?php

namespace App\Http\Controllers\Kependudukan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kependudukan\PendudukStoreRequest;
use App\Http\Requests\Kependudukan\PendudukUpdateRequest;
use App\Models\DesaProfile;
use App\Models\Penduduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PendudukController extends Controller
{
    /**
     * Display a listing of residents (Buku Induk Penduduk).
     */
    public function index(Request $request): View
    {
        $query = Penduduk::query()
            ->search($request->input('search'))
            ->dusun($request->input('dusun'))
            ->rtRw($request->input('rt'), $request->input('rw'))
            ->jenisKelamin($request->input('jenis_kelamin'))
            ->status($request->input('status_penduduk'))
            ->kategoriUsia($request->input('kategori_usia'))
            ->orderBy('nama_lengkap');

        $penduduks = $query->paginate(20)->withQueryString();

        // Summary statistics
        $stats = [
            'total' => Penduduk::count(),
            'laki_laki' => Penduduk::where('jenis_kelamin', 'L')->count(),
            'perempuan' => Penduduk::where('jenis_kelamin', 'P')->count(),
            'kk' => Penduduk::distinct('no_kk')->count('no_kk'),
            'pindah' => Penduduk::where('status_penduduk', 'pindah')->count(),
            'meninggal' => Penduduk::where('status_penduduk', 'meninggal')->count(),
        ];

        $dusunList = Penduduk::whereNotNull('dusun')
            ->distinct()
            ->orderBy('dusun')
            ->pluck('dusun');

        return view('kependudukan.index', compact('penduduks', 'stats', 'dusunList'));
    }

    /**
     * Show the form for creating a new resident.
     */
    public function create(): View
    {
        return view('kependudukan.create');
    }

    /**
     * Store a newly created resident.
     */
    public function store(PendudukStoreRequest $request): RedirectResponse
    {
        $penduduk = Penduduk::create($request->validated());

        return redirect()
            ->route('kependudukan.show', $penduduk)
            ->with('success', "Data warga {$penduduk->nama_lengkap} berhasil didaftarkan.");
    }

    /**
     * Display the specified resident.
     */
    public function show(Penduduk $penduduk): View
    {
        $penduduk->load(['documents', 'mutasis.createdBy']);

        return view('kependudukan.show', compact('penduduk'));
    }

    /**
     * Show the form for editing the specified resident.
     */
    public function edit(Penduduk $penduduk): View
    {
        return view('kependudukan.edit', compact('penduduk'));
    }

    /**
     * Update the specified resident.
     */
    public function update(PendudukUpdateRequest $request, Penduduk $penduduk): RedirectResponse
    {
        $penduduk->update($request->validated());

        return redirect()
            ->route('kependudukan.show', $penduduk)
            ->with('success', "Data warga {$penduduk->nama_lengkap} berhasil diperbarui.");
    }

    /**
     * Remove the specified resident.
     */
    public function destroy(Penduduk $penduduk): RedirectResponse
    {
        $nama = $penduduk->nama_lengkap;
        $penduduk->delete();

        return redirect()
            ->route('kependudukan.index')
            ->with('success', "Data warga {$nama} berhasil dihapus.");
    }

    /**
     * Bulk Delete selected residents.
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids');
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }

        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('kependudukan.index')->with('error', 'Tidak ada data warga yang dipilih.');
        }

        $count = Penduduk::whereIn('id', $ids)->delete();

        return redirect()->route('kependudukan.index')->with('success', "Berhasil menghapus {$count} data warga terpilih.");
    }

    /**
     * Build filtered query for exports.
     */
    private function getFilteredQuery(Request $request)
    {
        $selectedIds = $request->input('ids');
        if ($selectedIds) {
            $ids = is_string($selectedIds) ? explode(',', $selectedIds) : $selectedIds;
            return Penduduk::whereIn('id', $ids)->orderBy('nama_lengkap');
        }

        return Penduduk::query()
            ->search($request->input('search'))
            ->dusun($request->input('dusun'))
            ->rtRw($request->input('rt'), $request->input('rw'))
            ->jenisKelamin($request->input('jenis_kelamin'))
            ->status($request->input('status_penduduk'))
            ->kategoriUsia($request->input('kategori_usia'))
            ->orderBy('nama_lengkap');
    }

    /**
     * Export to PDF.
     */
    public function exportPdf(Request $request): Response
    {
        $desa = DesaProfile::current();
        $data = $this->getFilteredQuery($request)->get();

        $filters = [];
        if ($request->filled('search')) $filters[] = 'Pencarian: ' . $request->input('search');
        if ($request->filled('dusun')) $filters[] = 'Dusun: ' . $request->input('dusun');
        if ($request->filled('jenis_kelamin')) $filters[] = 'Gender: ' . ($request->input('jenis_kelamin') === 'L' ? 'Laki-Laki' : 'Perempuan');
        if ($request->filled('status_penduduk')) $filters[] = 'Status: ' . ucfirst($request->input('status_penduduk'));
        $filterText = implode(' | ', $filters);

        $pdf = Pdf::loadView('pdf.kependudukan.buku_induk_penduduk', compact('data', 'desa', 'filterText'))
            ->setPaper('a4', 'landscape');

        $filename = 'Buku_Induk_Penduduk_' . date('Ymd_His') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Export to Excel (CSV with UTF-8 BOM formatted for Microsoft Excel).
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $data = $this->getFilteredQuery($request)->get();
        $filename = 'Data_Kependudukan_Desa_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($data) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens indonesian characters & numbers properly
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($handle, [
                'No',
                'NIK',
                'Nomor KK',
                'Nama Lengkap',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Jenis Kelamin',
                'Agama',
                'Pendidikan Terakhir',
                'Pekerjaan',
                'Status Perkawinan',
                'Status Dalam Keluarga',
                'Kewarganegaraan',
                'Golongan Darah',
                'Alamat Lengkap',
                'RT',
                'RW',
                'Dusun',
                'Telepon',
                'Sumber Data',
                'Status Kependudukan',
            ]);

            foreach ($data as $index => $row) {
                fputcsv($handle, [
                    $index + 1,
                    "'" . $row->nik, // Prefix with apostrophe to ensure Excel preserves leading zeros and 16 digits
                    "'" . $row->no_kk,
                    $row->nama_lengkap,
                    $row->tempat_lahir,
                    $row->tanggal_lahir?->format('Y-m-d'),
                    $row->jenis_kelamin,
                    $row->agama,
                    $row->pendidikan_terakhir ?? '-',
                    $row->pekerjaan ?? '-',
                    $row->status_perkawinan_label,
                    $row->status_keluarga_label,
                    $row->kewarganegaraan ?? 'WNI',
                    $row->golongan_darah ?? '-',
                    $row->alamat_lengkap,
                    $row->rt,
                    $row->rw,
                    $row->dusun ?? '-',
                    $row->telepon ? "'" . $row->telepon : '-',
                    $row->sumber_data,
                    $row->status_penduduk,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        return $this->exportExcel($request);
    }

    /**
     * Download Sample Import Template.
     */
    public function downloadTemplate(): StreamedResponse
    {
        $filename = 'Template_Import_Kependudukan_DIGIDES.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header columns
            fputcsv($handle, [
                'nik',
                'no_kk',
                'nama_lengkap',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'agama',
                'pendidikan_terakhir',
                'pekerjaan',
                'status_perkawinan',
                'status_dalam_keluarga',
                'kewarganegaraan',
                'golongan_darah',
                'alamat_lengkap',
                'rt',
                'rw',
                'dusun',
                'telepon',
                'sumber_data',
                'status_penduduk',
            ]);

            // Sample row 1
            fputcsv($handle, [
                '3202110101850001',
                '3202110101850000',
                'Ahmad Fauzi',
                'Sukabumi',
                '1985-06-12',
                'L',
                'Islam',
                'Diploma IV/S1',
                'Wiraswasta',
                'kawin',
                'kepala_keluarga',
                'WNI',
                'O',
                'Jl. Cikole No. 15',
                '001',
                '002',
                'Cikole',
                '081234567890',
                'manual',
                'tetap',
            ]);

            // Sample row 2
            fputcsv($handle, [
                '3202115208880002',
                '3202110101850000',
                'Siti Nurhaliza',
                'Sukabumi',
                '1988-08-22',
                'P',
                'Islam',
                'SLTA/Sederajat',
                'Ibu Rumah Tangga',
                'kawin',
                'istri',
                'WNI',
                'A',
                'Jl. Cikole No. 15',
                '001',
                '002',
                'Cikole',
                '081298765432',
                'manual',
                'tetap',
            ]);

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Import Residents from uploaded CSV / Excel file.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ], [
            'file.required' => 'File berkas impor wajib dipilih.',
            'file.mimes' => 'Format berkas harus berupa .CSV atau .XLSX / .XLS.',
            'file.max' => 'Ukuran berkas maksimal 10 MB.',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            // Check BOM
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            // Detect delimiter (comma or semicolon or tab)
            $firstLine = fgets($handle);
            rewind($handle);
            if ($bom === "\xEF\xBB\xBF") {
                fread($handle, 3);
            }

            $delimiter = ',';
            if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                $delimiter = ';';
            } elseif (substr_count($firstLine, "\t") > substr_count($firstLine, ',')) {
                $delimiter = "\t";
            }

            $header = null;
            while (($data = fgetcsv($handle, 4000, $delimiter)) !== false) {
                if (!$header) {
                    $header = array_map(function ($col) {
                        return strtolower(trim(str_replace([' ', "'", '"', "\xEF\xBB\xBF"], '', $col)));
                    }, $data);
                } else {
                    if (count($data) >= count($header)) {
                        $row = array_combine(array_slice($header, 0, count($data)), $data);
                        $rows[] = $row;
                    }
                }
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return redirect()->route('kependudukan.index')
                ->with('error', 'Berkas impor kosong atau format baris tidak dapat dibaca.');
        }

        $inserted = 0;
        $updated = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $rowNum = $index + 2;

                // Extract & clean NIK & No KK
                $nik = preg_replace('/[^0-9]/', '', (string) ($row['nik'] ?? ''));
                $noKk = preg_replace('/[^0-9]/', '', (string) ($row['no_kk'] ?? $row['nomor_kk'] ?? ''));
                $nama = trim($row['nama_lengkap'] ?? $row['nama'] ?? '');

                if (empty($nik) && empty($nama)) {
                    continue; // Skip empty rows
                }

                if (strlen($nik) !== 16) {
                    $errors[] = "Baris #{$rowNum} ({$nama}): NIK harus tepat 16 digit angka numerik (terbaca: '{$nik}').";
                    continue;
                }

                if (empty($noKk) || strlen($noKk) !== 16) {
                    $noKk = $nik; // Fallback to NIK if KK missing
                }

                if (empty($nama)) {
                    $errors[] = "Baris #{$rowNum}: Nama lengkap warga tidak boleh kosong.";
                    continue;
                }

                // Format birth date
                $tglLahirRaw = trim($row['tanggal_lahir'] ?? $row['tgl_lahir'] ?? '1990-01-01');
                try {
                    $tglLahir = Carbon::parse($tglLahirRaw)->toDateString();
                } catch (\Exception $e) {
                    $tglLahir = '1990-01-01';
                }

                // Format Gender
                $jkRaw = strtoupper(trim($row['jenis_kelamin'] ?? $row['jk'] ?? 'L'));
                $jk = (str_starts_with($jkRaw, 'P') || $jkRaw === 'PEREMPUAN') ? 'P' : 'L';

                // Status perkawinan mapping
                $spRaw = strtolower(trim(str_replace(' ', '_', $row['status_perkawinan'] ?? 'belum_kawin')));
                $statusPerkawinan = in_array($spRaw, ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati']) ? $spRaw : 'belum_kawin';

                // Status dalam keluarga mapping
                $sdkRaw = strtolower(trim(str_replace(' ', '_', $row['status_dalam_keluarga'] ?? 'kepala_keluarga')));
                $statusKeluarga = in_array($sdkRaw, ['kepala_keluarga', 'istri', 'anak', 'famili_lain', 'lainnya']) ? $sdkRaw : 'kepala_keluarga';

                // Status penduduk mapping
                $stRaw = strtolower(trim($row['status_penduduk'] ?? 'tetap'));
                $statusPenduduk = in_array($stRaw, ['tetap', 'sementara', 'pindah', 'meninggal']) ? $stRaw : 'tetap';

                $payload = [
                    'nik' => $nik,
                    'no_kk' => $noKk,
                    'nama_lengkap' => $nama,
                    'tempat_lahir' => trim($row['tempat_lahir'] ?? 'Sukabumi') ?: 'Sukabumi',
                    'tanggal_lahir' => $tglLahir,
                    'jenis_kelamin' => $jk,
                    'agama' => trim($row['agama'] ?? 'Islam') ?: 'Islam',
                    'pendidikan_terakhir' => trim($row['pendidikan_terakhir'] ?? $row['pendidikan'] ?? null),
                    'pekerjaan' => trim($row['pekerjaan'] ?? null),
                    'status_perkawinan' => $statusPerkawinan,
                    'status_dalam_keluarga' => $statusKeluarga,
                    'kewarganegaraan' => trim($row['kewarganegaraan'] ?? 'WNI') ?: 'WNI',
                    'golongan_darah' => trim($row['golongan_darah'] ?? null),
                    'alamat_lengkap' => trim($row['alamat_lengkap'] ?? $row['alamat'] ?? 'Desa Sukamaju') ?: 'Desa Sukamaju',
                    'rt' => str_pad(preg_replace('/[^0-9]/', '', (string) ($row['rt'] ?? '001')) ?: '001', 3, '0', STR_PAD_LEFT),
                    'rw' => str_pad(preg_replace('/[^0-9]/', '', (string) ($row['rw'] ?? '001')) ?: '001', 3, '0', STR_PAD_LEFT),
                    'dusun' => trim($row['dusun'] ?? null),
                    'telepon' => trim($row['telepon'] ?? $row['no_hp'] ?? null),
                    'sumber_data' => in_array($row['sumber_data'] ?? '', ['prodeskel', 'migrasi_legacy']) ? $row['sumber_data'] : 'prodeskel',
                    'status_penduduk' => $statusPenduduk,
                ];

                $existing = Penduduk::where('nik', $nik)->first();
                if ($existing) {
                    $existing->update($payload);
                    $updated++;
                } else {
                    Penduduk::create($payload);
                    $inserted++;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kependudukan.index')
                ->with('error', 'Terjadi kesalahan saat memproses data impor: ' . $e->getMessage());
        }

        $msg = "Proses impor selesai. Berhasil menambahkan {$inserted} warga baru dan memperbarui {$updated} data warga.";
        if (!empty($errors)) {
            $msg .= ' Namun ada ' . count($errors) . ' baris yang dilewati karena format tidak sesuai: ' . implode('; ', array_slice($errors, 0, 3));
        }

        return redirect()->route('kependudukan.index')->with('success', $msg);
    }
}

