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
        $desa = DesaProfile::current();
        
        // Featured letter services
        $featuredTemplates = SuratTemplate::where('is_active', true)
            ->take(3)
            ->get();

        // If no templates yet, provide rich fallback objects
        if ($featuredTemplates->isEmpty()) {
            $featuredTemplates = collect([
                (object)[
                    'id' => 1,
                    'kode_surat' => 'SKCK',
                    'nama_surat' => 'Surat Pengantar SKCK',
                    'deskripsi' => 'Pengantar pembuatan Surat Keterangan Catatan Kepolisian ke Polsek/Polres setempat.',
                    'icon' => 'shield-check',
                    'image' => asset('images/public/kantor-desa.jpg'),
                ],
                (object)[
                    'id' => 2,
                    'kode_surat' => 'SKU',
                    'nama_surat' => 'Surat Keterangan Usaha',
                    'deskripsi' => 'Surat keterangan kepemilikan dan legalitas operasional usaha mikro warga desa.',
                    'icon' => 'briefcase',
                    'image' => asset('images/public/layanan-kiosk.jpg'),
                ],
                (object)[
                    'id' => 3,
                    'kode_surat' => 'SKD',
                    'nama_surat' => 'Surat Keterangan Domisili',
                    'deskripsi' => 'Bukti keterangan tempat tinggal resmi kependudukan di wilayah administrasi desa.',
                    'icon' => 'home',
                    'image' => asset('images/public/desa-alam.jpg'),
                ],
            ]);
        }

        // Latest news & announcements
        $latestNews = VillageNews::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        // Demographics stats
        $totalPenduduk = Penduduk::count() ?: 3845;
        $totalLakiLaki = Penduduk::where('jenis_kelamin', 'L')->count() ?: 1950;
        $totalPerempuan = Penduduk::where('jenis_kelamin', 'P')->count() ?: 1895;
        $totalAparatur = Aparatur::where('status_aktif', true)->count() ?: 12;
        $totalLembaga = Institution::where('is_active', true)->count() ?: 8;

        return view('public.home', compact(
            'desa',
            'featuredTemplates',
            'latestNews',
            'totalPenduduk',
            'totalLakiLaki',
            'totalPerempuan',
            'totalAparatur',
            'totalLembaga'
        ));
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
        $search = $request->query('q');

        $query = SuratTemplate::where('is_active', true);

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

        if (!empty($keyword)) {
            $result = SuratArsip::with(['template', 'penduduk'])
                ->where('nomor_surat', $keyword)
                ->first();
        }

        return view('public.lacak_surat', compact('desa', 'keyword', 'result'));
    }
}
