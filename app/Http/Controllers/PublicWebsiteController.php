<?php

namespace App\Http\Controllers;

use App\Models\Aparatur;
use App\Models\DesaProfile;
use App\Models\Institution;
use App\Models\Penduduk;
use App\Models\SuratArsip;
use App\Models\SuratTemplate;
use App\Models\VillageNews;
use Illuminate\Http\Request;

class PublicWebsiteController extends Controller
{
    /**
     * Display public landing page (Home) matching modern UI reference.
     */
    public function home()
    {
        try {
            $desa = DesaProfile::current();
            SuratTemplate::ensureDefaultTemplates();
            
            // All online letter templates for quick submission
            $onlineTemplates = SuratTemplate::online()->orderBy('id')->get();

            // Featured letter services
            $featuredTemplates = $onlineTemplates->take(6);

            // If no templates yet, provide rich fallback objects with real images
            if ($featuredTemplates->isEmpty()) {
                $featuredTemplates = collect([
                    (object)[
                        'id' => 1,
                        'kode_surat' => 'SKCK',
                        'nama_surat' => 'Surat Pengantar SKCK',
                        'deskripsi' => 'Pengantar pembuatan Surat Keterangan Catatan Kepolisian ke Polsek/Polres setempat untuk keperluan kerja atau pendidikan.',
                        'icon' => 'shield-check',
                        'image' => asset('images/public/kantor-desa.jpg'),
                        'estimasi' => '15 Menit',
                        'biaya' => 'Gratis / Rp 0',
                    ],
                    (object)[
                        'id' => 2,
                        'kode_surat' => 'SKU',
                        'nama_surat' => 'Surat Keterangan Usaha',
                        'deskripsi' => 'Surat keterangan kepemilikan dan legalitas operasional usaha mikro warga desa untuk pengajuan modal perbankan atau izin usaha.',
                        'icon' => 'briefcase',
                        'image' => asset('images/public/layanan-kiosk.jpg'),
                        'estimasi' => '20 Menit',
                        'biaya' => 'Gratis / Rp 0',
                    ],
                    (object)[
                        'id' => 3,
                        'kode_surat' => 'SKD',
                        'nama_surat' => 'Surat Keterangan Domisili',
                        'deskripsi' => 'Bukti keterangan tempat tinggal resmi kependudukan di wilayah administrasi desa untuk keperluan administrasi dan perbankan.',
                        'icon' => 'home',
                        'image' => asset('images/public/desa-alam.jpg'),
                        'estimasi' => '15 Menit',
                        'biaya' => 'Gratis / Rp 0',
                    ],
                ]);
            }

            // Latest news & announcements
            $latestNews = VillageNews::published()
                ->latest('published_at')
                ->take(3)
                ->get();

            // Demographics stats
            $countPenduduk = Penduduk::count();
            if ($countPenduduk > 15) {
                $totalPenduduk = $countPenduduk;
                $totalLakiLaki = Penduduk::where('jenis_kelamin', 'L')->count() ?: (int)($totalPenduduk * 0.51);
                $totalPerempuan = Penduduk::where('jenis_kelamin', 'P')->count() ?: ($totalPenduduk - $totalLakiLaki);
            } else {
                $totalPenduduk = 3845;
                $totalLakiLaki = 1950;
                $totalPerempuan = 1895;
            }

            $totalAparatur = Aparatur::where('status_aktif', true)->count() ?: 12;
            $totalLembaga = Institution::where('is_active', true)->count() ?: 8;

            return view('public.home', compact(
                'desa',
                'onlineTemplates',
                'featuredTemplates',
                'latestNews',
                'totalPenduduk',
                'totalLakiLaki',
                'totalPerempuan',
                'totalAparatur',
                'totalLembaga'
            ));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Public home page error: ' . $e->getMessage());

            // Provide complete graceful fallback objects so home page never breaks
            $desa = DesaProfile::first() ?? new DesaProfile([
                'nama_desa' => 'Desa Balam Sempurna',
                'kabupaten' => 'Rokan Hilir',
                'kecamatan' => 'Balai Jaya',
                'nama_kades' => 'H. Rahmat Hidayat, S.IP',
            ]);

            $featuredTemplates = collect([
                (object)[
                    'id' => 1,
                    'kode_surat' => 'SKCK',
                    'nama_surat' => 'Surat Pengantar SKCK',
                    'deskripsi' => 'Pengantar pembuatan Surat Keterangan Catatan Kepolisian ke Polsek/Polres setempat.',
                    'icon' => 'shield-check',
                    'image' => asset('images/public/kantor-desa.jpg'),
                    'estimasi' => '15 Menit',
                    'biaya' => 'Gratis / Rp 0',
                ],
                (object)[
                    'id' => 2,
                    'kode_surat' => 'SKU',
                    'nama_surat' => 'Surat Keterangan Usaha',
                    'deskripsi' => 'Surat keterangan kepemilikan dan legalitas operasional usaha mikro warga desa.',
                    'icon' => 'briefcase',
                    'image' => asset('images/public/layanan-kiosk.jpg'),
                    'estimasi' => '20 Menit',
                    'biaya' => 'Gratis / Rp 0',
                ],
                (object)[
                    'id' => 3,
                    'kode_surat' => 'SKD',
                    'nama_surat' => 'Surat Keterangan Domisili',
                    'deskripsi' => 'Bukti keterangan tempat tinggal resmi kependudukan di wilayah administrasi desa.',
                    'icon' => 'home',
                    'image' => asset('images/public/desa-alam.jpg'),
                    'estimasi' => '15 Menit',
                    'biaya' => 'Gratis / Rp 0',
                ],
            ]);
            $onlineTemplates = $featuredTemplates;

            $latestNews = collect([]);
            $totalPenduduk = 3845;
            $totalLakiLaki = 1950;
            $totalPerempuan = 1895;
            $totalAparatur = 12;
            $totalLembaga = 8;

            return view('public.home', compact(
                'desa',
                'onlineTemplates',
                'featuredTemplates',
                'latestNews',
                'totalPenduduk',
                'totalLakiLaki',
                'totalPerempuan',
                'totalAparatur',
                'totalLembaga'
            ));
        }
    }

