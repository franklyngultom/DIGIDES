<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aparatur;
use App\Models\DesaProfile;
use App\Models\Penduduk;
use App\Models\SuratPenandatangan;
use App\Models\SuratPengaturan;
use App\Models\SuratTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengaturanSuratController extends Controller
{
    /**
     * Tab 1: Pengaturan Surat (Kontak Pelayanan & Format Alamat)
     */
    public function pengaturan(): View
    {
        $pengaturan = SuratPengaturan::current();
        $desa = DesaProfile::current();
        $samplePenduduk = Penduduk::first();
        $activeTab = 'pengaturan';

        return view('admin.persuratan.pengaturan.index', compact('pengaturan', 'desa', 'samplePenduduk', 'activeTab'));
    }

    /**
     * Simpan Tab 1: Pengaturan Surat
     */
    public function updatePengaturan(Request $request): RedirectResponse
    {
        $request->validate([
            'no_hp_pelayanan' => ['nullable', 'string', 'max:50'],
            'email_pelayanan' => ['nullable', 'email', 'max:100'],
            'format_alamat'   => ['nullable', 'array'],
            'format_alamat.*' => ['string', 'in:jalan,nomor,kampung,rt,rw,dusun,desa,kecamatan,kabupaten,provinsi'],
        ]);

        $pengaturan = SuratPengaturan::current();
        $pengaturan->update([
            'no_hp_pelayanan'    => $request->no_hp_pelayanan,
            'email_pelayanan'    => $request->email_pelayanan,
            'format_alamat_json' => $request->format_alamat ?? ['dusun', 'desa', 'kecamatan', 'kabupaten'],
        ]);

        activity('surat_pengaturan')
            ->causedBy(auth()->user())
            ->log('Memperbarui pengaturan umum kontak & format alamat persuratan.');

        return back()->with('success', 'Pengaturan umum surat berhasil disimpan.');
    }

    /**
     * Tab 2: Format Cetak Surat (Ukuran Kertas, Font, Margin, & Per-Jenis Surat)
     */
    public function formatCetak(Request $request): View
    {
        $pengaturan = SuratPengaturan::current();
        $desa = DesaProfile::current();
        $activeTab = 'format-cetak';

        $search = $request->query('q');
        $templatesQuery = SuratTemplate::orderBy('nama_surat');
        if ($search) {
            $templatesQuery->where(function ($q) use ($search) {
                $q->where('nama_surat', 'like', "%{$search}%")
                  ->orWhere('kode_surat', 'like', "%{$search}%");
            });
        }
        $templates = $templatesQuery->get();

        return view('admin.persuratan.pengaturan.format_cetak', compact('pengaturan', 'desa', 'templates', 'search', 'activeTab'));
    }

    /**
     * Simpan Tab 2: Format Cetak Surat Global
     */
    public function updateFormatCetak(Request $request): RedirectResponse
    {
        $request->validate([
            'ukuran_kertas'    => ['required', 'string', 'max:50'],
            'margin_atas'      => ['required', 'integer', 'min:0', 'max:100'],
            'margin_kanan'     => ['required', 'integer', 'min:0', 'max:100'],
            'margin_bawah'     => ['required', 'integer', 'min:0', 'max:100'],
            'margin_kiri'      => ['required', 'integer', 'min:0', 'max:100'],
            'jenis_font'       => ['required', 'string', 'max:50'],
            'ukuran_font'      => ['required', 'string', 'max:20'],
            'tinggi_baris'     => ['required', 'string', 'max:20'],
            'munculkan_pangkat'=> ['required', 'in:Ya,Tidak'],
            'munculkan_nip'    => ['required', 'in:Ya,Tidak,-'],
            'munculkan_qr'     => ['required', 'in:Ya,Tidak'],
        ]);

        $pengaturan = SuratPengaturan::current();
        $pengaturan->update($request->only([
            'ukuran_kertas', 'margin_atas', 'margin_kanan', 'margin_bawah', 'margin_kiri',
            'jenis_font', 'ukuran_font', 'tinggi_baris', 'munculkan_pangkat', 'munculkan_nip', 'munculkan_qr',
        ]));

        activity('surat_pengaturan')
            ->causedBy(auth()->user())
            ->log('Memperbarui pengaturan format cetak surat global.');

        return back()->with('success', 'Pengaturan format cetak surat berhasil disimpan.');
    }

    /**
     * Simpan/Override Format Cetak Per-Jenis Surat (CRUD setting per template)
     */
    public function updateTemplateFormatCetak(Request $request, SuratTemplate $template): RedirectResponse
    {
        $request->validate([
            'ukuran_kertas' => ['nullable', 'string', 'max:50'],
            'jenis_font'    => ['nullable', 'string', 'max:50'],
            'ukuran_font'   => ['nullable', 'string', 'max:20'],
            'tinggi_baris'  => ['nullable', 'string', 'max:20'],
            'is_active'     => ['nullable', 'boolean'],
        ]);

        $customSettings = [
            'ukuran_kertas' => $request->ukuran_kertas ?: null,
            'jenis_font'    => $request->jenis_font ?: null,
            'ukuran_font'   => $request->ukuran_font ?: null,
            'tinggi_baris'  => $request->tinggi_baris ?: null,
            'custom_margin' => $request->filled('custom_margin_atas') ? [
                'atas'  => (int) $request->custom_margin_atas,
                'kanan' => (int) $request->custom_margin_kanan,
                'bawah' => (int) $request->custom_margin_bawah,
                'kiri'  => (int) $request->custom_margin_kiri,
            ] : null,
        ];

        $template->update([
            'custom_print_settings_json' => array_filter($customSettings),
        ]);

        return back()->with('success', "Format cetak khusus untuk {$template->nama_surat} berhasil disimpan.");
    }

    /**
     * Tab 3: Penomoran Surat
     */
    public function penomoran(): View
    {
        $pengaturan = SuratPengaturan::current();
        $desa = DesaProfile::current();
        $activeTab = 'penomoran';

        return view('admin.persuratan.pengaturan.penomoran', compact('pengaturan', 'desa', 'activeTab'));
    }

    /**
     * Simpan Tab 3: Penomoran Surat
     */
    public function updatePenomoran(Request $request): RedirectResponse
    {
        $request->validate([
            'aturan_penomoran'   => ['required', 'string', 'max:100'],
            'format_nomor_umum'  => ['required', 'string', 'max:200'],
            'inkremen_terakhir'  => ['required', 'integer', 'min:0'],
        ]);

        $pengaturan = SuratPengaturan::current();
        $pengaturan->update([
            'aturan_penomoran'  => $request->aturan_penomoran,
            'format_nomor_umum' => $request->format_nomor_umum,
            'inkremen_terakhir' => (int) $request->inkremen_terakhir,
        ]);

        activity('surat_pengaturan')
            ->causedBy(auth()->user())
            ->log('Memperbarui sistem format dan inkremen penomoran surat.');

        return back()->with('success', 'Sistem penomoran surat berhasil disimpan.');
    }

    /**
     * Tab 4: Kop Surat
     */
    public function kopSurat(): View
    {
        $pengaturan = SuratPengaturan::current();
        $desa = DesaProfile::current();
        $activeTab = 'kop-surat';

        return view('admin.persuratan.pengaturan.kop_surat', compact('pengaturan', 'desa', 'activeTab'));
    }

    /**
     * Simpan Tab 4: Kop Surat
     */
    public function updateKopSurat(Request $request): RedirectResponse
    {
        $request->validate([
            'kop_baris_1' => ['required', 'string', 'max:255'],
            'kop_baris_2' => ['required', 'string', 'max:255'],
            'kop_baris_3' => ['required', 'string', 'max:255'],
            'kop_alamat'  => ['required', 'string', 'max:500'],
            'logo'        => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        $pengaturan = SuratPengaturan::current();
        $data = [
            'kop_baris_1' => $request->kop_baris_1,
            'kop_baris_2' => $request->kop_baris_2,
            'kop_baris_3' => $request->kop_baris_3,
            'kop_alamat'  => $request->kop_alamat,
        ];

        if ($request->hasFile('logo')) {
            if ($pengaturan->kop_logo_path && Storage::disk('public')->exists($pengaturan->kop_logo_path)) {
                Storage::disk('public')->delete($pengaturan->kop_logo_path);
            }
            $data['kop_logo_path'] = $request->file('logo')->store('kop_surat', 'public');
        }

        $pengaturan->update($data);

        activity('surat_pengaturan')
            ->causedBy(auth()->user())
            ->log('Memperbarui desain dan teks kop surat resmi.');

        return back()->with('success', 'Pengaturan kop surat berhasil disimpan.');
    }

    /**
     * Tab 5: Manajemen Tanda Tangan
     */
    public function tandaTangan(): View
    {
        $pengaturan = SuratPengaturan::current();
        $desa = DesaProfile::current();
        $activeTab = 'tanda-tangan';

        // Auto-seed initial signers from active apparatur if table is empty
        if (SuratPenandatangan::count() === 0) {
            $aparaturs = Aparatur::with('penduduk')->where('status_aktif', true)->get();
            $order = 1;
            foreach ($aparaturs as $aparatur) {
                SuratPenandatangan::create([
                    'aparatur_id'       => $aparatur->id,
                    'nama'              => $aparatur->penduduk?->nama_lengkap ?? 'Aparatur Desa',
                    'jabatan'           => $aparatur->jabatan,
                    'nip'               => $aparatur->nip,
                    'urutan'            => $order++,
                    'bisa_tanda_tangan' => ($aparatur->jabatan === 'Kepala Desa' || $order <= 3),
                    'is_camat'          => false,
                ]);
            }
        }

        $signers = SuratPenandatangan::orderBy('urutan')->get();
        $aparaturs = Aparatur::with('penduduk')->where('status_aktif', true)->get();

        return view('admin.persuratan.pengaturan.tanda_tangan', compact('pengaturan', 'desa', 'signers', 'aparaturs', 'activeTab'));
    }

    /**
     * Tambah Penandatangan Baru (CRUD: Create)
     */
    public function storeSigner(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'              => ['required', 'string', 'max:255'],
            'jabatan'           => ['required', 'string', 'max:255'],
            'nip'               => ['nullable', 'string', 'max:50'],
            'urutan'            => ['required', 'integer', 'min:1'],
            'aparatur_id'       => ['nullable', 'exists:aparatur,id'],
            'bisa_tanda_tangan' => ['nullable', 'boolean'],
            'is_camat'          => ['nullable', 'boolean'],
        ]);

        SuratPenandatangan::create([
            'nama'              => $request->nama,
            'jabatan'           => $request->jabatan,
            'nip'               => $request->nip,
            'urutan'            => $request->urutan,
            'aparatur_id'       => $request->aparatur_id ?: null,
            'bisa_tanda_tangan' => $request->boolean('bisa_tanda_tangan', true),
            'is_camat'          => $request->boolean('is_camat', false),
        ]);

        return back()->with('success', "Penandatangan {$request->nama} berhasil ditambahkan.");
    }

    /**
     * Update Penandatangan (CRUD: Update)
     */
    public function updateSigner(Request $request, SuratPenandatangan $signer): RedirectResponse
    {
        $request->validate([
            'nama'              => ['required', 'string', 'max:255'],
            'jabatan'           => ['required', 'string', 'max:255'],
            'nip'               => ['nullable', 'string', 'max:50'],
            'urutan'            => ['required', 'integer', 'min:1'],
            'aparatur_id'       => ['nullable', 'exists:aparatur,id'],
            'bisa_tanda_tangan' => ['nullable', 'boolean'],
        ]);

        $signer->update([
            'nama'              => $request->nama,
            'jabatan'           => $request->jabatan,
            'nip'               => $request->nip,
            'urutan'            => $request->urutan,
            'aparatur_id'       => $request->aparatur_id ?: null,
            'bisa_tanda_tangan' => $request->boolean('bisa_tanda_tangan', true),
        ]);

        return back()->with('success', "Data penandatangan {$signer->nama} berhasil diperbarui.");
    }

    /**
     * Toggle Izin Tanda Tangan (CRUD: Toggle Active)
     */
    public function toggleSigner(SuratPenandatangan $signer): RedirectResponse
    {
        $signer->update([
            'bisa_tanda_tangan' => ! $signer->bisa_tanda_tangan,
        ]);

        $status = $signer->bisa_tanda_tangan ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Izin tanda tangan untuk {$signer->nama} berhasil {$status}.");
    }

    /**
     * Hapus Penandatangan (CRUD: Delete)
     */
    public function destroySigner(SuratPenandatangan $signer): RedirectResponse
    {
        $nama = $signer->nama;
        $signer->delete();

        return back()->with('success', "Penandatangan {$nama} berhasil dihapus.");
    }

    /**
     * Sinkronisasi dari Buku Aparat Pemerintah Desa (CRUD: Sync)
     */
    public function syncAparatur(): RedirectResponse
    {
        $aparaturs = Aparatur::with('penduduk')->where('status_aktif', true)->get();
        $importedCount = 0;

        foreach ($aparaturs as $aparatur) {
            $existing = SuratPenandatangan::where('aparatur_id', $aparatur->id)->first();
            if (! $existing) {
                $maxUrutan = (int) SuratPenandatangan::max('urutan') + 1;
                SuratPenandatangan::create([
                    'aparatur_id'       => $aparatur->id,
                    'nama'              => $aparatur->penduduk?->nama_lengkap ?? 'Aparatur Desa',
                    'jabatan'           => $aparatur->jabatan,
                    'nip'               => $aparatur->nip,
                    'urutan'            => $maxUrutan,
                    'bisa_tanda_tangan' => true,
                    'is_camat'          => false,
                ]);
                $importedCount++;
            }
        }

        return back()->with('success', "Sinkronisasi selesai. {$importedCount} aparatur baru ditambahkan ke daftar penandatangan.");
    }
}