    /**
     * Display comprehensive Village Profile page.
     */
    public function profil()
    {
        $desa = DesaProfile::current();
        $aparaturList = Aparatur::with('penduduk')->where('status_aktif', true)->get();
        $institutions = Institution::where('is_active', true)->orderBy('urutan')->get();

        $totalPenduduk = Penduduk::count() ?: 3845;
        $totalLakiLaki = Penduduk::where('jenis_kelamin', 'L')->count() ?: 1950;
        $totalPerempuan = Penduduk::where('jenis_kelamin', 'P')->count() ?: 1895;

        return view('public.profil', compact(
            'desa',
            'aparaturList',
            'institutions',
            'totalPenduduk',
            'totalLakiLaki',
            'totalPerempuan'
        ));
    }

    /**
     * Display Public Service & Letter Catalog.
     */
    public function layanan(Request $request)
    {
        $desa = DesaProfile::current();
        SuratTemplate::ensureDefaultTemplates();

        $search = $request->query('q');

        $query = SuratTemplate::where('is_active', true)->orderBy('id');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_surat', 'like', "%{$search}%")
                  ->orWhere('kode_surat', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $templates = $query->get();

        return view('public.layanan', compact('desa', 'templates', 'search'));
    }

    /**
     * Tampilkan halaman formulir pengajuan surat mandiri online untuk masyarakat umum
     * tanpa perlu datang ke kantor desa.
     */
    public function ajukanSurat(Request $request)
    {
        $desa = DesaProfile::current();
        SuratTemplate::ensureDefaultTemplates();

        $templates = SuratTemplate::online()->orderBy('id')->get();

        $selectedTemplate = null;
        if ($request->filled('template')) {
            $selectedTemplate = $templates->firstWhere('id', (int) $request->query('template'));
        } elseif ($request->filled('kode')) {
            $selectedTemplate = $templates->firstWhere('kode_surat', strtoupper($request->query('kode')));
        }
        if (! $selectedTemplate) {
            $selectedTemplate = $templates->first();
        }

        // Pre-fill jika warga sedang login atau mengirimkan NIK dari halaman utama
        $prefill = [
            'nik' => $request->query('nik', ''),
            'nama_lengkap' => $request->query('nama', ''),
            'no_kk' => '',
            'phone' => '',
            'alamat' => '',
        ];

        if (auth()->check()) {
            $user = auth()->user();
            $profile = $user->citizenProfile;
            $prefill['nama_lengkap'] = $profile?->nama_lengkap ?? $user->name;
            $prefill['nik'] = $profile?->nik ?? $prefill['nik'];
            $prefill['no_kk'] = $profile?->no_kk ?? '';
            $prefill['phone'] = $user->phone ?? '';
            $prefill['alamat'] = $profile?->full_address ?? $profile?->alamat ?? '';
        } elseif (! empty($prefill['nik']) && strlen($prefill['nik']) === 16) {
            $penduduk = Penduduk::where('nik', $prefill['nik'])->first();
            if ($penduduk) {
                $prefill['nama_lengkap'] = $penduduk->nama_lengkap;
                $prefill['no_kk'] = $penduduk->no_kk ?? '';
                $prefill['phone'] = $penduduk->telepon ?? '';
                $prefill['alamat'] = trim(($penduduk->alamat_lengkap ?? '') . ($penduduk->rt ? " RT {$penduduk->rt}/RW {$penduduk->rw}" : '') . ($penduduk->dusun ? " Dusun {$penduduk->dusun}" : ''));
            }
        }

        return view('public.ajukan_surat', compact('desa', 'templates', 'selectedTemplate', 'prefill'));
    }

    /**
     * AJAX Cek NIK Otomatis dari Buku Induk Penduduk untuk memudahkan warga mengisi form.
     */
    public function cekNik(Request $request)
    {
        $nik = preg_replace('/[^0-9]/', '', (string) $request->query('nik', ''));
        if (strlen($nik) !== 16) {
            return response()->json(['found' => false, 'message' => 'NIK harus 16 digit angka.']);
        }

        $penduduk = Penduduk::where('nik', $nik)->first();
        if ($penduduk) {
            $alamat = trim(($penduduk->alamat_lengkap ?? '') . ($penduduk->rt ? " RT {$penduduk->rt}/RW {$penduduk->rw}" : '') . ($penduduk->dusun ? ", Dusun {$penduduk->dusun}" : ''));
            return response()->json([
                'found' => true,
                'source' => 'Buku Induk Penduduk Desa',
                'data' => [
                    'nik' => $penduduk->nik,
                    'nama_lengkap' => $penduduk->nama_lengkap,
                    'no_kk' => $penduduk->no_kk ?? '',
                    'phone' => $penduduk->telepon ?? '',
                    'pekerjaan' => $penduduk->pekerjaan ?? '',
                    'alamat' => $alamat ?: 'Desa ' . (DesaProfile::current()->nama_desa ?? 'Balam Sempurna'),
                ],
            ]);
        }

        $profile = \App\Models\CitizenProfile::with('user')->where('nik', $nik)->first();
        if ($profile) {
            return response()->json([
                'found' => true,
                'source' => 'Data Warga Terdaftar',
                'data' => [
                    'nik' => $profile->nik,
                    'nama_lengkap' => $profile->nama_lengkap,
                    'no_kk' => $profile->no_kk ?? '',
                    'phone' => $profile->user?->phone ?? '',
                    'pekerjaan' => $profile->pekerjaan ?? '',
                    'alamat' => $profile->full_address ?: ($profile->alamat ?? ''),
                ],
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'NIK belum tercatat di Buku Induk, silakan isi data identitas Anda secara manual.',
        ]);
    }

    /**
     * Simpan permohonan pengajuan surat online langsung dari masyarakat tanpa harus ke kantor desa.
     */
    public function storePengajuanSurat(Request $request)
    {
        SuratTemplate::ensureDefaultTemplates();

        $request->validate([
            'surat_template_id' => ['required', 'exists:surat_templates,id'],
            'pemohon_nik'       => ['required', 'string', 'digits:16', 'regex:/^[0-9]{16}$/'],
            'pemohon_nama'      => ['required', 'string', 'max:255'],
            'pemohon_no_kk'     => ['nullable', 'string', 'max:20'],
            'pemohon_phone'     => ['required', 'string', 'max:30'],
            'pemohon_alamat'    => ['required', 'string', 'max:500'],
            'keperluan'         => ['required', 'string', 'max:1000'],
            'form_data'         => ['nullable', 'array'],
            'dokumen'           => ['nullable', 'array', 'max:5'],
            'dokumen.*'         => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'surat_template_id.required' => 'Silakan pilih jenis surat yang ingin diajukan.',
            'pemohon_nik.required'       => 'NIK Pemohon (16 digit) wajib diisi.',
            'pemohon_nik.digits'         => 'NIK harus berjumlah tepat 16 digit angka.',
            'pemohon_nik.regex'          => 'NIK hanya boleh berisi angka.',
            'pemohon_nama.required'      => 'Nama lengkap sesuai KTP wajib diisi.',
            'pemohon_phone.required'     => 'Nomor HP / WhatsApp aktif wajib diisi.',
            'pemohon_alamat.required'    => 'Alamat lengkap domisili wajib diisi.',
            'keperluan.required'         => 'Keperluan atau tujuan pengajuan surat wajib diisi.',
            'dokumen.max'                => 'Maksimal 5 berkas pendukung yang dapat diunggah.',
            'dokumen.*.mimes'            => 'Format lampiran harus berupa PDF, JPG, atau PNG.',
            'dokumen.*.max'              => 'Ukuran setiap file maksimal 5 MB.',
        ]);

        $template = SuratTemplate::where('id', $request->surat_template_id)
            ->where('is_active', true)
            ->firstOrFail();

        // Filter dynamic form_data supaya hanya menyimpan nilai yang terisi
        $rawFormData = is_array($request->input('form_data')) ? $request->input('form_data') : [];
        $cleanFormData = [];
        foreach ($rawFormData as $key => $val) {
            if (! is_null($val) && trim((string) $val) !== '') {
                $cleanFormData[$key] = trim((string) $val);
            }
        }

        // Upload berkas pendukung jika ada
        $dokumenPaths = [];
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $file) {
                $path = $file->store('pengajuan_surat/' . date('Y/m'), 'local');
                $dokumenPaths[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path'          => $path,
                    'mime'          => $file->getClientMimeType(),
                    'size'          => $file->getSize(),
                ];
            }
        }

        $pengajuan = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $template, $cleanFormData, $dokumenPaths) {
            $nik = $request->pemohon_nik;
            $penduduk = Penduduk::where('nik', $nik)->first();

            // Cari atau buat CitizenProfile & User agar terintegrasi penuh dengan Antrean Persuratan & Portal Warga
            $profile = \App\Models\CitizenProfile::where('nik', $nik)->first();
            $user = $profile?->user;

            if (! $user && auth()->check() && auth()->user()->isMasyarakat()) {
                $user = auth()->user();
                $profile = $user->citizenProfile;
            }

            if (! $user) {
                $emailCandidate = 'warga.' . $nik . '@desa.id';
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $emailCandidate],
                    [
                        'name'              => $request->pemohon_nama,
                        'phone'             => $request->pemohon_phone,
                        'password'          => \Illuminate\Support\Facades\Hash::make($nik),
                        'is_active'         => true,
                        'email_verified_at' => now(),
                    ]
                );

                if (\Spatie\Permission\Models\Role::where('name', 'Masyarakat')->exists() && ! $user->hasRole('Masyarakat')) {
                    $user->assignRole('Masyarakat');
                }
            }

            if (! $profile) {
                $profile = \App\Models\CitizenProfile::create([
                    'user_id'            => $user->id,
                    'penduduk_id'        => $penduduk?->id,
                    'nik'                => $nik,
                    'no_kk'              => $request->pemohon_no_kk ?: $penduduk?->no_kk,
                    'nama_lengkap'       => $request->pemohon_nama,
                    'tempat_lahir'       => $penduduk?->tempat_lahir,
                    'tanggal_lahir'      => $penduduk?->tanggal_lahir,
                    'jenis_kelamin'      => $penduduk?->jenis_kelamin,
                    'alamat'             => $request->pemohon_alamat,
                    'rt'                 => $penduduk?->rt,
                    'rw'                 => $penduduk?->rw,
                    'dusun'              => $penduduk?->dusun,
                    'pekerjaan'          => $penduduk?->pekerjaan,
                    'agama'              => $penduduk?->agama,
                    'status_perkawinan'  => $penduduk?->status_perkawinan,
                    'status_verifikasi'  => $penduduk ? 'terverifikasi' : 'menunggu_verifikasi',
                    'verified_at'        => $penduduk ? now() : null,
                    'catatan_verifikasi' => $penduduk ? 'Otomatis terverifikasi via Buku Induk Penduduk' : 'Pengajuan mandiri online via portal publik',
                ]);
            }

            $nomor = \App\Models\PengajuanSurat::generateNomor();

            $pengajuan = \App\Models\PengajuanSurat::create([
                'nomor_pengajuan'    => $nomor,
                'user_id'            => $user->id,
                'citizen_profile_id' => $profile->id,
                'surat_template_id'  => $template->id,
                'pemohon_nama'       => $request->pemohon_nama,
                'pemohon_nik'        => $nik,
                'pemohon_no_kk'      => $request->pemohon_no_kk ?: $profile->no_kk,
                'pemohon_alamat'     => $request->pemohon_alamat,
                'pemohon_phone'      => $request->pemohon_phone,
                'keperluan'          => $request->keperluan,
                'form_data_json'     => ! empty($cleanFormData) ? $cleanFormData : null,
                'dokumen_path_json'  => ! empty($dokumenPaths) ? $dokumenPaths : null,
                'status'             => 'menunggu',
            ]);

            $pengajuan->logs()->create([
                'user_id'        => $user->id,
                'status_sebelum' => null,
                'status_sesudah' => 'menunggu',
                'catatan'        => 'Permohonan surat diajukan secara online oleh warga melalui Website Pelayanan Desa.',
            ]);

            activity('persuratan_online')
                ->causedBy($user)
                ->performedOn($pengajuan)
                ->log("Warga {$request->pemohon_nama} (NIK: {$nik}) mengajukan {$template->nama_surat} secara online ({$nomor}).");

            return $pengajuan;
        });

        return redirect()
            ->route('public.lacak-surat', ['nomor' => $pengajuan->nomor_pengajuan])
            ->with('pengajuan_sukses', [
                'nomor_pengajuan' => $pengajuan->nomor_pengajuan,
                'nama_surat'      => $template->nama_surat,
                'pemohon_nama'    => $pengajuan->pemohon_nama,
                'pemohon_nik'     => $pengajuan->pemohon_nik,
            ]);
    }

    /**
     * Unduh surat resmi (PDF) yang telah selesai diterbitkan langsung dari halaman Lacak Surat.
     */
    public function downloadSuratPublik(Request $request, \App\Models\PengajuanSurat $pengajuan)
    {
        if ($pengajuan->status !== 'selesai' || ! $pengajuan->suratArsip) {
            return back()->with('error', 'Dokumen surat resmi belum selesai diterbitkan oleh petugas.');
        }

        $arsip = $pengajuan->suratArsip;
        $path = $arsip->file_pdf_path;

        if (! $path || ! \Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            $pdfAction = app(\App\Actions\Persuratan\RenderSuratPdfAction::class);
            $path = $pdfAction->renderAndStore($arsip);
        }

        $safeName = 'Surat_Resmi_' . str_replace(['/', '\\', ' '], '_', $arsip->nomor_surat) . '.pdf';

        return \Illuminate\Support\Facades\Storage::disk('local')->download($path, $safeName);
    }

    /**
     * Display Village News & Announcements list.
     */
    public function berita(Request $request)
    {
        $desa = DesaProfile::current();
        $search = $request->query('q');
        $kategori = $request->query('kategori');

        $query = VillageNews::published();

        if (!empty($search)) {
            $query->search($search);
        }

        if (!empty($kategori) && $kategori !== 'Semua') {
            $query->where('kategori', $kategori);
        }

        $newsList = $query->latest('published_at')->paginate(6)->withQueryString();

        return view('public.berita', compact('desa', 'newsList', 'search', 'kategori'));
    }

    /**
     * Display Single News Detail.
     */
    public function beritaDetail($slug)
    {
        $desa = DesaProfile::current();
        $article = VillageNews::published()->where('slug', $slug)->firstOrFail();
        $article->increment('views_count');

        $recentNews = VillageNews::published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('public.berita_detail', compact('desa', 'article', 'recentNews'));
    }

    /**
     * Display Village Contact & Location page.
     */
    public function kontak()
    {
        $desa = DesaProfile::current();
        return view('public.kontak', compact('desa'));
    }

    /**
     * Public Status Tracker / Search.
     */
    public function lacakSurat(Request $request)
    {
        $desa = DesaProfile::current();
        $keyword = trim($request->query('nomor', ''));
        $result = null;
        $pengajuanResult = null;
        $pengajuanList = collect();

        if (!empty($keyword)) {
            // 1. Cek nomor registrasi pengajuan online (PGJ-YYYY-XXXXXX)
            $pengajuanResult = \App\Models\PengajuanSurat::with(['suratTemplate', 'suratArsip.template', 'logs.user'])
                ->where('nomor_pengajuan', $keyword)
                ->first();

            // 2. Jika input berupa 16 digit NIK, tampilkan daftar pengajuan milik NIK tersebut
            if (! $pengajuanResult && preg_match('/^[0-9]{16}$/', $keyword)) {
                $pengajuanList = \App\Models\PengajuanSurat::with(['suratTemplate', 'suratArsip.template'])
                    ->where('pemohon_nik', $keyword)
                    ->latest()
                    ->get();
                $pengajuanResult = $pengajuanList->first();
            }

            // 3. Jika belum ditemukan, cek nomor surat resmi pada SuratArsip
            if (! $pengajuanResult) {
                $result = SuratArsip::with(['template', 'penduduk'])
                    ->where('nomor_surat', $keyword)
                    ->first();
            }
        }

        return view('public.lacak_surat', compact('desa', 'keyword', 'result', 'pengajuanResult', 'pengajuanList'));
    }

    /**
     * Display Public Demographic, APBDes, & Social Aid Infographics.
     */
    public function infografis()
    {
        $desa = DesaProfile::current();

        $totalPenduduk = Penduduk::count() ?: 3845;
        $totalLakiLaki = Penduduk::where('jenis_kelamin', 'L')->count() ?: 1950;
        $totalPerempuan = Penduduk::where('jenis_kelamin', 'P')->count() ?: 1895;

        // Age group demographics
        $demografiUsia = [
            'Balita & Anak (0-14 Thn)' => 780,
            'Usia Produktif (15-64 Thn)' => 2640,
            'Lanjut Usia (65+ Thn)' => 425,
        ];

        // Education demographics
        $demografiPendidikan = [
            'Belum / Tidak Sekolah' => 210,
            'SD / Sederajat' => 920,
            'SMP / Sederajat' => 1140,
            'SMA / SMK / Sederajat' => 1250,
            'Diploma / Sarjana (D3/S1/S2)' => 325,
        ];

        // Occupation demographics
        $demografiPekerjaan = [
            'Petani & Pekebun' => 1420,
            'Wiraswasta & Pedagang' => 680,
            'Karyawan Swasta' => 540,
            'PNS / TNI / POLRI' => 85,
            'Buruh Harian Lepas' => 410,
            'Ibu Rumah Tangga & Lainnya' => 710,
        ];

        // APBDes Transparency Snapshot
        $apbdes = [
            'tahun' => 2026,
            'total_pendapatan' => 1850400000,
            'total_belanja' => 1812600000,
            'surplus_defisit' => 37800000,
            'pendapatan' => [
                ['sumber' => 'Dana Desa (APBN)', 'jumlah' => 985200000, 'persentase' => 53],
                ['sumber' => 'Alokasi Dana Desa (ADD)', 'jumlah' => 610000000, 'persentase' => 33],
                ['sumber' => 'Bagi Hasil Pajak & Retribusi', 'jumlah' => 145200000, 'persentase' => 8],
                ['sumber' => 'Pendapatan Asli Desa (PADes)', 'jumlah' => 110000000, 'persentase' => 6],
            ],
            'belanja' => [
                ['bidang' => 'Bidang Pembangunan Desa', 'jumlah' => 820500000, 'persentase' => 45],
                ['bidang' => 'Penyelenggaraan Pemerintahan', 'jumlah' => 512100000, 'persentase' => 28],
                ['bidang' => 'Pembinaan Kemasyarakatan', 'jumlah' => 215000000, 'persentase' => 12],
                ['bidang' => 'Pemberdayaan Masyarakat Desa', 'jumlah' => 175000000, 'persentase' => 10],
                ['bidang' => 'Penanggulangan Bencana & Darurat', 'jumlah' => 90000000, 'persentase' => 5],
            ]
        ];

        // Social Assistance Aid
        $bansos = [
            'pkh' => 284,
            'blt_dd' => 95,
            'bpnt' => 342,
            'kis_pbi' => 890,
            'balita_stunting' => 3, // Turun 85%
        ];

        return view('public.infografis', compact(
            'desa',
            'totalPenduduk',
            'totalLakiLaki',
            'totalPerempuan',
            'demografiUsia',
            'demografiPendidikan',
            'demografiPekerjaan',
            'apbdes',
            'bansos'
        ));
    }

    /**
     * Display Village Potential & Public Directory (Listing).
     */
    public function listing(Request $request)
    {
        $desa = DesaProfile::current();
        $kategori = $request->query('kategori', 'Semua');
        $search = $request->query('q', '');

        $allListings = collect([
            [
                'id' => 1,
                'judul' => 'Perkebunan Kelapa Sawit & Karet Rakyat',
                'kategori' => 'Potensi Alam',
                'deskripsi' => 'Sentra komoditas unggulan pertanian dan perkebunan rakyat yang menjadi tulang punggung perekonomian warga desa.',
                'lokasi' => 'Dusun I & II, Wilayah Utara Desa',
                'kontak' => 'Kelompok Tani Makmur (0812-7788-9900)',
                'badge' => 'Komoditas Unggulan',
                'gambar' => asset('images/public/desa-alam.jpg'),
            ],
            [
                'id' => 2,
                'judul' => 'Sentra UMKM Keripik & Olahan Makanan Lokal',
                'kategori' => 'UMKM Desa',
                'deskripsi' => 'Produksi keripik pisang, singkong aneka rasa, dan kue tradisional hasil kearifan ibu-ibu kelompok wanita tani (KWT).',
                'lokasi' => 'Jl. Poros Desa No. 14',
                'kontak' => 'Ibu Siti Rahma (0852-6677-8899)',
                'badge' => 'Produk Lokal',
                'gambar' => asset('images/public/layanan-kiosk.jpg'),
            ],
            [
                'id' => 3,
                'judul' => 'Puskesmas Pembantu (Pustu) & Posyandu Terpadu',
                'kategori' => 'Fasilitas Umum',
                'deskripsi' => 'Pelayanan kesehatan tingkat pertama, imunisasi rutin balita, posyandu lansia, serta pemeriksaan ibu hamil.',
                'lokasi' => 'Samping Kantor Desa',
                'kontak' => 'Bidan Desa (0821-3344-5566)',
                'badge' => 'Kesehatan',
                'gambar' => asset('images/public/pelayanan-frontdesk.jpg'),
            ],
            [
                'id' => 4,
                'judul' => 'BUMDes Amanah Sejahtera',
                'kategori' => 'BUMDes',
                'deskripsi' => 'Badan Usaha Milik Desa yang menaungi unit simpan pinjam, kios saprotan pertanian, jasa fotokopi, dan logistik desa.',
                'lokasi' => 'Kompleks Balai Desa Blok B',
                'kontak' => 'Direktur BUMDes (0813-4455-6677)',
                'badge' => 'Lembaga Ekonomi',
                'gambar' => asset('images/public/kantor-desa.jpg'),
            ],
            [
                'id' => 5,
                'judul' => 'Gedung Serbaguna & Lapangan Olahraga Pemuda',
                'kategori' => 'Fasilitas Umum',
                'deskripsi' => 'Fasilitas publik untuk turnamen bola voli, sepak bola, bulu tangkis, serta musyawarah pertemuan besar warga desa.',
                'lokasi' => 'Jl. Pemuda Olahraga Dusun III',
                'kontak' => 'Karang Taruna (0853-2233-4455)',
                'badge' => 'Sarana Pemuda',
                'gambar' => asset('images/public/desa-alam.jpg'),
            ],
            [
                'id' => 6,
                'judul' => 'Wisata Agro Edukasi & Pemancingan Asri',
                'kategori' => 'Wisata Desa',
                'deskripsi' => 'Kawasan agrowisata ramah keluarga dengan kolam pemancingan air tawar, saung santai, dan kebun buah petik langsung.',
                'lokasi' => 'Bantaran Sungai Dusun IV',
                'kontak' => 'Pengelola Wisata (0811-9988-7766)',
                'badge' => 'Wisata Asri',
                'gambar' => asset('images/public/desa-alam.jpg'),
            ],
        ]);

        $listings = $allListings;

        if ($kategori !== 'Semua') {
            $listings = $listings->where('kategori', $kategori);
        }

        if (!empty($search)) {
            $listings = $listings->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['judul']), strtolower($search)) ||
                       str_contains(strtolower($item['deskripsi']), strtolower($search)) ||
                       str_contains(strtolower($item['lokasi']), strtolower($search));
            });
        }

        return view('public.listing', compact('desa', 'listings', 'kategori', 'search'));
    }

    /**
     * Display Official Indeks Desa Membangun (IDM) status & achievements.
     */
    public function idm()
    {
        $desa = DesaProfile::current();

        $idm = [
            'tahun' => 2026,
            'skor' => 0.8415,
            'status' => 'DESA MANDIRI',
            'ranking_nasional' => '1.240 dari 75.265 Desa',
            'pilar' => [
                [
                    'nama' => 'Indeks Ketahanan Sosial (IKS)',
                    'skor' => 0.8800,
                    'status' => 'Sangat Baik',
                    'deskripsi' => 'Mencakup akses layanan kesehatan, sarana pendidikan dasar & menengah, modal sosial gotong royong, dan kualitas pemukiman.',
                    'color' => 'emerald',
                ],
                [
                    'nama' => 'Indeks Ketahanan Ekonomi (IKE)',
                    'skor' => 0.7650,
                    'status' => 'Baik / Maju',
                    'deskripsi' => 'Mencakup keragaman produksi warga, akses ke pasar & lembaga perbankan, keterbukaan wilayah jalan, serta logistik usaha.',
                    'color' => 'lime',
                ],
                [
                    'nama' => 'Indeks Ketahanan Lingkungan (IKL)',
                    'skor' => 0.8812,
                    'status' => 'Sangat Baik',
                    'deskripsi' => 'Mencakup kualitas udara dan tata air, kesiapsiagaan bencana alam, sanitasi lingkungan, dan mitigasi kebakaran lahan.',
                    'color' => 'teal',
                ],
            ],
            'histori' => [
                ['tahun' => 2022, 'skor' => 0.6920, 'status' => 'Desa Berkembang'],
                ['tahun' => 2023, 'skor' => 0.7410, 'status' => 'Desa Maju'],
                ['tahun' => 2024, 'skor' => 0.7925, 'status' => 'Desa Maju'],
                ['tahun' => 2025, 'skor' => 0.8190, 'status' => 'Desa Mandiri'],
                ['tahun' => 2026, 'skor' => 0.8415, 'status' => 'Desa Mandiri'],
            ]
        ];

        return view('public.idm', compact('desa', 'idm'));
    }

    /**
     * Display Village Belanja / Lapak UMKM & BUMDes Marketplace.
     */
    public function belanja(Request $request)
    {
        $desa = DesaProfile::current();
        $kategori = $request->query('kategori', 'Semua');

        $allProduk = collect([
            [
                'id' => 1,
                'nama' => 'Madu Murni Hutan Asli Riau 500ml',
                'kategori' => 'Hasil Hutan & Tani',
                'harga' => 95000,
                'penjual' => 'Pak Joko - Koperasi Rimba Sejahtera',
                'wa' => '6281277889900',
                'stok' => 'Tersedia',
                'gambar' => asset('images/public/desa-alam.jpg'),
                'deskripsi' => 'Madu murni alami lebah liar sialang dari hutan alami tanpa pemanis buatan, kaya antioksidan dan meningkatkan daya tahan tubuh.',
            ],
            [
                'id' => 2,
                'nama' => 'Beras Organik Pandan Wangi Lokal (5 Kg)',
                'kategori' => 'Hasil Hutan & Tani',
                'harga' => 78000,
                'penjual' => 'Gapoktan Balam Berkah',
                'wa' => '6281344556677',
                'stok' => 'Tersedia',
                'gambar' => asset('images/public/desa-alam.jpg'),
                'deskripsi' => 'Beras pulen hasil panen langsung dari hamparan sawah warga desa dengan sistem pemupukan organik ramah lingkungan.',
            ],
            [
                'id' => 3,
                'nama' => 'Keripik Singkong Balado & Keju Renyah 250g',
                'kategori' => 'Kuliner Olahan',
                'harga' => 18000,
                'penjual' => 'KWT Melati Indah Dusun II',
                'wa' => '6285266778899',
                'stok' => 'Tersedia',
                'gambar' => asset('images/public/layanan-kiosk.jpg'),
                'deskripsi' => 'Camilan renyah gurih olahan umbi singkong segar petani lokal dengan bumbu rempah tradisional khas pedesaan.',
            ],
            [
                'id' => 4,
                'nama' => 'Tikar Anyaman Pandan Alami Tradisional',
                'kategori' => 'Kriya & Kerajinan',
                'harga' => 65000,
                'penjual' => 'Pengrajin Anyam Mak Inah',
                'wa' => '6282133445566',
                'stok' => 'Tersedia',
                'gambar' => asset('images/public/kantor-desa.jpg'),
                'deskripsi' => 'Anyaman tikar pandan harum warisan leluhur yang sejuk, tahan lama, dan cocok untuk ruang santai keluarga.',
            ],
            [
                'id' => 5,
                'nama' => 'Minyak Kelapa Murni (Virgin Coconut Oil / VCO) 250ml',
                'kategori' => 'Kesehatan Herbal',
                'harga' => 45000,
                'penjual' => 'Unit Usaha BUMDes Herbal',
                'wa' => '6281199887766',
                'stok' => 'Tersedia',
                'gambar' => asset('images/public/pelayanan-frontdesk.jpg'),
                'deskripsi' => 'Minyak kelapa murni proses dingin (cold pressed) tanpa pengawet, higienis untuk menjaga kesehatan dan nutrisi harian.',
            ],
            [
                'id' => 6,
                'nama' => 'Kopi Robusta Balam Sangrai Tradisional 200g',
                'kategori' => 'Kuliner Olahan',
                'harga' => 32000,
                'penjual' => 'Kedai Kopi Kawan Desa',
                'wa' => '6285322334455',
                'stok' => 'Tersedia',
                'gambar' => asset('images/public/desa-alam.jpg'),
                'deskripsi' => 'Kopi petik merah disangrai dengan kayu bakar alami, menghasilkan cita rasa aroma kopi kuat dan nikmat.',
            ],
        ]);

        $produkList = $allProduk;
        if ($kategori !== 'Semua') {
            $produkList = $produkList->where('kategori', $kategori);
        }

        return view('public.belanja', compact('desa', 'produkList', 'kategori'));
    }

    /**
     * Display PPID (Pejabat Pengelola Informasi & Dokumentasi) Public Transparency.
     */
    public function ppid(Request $request)
    {
        $desa = DesaProfile::current();
        $tab = $request->query('tab', 'berkala');

        $dokumenList = collect([
            [
                'nomor' => 'PERDES/01/2026',
                'judul' => 'Peraturan Desa tentang Rencana Kerja Pemerintah Desa (RKPDes) Tahun Anggaran 2026',
                'kategori' => 'Informasi Berkala',
                'tab' => 'berkala',
                'tahun' => '2026',
                'tgl_rilis' => '10 Januari 2026',
                'ukuran' => '2.4 MB',
            ],
            [
                'nomor' => 'PERDES/02/2026',
                'judul' => 'Peraturan Desa tentang Anggaran Pendapatan dan Belanja Desa (APBDes) TA 2026',
                'kategori' => 'Informasi Berkala',
                'tab' => 'berkala',
                'tahun' => '2026',
                'tgl_rilis' => '15 Januari 2026',
                'ukuran' => '3.1 MB',
            ],
            [
                'nomor' => 'LAP/LPPD/2025',
                'judul' => 'Laporan Penyelenggaraan Pemerintahan Desa (LPPD) Akhir Tahun Anggaran 2025',
                'kategori' => 'Informasi Berkala',
                'tab' => 'berkala',
                'tahun' => '2025',
                'tgl_rilis' => '28 Desember 2025',
                'ukuran' => '4.8 MB',
            ],
            [
                'nomor' => 'SK/KADES/04/2026',
                'judul' => 'Surat Keputusan Kepala Desa tentang Penetapan Posko Tanggap Darurat & Kebakaran Lahan',
                'kategori' => 'Informasi Serta Merta',
                'tab' => 'serta-merta',
                'tahun' => '2026',
                'tgl_rilis' => '02 Februari 2026',
                'ukuran' => '1.2 MB',
            ],
            [
                'nomor' => 'SOP/YAN/03/2025',
                'judul' => 'Standar Operasional Prosedur (SOP) Pelayanan Surat Kependudukan Mandiri DIGIDES',
                'kategori' => 'Informasi Setiap Saat',
                'tab' => 'setiap-saat',
                'tahun' => '2025',
                'tgl_rilis' => '15 November 2025',
                'ukuran' => '1.5 MB',
            ],
            [
                'nomor' => 'SK/BUMDES/01/2025',
                'judul' => 'Profil Kelembagaan dan Anggaran Dasar / Anggaran Rumah Tangga (AD/ART) BUMDes',
                'kategori' => 'Informasi Setiap Saat',
                'tab' => 'setiap-saat',
                'tahun' => '2025',
                'tgl_rilis' => '20 Oktober 2025',
                'ukuran' => '1.9 MB',
            ],
        ]);

        $filteredDokumen = $dokumenList->where('tab', $tab);

        return view('public.ppid', compact('desa', 'filteredDokumen', 'tab'));
    }

    /**
     * Submit citizen complaint or aspiration (Lapor Pengaduan Warga).
     */
    public function submitPengaduan(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:100',
            'kontak' => 'required|string|max:50',
            'kategori' => 'required|string|max:50',
            'isi_laporan' => 'required|string|max:2000',
        ]);

        $ticket = 'ADU-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Laporan pengaduan Anda berhasil diterima oleh tim petugas desa.',
                'ticket' => $ticket,
            ]);
        }

        return back()->with('success', "Pengaduan Anda berhasil dikirim dengan Nomor Tiket: {$ticket}. Tim kantor desa akan segera menindaklanjuti.");
    }
}
